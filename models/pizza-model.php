<?php
class Pizza {
  public $pizza_id;
  public $name;
  public $dough_id;
  public $dough_name;
  public $dough_price;
  public $sauce_id;
  public $sauce_name;
  public $sauce_price;
  public $topping_id;
  public $topping_name;
  public $topping_price;
  public $topping_type;
  public function __construct() {}
}
class PizzaWithToppings {
  public $pizza_id;
  public $name;
  public $dough;
  public $sauce;
  public $toppings = [];
  public function __construct() {}
  public function setId($id) {
    $this->pizza_id = $id;
    return $this;
  }
  public function setName($name) {
    $this->name = $name;
    return $this;
  }
  public function setDough($dough) {
    $this->dough = $dough;
    return $this;
  }
  public function setSauce($sauce) {
    $this->sauce = $sauce;
    return $this;
  }
  public function addTopping($topping) {
    array_push($this->toppings, $topping);
    return $this;
  }
}

class PizzaWithCost {
  public $pizza_id;
  public $name;
  public $price;
  public function __construct() {}
}

class CreatePizza {
  public $pizza_id;
  public $name;
  public $dough_id;
  public $sauce_id;
  public $topping_ids = [];

  public function __construct() {}

  public function addToppingId($id) {
    array_push($this->topping_ids, $id);
    return $this;
  }
}
class Dough {
  public $id;
  public $name;
  public $price;

  public function __construct() {}
  public function setId($id) {
    $this->id = $id;
    return $this;
  }
  public function setName($name) {
    $this->name = $name;
    return $this;
  }
  public function setPrice($price) {
    $this->price = $price;
    return $this;
  }
}
class Sauce {

  public $id;
  public $name;
  public $price;
  public function __construct() {}
  public function setId($id) {
    $this->id = $id;
    return $this;
  }
  public function setName($name) {
    $this->name = $name;
    return $this;
  }
  public function setPrice($price) {
    $this->price = $price;
    return $this;
  }
}
class Topping {

  public $id;
  public $name;
  public $price;
  public $type;
  public function __construct() {}
  public function setId($id) {
    $this->id = $id;
    return $this;
  }
  public function setName($name) {
    $this->name = $name;
    return $this;
  }
  public function setPrice($price) {
    $this->price = $price;
    return $this;
  }
  public function setType($type) {
    $this->type = $type;
    return $this;
  }
}
?>