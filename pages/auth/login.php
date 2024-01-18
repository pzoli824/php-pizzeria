<?php 

$customerService = new CustomerService();
$authService = new AuthService();
$error = '';

if(isset($_POST['loginForm']))
{
  $existPerson = $authService->getPersonByEmailAndPassword($_POST['email'], $_POST['password']); 
  if($existPerson) {
    $customer = $customerService->getCustomerByPersonId($existPerson->id);
    AuthService::login($existPerson, $customer);
  }
  else $error = 'Check if you entered the correct login credentials!';
}
?>

<div class="index-card">

<div class="black-text text-center card index-choose-buyer">
<h3>Login</h3>

<form class="text-center" action="" method="post">

<?php 
echo "<p class='error'>".$error."</p>";
?>

<label for="email">Email</label><br>
<input class="input" type="email" name="email" id=""><br>

<label for="password">Password</label><br>
<input class="input" type="password" name="password" id=""><br>

<br>
<input class="button" name="loginForm" type="submit" value="Login">
</form>
<br>
<a href="index.php?page=register">Register</a>
</div>

</div>