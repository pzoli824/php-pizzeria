<?php 
class CustomerService extends Database implements CrudOperations {

  public function __construct() {}

  public function getAll() {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM customers_with_persons_account");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'CustomerWithPerson');
    $customers = $stmt->fetchAll();
    $this->closeConnection();
    return $customers;
  }

  public function getCustomerByPersonId($personId) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM customer WHERE person_id = :person_id");
    $stmt->bindParam(':person_id', $personId, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Customer');
    $stmt->execute();
    $customer = $stmt->fetch();
    $this->closeConnection();
    return $customer;
  }
  public function updateBalance($customerId, $newBalance) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE customer SET balance = :balance WHERE id = :customer_id");
    $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
    $stmt->bindParam(':balance', $newBalance, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
    return $newBalance;
  }

  function payOrder($customerId, $when, $cost) {
    $customer = $this->get($customerId);
    $date = date("Y-m-d H-i-s");
    $conn = $this->getConnection();
    try {
      $conn->beginTransaction();

      $newBalance = $customer->balance - $cost;
      $stmt = $conn->prepare("UPDATE customer SET balance = :balance WHERE id = :customer_id");
      $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
      $stmt->bindParam(':balance', $newBalance, PDO::PARAM_INT);
      $stmt->execute();

      $stmt2 = $conn->prepare("UPDATE orders SET paid_date = :paid_date WHERE customer_id = :customer_id AND order_date = :order_date");
      $stmt2->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
      $stmt2->bindParam(':order_date', $when);
      $stmt2->bindParam(':paid_date', $date);
      $stmt2->execute();

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollBack();
      $this->closeConnection();
      die($e->getMessage());
    }
    $this->closeConnection();
  }
  public function get($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM customers_with_persons_account WHERE customer_id = :customer_id");
    $stmt->bindParam(':customer_id', $id, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Customer');
    $stmt->execute();
    $customer = $stmt->fetch();
    $this->closeConnection();
    return $customer;
  }
  public function delete($id) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("DELETE FROM customer WHERE id = :customer_id");
    $stmt->bindParam(':customer_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $this->closeConnection();
  }
  public function create($dto) {}
  public function createCustomerAndPerson($customerWithPersonDto) {
    $conn = $this->getConnection();
    try {
      $conn->beginTransaction();

      $conn
      ->prepare("INSERT INTO person (email, lastname, firstname, password, type) VALUES (?, ?, ?, ?, ?)")
      ->execute(
        [
          $customerWithPersonDto->email, 
          $customerWithPersonDto->lastname,
          $customerWithPersonDto->firstname,
          $customerWithPersonDto->password,
          0
        ]);
      $lastInsertId = $conn->lastInsertId();

      $conn
      ->prepare("INSERT INTO customer (balance, person_id) VALUES (?, ?)")
      ->execute([$customerWithPersonDto->balance, $lastInsertId]);

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollBack();
      $this->closeConnection();
      die($e->getMessage());
    }
    $this->closeConnection();
  }
  public function update($customerWithPersonDto) {
    $oldCustomer = $this->get($customerWithPersonDto->customer_id);
    $conn = $this->getConnection();
    try {
      $conn->beginTransaction();

      $stmt = $conn
      ->prepare("UPDATE person 
      SET email = :email, lastname = :lastname, firstname = :firstname, password = :password
      WHERE id = :person_id");
      $stmt->bindParam(':email', $customerWithPersonDto->email, PDO::PARAM_STR);
      $stmt->bindParam(':lastname', $customerWithPersonDto->lastname, PDO::PARAM_STR);
      $stmt->bindParam(':firstname', $customerWithPersonDto->firstname, PDO::PARAM_STR);
      $stmt->bindParam(':password', $customerWithPersonDto->password, PDO::PARAM_STR);
      $stmt->bindParam(':person_id', $oldCustomer->person_id, PDO::PARAM_INT);
      $stmt->execute();

      $stmt2 = $conn->prepare("UPDATE customer SET balance = :balance WHERE id = :customer_id");
      $stmt2->bindParam(':balance', $customerWithPersonDto->balance, PDO::PARAM_INT);
      $stmt2->bindParam(':customer_id', $customerWithPersonDto->customer_id, PDO::PARAM_INT);
      $stmt2->execute();

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollBack();
      $this->closeConnection();
      die($e->getMessage());
    }
    $this->closeConnection();
  }
  public function renderEdit($customerWithPersonDto) {
    echo "
    <div class='text-center'>
    <h2>Edit Customer and Person informations</h2>
    <form class='profile-forms' action='' method='post'>
    
    <label class='white-text' for='email'>Email</label><br>
    <input class='input width-20' type='email' name='email' value='".$customerWithPersonDto->email."'><br>

    <label class='white-text' for='lastname'>Lastname</label><br>
    <input class='input width-20' type='text' name='lastname' value='".$customerWithPersonDto->lastname."'><br>

    <label class='white-text' for='firstname'>Firstname</label><br>
    <input class='input width-20' type='text' name='firstname' value='".$customerWithPersonDto->firstname."'><br>

    <label class='white-text' for='password'>Password</label><br>
    <input class='input width-20' type='password' name='password' value='".$customerWithPersonDto->password."'><br>

    <label class='white-text' for='balance'>Balance</label><br>
    <input class='input width-20' type='number' name='balance' value='".$customerWithPersonDto->balance."'><br>

    <input type=submit class='button' name='editCustomerAndUser' value='Edit'></input>
    </form>
    </div>
    ";
  }
  public function getAccountTypeAsText($type) {
    return ACCOUNT_TYPES[$type];
  }
}
?>