<?php

// Database connection
$conn= mysqli_connect('localhost','root','');
    if(! $conn)
        {
            die('not connected'.mysqli_connect_error());
        }
    $db_selected=mysqli_select_db($conn,'Grapple');
    if(! $db_selected)
        {
            die('cant open the database'. mysqli_connect_error()); // if database is not connected
        }
        else{
           
        }
      
?>