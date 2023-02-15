<?php
    // Include the database configuration file
    include 'database.php';
    $id = $_GET['Id'];          
    $sql = mysqli_query($conn,"DELETE FROM contact WHERE id='$id'");
    if($sql){
        header("location:message.php");
    }
    else{
        header("location:message.php");
    }
?>