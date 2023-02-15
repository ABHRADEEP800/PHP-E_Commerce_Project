<?php
session_start();

// destroy session for logout
session_destroy();

// redirect to index page
header("Location: index.php");
?>