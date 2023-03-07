<?php
//seller logout
session_start();
session_destroy();
setcookie("seller", "", time() - 3600, "/");
header("Location: /login.php");

?>