<?php
    // Include the database configuration file
    include 'database.php';
    $id = $_GET['Id']; // Get the id from the URL         
    $sql = mysqli_query($conn,"DELETE FROM contact WHERE id='$id'"); // Delete the message from contact table
    // Redirect to the message page
    if($sql){
        header("location:message.php");
    }
    // If the message is not deleted, redirect to the message page
    else{
        header("location:message.php");
    }
?>