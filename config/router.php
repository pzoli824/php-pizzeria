<?php 

class Router {

    private $userRoutes = [];
    private $adminRoutes = [];
    private $anonymRoutes = [];

    public function __construct() {
            $this->anonymRoutes = [
                'login' => 'pages/auth/login.php',
                'register' => 'pages/auth/register.php',
                'logout' => 'pages/auth/login.php',
                '' => 'pages/auth/login.php'
            ];

            $this->userRoutes = [
                'home' => 'pages/user/home.php',
                'customer-order' => 'pages/user/customer-order.php',
                'orders' => 'pages/user/orders.php',
                'profile' => 'pages/user/profile.php',
                '' => 'pages/user/home.php'
            ];
            $this->adminRoutes = [
                'home' => 'pages/admin/home.php',
				'customers' => 'pages/admin/customers.php',
				'pizzas' => 'pages/admin/pizzas.php',
                'pizza' => 'pages/admin/pizza.php',
				'drinks' => 'pages/admin/drinks.php',
				'orders' => 'pages/admin/orders.php',
				'sauces' => 'pages/admin/sauces.php',
				'dough' => 'pages/admin/dough.php',
				'toppings' => 'pages/admin/toppings.php',
                '' => 'pages/admin/home.php'
            ];
    }

    public function renderPage() {
        $routes = [];
        if(AuthService::isLoggedIn() && AuthService::isAdmin()) $routes = $this->adminRoutes;
        else if(AuthService::isLoggedIn() && !AuthService::isAdmin()) $routes = $this->userRoutes;
        else if(!AuthService::isLoggedIn()) $routes = $this->anonymRoutes;
        if(AuthService::isLoggedIn() && $this->isLogginOut()) {
            AuthService::logout();
            return;
        }
        if(!$this->isPageSet()) {
            include($routes['']);
            return;
        }
        if($_GET['page'] === '') Router::navigateByPage();
        $filePath = $routes[$_GET['page']];
        if(file_exists($filePath)) {
            include($filePath);
        }
        else if(!array_key_exists($_GET['page'], $routes)) Router::navigateByPage();
        else echo "This content doesn't exist";
    }

    private function isPageSet() {
        return isset($_GET['page']);
    }

    private function isLogginOut() {
        if(isset($_GET['page'])) return $_GET['page'] === 'logout';
        return false;
    }

    public static function navigateByPage($pageName = null) {
        if($pageName === 'self') header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
        else if($pageName !== null) header("Location: index.php?page=$pageName");
        else if($pageName === null) header("Location: index.php");
        die();
    }

    public function renderCustomerMenus () {
    	$balance = $_SESSION['balance'] ?? 0;
      echo "
      <ul>
      	<li><a href='index.php?page=orders'>Order</a></li>
        <li><a href='index.php?page=customer-order'>My orders</a></li>
        <li style='float: right'><a href='index.php?page=logout'>Logout</a></li>
        <li style='float: right'><a href='index.php?page=profile'>".$_SESSION['name']."</a></li>
        <li style='float: right'><a href='#' class='disabled'>".$balance." Ft</a></li>
      </ul>";
    }

    public function renderAdminMenus() {
        echo "
        <ul>
					<li><a href='index.php?page=customers'>Customers</a></li>
					<li><a href='index.php?page=pizzas'>Pizzas</a></li>
					<li><a href='index.php?page=drinks'>Drinks</a></li>
					<li><a href='index.php?page=orders'>Orders</a></li>
					<li><a href='index.php?page=sauces'>Sauces</a></li>
        	<li><a href='index.php?page=dough'>Doughs</a></li>
          <li><a href='index.php?page=toppings'>Toppings</a></li>
          <li style='float: right'><a href='index.php?page=logout'>Logout</a></li>
          <li style='float: right'><a href='index.php?page=profile'>".$_SESSION['name']."</a></li>
        </ul>";
    }
}


?>