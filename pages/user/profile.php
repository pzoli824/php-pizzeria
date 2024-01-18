<?php 
$customerService = new CustomerService();
$authService = new AuthService();

$user = $authService->getPersonById($_SESSION['person_id']);
$customer = $customerService->getCustomerByPersonId($_SESSION['person_id']);
$emailValue = $user->email ?? '';
$firstnameValue = $user->firstname ?? '';
$lastnameValue = $user->lastname ?? '';
$balanceValue = $customer->balance ?? 0;

if(isset($_POST['saveProfile'])) {
  $newUser = new Person();
  $newUser->email = $_POST['email'];
  $newUser->firstname = $_POST['firstname'];
  $newUser->lastname = $_POST['lastname'];
  $newUser->setNewPassword($_POST['password']);
  $newUser->person_id = $user->person_id;
  $authService->updatePerson($newUser);
  $authService->refreshSession($_SESSION['person_id']);
  Router::navigateByPage('self');
}
if(isset($_POST['saveBalance'])) {
  $newBalance = $customerService->updateBalance($customer->id, $_POST['balance']);
  $_SESSION['balance'] = $newBalance;
  Router::navigateByPage('self');
}

?>

<div class="content-card align-items-center">
<h1>User Profile</h1>
<form class="text-center profile-forms" action="" method="post">
<label class="white-text" for="email">Email</label><br>
<input class="input" type="email" name="email" id="" value=<?php echo "$emailValue"?> ><br>

<label class="white-text" for="firstname">Firstname</label><br>
<input class="input" type="text" name="firstname" value=<?php echo "$firstnameValue"?> ><br>

<label class="white-text" for="lastname">Lastname</label><br>
<input class="input" type="text" name="lastname" value=<?php echo "$lastnameValue"?> ><br>

<label class="white-text" for="password">New Password</label><br>
<input class="input" type="text" name="password" id=""><br>

<input type=submit class="button" name='saveProfile' value="Save"></input>
</form>

<h2>Balance Upload</h2>
<form class="text-center profile-forms" action="" method="post">
<label class="white-text" for="balance">New Balance</label><br>
<input class="input" type="number" name="balance" style="margin-top: 15px;margin-bottom: 15px; width: 20%;" value=<?php echo "$balanceValue"?> ><br>
<input type=submit class="button" name='saveBalance' value="Upload"></input>
</form>
</div>