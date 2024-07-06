<?php 
require 'connection.php';
include 'session.php';
// var_dump($_SESSION['id']);
// var_dump($_SESSION['order']);
if(!empty($_SESSION['id'])){
   $receiptNumber = rand(10000, 99999);
   $type = "payment";
   $user_id = $_SESSION["id"];
   $orderQuery = mysqli_query($conn, "SELECT * FROM `order` WHERE user_id = '$user_id' ORDER BY order_id DESC LIMIT 1");

   if(mysqli_num_rows($orderQuery)>0){

      $orderrow = mysqli_fetch_assoc($orderQuery);
      $order_id = $orderrow['order_id'];
      $orderedMeals = explode(',', $orderrow['meals']);
      $name = $_SESSION['name'];

      if(mysqli_num_rows($orderQuery)>0){

         $total = $orderrow['total'];
         $transactionQuery = "SELECT * FROM `transaction` WHERE `user_id` = '$user_id' AND `user_id` = '$user_id' ORDER BY `transaction_id` DESC LIMIT 1";
         $transactionSql = mysqli_query($conn, $transactionQuery);
         $transactionRow = mysqli_fetch_assoc($transactionSql);


         if(isset($_POST['submit'])){

            if($transactionSql->num_rows > 0){
               $balance = $transactionRow['balance'];

               if($balance >= $total){

                  $status = "paid";  
                  $newBalance = $balance - $total;
                  $transaction = "INSERT INTO `transaction` VALUES ('','$user_id','$type','$total','$newBalance', NOW())";
                  $payment = "INSERT INTO `payment` VALUES ('','$order_id','$total','$status','$receiptNumber', NOW())";
                  if($conn->query($payment) === TRUE  && $conn->query($transaction)){
                     $message = "Payment successfully";
                  }
                  else{
                     echo  $conn->error; 
                  }
               }
               else {

                  $status = "Not paid";
                  $transaction = "INSERT INTO `transaction` VALUES ('','$user_id','$type',0,'$balance', NOW())";
                  $payment = "INSERT INTO `payment` VALUES ('','$order_id','$total','$status',NULL, NOW())";
                  $message = "You don't have enough cash in your wallet";
                  
                  if($conn->query($payment) !== TRUE || $conn->query($transaction)){
                     echo  $conn->error;
                  }
               }
            }
            else {

               $status = "Not paid";
               $transaction = "INSERT INTO `transaction` VALUES ('','$user_id','$type',0,0, NOW())";
               $payment = "INSERT INTO `payment` VALUES ('','$order_id','$total','$status',NULL, NOW())";
               
               if($conn->query($transaction) && $conn->query($payment)){    
                  $message = "Deposit into your wallet";
      } 
      else {
         echo $conn->error;
      }
   }
} 
}
}  
} 
else {
   header("location:login.php");
}

require_once 'helpers.php';
render('header',array('title'=>'payment','link'=>'payment.css','main'=>'main.css','heading'=>'PAYMENT','log'=>'logout','page'=>'dashboard','page2'=>''));



?>
<div class="body">
    <div class="w3-card-4 card">
        <div class="container">
           <div class="d-flex justify-content-between mb-2 head">
               <p class="fs-14 fw-bold">Order Details</p>
           </div>
           <div class="row m-0 ">
               <div class="col-12 px-4">
                   <div class="d-flex justify-content-between mb-2">
                       <p class="text-muted">Order Id</p>
                       <p><?php echo $order_id ?></p>
                   </div>
                   <div class="d-flex justify-content-between mb-2">
                       <p class="text-muted">Ordered Meals</p>
                      <ul>
                           <?php foreach ($orderedMeals as $meal): ?>
                               <?php echo $meal; ?> <br>
                           <?php endforeach; ?>
                       </ul>
                   </div>
                   <div class="d-flex justify-content-between mb-2">
                       <p class="text-muted">Token</p>
                       <p><?php if(!empty($orderrow['token'])) echo $orderrow['token'];  ?></p>
                   </div>
                   <div class="d-flex justify-content-between mb-2">
                       <p class="text-muted">Total</p>
                       <p><?php if(!empty($orderrow['total'])) echo $orderrow['total']; ?></p>
                   </div>
               </div>
                   <div class="d-flex mb-5">
                       <span>
                           <p>Cardholder name</p>
                           <input type="text" value="<?php echo $name; ?>">
                       </span>
                   </div>
                   <div class="col-12  mb-4 p-0">
                       <div class="btn btn-primary">
                        <form action="payment.php" method="post">
                           <button type="submit" name="submit" <?php if(isset($_POST['submit'])) echo "disabled"; ?>>Pay Now</button> 
                       </form>
                        </div><?php if(!empty($message)) echo $message; ?>
                   </div>
               </div>
        </div>
        </div>
    </div>
</div>

<!-- <div class="container">
   <div class="w3-container w3-card-4 card1">
   <form method="post" action="payment.php">
      <button type="submit" class="pay" name="submit" <?php if(isset($_POST['submit'])) echo "disabled"; ?>>Pay</button>
      <div>
         <?php if(!empty($paid)) echo $paid; ?>
      </div> 
   </form>
   </div>
   <div class="Token_details">
   
   <h2>TOKEN DETAILS</h2>
   <b>Food:</b> <span class="details"><?php if(!empty($orderrow['meals'])) echo $orderrow['meals']; ?></span> <br>
   <b>Total Price:</b><span> </span> <br>
   <b>Token number:</b> <span></span>
   </div>
</div> -->



<?php
render('footer');
?>