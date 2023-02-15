<?php

// Include the database configuration file
include 'database.php';

$id = $_GET['userId'];
            
            
$sql = mysqli_query($conn,"DELETE FROM user WHERE user_id='$id'");
if($sql){
    header("location:user_mgmt.php");
}
else{
    header("location:user_mgmt.php");
}

?>