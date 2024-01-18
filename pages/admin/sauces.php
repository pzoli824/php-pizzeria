<?php 
$sauceService = new SauceService();
$sauces = $sauceService->getAll();
?>

<div class="content-card">
  <div class="text-center">
    <h1>Sauces</h1>
  </div>

<?php 

if(isset($_GET['remove']) && !isset($_GET['edit'])) {
  $sauceService->delete($_GET['remove']);
  Router::navigateByPage('sauces');
}
if(isset($_GET['edit']) && !isset($_GET['remove'])) {
  $s = $sauceService->get($_GET['edit']);
  $sauceService->renderEdit($s);
}
if(isset($_POST['createSauce'])) {
  $s = new Sauce();
  $s->price = $_POST['price'];
  $s->name = $_POST['name'];
  $sauceService->create($s);
  Router::navigateByPage('self');
}
if(isset($_POST['editSauce'])) {
  $newSauce = new Sauce();
  $newSauce->id = $_GET['edit'];
  $newSauce->name = $_POST['name'];
  $newSauce->price = $_POST['price'];
  $sauceService->update($newSauce);
  Router::navigateByPage('sauces');
}
?>

<table class="custom-table">
  <tr>
    <th>Name</th>
    <th>Price</th>
    <th>Action</th>
  </tr>
  <?php
  foreach ($sauces as $key => $sauce) {
  echo "
  <tr>
  <td><p class='white-text'>".$sauce->name."</p></td>
  <td>".$sauce->price."</td>
  <td>
  <a class='red-text' href='index.php?page=sauces&remove=".$sauce->id."'>Delete</a>,
  <a class='green-yellow-text' href='index.php?page=sauces&edit=".$sauce->id."'>Edit</a>
  </td>
  </tr>";
  }
  ?>
</table>

<div class="text-center">
<h2>Create new sauce</h2>
<form class="profile-forms" action="" method="post">
<label class="white-text" for="name">Name</label><br>
<input class="input width-20" type="text" name="name"><br>

<label class="white-text" for="ar">Price</label><br>
<input class="input width-20" type="number" name="price"><br>

<input type=submit class="button" name='createSauce' value="Create"></input>
</form>
</div>

</div>