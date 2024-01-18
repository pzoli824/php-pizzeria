<?php 
$pizzaService = new PizzaService();

if(isset($_POST['editPizza'])) {
  $p = new CreatePizza();
  $p->id = $_GET['edit'];
  $p->name = $_POST['name'];
  $p->dough_id = $_POST['dough_id'];
  $p->sauce_id = $_POST['sauce_id'];
  $checkboxes = isset($_POST['checkboxes']) ? $_POST['checkboxes'] : [];
  foreach ($_POST['checkboxes'] as $key => $check) {
    $p->addToppingId($check);
  }
  $pizzaService->update($p);
  Router::navigateByPage('pizzas');
}

if(isset($_GET['edit']) && !isset($_GET['remove'])) {
  $ps = $pizzaService->get($_GET['edit']);
  $p = array_shift($ps);
  $pizzaService->renderEdit($p);
}

?>