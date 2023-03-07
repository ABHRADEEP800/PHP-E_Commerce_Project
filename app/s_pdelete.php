<?php

// Include the database configuration file
include 'database.php';

$id = $_GET['productId'];
            
//Delete product from product table     
$sql = mysqli_query($conn,"DELETE FROM product WHERE product_id='$id'");
// Delete product from cart table
if($sql){ 
    header("location:s_pmgmt.php");
}
// If product not deleted from product table
else{
    header("location:s_pmgmt.php");
}
?>