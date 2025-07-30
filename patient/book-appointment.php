<?php
session_start();
require_once '../includes/db.php';

$success = '';
$error = '';

// Get doctor_id from query or form
$prefilledDoctorId = $_GET['doctor_id'] ?? null;
$selectedDoctorId = $_POST['doctor_id'] ?? $prefilledDoctorId ?? null;
$patient_id = $_SESSION['patient_id'] ?? null;

// Fetch all doctors for dropdown
$doctorList = $conn->query("SELECT id, name FROM doctors")->fetch_all(MYSQLI_ASSOC);

// Fetch patient information
$patientInfo = null;
if ($patient_id) {
    $stmt = $conn->prepare("SELECT name, phone, age, gender FROM patients WHERE id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $patientInfo = $stmt->get_result()->fetch_assoc();
}

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ✅ Mobile scaling -->
  <title>Book Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: #f8fafc;
      color: #334155;
      font-family: 'Poppins', sans-serif;
    }

    .form-card {
      background: white;
      padding: 2rem;
      border-radius: 16px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    h3 {
      font-weight: 700;
      color: #1e293b;
    }

    .form-label {
      font-weight: 500;
      color: #1e293b;
      margin-bottom: 0.3rem;
    }

    .form-control, .form-select, textarea {
      border-radius: 8px;
      padding: 0.7rem;
    }

    .btn-primary {
      background: #2246d2;
      border: none;
      border-radius: 8px;
      padding: 0.8rem;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: #1a37a7;
      transform: translateY(-2px);
    }

    .section-title {
      font-size: 0.85rem;
      color: #2246d2;
      text-transform: uppercase;
      font-weight: 600;
      border-bottom: 2px solid #e2e8f0;
      margin-bottom: 1rem;
      padding-bottom: 0.3rem;
    }

    /* ✅ Responsive tweaks */
    @media (max-width: 768px) {
      .form-card {
        padding: 1.5rem;
      }
      h3 {
        font-size: 1.4rem;
      }
      .section-title {
        font-size: 0.8rem;
      }
    }

    @media (max-width: 576px) {
      .form-card {
        padding: 1.2rem;
      }
      .form-control, .form-select, textarea {
        font-size: 14px;
        padding: 0.6rem;
      }
      h3 {
        font-size: 1.3rem;
      }
    }
  </style>
</head>

<body>
  <?php include '../components/PatientComp/navbar.php'; ?>

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-12"> <!-- ✅ Makes form scale on all devices -->
        <div class="form-card">
          <h3 class="mb-4 text-center">Book an Appointment</h3>

          <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php elseif ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php endif; ?>

          <form method="POST">
            <!-- Patient Info -->
            <div class="section-title">Patient Information</div>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($patientInfo['name'] ?? '') ?>" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="tel" class="form-control" value="<?= htmlspecialchars($patientInfo['phone'] ?? '') ?>" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label">Age</label>
                <input type="number" class="form-control" value="<?= htmlspecialchars($patientInfo['age'] ?? '') ?>" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label">Gender</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($patientInfo['gender'] ?? '') ?>" readonly>
              </div>
            </div>

            <!-- Medical Info -->
            <div class="section-title">Medical Information</div>
            <div class="mb-3">
              <label class="form-label">Primary Symptoms</label>
              <textarea name="symptoms" class="form-control" rows="2" placeholder="Describe your symptoms" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Medical History</label>
              <textarea name="medical_history" class="form-control" rows="2" placeholder="Existing conditions or allergies"></textarea>
            </div>

            <!-- Doctor Selection -->
            <div class="section-title">Doctor Selection</div>
            <div class="mb-3">
              <label class="form-label">Select Doctor</label>
              <select name="doctor_id" class="form-select" required>
                <option value="">-- Choose a Doctor --</option>
                <?php foreach ($doctorList as $doc): ?>
                  <option value="<?= $doc['id'] ?>" <?= $selectedDoctorId == $doc['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($doc['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Appointment Details -->
            <div class="section-title">Appointment Details</div>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Preferred Date</label>
                <input type="date" name="appointment_date" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Preferred Time</label>
                <input type="time" name="appointment_time" class="form-control" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Appointment Type</label>
              <select name="appointment_type" class="form-select" required>
                <option value="">Select Type</option>
                <option value="consultation">General Consultation</option>
                <option value="follow_up">Follow-up Visit</option>
                <option value="emergency">Emergency</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Additional Notes</label>
              <textarea name="notes" class="form-control" rows="2" placeholder="Any specific concerns"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Schedule Appointment</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include '../components/PatientComp/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
