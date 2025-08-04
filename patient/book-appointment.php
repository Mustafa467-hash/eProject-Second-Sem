<?php

require_once '../includes/db.php';
require_once '../includes/auth-patient.php';

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
  $symptoms = trim($_POST['symptoms']);
  $medical_history = trim($_POST['medical_history']);
  $appointment_type = trim($_POST['appointment_type']);
  $notes = trim($_POST['notes']);

  if (!$selectedDoctorId || !$date || !$time || !$symptoms || !$appointment_type) {
    $error = 'Please fill out all required fields.';
  } else {
    $stmt = $conn->prepare("INSERT INTO appointments 
            (doctor_id, patient_id, appointment_date, appointment_time, symptoms, medical_history, appointment_type, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
      "iissssss",
      $selectedDoctorId,
      $patient_id,
      $date,
      $time,
      $symptoms,
      $medical_history,
      $appointment_type,
      $notes
    );

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
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
      --primary-color: #2246d2;
      --secondary-color: #6366f1;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --dark-color: #1e293b;
      --light-color: #f8fafc;
      --border-radius: 16px;
      --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
      background: var(--light-color);
      color: #334155;
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
    }

    .form-card {
      background: rgba(255, 255, 255, 0.95);
      padding: 2.5rem;
      border-radius: var(--border-radius);
      border: 1px solid rgba(226, 232, 240, 0.8);
      box-shadow: 0 10px 25px -5px rgba(34, 70, 210, 0.15);
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
      transition: var(--transition);
    }

    .form-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg,
          rgba(34, 70, 210, 0.05),
          rgba(99, 102, 241, 0.05));
      opacity: 0;
      transition: var(--transition);
      pointer-events: none;
      /* ✅ Fixes the interaction bug */
    }

    .form-card:hover::before {
      opacity: 1;
    }

    h3 {
      font-weight: 800;
      color: var(--dark-color);
      font-size: 2rem;
      position: relative;
      padding-bottom: 1rem;
      margin-bottom: 2rem;
    }

    h3::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 4px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 2px;
    }

    .form-label {
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .form-control,
    .form-select,
    textarea {
      border-radius: var(--border-radius);
      padding: 0.875rem 1rem;
      border: 1px solid rgba(226, 232, 240, 0.8);
      background: rgba(255, 255, 255, 0.9);
      font-size: 0.95rem;
      transition: var(--transition);
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .form-control:focus,
    .form-select:focus,
    textarea:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(34, 70, 210, 0.1);
    }

    .form-control:hover,
    .form-select:hover,
    textarea:hover {
      border-color: var(--secondary-color);
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 9999px;
      padding: 1rem;
      font-weight: 600;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
      font-size: 1rem;
      box-shadow: 0 4px 6px -1px rgba(34, 70, 210, 0.2);
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
      opacity: 0;
      transition: var(--transition);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px -4px rgba(34, 70, 210, 0.3);
    }

    .btn-primary:hover::before {
      opacity: 1;
    }

    .btn-primary span {
      position: relative;
      z-index: 1;
    }

    .section-title {
      font-size: 0.875rem;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: 0.5px;
      margin-bottom: 1.25rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid rgba(226, 232, 240, 0.8);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .section-title i {
      color: var(--primary-color);
      font-size: 1rem;
    }

    .alert {
      border: none;
      border-radius: var(--border-radius);
      padding: 1rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-weight: 500;
    }

    .alert-success {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success-color);
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger-color);
    }

    .row {
      --bs-gutter-x: 1.25rem;
    }

    /* ✅ Responsive tweaks */
    @media (max-width: 768px) {
      .form-card {
        padding: 2rem;
      }

      h3 {
        font-size: 1.75rem;
      }

      .section-title {
        font-size: 0.8125rem;
      }
    }

    @media (max-width: 576px) {
      .form-card {
        padding: 1.5rem;
      }

      .form-control,
      .form-select,
      textarea {
        font-size: 0.9375rem;
        padding: 0.75rem;
      }

      h3 {
        font-size: 1.5rem;
      }

      .row {
        --bs-gutter-x: 1rem;
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
            <div class="section-title"><i class="fas fa-user-circle"></i>Patient Information</div>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-user"></i>Full Name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($patientInfo['name'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-phone"></i>Phone</label>
                <input type="tel" class="form-control" value="<?= htmlspecialchars($patientInfo['phone'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-birthday-cake"></i>Age</label>
                <input type="number" class="form-control" value="<?= htmlspecialchars($patientInfo['age'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-venus-mars"></i>Gender</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($patientInfo['gender'] ?? '') ?>">
              </div>
            </div>

            <!-- Medical Info -->
            <div class="section-title"><i class="fas fa-notes-medical"></i>Medical Information</div>
            <div class="mb-4">
              <label class="form-label"><i class="fas fa-heartbeat"></i>Primary Symptoms</label>
              <textarea name="symptoms" class="form-control" rows="3"
                placeholder="Please describe your symptoms in detail" required></textarea>
            </div>
            <div class="mb-4">
              <label class="form-label"><i class="fas fa-file-medical"></i>Medical History</label>
              <textarea name="medical_history" class="form-control" rows="3"
                placeholder="Any existing conditions, allergies, or medications you're currently taking"></textarea>
            </div>

            <!-- Doctor Selection -->
            <div class="section-title"><i class="fas fa-user-md"></i>Doctor Selection</div>
            <div class="mb-4">
              <label class="form-label"><i class="fas fa-stethoscope"></i>Select Doctor</label>
              <select name="doctor_id" class="form-select" required>
                <option value="">Select your preferred doctor</option>
                <?php foreach ($doctorList as $doc): ?>
                  <option value="<?= $doc['id'] ?>" <?= $selectedDoctorId == $doc['id'] ? 'selected' : '' ?>>
                    Dr. <?= htmlspecialchars($doc['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Appointment Details -->
            <div class="section-title"><i class="fas fa-calendar-alt"></i>Appointment Details</div>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-calendar-day"></i>Preferred Date</label>
                <input type="date" name="appointment_date" class="form-control" required min="<?= date('Y-m-d') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-clock"></i>Preferred Time</label>
                <input type="time" name="appointment_time" class="form-control" required>
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label"><i class="fas fa-tag"></i>Appointment Type</label>
              <select name="appointment_type" class="form-select" required>
                <option value="">Choose appointment type</option>
                <option value="consultation">🩺 General Consultation</option>
                <option value="follow_up">🔄 Follow-up Visit</option>
                <option value="emergency">🚨 Emergency</option>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label"><i class="fas fa-comment-medical"></i>Additional Notes</label>
              <textarea name="notes" class="form-control" rows="3"
                placeholder="Any specific concerns or questions for the doctor?"></textarea>
            </div>

            <div class="d-flex flex-column gap-3">
              <button type="submit" class="btn btn-primary w-100">
                <span><i class="fas fa-calendar-check me-2"></i>Schedule Appointment</span>
              </button>
              
              <div class="text-center">
                <span class="text-muted">Not ready to book? </span>
                <a href="logout.php" class="text-decoration-none" style="color: var(--primary-color); font-weight: 500;">
                  <i class="fas fa-sign-out-alt me-1"></i>Log out and come back later
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include '../components/PatientComp/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>