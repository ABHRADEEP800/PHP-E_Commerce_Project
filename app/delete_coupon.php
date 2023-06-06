<?php
session_start(); 
if (!isset($_SESSION['admin'])) {  // if admin is not logged in
    header('location:  ../admin_login.php');
  exit;
}
    // Include the database configuration file
    require('../env/database.php');
    $id = $_GET['cId'];          
    $sql = mysqli_query($conn,"DELETE FROM Coupon WHERE id='$id'");
    if($sql){
        header("location:coupon.php");
    }
    else{
        header("location:coupon.php");
    }
?>