<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function isDoctorLoggedIn() {
    return isset($_SESSION['doctor_id']);
}

function isPatientLoggedIn() {
    return isset($_SESSION['patient_id']);
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: ../admin/login.php");
        exit;
    }
}

function requireDoctorLogin() {
    if (!isDoctorLoggedIn()) {
        header("Location: ../doctor/login.php");
        exit;
    }
}

function requirePatientLogin() {
    if (!isPatientLoggedIn()) {
        header("Location: ../patient/login.php");
        exit;
    }
}
