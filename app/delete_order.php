<?php

// Include the database configuration file
include 'database.php';

$id = $_GET['orderId']; // Get the order_id from the URL
            
$sql = mysqli_query($conn,"DELETE FROM order_p WHERE order_id='$id'");  // Delete the order from order_p table         
$sql = mysqli_query($conn,"DELETE FROM orders WHERE order_id='$id'");  // Delete the order from orders table

// Redirect to the order management page
if($sql){
    header("location:order_mgmt.php");
}
// If the order is not deleted, redirect to the order management page
else{
    header("location:order_mgmt.php");
}
?>