<?php 
$doughService = new DoughService();
$doughs = $doughService->getAll();
?>

<div class="content-card">
  <div class="text-center">
    <h1>Doughs</h1>
  </div>

<?php 

if(isset($_GET['remove']) && !isset($_GET['edit'])) {
  $doughService->delete($_GET['remove']);
  Router::navigateByPage('dough');
}
if(isset($_GET['edit']) && !isset($_GET['remove'])) {
  $p = $doughService->get($_GET['edit']);
  $doughService->renderEdit($p);
}
if(isset($_POST['createDough'])) {
  $p = new Dough();
  $p->price = $_POST['price'];
  $p->name = $_POST['name'];
  $doughService->create($p);
  Router::navigateByPage('self');
}
if(isset($_POST['editDough'])) {
  $newPasta = new Dough();
  $newPasta->teszta_id = $_GET['edit'];
  $newPasta->name = $_POST['name'];
  $newPasta->price = $_POST['price'];
  $doughService->update($newPasta);
  Router::navigateByPage('dough');
}
?>

<table class="custom-table">
  <tr>
    <th>Name</th>
    <th>Price</th>
    <th>Action</th>
  </tr>
  <?php
  foreach ($doughs as $key => $dough) {
  echo "
  <tr>
  <td><p class='white-text'>".$dough->name."</p></td>
  <td>".$dough->price."</td>
  <td>
  <a class='red-text' href='index.php?page=dough&remove=".$dough->id."'>Delete</a>,
  <a class='green-yellow-text' href='index.php?page=dough&edit=".$dough->id."'>Edit</a>
  </td>
  </tr>";
  }
  ?>
</table>

<div class="text-center">
<h2>Create new dough</h2>
<form class="profile-forms" action="" method="post">
<label class="white-text" for="name">Name</label><br>
<input class="input width-20" type="text" name="name"><br>

<label class="white-text" for="price">Price</label><br>
<input class="input width-20" type="number" name="price"><br>

<input type=submit class="button" name='createDough' value="Create"></input>
</form>
</div>

</div>