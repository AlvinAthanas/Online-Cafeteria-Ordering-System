<?php
session_start();
var_dump($_SESSION['order']);
if(!empty($_SESSION["order"])){
    require 'connection.php'; // Assuming you need the database connection
    $order = $_SESSION["order"];
    $result = mysqli_query($conn, "SELECT * FROM `order` WHERE order_id = '$order'");
    $row = mysqli_fetch_assoc($result);

    // Perform any necessary logic with $row or other variables
    // You can echo, print, or manipulate data here without including HTML content
    // For example: echo $row['total'];
}
?>
