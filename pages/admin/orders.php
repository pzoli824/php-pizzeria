<?php 
$orderService = new OrderService();
$orders = $orderService->getAll();

?>

<div class="content-card align-items-center">
<h1>Previous orders</h1>
<p>All your previous orders are displayed in the table below.</p>
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
  <td>-</td>
  </tr>";
  }
  ?>
</table>
</div>