<?php
//seller logout
session_start();
session_destroy();
header("Location: /login.php");

?>