<?php 
session_start();
require_once 'helpers.php';
if(!isset($_SESSION['id'])) {
   header("location:login.php");
   exit();
}
else if(!empty($_SESSION["id"])){
   require 'connection.php';
   $id = $_SESSION["id"];
   $result = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$id'");
   $row = mysqli_fetch_assoc($result);
   $_SESSION['name'] = $row['name'];
}
render('header',array('title'=>'Dashboard','link'=>'dashboard.css','main'=>'main.css','heading'=>'DASHBOARD','log'=>'logout','page'=>'deposit','page2'=>'indexx'));

?>
 <div class="head">
    <h1 class="welcome">WELCOME <?php 
    echo $row['name'];
    ?>!</h1>
 </div>

<div class="outer">

<div class="inner">
<a class="btn" href="payment.php">
  <div class="w3-container w3-card-4 c1">
    <div class="card c2">
      <span>PAYMENT</span>
    </div>
  </div>
  </a>

  <a class="btn" href="profile.php">
  <div class="w3-container w3-card-4 c3">
    <div class="card c4">
      <span>PROFILE</span>
    </div>
  </div>
  </a>
  <a class="btn" href="menu.php">
  <div class="W3-container w3-card-4 c5">
    <div class="card c6">
      <span>Menu</span>
    </div>
  </div>
  </a>
</div>
</div>
<?php
render('footer');
?>