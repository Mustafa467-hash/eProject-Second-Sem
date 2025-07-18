<?php
session_start();

// Block access if not logged in
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] !== true) {
    header("Location: login.php");
    exit();
}

// Prevent back button after logout
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>
