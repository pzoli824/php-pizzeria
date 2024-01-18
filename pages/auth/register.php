<?php 

if(isset($_POST['register'])) {
    $authService = new AuthService();
    $u = new RegisterPerson();
    $u->email = $_POST['email'];
    $u->firstname = $_POST['firstname'];
    $u->lastname = $_POST['lastname'];
    $u->password = $_POST['password'];
    $authService->register($u);
    Router::navigateByPage('login');
}
?>


<div class="index-card">

<div class="black-text text-center card index-create-buyer">
    <h3>Create a new account!</h3>

        <form action="" method="post">

        <label for="email">Email</label><br>
        <input class="input" type="email" name="email" id=""><br>

        <label for="password">Password</label><br>
        <input class="input" type="password" name="password" id=""><br>

        <label for="lastname">Lastname</label><br>
        <input class="input" type="text" name="lastname" id=""><br>
        
        <label for="firstname">Firstname</label><br>
        <input class="input" type="text" name="firstname" id=""><br>

        <input class="button" type="submit" name="register" value="Register">
    </form>
    <br>
<a href="index.php?page=login">Login</a>
</div>
</div>