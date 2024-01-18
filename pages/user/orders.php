<?php 
$builder = new DropdownBuilder();
$pizzaService = new PizzaService();
$drinkService = new DrinkService();
$cartService = unserialize($_SESSION['cart']);
$pizzas = $pizzaService->mapToArray($pizzaService->getAll());
$drinks = $drinkService->mapToArray($drinkService->getAll());
$quantity = [];
$cartOrders = $cartService->getCart();
for ($i=1; $i < 100; $i++) { 
  $quantity[$i] = $i;
}
if(isset($_POST['addPizzaToOrder'])) {
  $cartService->addToCart($_POST['pizza_id'], $_POST['pizza_quantity']);
}
if(isset($_POST['addDrinkToOrder'])) {
  $cartService->addToCart($_POST['drink_id'], $_POST['drink_quantity'], 'drink');
}
if(isset($_POST['saveOrder'])) {
  $orderService = new OrderService();
  $orderService->create($_SESSION['customer_id'], $cartOrders);
  $cartService->clearCart();
  Router::navigateByPage('self');
}
if(isset($_GET['removeItem'])) {
  $cartService->removeItemById($_GET['removeItem']);
  Router::navigateByPage('orders');
}
?>

<div class="content-card">
  <h1>Order</h1>

  <div>
  <form class="inline-flex-row cart-actions" action="" method="post">
  <?php 
  $builder->clear()->setName('pizza_id')->setValues($pizzas)->setValueName('name')->setKeyName('pizza_id')->setInfoKey('price')->render();
  $builder->clear()->setName('pizza_quantity')->setValues($quantity)->renderByValues();
  ?>
  <input class="button" name="addPizzaToOrder" style="padding: 0px" type="submit" value="Add">
  </form>
  </div>

  <div>
  <form class="inline-flex-row cart-actions" action="" method="post">
  <?php 
  $builder->clear()->setName('drink_id')->setValues($drinks)->setValueName('name')->setKeyName('drink_id')->setInfoKey('price')->render();
  $builder->clear()->setName('drink_quantity')->setValues($quantity)->renderByValues();
  ?>
  <input class="button" name="addDrinkToOrder" type="submit" value="Add">
  </form>
  </div>

  <h2>My Orders</h2>

  <?php if(empty($cartOrders)) {?>
    <p class="red-text">There are no orders currently!</p>
  <?php }else{ ?>
    <table class="custom-table">
  <tr>
    <th>Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Action</th>
  </tr>
  <?php
  foreach ($cartOrders as $key => $order) {
  echo "
  <tr>
  <td><p class='white-text'>".$order->name."</p></td>
  <td>".$order->price."</td>
  <td>".$order->quantity."</td>
  <td>
  <a class='red-text' href='index.php?page=orders&removeItem=".$order->id."'>Delete</a>
  </td>
  </tr>";
  }
  ?>
</table>
<form class="text-center" action="" method="post" style="margin-top: 20px">
<input class="button" name="saveOrder" type="submit" value="Send order">
</form>
  <?php } ?>
</div>