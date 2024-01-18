<?php 
class Customer {
  public $id;
  public $balance;
  public $person_id;

  public function __construct() {}
}

class CustomerWithPerson {
  public $customer_id;
  public $person_id;
  public $email;
  public $firstname;
  public $lastname;
  public $password;
  public $type;
  public $balance;
  public function __construct() {}
}

?>