<?php 

class DrinkService extends Database implements CrudOperations {

  public function __construct() {}

  public function getAll() {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM drink");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Drink');
    $drinks = $stmt->fetchAll();
    $this->closeConnection();
    return $drinks;
  }
  public function get($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM drink WHERE id = :drink_id");
    $stmt->bindParam(':drink_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Drink');
    $drink = $stmt->fetch();
    $this->closeConnection();
    return $drink;
  }

  public function getByIdWithCost($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM drink WHERE id = :drink_id");
    $stmt->bindParam(':drink_id', $id, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'DrinkWithCost');
    $stmt->execute();
    $drink = $stmt->fetch();
    $this->closeConnection();
    return $drink;
  }

  public function update($drinkDto) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE drink SET name = :name, price = :price WHERE id = :drink_id");
    $stmt->bindParam(':drink_id', $drinkDto->id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $drinkDto->name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $drinkDto->price, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
  }
  public function delete($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("DELETE FROM drink WHERE id = :drink_id");
    $stmt->bindParam(':drink_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
  }
  public function create($drinkDto) {
    $conn = $this->getConnection();
    $conn
    ->prepare("INSERT INTO drink (name, price) VALUES (?, ?)")
    ->execute([$drinkDto->name, $drinkDto->price]);
    $this->closeConnection();
  }
  public function renderEdit($drinkDto) {
    echo "
    <div class='text-center'>
    <h2>Edit Drink</h2>
    <form class='profile-forms' action='' method='post'>
    
    <label class='white-text' for='name'>Name</label><br>
    <input class='input width-20' type='text' name='name' value='".$drinkDto->name."'><br>

    <label class='white-text' for='price'>Price</label><br>
    <input class='input width-20' type='number' name='price' value='".$drinkDto->price."'><br>

    <input type=submit class='button' name='editDrink' value='Edit'></input>
    </form>
    </div>
    ";
  }
  public function mapToArray($drinkDtos) {
    $arr = [];
    foreach ($drinkDtos as $key => $value) {
      $little_arr = [
        'name' => $value->name, 
        'price' => $value->price, 
        'drink_id' => $value->id];
      array_push($arr, $little_arr);
    }
    return $arr;
  }
}

?>