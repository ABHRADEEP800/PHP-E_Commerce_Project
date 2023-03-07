<?php
session_start();

// destroy session for logout
session_destroy();
//destroy all cookies
setcookie("customer", "", time() - 3600, "/");

// redirect to index page
header("Location: index.php");
?>