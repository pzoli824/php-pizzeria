<?php 
$orderService = new OrderService();
$authService = new AuthService();
$customerService = new CustomerService();

$orders = $orderService->getAllByCustomerId($_SESSION['customer_id']);
$paid = '';

if(isset($_GET['orderItem']) && isset($_GET['pay'])) {
  $index = $_GET['orderItem'];
  if($orders[$index]->price <= $_SESSION['balance']) {
  $customerService->payOrder($_SESSION['customer_id'], $orders[$index]->order_date, $orders[$index]->price);
  $paid = "<p class='green-text'>Payment was successful for the chosen orders!</p>";
  $authService->refreshSession($_SESSION['person_id']);
  } else {
    $paid = "<p class='red-text'>Not enough money to pay for the chosen orders!</p>";
  }
}
?>

<div class="content-card align-items-center">
<h1>Previous Orders</h1>
<p>In this table you can see all your previous orders.</p>
<?php if($paid) echo "$paid" ?>
<table class="custom-table">
  <tr>
    <th>Order date</th>
    <th>Price</th>
    <th>Paid</th>
    <th>Action</th>
  </tr>
  <?php
  foreach ($orders as $key => $order) {
  echo "
  <tr>
  <td><a class='white-text' href='index.php'>".$order->order_date."</a></td>
  <td>".$order->price."</td>
  <td>".$order->paid_date."</td>
  <td>";
  if($order->paid_date == null) echo "<a class='green-text' href='index.php?page=customer-order&orderItem=".$key."&pay=1'>Pay</a>";
  else echo '-';
  echo "</td>
  </tr>";
  }
  ?>
</table>
</div>