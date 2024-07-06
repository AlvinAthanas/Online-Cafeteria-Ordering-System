<?php
require 'connection.php';

if (isset($_POST['order_id']) && isset($_POST['token'])) {
    $order_id = $_POST['order_id'];
    $token = $_POST['token'];

    $updateQuery = "UPDATE `order` SET token = '$token' WHERE order_id = '$order_id'";
    if ($conn->query($updateQuery) !== TRUE) {
        echo "Error updating token: " . $conn->error;
    }
}
?>
