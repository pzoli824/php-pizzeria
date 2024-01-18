<?php 

class SauceService extends Database implements CrudOperations {

  public function __construct() {}

  public function getAll() {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM sauce");
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Sauce');
    $stmt->execute();
    $sauces = $stmt->fetchAll();
    $this->closeConnection();
    return $sauces;
  }
  public function get($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM sauce WHERE id = :sauce_id");
    $stmt->bindParam(':sauce_id', $id, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Sauce');
    $stmt->execute();
    $sauce = $stmt->fetch();
    $this->closeConnection();
    return $sauce;
  }
  public function update($sauceDto) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE sauce SET name = :name, price = :price WHERE id = :sauce_id");
    $stmt->bindParam(':sauce_id', $sauceDto->id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $sauceDto->name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $sauceDto->price, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
  }
  public function delete($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("DELETE FROM sauce WHERE id = :sauce_id");
    $stmt->bindParam(':sauce_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
  }
  public function create($sauceDto) {
    $conn = $this->getConnection();
    $conn
    ->prepare("INSERT INTO sauce (name, price) VALUES (?, ?)")
    ->execute([$sauceDto->name, $sauceDto->price]);
    $this->closeConnection();
  }
  public function renderEdit($sauceDto) {
    echo "
    <div class='text-center'>
    <h2>Edit Sauce</h2>
    <form class='profile-forms' action='' method='post'>
    
    <label class='white-text' for='name'>Name</label><br>
    <input class='input width-20' type='text' name='name' value='".$sauceDto->name."'><br>

    <label class='white-text' for='price'>Price</label><br>
    <input class='input width-20' type='number' name='price' value='".$sauceDto->price."'><br>

    <input type=submit class='button' name='editSauce' value='Edit'></input>
    </form>
    </div>
    ";
  }
  public function mapToArray($sauces, $withCost = false) {
    $array = [];
    foreach ($sauces as $key => $sauce) {
      $name = $withCost === false ? $sauce->name : $sauce->name.' ('.$sauce->price.' Ft)';
      $array[$sauce->id] = $name;
    }
    return $array;
  }
}

?>