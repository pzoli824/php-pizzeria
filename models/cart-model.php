<?php 
class Item {
  public $id;
  public $pizza_id;
  public $drink_id;
  public $quantity;
  public $price;
  public $name;
  public function __construct() {}
  public function setId($id) {
    $this->id = $id;
    return $this;
  }
  public function pizzaId($id) {
    $this->pizza_id = $id;
    return $this;
  }
  public function drinkId($id) {
    $this->drink_id = $id;
    return $this;
  }
  public function quantity($quantity) {
    $this->quantity = $quantity;
    return $this;
  }
  public function price($cost) {
    $this->price = $cost;
    return $this;
  }
  public function name($name) {
    $this->name = $name;
    return $this;
  }
}
?>