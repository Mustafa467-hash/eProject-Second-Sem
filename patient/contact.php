<?php
$title = "Add Appointment";
require_once '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      background: linear-gradient(to right, #f8f9fa, #ffffff);
    }

    .card-custom {
      background: #fff;
      border: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-radius: 1rem;
    }

    .card-custom .card-header {
      background: linear-gradient(135deg, #2246d2, #041a83);
      color: white;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
    }

    .form-control:focus {
      border-color: #2246d2;
      box-shadow: 0 0 0 0.2rem rgba(34, 70, 210, 0.25);
    }

    .btn-primary {
      background: linear-gradient(135deg, #2246d2, #041a83);
      border: none;
      box-shadow: 0 0 10px #041A83;
    }

    .btn-primary:hover {
      transform: scale(1.03);
    }
  </style>
</head>

<body>

  <!-- Your Navbar will be here -->
  <?php include '../components/PatientComp/navbar.php'; ?>

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-custom">
          <div class="card-header text-center fw-bold fs-4">
            Book Appointment
          </div>
          <div class="card-body">
            <form action="add_appointment.php" method="POST">
              <div class="mb-3">
                <label for="patient" class="form-label">Select Patient</label>
                <select class="form-select" id="patient" name="patient_id" required>
                  <option value="">Choose...</option>
                  <!-- Populate from DB -->
                </select>
              </div>
              <div class="mb-3">
                <label for="doctor" class="form-label">Select Doctor</label>
                <select class="form-select" id="doctor" name="doctor_id" required>
                  <option value="">Choose...</option>
                  <!-- Populate from DB -->
                </select>
              </div>
              <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" id="date" name="appointment_date" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="time" id="time" name="appointment_time" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Book Now</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Your Footer will be here -->
  <?php include '../components/PatientComp/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
