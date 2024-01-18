<?php 
// Session uses the CartService so CartService has to be imported sooner than the session
require_once('services/cart-service.php');
require_once('models/cart-model.php');

// Configs
require_once('constants.php');
require_once('router.php');
require_once('database.php');
require_once('session.php');

// Services
require_once('services/pizza-service.php');
require_once('services/customer-service.php');
require_once('services/order-service.php');
require_once('services/drink-service.php');
require_once('services/topping-service.php');
require_once('services/sauce-service.php');
require_once('services/dough-service.php');
require_once('services/auth-service.php');


// Models
require_once('models/pizza-model.php');
require_once('models/drink-model.php');
require_once('models/order-model.php');
require_once('models/customer-model.php');
require_once('models/auth-model.php');


// Builders
require_once('builders/dropdown-builder.php');
require_once('builders/checkbox-builder.php');
?>