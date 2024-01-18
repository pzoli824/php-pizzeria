<?php 

class AuthService extends Database {

  public function __construct() {}

  public function register($personDto) {
    $conn = $this->getConnection();
    try {
      $conn->beginTransaction();
      $stmt = $conn->prepare("INSERT INTO person (email, firstname, lastname) VALUES (:email, :firstname, :lastname)");
      $stmt->bindParam(':lastname', $personDto->lastname, PDO::PARAM_STR);
      $stmt->bindParam(':firstname', $personDto->firstname, PDO::PARAM_STR);
      $stmt->bindParam(':email', $personDto->email, PDO::PARAM_STR);
      $stmt->execute();

      $lastId = $conn->lastInsertId();

      if($personDto->password !== null && $personDto->password !== '') {
        $newPassword = crypt($personDto->password, 10);
        $stmtpass = $conn->prepare("UPDATE person SET password = :password WHERE id = :person_id");
        $stmtpass->bindParam(':person_id', $lastId, PDO::PARAM_INT);
        $stmtpass->bindParam(':password', $newPassword, PDO::PARAM_STR);
        $stmtpass->execute();
      }

      $conn->prepare("INSERT INTO customer (balance, person_id) VALUES (?, ?)")->execute([0, $lastId]);

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollBack();
      $this->closeConnection();
      die($e->getMessage());
    }
    $this->closeConnection();
  }
  public static function login($personDto, $customerDto = null) {
    $_SESSION['person_id'] = $personDto->id;
    $_SESSION['name'] = $personDto->getFullName();
    $_SESSION['email'] = $personDto->email;
    $_SESSION['account_type'] = $personDto->type;
    if($customerDto !== null) {
      $_SESSION['balance'] = $customerDto->balance;
      $_SESSION['customer_id'] = $customerDto->id;
    }
    
    Router::navigateByPage('home');
  }
  public static function logout() {
    session_destroy();
    Router::navigateByPage('login');
  }
  public static function isLoggedIn() {
    return $_SESSION['person_id'] !== null;
  }
  public static function isAdmin() {
    return $_SESSION['account_type'] === 1;
  }
  public function refreshSession($personId) {
    $person = $this->getPersonById($personId);
    $customerService = new CustomerService();
    $customer = $customerService->getCustomerByPersonId($personId);
    $_SESSION['person_id'] = $person->id;
    $_SESSION['name'] = $person->getFullName();
    $_SESSION['email'] = $person->email;
    $_SESSION['account_type'] = $person->type;
    if($customer) $_SESSION['balance'] = $customer->balance;
  }

  public function getPersonById($personId) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM person WHERE id = :person_id");
    $stmt->bindParam(':person_id', $personId, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Person');
    $stmt->execute();
    $user = $stmt->fetch();
    $this->closeConnection();
    return $user;
  }

  /**
   * @param mixed $personDto ezeket tartalmazza
   * - id
   * - email
   * - firstname
   * - lastname
   * - password
   */
  public function updatePerson($personDto) {
    $conn = $this->getConnection();
    try {
      $conn->beginTransaction();
      $stmt = $conn->prepare("UPDATE person SET email = :email, firstname = :firstname, lastname = :lastname WHERE id = :person_id");
      $stmt->bindParam(':person_id', $personDto->id, PDO::PARAM_INT);
      $stmt->bindParam(':lastname', $personDto->lastname, PDO::PARAM_STR);
      $stmt->bindParam(':firstname', $personDto->firstname, PDO::PARAM_STR);
      $stmt->bindParam(':email', $personDto->email, PDO::PARAM_STR);
      $stmt->execute();

      $newPassword = $personDto->getNewPassword();
      if($newPassword !== null && $newPassword !== '') {
        $newPassword = crypt($newPassword, 10);
        $stmtpass = $conn->prepare("UPDATE person SET password = :password WHERE id = :person_id");
        $stmtpass->bindParam(':person_id', $personDto->id, PDO::PARAM_INT);
        $stmtpass->bindParam(':password', $newPassword, PDO::PARAM_STR);
        $stmtpass->execute();
      }
      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollBack();
      $this->closeConnection();
      die($e->getMessage());
    }
    $this->closeConnection();
  }
  public function getAllPersons() {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM person WHERE type = 0");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $persons = $stmt->fetchAll();
    $this->closeConnection();
    return $persons;
  }

  public function getPersonByEmailAndPassword($email, $password) {
    $password = crypt($password, 10);
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM person WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
    $stmt->execute();
    $person = $stmt->fetch();
    $this->closeConnection();
    return $person;
  }

}
?>