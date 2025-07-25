<?php
require_once '../includes/db.php'; // or wherever your connection is

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Example: Insert into DB (you must have a contact_messages table)
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "<script> alert('Message sent successfully!'); window.location.href = 'contact.php'; </script>";
    } else {
        echo "Error: Something went wrong.";
    }
}
