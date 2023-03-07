<?php

// Include the database configuration file
include 'database.php';

$id = $_GET['userId']; // Get the user_id from the URL         
$sql = mysqli_query($conn,"DELETE FROM user WHERE user_id='$id'"); // Delete the user from user table

// Redirect to the user management page
if($sql){
    header("location:user_mgmt.php");
}
// If the user is not deleted, redirect to the user management page
else{
    header("location:user_mgmt.php");
}
?>