<?php
session_start();
require_once '../includes/db.php';


$success = '';
$error = '';

// Patient ID (assuming stored in session)
$patient_id = $_SESSION['patient_id'] ?? null;

// Check if doctor_id passed via GET or hidden form input
$doctor_id = $_GET['doctor_id'] ?? $_POST['doctor_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time']; // HH:MM format

    if ($doctor_id && $patient_id && $date && $time) {
        $datetime = $date . ' ' . $time;

        // Check for existing appointment
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE doctor_id = ? AND appointment_time = ?");
        $stmt->bind_param("is", $doctor_id, $datetime);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['total'] > 0) {
            $error = "Selected slot is already booked.";
        } else {
            // Insert appointment
            $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_time) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $patient_id, $doctor_id, $datetime);
            if ($stmt->execute()) {
                $success = "Appointment booked successfully!";
            } else {
                $error = "Error booking appointment.";
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2>Book Appointment</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($doctor_id) ?>">

        <div class="mb-3">
            <label for="date" class="form-label">Select Date</label>
            <input type="date" id="date" name="date" class="form-control" required min="<?= date('Y-m-d') ?>">
        </div>

        <div class="mb-3">
            <label for="time" class="form-label">Select Time (9AM - 5PM)</label>
            <input type="time" id="time" name="time" class="form-control" required min="09:00" max="17:00">
        </div>

        <button type="submit" class="btn btn-danger">Book Now</button>
    </form>

    <a href="dashboard.php" class="btn btn-link mt-3">Back to Dashboard</a>
</div>

</body>
</html>
