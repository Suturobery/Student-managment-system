<?php
// logout.php
session_start();
session_destroy(); // Destroy the session
header("Location:../pages/login.php"); // Redirect to login page
exit;
?>