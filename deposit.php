<?php
require 'connection.php';
require 'session.php';
require_once 'helpers.php';
// var_dump($_SESSION['id']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deposit = $_POST['deposit'];
    $type = "deposit";
    $user_id = $_SESSION['id'];

    $transactionQuery = "SELECT * FROM `transaction` WHERE `user_id` = '$user_id' ORDER BY `transaction_id` DESC LIMIT 1";
    $transactionSql = mysqli_query($conn, $transactionQuery);

    if(mysqli_num_rows($transactionSql)>0){
    $transactionRow = mysqli_fetch_assoc($transactionSql);
    $balance = $transactionRow['balance'];
    $newBalance = $deposit + $balance;
    $transaction = "INSERT INTO `transaction` VALUES ('','$user_id','$type','$deposit','$newBalance', NOW())";
    if ($conn->query($transaction) === TRUE) {
        // Data inserted successfully
        $deposit = "Wallet balance increased successfully";
    } else {
        // Error inserting data, data hasn't been inserted
        echo "Error: " . $conn->error;
    }
} else {
    $transaction = "INSERT INTO `transaction` VALUES ('','$user_id','$type','$deposit','$deposit', NOW())";
    if ($conn->query($transaction) === TRUE) {
        // Data inserted successfully
        echo "Your first deposit received successfully";
    } else {
        // Error inserting data, data hasn't been inserted
        echo "Error: " . $conn->error;
    }

}

}

render('header', array('title' => 'deposit', 'link' => 'deposit.css', 'main'=>'main.css','heading' => 'DEPOSIT', 'log' => 'logout', 'page' => 'dashboard','page2'=>''));
?>

<div class="w3-container w3-card-4 card1">
    <form method="post" action="deposit.php">
        <p class="p_token">Enter the amount you want to deposit</p>
        <input type="text" name="deposit" class="token"> <br> <br>
        <button type="submit" class="pay">Deposit</button>
        <?php if(!empty($deposit)) echo $deposit; ?>
    </form>
</div>
<div class="img_container">
    <img src="cafeimage/airtelmoney1.png" alt="" class="img">
    <img src="cafeimage/crdb1.png" alt="" class="img">
    <img src="cafeimage/nmb.jpeg" alt="" class="img">
    <img src="cafeimage/mpesa.png" alt="" class="img">
    <img src="cafeimage/tigopesa1.jpeg" alt="" class="img">
    <img src="cafeimage/nbc1.png" alt="" class="img">
</div>
<?php
render('footer');
?>
