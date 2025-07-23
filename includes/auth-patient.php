<?php
session_start();

if (!isset($_SESSION['ispatient']) || $_SESSION['ispatient'] !== true) {
    header("Location: ../patient/login.php");
    exit();
}
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>
