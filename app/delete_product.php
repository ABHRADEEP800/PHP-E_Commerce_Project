<?php

// Include the database configuration file
include 'database.php';

$id = $_GET['productId']; // Get the product_id from the URL
$sql = mysqli_query($conn,"DELETE FROM product WHERE product_id='$id'"); // Delete the product from product table

// Redirect to the product management page
if($sql){
    header("location:product_mgmt.php");
}
// If the product is not deleted, redirect to the product management page
else{
    header("location:product_mgmt.php");
}
?>