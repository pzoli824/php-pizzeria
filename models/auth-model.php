<?php 

class Person {
  public $id;
  public $firstname;
  public $lastname;
  public $email;
  public $type;
  private $newPassword;

  public function __construct() {}

  public function getFullName() {
    return $this->lastname.' '.$this->firstname;
  }
  public function setNewPassword($p) {
    $this->newPassword = $p;
  }

  public function getNewPassword() {
    return $this->newPassword;
  }
}

class RegisterPerson {
  public $email;
  public $lastname;
  public $firstname;
  public $password;

  public function __construct() {}
}

?>