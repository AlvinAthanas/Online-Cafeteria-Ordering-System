<?php
require 'connection.php';
session_start();

if(isset($_POST["submit"])) {

    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $result = mysqli_query($conn, "SELECT * FROM `admin` WHERE `name` ='$name'");
    $row = mysqli_fetch_assoc($result); 
    if(mysqli_num_rows($result) > 0){
        if($pass == $row["password"]){
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["user_id"];
             header("location: admin.php");
        }
        else{
            $validity = "Please enter the correct password";
        }
    } else{
        $validity = "Please register";
    }

}

require_once 'helpers.php';
render('header',array('title'=>'login','link'=>'login.css','main'=>'main.css','heading'=>'Login','log'=>'','page'=>'','page2'=>''));

?>
<div class="container mti-3">
<form action="adminlogin.php" method="POST">
<div class="form-floating mb-3 mt-3">
      <input type="text" class="form-control" id="name" placeholder="Enter ID" name="name">
      <label for="name">Name</label>
</div>
<div class="form-floating mb-3 mt-3">
      <input type="password" class="form-control" id="pass" placeholder="Enter password" name="pass">
      <label for="pass">Password</label>
      <span class="no_acc"><?php if(isset($_POST['submit'])) echo $validity; ?></span>
</div>
<button type="submit" name="submit" class="btn">Submit</button> <br>
<span class="no_acc">Don't have an account?<a id="no_acc" href="signup.php">sign up</a></span>
</form>
</div>
<?php
render('footer');
?>






