<?php 

class Order {
  public $customer_id;
  public $order_date;
  public $paid_date;
  public $pizza_id;
  public $pizza_quantity;
  public $drink_id;
  public $drink_quantity;
  public $pizzas = [];
  public $drinks = [];
  public $price;
  public function __construct() {}

  public function addPizzaId($id, $quantity = 1) {
    if(!array_key_exists($id, $this->pizzas)) $this->pizzas[$id] = (int)$quantity;
    return $this;
  }
  public function addDrinkId($id, $quantity = 1) {
    if(!array_key_exists($id, $this->drinks)) $this->drinks[$id] = (int)$quantity;
    return $this;
  }
}

class IdWithQuantity {
  public $id;
  public $quantity;
  public function __construct($id, $quantity) {
    $this->id = $id;
    $this->quantity = $quantity;
  }
}

class OrderedPizzas {
  public $customer_id;
  public $order_date;
  public $pizza_id;
  public $quantity = 1;
  public function __construct($customer_id, $order_date, $pizza_id, $quantity = null) {
    $this->customer_id = $customer_id;
    $this->order_date = $order_date;
    $this->pizza_id = $pizza_id;
    $this->quantity = $quantity;
  }
}

class OrderedDrinks {
  public $customer_id;
  public $order_date;
  public $drink_id;
  public $quantity = 1;
  public function __construct($customer_id, $order_date, $drink_id, $quantity = null) {
    $this->customer_id = $customer_id;
    $this->order_date = $order_date;
    $this->drink_id = $drink_id;
    $this->quantity = $quantity;
  }
}

?>