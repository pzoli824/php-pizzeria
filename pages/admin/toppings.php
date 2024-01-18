<?php 
$toppingService = new ToppingService();
$dropdownBuilder = new DropdownBuilder();
$toppings = $toppingService->getAll();
?>

<div class="content-card">
  <div class="text-center">
    <h1>Toppings</h1>
  </div>

<?php 

if(isset($_GET['remove']) && !isset($_GET['edit'])) {
  $toppingService->delete($_GET['remove']);
  Router::navigateByPage('toppings');
}
if(isset($_GET['edit']) && !isset($_GET['remove'])) {
  $t = $toppingService->get($_GET['edit']);
  $toppingService->renderEdit($t);
}
if(isset($_POST['createTopping'])) {
  $t = new Topping();
  $t->price = $_POST['price'];
  $t->name = $_POST['name'];
  $t->type = (int)$_POST['type'];
  $toppingService->create($t);
  Router::navigateByPage('self');
}
if(isset($_POST['editTopping'])) {
  $newTopping = new Topping();
  $newTopping->id = $_GET['edit'];
  $newTopping->name = $_POST['name'];
  $newTopping->price = $_POST['price'];
  $newTopping->type = (int)$_POST['type'];
  $toppingService->update($newTopping);
  Router::navigateByPage('toppings');
}
?>

<table class="custom-table">
  <tr>
    <th>Name</th>
    <th>Type</th>
    <th>Price</th>
    <th>Action</th>
  </tr>
  <?php
  foreach ($toppings as $key => $topping) {
  echo "
  <tr>
  <td><p class='white-text'>".$topping->name."</p></td>
  <td><p class='white-text'>".$toppingService->getToppingTypeAsString($topping->type)."</p></td>
  <td>".$topping->price."</td>
  <td>
  <a class='red-text' href='index.php?page=toppings&remove=".$topping->id."'>Delete</a>,
  <a class='green-yellow-text' href='index.php?page=toppings&edit=".$topping->id."'>Edit</a>
  </td>
  </tr>";
  }
  ?>
</table>

<div class="text-center">
<h2>Create new topping</h2>
<form class="profile-forms" action="" method="post">
<label class="white-text" for="name">Name</label><br>
<input class="input width-20" type="text" name="name"><br>

<label class="white-text" for="price">Price</label><br>
<input class="input width-20" type="number" name="price"><br>

<?php $dropdownBuilder->setName('type')->setTitle('Type')->setValues(TOPPING_TYPES)->renderByValues(); ?>
<br>

<input type=submit class="button" name='createTopping' value="Create"></input>
</form>
</div>

</div>