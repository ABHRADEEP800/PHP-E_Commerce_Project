<?php
// start session
session_start(); 
if (!isset($_SESSION['admin'])) { // If no session value is present, redirect the user:
    header('location:  ../admin_login.php'); // Redirect the user:
  exit; // Quit the script.
}
// Include the database configuration file
require('../env/database.php');

$id = $_GET['productId'];

            
//Delete product from product table     
$sql = "UPDATE `product` SET `product_status`='Enable' WHERE product_id='$id'";
$result = mysqli_query($conn,$sql);

// Delete product from cart table
if($result){ 
    header("location:product_mgmt.php");
}
// If product not deleted from product table
else{
    header("location:product_mgmt.php");
}
?>