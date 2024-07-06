<?php
$servername="localhost";
$username="root";
$password="";
$dbname="cafeteria";

$conn=mysqli_connect($servername,$username,$password,$dbname);
if(!$conn){
    echo ("mysqli error!".mysqli_connect_error());
}
?>