<?php

// Include the database configuration file
include 'database.php';

$id = $_GET['orderId'];
            
$sql = mysqli_query($conn,"DELETE FROM order_p WHERE order_id='$id'");           
$sql = mysqli_query($conn,"DELETE FROM orders WHERE order_id='$id'");
if($sql){
    header("location:order_mgmt.php");
}
else{
    header("location:order_mgmt.php");
}

?>