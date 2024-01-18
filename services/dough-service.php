<?php 

class PastaService extends Database implements CrudOperations {

  public function __construct() {}

  public function getAll() {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM dough");
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Dough');
    $stmt->execute();
    $dough = $stmt->fetchAll();
    $this->closeConnection();
    return $dough;
  }
  public function get($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM dough WHERE id = :dough_id");
    $stmt->bindParam(':dough_id', $id, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Dough');
    $stmt->execute();
    $dough = $stmt->fetch();
    $this->closeConnection();
    return $dough;
  }
  public function update($doughDto) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE dough SET name = :name, price = :price WHERE id = :dough_id");
    $stmt->bindParam(':dough_id', $doughDto->id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $doughDto->name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $doughDto->price, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
  }
  public function delete($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("DELETE FROM dough WHERE id = :dough_id");
    $stmt->bindParam(':dough_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
  }
  public function create($doughDto) {
    $conn = $this->getConnection();
    $conn
    ->prepare("INSERT INTO dough (name, price) VALUES (?, ?)")
    ->execute([$doughDto->name, $doughDto->price]);
    $this->closeConnection();
  }
  public function renderEdit($doughDto) {
    echo "
    <div class='text-center'>
    <h2>Edit Dough</h2>
    <form class='profile-forms' action='' method='post'>
    
    <label class='white-text' for='name'>Name</label><br>
    <input class='input width-20' type='text' name='name' value='".$doughDto->name."'><br>

    <label class='white-text' for='price'>Price</label><br>
    <input class='input width-20' type='number' name='price' value='".$doughDto->price."'><br>

    <input type=submit class='button' name='editPasta' value='Edit'></input>
    </form>
    </div>
    ";
  }
  public function mapToArray($doughs, $withCost = false) {
    $array = [];
    foreach ($doughs as $key => $dough) {
      $name = $withCost === false ? $dough->name : $dough->name.' ('.$dough->price.' Ft)';
      $array[$dough->id] = $name;
    }
    return $array;
  }
}


?>