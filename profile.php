<?php
session_start();
require 'connection.php';
if (!isset($_SESSION["id"])) {

}
$id = $_SESSION["id"];

$sql = "SELECT name, user_id, phone_no, password FROM user WHERE `user_id` = '$id'";
$result = $conn->query($sql);

// SQL query to count how many times the user_id appears
$orderSql = "SELECT COUNT(*) AS order_count FROM `ordered_meals` WHERE `user_id` = '$id'";
$orderResult = mysqli_query($conn,$orderSql);
$orderRow = mysqli_fetch_assoc($orderResult);
$count = $orderRow['order_count'];
if ($result->num_rows > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row["name"];
    $reg_no = $row["user_id"];
    $phone_no = $row["phone_no"];
    $pass=$row["password"];
} else {
    echo "User not found.";
}
$transactionSql = "SELECT * FROM `transaction` WHERE `user_id` = '$id' ORDER BY `transaction_id` DESC LIMIT 1";
$transactionQuery = mysqli_query($conn, $transactionSql);
if(mysqli_num_rows($transactionQuery)>0){
    $transactionRow = mysqli_fetch_assoc($transactionQuery);
    $balance = $transactionRow['balance'];
}else {
    $balance = 0;
}
// Handle password change
if(isset($_POST['submit'])){
    
$currentPassword = $_POST['password'];
$passwordSql = "SELECT * FROM `user` WHERE `user_id` = '$id' AND `password` = '$currentPassword'";
$passwordQuery = mysqli_query($conn, $passwordSql);
if(mysqli_num_rows($passwordQuery)>0){
    
$newPassword = $_POST["new_password"];
    // password validation
    $validLength = strlen($newPassword) >= 8;
    $hasLetter = preg_match('/[a-zA-Z]/', $newPassword);
    $hasDigit = preg_match('/\d/', $newPassword);
    $hasSpecialChar = preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/', $newPassword);
    $verifyPass = $_POST['verifyPass'];
    $comparePass = $verifyPass ==$newPassword;
    $passValid = $validLength && $hasLetter && $hasDigit && $hasSpecialChar;
    if($comparePass){
    if($passValid){
        $updateSql = "UPDATE `user` SET `password` = '$newPassword' WHERE `user_id` = '$id'";
        if ($conn->query($updateSql) === FALSE) {

            echo "Error updating password: " . $conn->error;
        }
}
} else {
    $noMatch = "verification password doesn't match your new password";
}
}else{
    $wrongPass = "please enter the correct password";
}



}

$conn->close();
require_once 'helpers.php';
render('header',array('title'=>'PROFILE','link'=>'profile.css','main'=>'main.css','heading'=>'USERS PROFILE','log'=>'logout','page'=>'Dashboard','page2'=>''));
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
</head>
<body>
    <div class="mti-3">
    
    <div class="container">
        <p><strong>Username:</strong> <?php echo $name; ?></p>
        <p><strong>Registration Number:</strong> <?php echo $reg_no; ?></p>
        <p><strong>Wallet Balance:</strong> <?php echo $balance; ?></p>
        <p><strong>Orders made:</strong> <?php echo $count; ?></p>

    <!-- Change Password Button -->
    <button id="changePasswordBtn" onclick="togglePasswordForm()">Change Password</button>
   
    
    <?php 


    ?>
    <!-- Password Change Form -->
    <form id="passwordForm" action="profile.php" method="post">
        <label for="currentPassword">Current Password:</label>
        <input type="password" id="currentPassword" name="password" required><br>
        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="new_password" onkeyup="validatePassword(this.value)" required><br>
        <h6 id="passwordMessage"></h6>
        <label for="newPassword">Verify Password:</label>
        <input type="password" id="verifyPass" name="verifyPass" required><br>
        <b><?php if(!empty($noMatch)) echo $noMatch; ?></b>
        <button type="submit"  name="submit">Change</button>
        <b><?php if(!empty($wrongPass)) echo $wrongPass; ?></b>


        <script>
                    function validatePassword(password) {
            var passwordMessage = document.getElementById("passwordMessage");
            var validLength = password.length >= 8;
            var hasLetter = /[a-zA-Z]/.test(password);
            var hasDigit = /\d/.test(password);
            var hasSpecialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(password);

            if (validLength && hasLetter && hasDigit && hasSpecialChar) {
                passwordMessage.textContent = "Password is valid!"
                document.getElementById("passwordMessage").style.color = "blue";

            } else if (validLength && hasLetter && hasDigit) {
                passwordMessage.textContent = "Your password should have at least one special character.";
                document.getElementById("passwordMessage").style.color = "red";

            } else if (validLength && hasLetter && hasSpecialChar) {
                passwordMessage.textContent = "Your password should have at least one digit.";
                document.getElementById("passwordMessage").style.color = "red";

            } else if (validLength && hasDigit && hasSpecialChar) {
                passwordMessage.textContent = "Your password should have at least one letter.";
                document.getElementById("passwordMessage").style.color = "red";

            }else if(validLength && hasLetter && !hasDigit && !hasSpecialChar){
              passwordMessage.textContent = "Your password should have at least one digit and one special character";
              document.getElementById("passwordMessage").style.color = "red";

            }else if(validLength && !hasLetter && hasDigit && !hasSpecialChar){
              passwordMessage.textContent = "Your password should have at least one letter and one special character";
              document.getElementById("passwordMessage").style.color = "red";

            }else if(validLength && !hasLetter && !hasDigit && hasSpecialChar){
              passwordMessage.textContent = "Your password should have at least one digit and one letter ";
              document.getElementById("passwordMessage").style.color = "red";

            } else {
                passwordMessage.textContent = "Your password should have not less than 8 characters and should have at least one letter, one digit, and one special character.";
                document.getElementById("passwordMessage").style.color = "red";

            }
        }
        </script>
    </form>
    
    <script>
        let isPasswordFormVisible = false;

        function togglePasswordForm() {
            const passwordForm = document.getElementById('passwordForm');
            if (isPasswordFormVisible) {
                passwordForm.style.display = 'none';
            } else {
                passwordForm.style.display = 'block';
            }
            isPasswordFormVisible = !isPasswordFormVisible;
        }


    </script>
    </div>
    </form>
    </div>
</body>
</html>
<?php
render('footer');
?>