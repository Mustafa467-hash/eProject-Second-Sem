<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/helpers.php';


$city_count = $conn->query("SELECT COUNT(*) as total FROM cities")->fetch_assoc()['total'];
$doctor_count = $conn->query("SELECT COUNT(*) as total FROM doctors")->fetch_assoc()['total'];
$patient_count = $conn->query("SELECT COUNT(*) as total FROM patients")->fetch_assoc()['total'];
$appointment_count = $conn->query("SELECT COUNT(*) as total FROM appointments")->fetch_assoc()['total'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Care Group</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Theme and Custom CSS -->
  <link rel="stylesheet" href="../assets/css/theme.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  
  <!-- AOS Animation Library -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-light">

  <?php include '../components/adminComp/header.php'; ?>

  <div class="container py-5">
    <div class="text-center mb-4" data-aos="fade-down">
      <h1 class="text-primary fw-bold">Admin Dashboard</h1>
      <p class="text-secondary">Here’s a quick overview of your control panel.</p>
    </div>

    <div class="row g-4 mb-5">
      <!-- Doctors -->
      <div class="col-md-4" data-aos="zoom-in">
        <div class="card bg-primary text-white shadow h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-user-md me-2"></i>Doctors</h5>
            <p class="display-6">
              <?php
              $query = "SELECT COUNT(*) as total FROM doctors";
              $result = mysqli_query($conn, $query);
              $row = mysqli_fetch_assoc($result);
              echo $row['total'];
              ?>
            </p>
          </div>
        </div>
      </div>

      <!-- Patients -->
      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
        <div class="card bg-success text-white shadow h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-users me-2"></i>Patients</h5>
            <p class="display-6">
              <?php
              $query = "SELECT COUNT(*) as total FROM patients";
              $result = mysqli_query($conn, $query);
              $row = mysqli_fetch_assoc($result);
              echo $row['total'];
              ?>
            </p>
          </div>
        </div>
      </div>

      <!-- Appointments -->
      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
        <div class="card bg-warning text-white shadow h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>Appointments</h5>
            <p class="display-6">
              <?php
              $query = "SELECT COUNT(*) as total FROM appointments";
              $result = mysqli_query($conn, $query);
              $row = mysqli_fetch_assoc($result);
              echo $row['total'];
              ?>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="text-center" data-aos="fade-up">
      <h2 class="text-primary fw-semibold">Quick Actions</h2>
      <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
        <a href="manage_doctors.php" class="btn btn-outline-primary"><i class="fas fa-user-md"></i> Manage Doctors</a>
        <a href="manage_patients.php" class="btn btn-outline-success"><i class="fas fa-users"></i> Manage Patients</a>
        <a href="manage_cities.php" class="btn btn-outline-secondary"><i class="fas fa-city"></i> Manage Cities</a>
        <a href="manage_users.php" class="btn btn-outline-danger"><i class="fas fa-user-cog"></i> Manage Logins</a>
      </div>
    </div>
  </div>

  <?php include '../components/adminComp/footer.php'; ?>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>
 