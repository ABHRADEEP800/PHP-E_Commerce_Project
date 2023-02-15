<?php

    // Database connection
    $conn= mysqli_connect('localhost','root','');

    // Check connection
    if(! $conn)
        {
            die('not connected'.mysqli_connect_error());
        }
    // Select database
    $db_selected=mysqli_select_db($conn,'Grapple');
    
    // Check database
    if(! $db_selected)
        {
            die('cant open the database'. mysqli_connect_error());
        }
        else{
           
        }
       
?>