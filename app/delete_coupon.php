<?php
    // Include the database configuration file
    include 'database.php';
    $id = $_GET['cId'];          
    $sql = mysqli_query($conn,"DELETE FROM Coupon WHERE id='$id'");
    if($sql){
        header("location:coupon.php");
    }
    else{
        header("location:coupon.php");
    }
?>