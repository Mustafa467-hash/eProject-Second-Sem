<?php
session_start();

if (!isset($_SESSION['isdoctor']) || $_SESSION['isdoctor'] !== true) {
    header("Location: ../doctor/login.php");
    exit();
}
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>
