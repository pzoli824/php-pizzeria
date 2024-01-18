<?php 
$pizzaService = new PizzaService();
$toppingService = new ToppingService();
$sauceService = new SauceService();
$doughService = new DoughService();

$checkboxBuilder = new CheckboxBuilder();
$dropdownBuilder = new DropdownBuilder();

$toppingsAsArray = $toppingService->mapToArray($toppingService->getAll(), true);
$pizzas = $pizzaService->getAll();
$sauces = $sauceService->mapToArray($sauceService->getAll(), true);
$doughs = $doughService->mapToArray($doughService->getAll(), true);
?>

<div class="content-card">
  <div class="text-center">
    <h1>Pizzas</h1>
  </div>

<?php 

if(isset($_GET['remove']) && !isset($_GET['edit'])) {
  $pizzaService->delete($_GET['remove']);
  Router::navigateByPage('pizzas');
}
if(isset($_GET['edit']) && !isset($_GET['remove'])) {
  Router::navigateByPage('pizza&edit='.$_GET['edit']);
}

if(isset($_POST['createPizza'])) {
  $p = new CreatePizza();
  $p->name = $_POST['name'];
  $p->dough_id = $_POST['dough_id'];
  $p->sauce_id = $_POST['sauce_id'];
  $checkboxes = isset($_POST['checkboxes']) ? $_POST['checkboxes'] : [];
  foreach ($_POST['checkboxes'] as $key => $check) {
    $p->addToppingId($check);
  }
  $pizzaService->create($p);
  Router::navigateByPage('pizzas');
}
?>

<table class="custom-table">
  <tr>
    <th>Name</th>
    <th>Price</th>
    <th>Action</th>
  </tr>
  <?php
  foreach ($pizzas as $key => $pizza) {
  echo "
  <tr>
  <td><p class='white-text'>".$pizza->name."</p></td>
  <td>".$pizza->price."</td>
  <td>
  <a class='red-text' href='index.php?page=pizzas&remove=".$pizza->pizza_id."'>Delete</a>,
  <a class='green-yellow-text' href='index.php?page=pizzas&edit=".$pizza->pizza_id."'>Edit</a>
  </td>
  </tr>";
  }
  ?>
</table>

<div class="text-center">
<h2>Create new pizza</h2>
<form class="profile-forms" action="" method="post">
<label class="white-text" for="name">Name</label><br>
<input class="input width-20" type="text" name="name"><br>

<?php $dropdownBuilder->clear()->setName('dough_id')->setTitle('Doughs')->setKeyName('dough_id')->setValueName('name')->setValues($doughs)->renderByValues(); ?>
<br>
<?php $dropdownBuilder->clear()->setName('sauce_id')->setTitle('Sauces')->setKeyName('sauce_id')->setValueName('name')->setValues($sauces)->renderByValues(); ?>
<br>
<h2>Toppings</h2>
<?php $checkboxBuilder->setValues($toppingsAsArray)->render(); ?>
<br>
<input type=submit class="button" name='createPizza' value="Create"></input>
</form>
</div>

</div>