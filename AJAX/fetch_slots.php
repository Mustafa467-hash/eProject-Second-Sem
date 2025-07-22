<?php
require_once '../includes/db.php';

$doctor_id = $_GET['doctor_id'] ?? null;
$date = $_GET['date'] ?? null;

if (!$doctor_id || !$date) {
    echo json_encode([]);
    exit;
}

// All possible slots
$allSlots = [];
for ($hour = 9; $hour <= 16; $hour++) {
    foreach (['00', '30'] as $min) {
        $allSlots[] = sprintf('%02d:%s', $hour, $min);
    }
}

// Fetch booked slots for the doctor on that date
$stmt = $conn->prepare("SELECT appointment_time FROM appointments WHERE doctor_id = ? AND DATE(appointment_time) = ?");
$stmt->bind_param("is", $doctor_id, $date);
$stmt->execute();
$result = $stmt->get_result();

$bookedSlots = [];
while ($row = $result->fetch_assoc()) {
    $bookedTime = date('H:i', strtotime($row['appointment_time']));
    $bookedSlots[] = $bookedTime;
}

// Filter out booked slots
$availableSlots = array_values(array_diff($allSlots, $bookedSlots));

echo json_encode($availableSlots);
