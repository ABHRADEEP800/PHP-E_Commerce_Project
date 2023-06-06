<?php
session_start(); // Starting Session
if (!isset($_SESSION['seller'])) { //if not logged in
    header('location:  ../login.php'); // Redirecting To Home Page
  exit; // stop further executing, very important
}
// Include the database configuration file
require('../env/database.php');

$id = $_GET['productId'];

            
//Delete product from product table     
$sql = "UPDATE `product` SET `product_status`='Enable' WHERE product_id='$id'";
$result = mysqli_query($conn,$sql);

// Delete product from cart table
if($result){ 
    header("location:s_pmgmt.php");
}
// If product not deleted from product table
else{
    header("location:s_pmgmt.php");
}
?>