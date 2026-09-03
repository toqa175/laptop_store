<?php

session_start();

// Clear all session data
$_SESSION = [];

// Destroy session
session_destroy();

// Redirect to admin login
header("Location: ../auth/login.php");
exit;
?>
