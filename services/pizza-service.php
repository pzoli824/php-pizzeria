<?php 
class PizzaService extends Database implements CrudOperations {

  public function _construct() {}

	public function getAll() {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM pizzas_with_cost");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'PizzaWithCost');
    $pizzas = $stmt->fetchAll();
    $this->closeConnection();
    return $pizzas;
	}

  public function getByIdWithCost($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM pizzas_with_cost WHERE pizza_id = :pizza_id");
    $stmt->bindParam(':pizza_id', $id, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'PizzaWithCost');
    $stmt->execute();
    $pizza = $stmt->fetch();
    $this->closeConnection();
    return $pizza;
  }
	
	public function get($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM pizzas_with_toppings WHERE pizza_id = :pizza_id");
    $stmt->bindParam(':pizza_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pizza');
    $data = $stmt->fetchAll();
    $pizza = $this->mapPizzaToPizzaWithToppings($data);
    $this->closeConnection();
    return $pizza;
	}
	
	public function update($pizzaDto) {
    $conn = $this->getConnection();
    try {
      $conn->beginTransaction();

      $stmt1 = $conn->prepare("UPDATE pizzas SET name = :name, dough_id = :dough_id, sauce_id = :sauce_id WHERE pizza_id = :pizza_id");

      $stmt1->bindParam(':pizza_id', $pizzaDto->pizza_id, PDO::PARAM_INT);
      $stmt1->bindParam(':name', $pizzaDto->name, PDO::PARAM_STR);
      $stmt1->bindParam(':dough_id', $pizzaDto->dough_id, PDO::PARAM_INT);
      $stmt1->bindParam(':sauce_id', $pizzaDto->sauce_id, PDO::PARAM_INT);
      $stmt1->execute();

      $stmt_delete = $conn->prepare("DELETE FROM toppings WHERE pizza_id = :pid");
      $stmt_delete->bindParam(':pid', $pizzaDto->pizza_id, PDO::PARAM_INT);
      $stmt_delete->execute();

      foreach ($pizzaDto->topping_ids as $key => $value) {
        $conn
        ->prepare("INSERT INTO toppings(pizza_id, topping_id) VALUES (?, ?)")
        ->execute(array($pizzaDto->pizza_id, $value));
      }
      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollBack();
      $this->closeConnection();
      die($e->getMessage());
    }
    $this->closeConnection();
	}

	public function delete($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("DELETE FROM pizzas WHERE pizza_id = :pizza_id");
    $stmt->bindParam(':pizza_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
	}
	
	public function create($pizzaDto) {
    $conn = $this->getConnection();
    try {
      $conn->beginTransaction();
      $conn
      ->prepare("INSERT INTO pizzas (name, dough_id, sauce_id) VALUES (?, ?, ?)")
      ->execute([$pizzaDto->name, $pizzaDto->dough_id, $pizzaDto->sauce_id]);
      $last_id = $conn->lastInsertId();
      foreach ($pizzaDto->topping_ids as $key => $value) {
        $conn
        ->prepare("INSERT INTO toppings (pizza_id, topping_id) VALUES (:pid, :fid)")
        ->execute(array(
          ':pid' => $last_id,
          ':fid' => $value
        ));
      }
      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollBack();
      $this->closeConnection();
      die($e->getMessage());
    }
    $this->closeConnection();
	}
  public function renderEdit($pizzaDto) {
    $toppingService = new ToppingService();
    $sauceService = new SauceService();
    $doughService = new DoughService();

    $checkboxBuilder = new CheckboxBuilder();
    $dropdownBuilder = new DropdownBuilder();

    $sauces = $sauceService->mapToArray($sauceService->getAll(), true);
    $doughs = $doughService->mapToArray($doughService->getAll(), true);
    $toppingsAsArray = $toppingService->mapToArray($toppingService->getAll(), true);

    $toppingIds = [];
    $toppings = $pizzaDto->toppings ?? [];
    foreach ($pizzaDto->toppings as $key => $topping) {
      array_push($toppingIds, $topping->id);
    }
    echo "
    <div class='text-center'>
    <h2>New Pizza Edit</h2>
    <form class='profile-forms' action='' method='post'>
    <label class='white-text' for='name'>Name</label><br>
    <input class='input width-20' type='text' name='name' value='".$pizzaDto->name."'><br>";

    $dropdownBuilder->clear()->setSelectedKey($pizzaDto->dough->id)->setName('dough_id')->setTitle('Doughs')->setKeyName('dough_id')->setValueName('name')->setValues($doughs)->renderByValues();
    echo "<br>";
    $dropdownBuilder->clear()->setSelectedKey($pizzaDto->sauce->id)->setName('sauce_id')->setTitle('Sauces')->setKeyName('sauce_id')->setValueName('name')->setValues($sauces)->renderByValues();
    echo "<br>
    <h2>Feltétek</h2>";
    $checkboxBuilder->clear()->setCheckedKeys($toppingIds)->setValues($toppingsAsArray)->render();
    echo "<br>
    <input type=submit class='button' name='editPizza' value='Edit'></input>
    </form>
    </div>
    ";
  }

  public function mapToArray($pizzaDtos) {
    $arr = [];
    foreach ($pizzaDtos as $key => $value) {
      $little_arr = [
        'name' => $value->name, 
        'price' => $value->ar, 
        'pizza_id' => $value->id];
      array_push($arr, $little_arr);
    }
    return $arr;
  }
  private function mapPizzaToPizzaWithToppings($pizzas) {
    $pizzasWithToppings = Array();
    foreach ($pizzas as $key => $pizza) {
      $topping = new Topping();
      $topping->setId($pizza->topping_id)->setName($pizza->topping_name)->setPrice($pizza->topping_price)->setType($pizza->topping_type);
      if(array_key_exists($pizza->pizza_id, $pizzasWithToppings)) {
        array_push($pizzasWithToppings[$pizza->pizza_id]->toppings, $topping);
        continue;
      }
      $sauce = new Sauce();
      $sauce->setId($pizza->sauce_id)->setName($pizza->sauce_name)->setPrice($pizza->sauce_price);
      $dough = new Dough();
      $pasta->setId($pizza->dough_id)->setName($pizza->dough_name)->setPrice($pizza->dough_price);
      $newPizza = new PizzaWithToppings();
      $newPizza->setId($pizza->pizza_id)->setName($pizza->name)->setSauce($sauce)->setDough($dough)->addTopping($topping);
      $pizzasWithToppings[$pizza->pizza_id] = $newPizza;
    }
    return $pizzasWithToppings;
  }
}
?>