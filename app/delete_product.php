<?php

// Include the database configuration file
include 'database.php';

$id = $_GET['productId'];
            
            
$sql = mysqli_query($conn,"DELETE FROM product WHERE product_id='$id'");
if($sql){
    header("location:product_mgmt.php");
}
else{
    header("location:product_mgmt.php");
}

?>