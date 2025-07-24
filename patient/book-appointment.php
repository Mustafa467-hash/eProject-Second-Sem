<?php
session_start();
require_once '../includes/db.php';

$success = '';
$error = '';

// Get doctor_id from query or form (if redirected from profile)
$prefilledDoctorId = $_GET['doctor_id'] ?? null;
$selectedDoctorId = $_POST['doctor_id'] ?? $prefilledDoctorId ?? null;
$patient_id = $_SESSION['patient_id'] ?? null;

// Fetch all doctors for dropdown
$doctorList = $conn->query("SELECT id, name FROM doctors")->fetch_all(MYSQLI_ASSOC);

if (!$patient_id) {
    $error = 'You must be logged in to book an appointment.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = trim($_POST['appointment_date']);
    $time = trim($_POST['appointment_time']);

    if (!$selectedDoctorId || !$date || !$time) {
        $error = 'Please fill out all fields.';
    } else {
        $stmt = $conn->prepare("INSERT INTO appointments (doctor_id, patient_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $selectedDoctorId, $patient_id, $date, $time);

        if ($stmt->execute()) {
            $success = 'Appointment booked successfully!';
        } else {
            $error = 'Failed to book appointment. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #F1F8FF;
    }
    .form-card {
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      margin-top: 80px;
    }
  </style>
</head>
<body>

<?php include '../components/PatientComp/navbar.php'; ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="form-card">
        <h3 class="mb-4 text-center">Book an Appointment</h3>

        <?php if ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif ($success): ?>
          <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label for="doctor_id" class="form-label">Choose Doctor</label>
            <select name="doctor_id" id="doctor_id" class="form-select" required>
              <option value="">-- Select Doctor --</option>
              <?php foreach ($doctorList as $doc): ?>
                <option value="<?= $doc['id'] ?>" <?= $selectedDoctorId == $doc['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($doc['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="appointment_date" class="form-label">Date</label>
            <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="appointment_time" class="form-label">Time</label>
            <input type="time" name="appointment_time" id="appointment_time" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Confirm Appointment</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../components/PatientComp/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
