<?php 
require 'connection.php';
include 'session.php';
$Sql = "SELECT * FROM `order` WHERE user_id = '$user_id' ORDER BY order_id DESC LIMIT 1";
$Result = mysqli_query($conn, $Sql);
$Row = mysqli_fetch_assoc($Result);
if ($conn->query($Query) === TRUE) {
 $id = $Row["order_id"];
 $_SESSION["order"] = $id;
// header("location: menu.php");
}
else echo 'data not inserted';

var_dump($_SESSION["order"]);
?>