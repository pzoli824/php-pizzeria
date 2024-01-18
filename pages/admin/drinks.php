<?php 
$drinkService = new DrinkService();
$drinks = $drinkService->getAll();
?>

<div class="content-card">
  <div class="text-center">
    <h1>Drinks</h1>
  </div>

<?php 

if(isset($_GET['remove']) && !isset($_GET['edit'])) {
  $drinkService->delete($_GET['remove']);
  Router::navigateByPage('drinks');
}
if(isset($_GET['edit']) && !isset($_GET['remove'])) {
  $d = $drinkService->get($_GET['edit']);
  $drinkService->renderEdit($d);
}
if(isset($_POST['createDrink'])) {
  $d = new Drink();
  $d->price = $_POST['price'];
  $d->name = $_POST['name'];
  $drinkService->create($d);
  Router::navigateByPage('self');
}
if(isset($_POST['editDrink'])) {
  $newDrink = new Drink();
  $newDrink->id = $_GET['edit'];
  $newDrink->name = $_POST['name'];
  $newDrink->price = $_POST['price'];
  $drinkService->update($newDrink);
  Router::navigateByPage('drinks');
}
?>

<table class="custom-table">
  <tr>
    <th>Name</th>
    <th>Price</th>
    <th>Action</th>
  </tr>
  <?php
  foreach ($drinks as $key => $drink) {
  echo "
  <tr>
  <td><p class='white-text'>".$drink->name."</p></td>
  <td>".$drink->price."</td>
  <td>
  <a class='red-text' href='index.php?page=drinks&remove=".$drink->id."'>Delete</a>,
  <a class='green-yellow-text' href='index.php?page=drinks&edit=".$drink->id."'>Edit</a>
  </td>
  </tr>";
  }
  ?>
</table>

<div class="text-center">
<h2>Create new drink</h2>
<form class="profile-forms" action="" method="post">
<label class="white-text" for="name">Name</label><br>
<input class="input width-20" type="text" name="name"><br>

<label class="white-text" for="price">Price</label><br>
<input class="input width-20" type="number" name="price"><br>

<input type=submit class="button" name='createDrink' value="Create"></input>
</form>
</div>

</div>