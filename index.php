<?php
require_once('config/imports.php');

$router = new Router();

?>

<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles.css">
    <title>Pizzeria</title>
</head>

<body>

<?php if(isset($_SESSION['person_id'])) {?>
<div class="navbar">
<?php 
if($_SESSION['account_type'] === 0) $router->renderCustomerMenus(); 
else if($_SESSION['account_type'] === 1) $router->renderAdminMenus();
?>
</div>

<div class="content">
		<?php 
		$router->renderPage();
		?>
</div>
<?php }else{
	$router->renderPage();
} ?>

</body>
</html>
