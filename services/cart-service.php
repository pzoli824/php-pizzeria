<?php 
class CartService {
  private $currentOrder = [];
  private $currentIncrementer = 1;

  public function __construct() {}

  public function getCart() {
    return $this->currentOrder;
  }
  public function clearCart() {
    $this->currentOrder = [];
    $_SESSION['cart'] = serialize($this);
  }
  public function addToCart($id, $quantity, $type = 'pizza') {
    if($type == 'pizza') {
      if($this->isInCurrentOrder($id)) {
        $this->removeItem($id);
        $item = $this->createItem($id, $quantity);
        $this->saveItem($item);
      }else{
        $item = $this->createItem($id, $quantity);
        $this->saveItem($item);
      }
    }else if ($type == 'drink') {
      if($this->isInCurrentOrder($id, 'drink')) {
        $this->removeItem($id, 'drink');
        $item = $this->createItem($id, $quantity, 'drink');
        $this->saveItem($item);
      }else{
        $item = $this->createItem($id, $quantity, 'drink');
        $this->saveItem($item);
      }
    }
  }
  public function removeItemById($itemId) {
    unset($this->currentOrder[$itemId]);
    $_SESSION['cart'] = serialize($this);
  }
  private function saveItem($item) {
    $this->currentOrder[$item->id] = $item;
    $_SESSION['cart'] = serialize($this);
    Router::navigateByPage('self');
  }
  private function createItem($id, $quantity, $type = 'pizza') {
    $item = new Item();
    while(array_key_exists($this->currentIncrementer, $this->currentOrder)) {$this->currentIncrementer++;}
    if($type === 'pizza') {
      $pizzaService = new PizzaService();
      $pizza = $pizzaService->getByIdWithCost($id);
      $item->setId($this->currentIncrementer)->pizzaId($pizza->id)->quantity($quantity)->price($pizza->price)->name($pizza->name);
    }else if($type === 'drink') {
      $drinkService = new DrinkService();
      $drink = $drinkService->getByIdWithCost($id);
      $item->setId($this->currentIncrementer)->drinkId($drink->id)->quantity($quantity)->price($drink->price)->name($drink->name);
    }
    $this->currentIncrementer++;
    return $item;
  }
  private function removeItem($id, $search = 'pizza') {
    if($search === 'pizza')
    foreach ($this->currentOrder as $key => $value) {
      if($value->id == $id) {
        unset($this->currentOrder[$key]);
        break;
      }
    }
    else if($search === 'drink')
    foreach ($this->currentOrder as $key => $value) {
      if($value->id == $id) {
        unset($this->currentOrder[$key]);
        break;
      }
    }
    $_SESSION['kosar'] = serialize($this);
  }
  private function isInCurrentOrder($id, $type = 'pizza') {
    if($type == 'pizza')
    foreach ($this->currentOrder as $key => $value) {
      if($value->id == $id) return true;
    }
    else if ($type == 'drink')
    foreach ($this->currentOrder as $key => $value) {
      if($value->id == $id) return true;
    }
    return false;
  }
}
?>