<?php 

class OrderService extends Database {

  public $currentOrder = [];
  public function __construct() {}

  public function getAllByCustomerId($customerId) {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM orders_with_pizzas_and_drinks WHERE customer_id = :customer_id ORDER BY order_date");
    $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Order');
    $stmt->execute();
    $orders = $stmt->fetchAll();
    $this->closeConnection();
    $orders = $this->mergeOrders($orders);
    $this->calculateCostOfOrders($orders);
    return $orders;
  }
  public function getAll() {
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM orders_with_pizzas_and_drinks ORDER BY order_date");
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Order');
    $stmt->execute();
    $orders = $stmt->fetchAll();
    $this->closeConnection();
    $orders = $this->mergeOrders($orders);
    $this->calculateCostOfOrders($orders);
    return $orders;
  }
  public function create($customerId, $itemDtos) {
    $date = date("Y-m-d H-i-s");
    $items = [];
    if(!is_array($itemDtos)) array_push($items, $itemDtos);
    else if(is_array($itemDtos)) $items = $itemDtos;
    $conn = $this->getConnection();
    try {
      $conn->beginTransaction();
      $conn
      ->prepare("INSERT INTO orders (customer_id, order_date) VALUES (?, ?)")
      ->execute([$customerId, $date]);
      $last_id = $conn->lastInsertId();
      foreach ($items as $key => $item) {
        if($item->pizza_id !== null)
        $conn
        ->prepare("INSERT INTO ordered_pizzas (customer_id, order_date, pizza_id, quantity) VALUES (:customer_id, :order_date, :pizza_id, :quantity)")
        ->execute(array(
          ':customer_id' => $customerId,
          ':order_date' => $date,
          ':pizza_id' => $item->pizza_id,
          ':quantity' => $item->quantity
        ));
        else if($item->drink_id !== null)
        $conn
        ->prepare("INSERT INTO ordered_drinks (customer_id, order_date, drink_id, quantity) VALUES (:customer_id, :order_date, :drink_id, :quantity)")
        ->execute(array(
          ':customer_id' => $customerId,
          ':order_date' => $date,
          ':drink_id' => $item->drink_id,
          ':quantity' => $item->quantity
        ));
      }
      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollBack();
      $this->closeConnection();
      die($e->getMessage());
    }
    $this->closeConnection();
  }

  private function mergeOrders($orders) {
    $newOrders = [];
    foreach ($orders as $key => $o) {
      $found = false;
      foreach ($newOrders as $key => $newO) {
        if($o->customer_id === $newO->customer_id && $o->order_date === $newO->order_date) {
          if($o->pizza_id !== null) $newO->addPizzaId($o->pizza_id, $o->pizza_quantity);
          if($o->drink_id !== null) $newO->addDrinkId($o->drink_id, $o->drink_quantity);
          $found = true;
          break;
        }
      }
      if(!$found) {
        if($o->pizza_id !== null) $o->addPizzaId($o->pizza_id, $o->pizza_quantity);
        if($o->drink_id !== null) $o->addDrinkId($o->drink_id, $o->drink_quantity);
        array_push($newOrders, $o);
      }
    }
    return $newOrders;
  }

  private function calculateCostOfOrders($orders) {
    $pizzaService = new PizzaService();
    $drinkService = new DrinkService();
    foreach ($orders as $key => $order) {
      foreach ($order->pizzas as $id => $quantity) {
        $pizza = $pizzaService->getByIdWithCost($id);
        $order->price += (int)$pizza->price*$quantity;
      }
      foreach ($order->drinks as $id => $quantity) {
        $drink = $drinkService->getByIdWithCost($id);
        $order->price += (int)$drink->price*$quantity;
      }
    }
  }
}

?>