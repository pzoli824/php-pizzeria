<?php 
$customerService = new CustomerService();
$customers = $customerService->getAll();
?>

<div class="content-card">
  <div class="text-center">
    <h1>Customers</h1>
  </div>

<?php 

if(isset($_GET['remove']) && !isset($_GET['edit'])) {
  $customerService->delete($_GET['remove']);
  Router::navigateByPage('customers');
}
if(isset($_GET['edit']) && !isset($_GET['remove'])) {
  $c = $customerService->get($_GET['edit']);
  $customerService->renderEdit($c);
}
if(isset($_POST['createCustomerAndUser'])) {
  $c = new CustomerWithUser();
  $c->email = $_POST['email'];
  $c->lastname = $_POST['lastname'];
  $c->firstname = $_POST['firstname'];
  $c->password = $_POST['password'];
  $c->balance = $_POST['balance'];
  $customerService->createCustomerAndPerson($c);
  Router::navigateByPage('self');
}
if(isset($_POST['editCustomerAndUser'])) {
  $newCustomer = new CustomerWithUser();
  $newCustomer->ugyfel_id = $_GET['edit'];
  $newCustomer->email = $_POST['email'];
  $newCustomer->lastname = $_POST['lastname'];
  $newCustomer->firstname = $_POST['firstname'];
  $newCustomer->password = $_POST['password'];
  $newCustomer->balance = $_POST['balance'];
  $customerService->update($newCustomer);
  Router::navigateByPage('customers');
}
?>

<table class="custom-table">
  <tr>
    <th>Customer ID</th>
    <th>User Account ID</th>
    <th>Email</th>
    <th>Lastname</th>
    <th>Firstname</th>
    <th>Password</th>
    <th>Type</th>
    <th>Balance</th>
    <th>Action</th>
  </tr>
  <?php
  foreach ($customers as $key => $customer) {
  echo "
  <tr>
  <td>".$customer->id."</td>
  <td>".$customer->person_id."</td>
  <td>".$customer->email."</td>
  <td>".$customer->lastname."</td>
  <td>".$customer->firstname."</td>
  <td>".$customer->password."</td>
  <td>".$customerService->getAccountTypeAsText($customer->type)."</td>
  <td>".$customer->balance."</td>
  <td>
  <a class='red-text' href='index.php?page=customers&remove=".$customer->id."'>Delete</a>,
  <a class='green-yellow-text' href='index.php?page=customers&edit=".$customer->id."'>Edit</a>
  </td>
  </tr>";
  }
  ?>
</table>

<div class="text-center">
<h2>Create new Customer with User Account</h2>
<form class="profile-forms" action="" method="post">
<label class="white-text" for="email">Email</label><br>
<input class="input width-20" type="email" name="email"><br>

<label class="white-text" for="lastname">Lastname</label><br>
<input class="input width-20" type="text" name="lastname"><br>

<label class="white-text" for="firstname">Firstname</label><br>
<input class="input width-20" type="text" name="firstname"><br>

<label class="white-text" for="password">Password</label><br>
<input class="input width-20" type="password" name="password"><br>

<label class="white-text" for="balance">Balance</label><br>
<input class="input width-20" type="number" name="balance"><br>

<input type=submit class="button" name='createCustomerAndUser' value="Create"></input>
</form>
</div>

</div>