<?php
require 'connection.php';
if (isset($_POST['submit'])) {


  $name = $_POST['name'];
  $pass = $_POST['pass'];
  $verifyPass = $_POST['verify-pass'];
  $reg_no = $_POST['reg_no'];
  $phone_no = $_POST['phone_no'];

  // ID validation
  if (preg_match('/^20\d{2}-0\d{1}-\d{5}$/', $reg_no)) {
    $title = 'student';
  } elseif (preg_match('/^20\d{2}-\d{5}$/', $reg_no)) {
    $title = 'staff';
  } else {
    $userFormat = "Invalid user_id format.";
  }
  // name validation
  if (strpos($name, ' ') !== false) {
    $NAME = $name;
  }
  // phone validation
  $phone_no = str_replace(' ', '', $phone_no);

  // Define regex patterns for valid phone number formats
  $pattern1 = '/^\+2556\d{8}$/';   // +2556 followed by 8 digits
  $pattern2 = '/^\+2557\d{8}$/';   // +2557 followed by 8 digits
  $pattern3 = '/^06\d{8}$/';     // 06 followed by 8 digits
  $pattern4 = '/^07\d{8}$/';     // 07 followed by 8 digits


  // password validation
  $validLength = strlen($pass) >= 8;
  $hasLetter = preg_match('/[a-zA-Z]/', $pass);
  $hasDigit = preg_match('/\d/', $pass);
  $hasSpecialChar = preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/', $pass);
  $passValid = $validLength && $hasLetter && $hasDigit && $hasSpecialChar;
  $validPhone = preg_match($pattern1, $phone_no) || preg_match($pattern2, $phone_no) || preg_match($pattern3, $phone_no)
    || preg_match($pattern4, $phone_no);
  $passMatch = $pass == $verifyPass;

  if (!empty($NAME) && !empty($pass) && !empty($verifyPass) && !empty($reg_no) && !empty($phone_no)) {
    if (preg_match($pattern1, $phone_no) || preg_match($pattern2, $phone_no) || preg_match($pattern3, $phone_no) || preg_match($pattern4, $phone_no)) {
      if ($passValid === TRUE && $pass == $verifyPass) {
        // Insert into the database
        $sql = "INSERT INTO user VALUES ('$reg_no','$NAME', '$pass','$phone_no', '$title')";

        if ($conn->query($sql) === TRUE) {


          header("location:login.php");
          exit();
        } else {
          echo ("Error!" . mysqli_connect_error());
        }

        $conn->close();
      }
    }
  } else {
    $incomplete = "please fill all the form fields";
  }
}

require_once 'helpers.php';

render('header', array('title' => 'register', 'link' => 'signup.css', 'main' => 'main.css', 'heading' => 'Registration', 'log' => 'login', 'page' => '', 'page2' => ''));

?>
<div class="container mti-3">
  <h2 class="title">Register Now!</h2>
  <p class="title">TO GET REGISTERED PLEASE FILL THE FORM BELOW</p>
  <form action="signup.php" method="POST">
    <div class="form-floating mt-3 mb-3">
      <input type="text" class="form-control" id="reg_no" placeholder="Enter ID" name="reg_no" value="<?php if (isset($_POST['reg_no'])) echo $_POST['reg_no']; ?>">
      <label for="reg_no">ID</label>
      <span><?php if (!empty($userFormat)) echo $userFormat; ?></span>
    </div>
    <div class="form-floating mb-3 mt-3">
      <input type="password" class="form-control" id="pass" placeholder="Enter password" name="pass" onkeyup="validatePassword(this.value)" value="<?php if (isset($_POST['pass'])) echo $_POST['pass']; ?>">
      <label for="pass">Password</label>
      <span id="passwordMessage"></span>
    </div>
    <div class="form-floating mb-3 mt-3">
      <input type="password" class="form-control" id="verify-pass" placeholder="Enter password" name="verify-pass" value="<?php if (isset($_POST['verify-pass'])) echo $_POST['verify-pass']; ?>">
      <label for="verify-pass">Verify Password</label>
      <span><?php if (isset($passMatch) && !empty($_POST['verify-pass']) && !$passMatch) echo "your verification password does not match"; ?></span>
    </div>
    <div class="form-floating mb-3 mt-3">
      <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" onkeyup="validateFullName(this.value)" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
      <label for="name">Name</label>
      <span id="fullNameMessage"></span>
    </div>
    <div class="form-floating mt-3 mb-3">
      <input type="text" class="form-control" id="reg_no" placeholder="Enter Phone Number" name="phone_no" value="<?php if (isset($_POST['phone_no'])) echo $_POST['phone_no']; ?>">
      <label for="phone_no">Phone Number</label>
      <span><?php if (isset($validPhone) && !empty($_POST['phone_no']) && !$validPhone) echo "please enter a valid phone number"; ?></span>
    </div>
    <div class="mt-3">
      <span><?php if (!empty($incomplete)) echo $incomplete; ?></span>
    </div>


    <div class="mt-3">
      <button type="submit" name="submit" class="btn">Submit</button>
    </div>
  </form>
</div>
<script>
  function validateFullName(fullName) {
    var fullNameMessage = document.getElementById("fullNameMessage");
    var names = fullName.split(" ");

    if (names.length === 2 && names[0].length > 0 && names[1].length > 0) {
      fullNameMessage.textContent = "";
    } else {
      fullNameMessage.textContent = "Enter your first name and surname.";
    }
  }

  function validatePassword(password) {
    var passwordMessage = document.getElementById("passwordMessage");
    var validLength = password.length >= 8;
    var hasLetter = /[a-zA-Z]/.test(password);
    var hasDigit = /\d/.test(password);
    var hasSpecialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(password);

    if (validLength && hasLetter && hasDigit && hasSpecialChar) {
      passwordMessage.textContent = "Password is valid!"
      document.getElementById("passwordMessage").style.color = "white";

    } else if (validLength && hasLetter && hasDigit) {
      passwordMessage.textContent = "Your password should have at least one special character.";
      document.getElementById("passwordMessage").style.color = "aqua";

    } else if (validLength && hasLetter && hasSpecialChar) {
      passwordMessage.textContent = "Your password should have at least one digit.";
      document.getElementById("passwordMessage").style.color = "aqua";

    } else if (validLength && hasDigit && hasSpecialChar) {
      passwordMessage.textContent = "Your password should have at least one letter.";
      document.getElementById("passwordMessage").style.color = "aqua";

    } else if (validLength && hasLetter && !hasDigit && !hasSpecialChar) {
      passwordMessage.textContent = "Your password should have at least one digit and one special character";
      document.getElementById("passwordMessage").style.color = "aqua";

    } else if (validLength && !hasLetter && hasDigit && !hasSpecialChar) {
      passwordMessage.textContent = "Your password should have at least one letter and one special character";
      document.getElementById("passwordMessage").style.color = "aqua";

    } else if (validLength && !hasLetter && !hasDigit && hasSpecialChar) {
      passwordMessage.textContent = "Your password should have at least one digit and one letter ";
      document.getElementById("passwordMessage").style.color = "aqua";

    } else {
      passwordMessage.textContent = "Your password should have not less than 8 characters and should have at least one letter, one digit, and one special character.";
      document.getElementById("passwordMessage").style.color = "aqua";

    }
  }
</script>
<?php
render('footer');

?>