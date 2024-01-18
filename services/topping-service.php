<?php 

class ToppingService extends Database implements CrudOperations {

  public function __construct() {}

  public function getAll() {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM topping");
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Topping');
    $stmt->execute();
    $toppings = $stmt->fetchAll();
    $this->closeConnection();
    return $toppings;
  }
  public function get($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM topping WHERE id = :topping_id");
    $stmt->bindParam(':topping_id', $id, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Topping');
    $stmt->execute();
    $topping = $stmt->fetch();
    $this->closeConnection();
    return $topping;
  }
  public function update($toppingDto) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE topping SET name = :name, price = :price, type = :type WHERE id = :topping_id");
    $stmt->bindParam(':topping_id', $toppingDto->id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $toppingDto->name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $toppingDto->price, PDO::PARAM_INT);
    $stmt->bindParam(':type', $toppingDto->type, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
  }
  public function delete($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("DELETE FROM topping WHERE id = :topping_id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
  }
  public function create($toppingDto) {
    $conn = $this->getConnection();
    $conn
    ->prepare("INSERT INTO topping (name, price, type) VALUES (?, ?, ?)")
    ->execute([$toppingDto->name, $toppingDto->price, $toppingDto->type]);
    $this->closeConnection();
  }
  public function getToppingTypeAsString($type) {
    switch ($type) {
      case 0:
        return 'Meat, fish, egg';
      case 1:
        return 'Vegetable';
      case 2:
        return 'Cheese';
    }
  }
  public function mapToArray($toppings, $withCost = false) {
    $array = [];
    foreach ($toppings as $key => $topping) {
      $name = $withCost === false ? $topping->name : $topping->name.' ('.$topping->price.' Ft)';
      $array[$topping->id] = $name;
    }
    return $array;
  }
  public function renderEdit($toppingDto) {
    $dropdownBuilder = new DropdownBuilder();
    echo "
    <div class='text-center'>
    <h2>Edit Topping</h2>
    <form class='profile-forms' action='' method='post'>
    
    <label class='white-text' for='name'>Name</label><br>
    <input class='input width-20' type='text' name='name' value='".$toppingDto->name."'><br>

    <label class='white-text' for='price'>Price</label><br>
    <input class='input width-20' type='number' name='price' value='".$toppingDto->price."'><br>";

    $dropdownBuilder->setName('type')->setTitle('Type')->setValues(TOPPING_TYPES)->renderByValues();

    echo "
    <br>
    <input type=submit class='button' name='editTopping' value='Edit'></input>
    </form>
    </div>
    ";
  }
}

?>