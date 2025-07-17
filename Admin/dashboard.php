<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Fetch counts
$doctor_count = $conn->query("SELECT COUNT(*) as total FROM doctors")->fetch_assoc()['total'];
$city_count = $conn->query("SELECT COUNT(*) as total FROM cities")->fetch_assoc()['total'];
$patient_count = $conn->query("SELECT COUNT(*) as total FROM patients")->fetch_assoc()['total'];
$appointment_count = $conn->query("SELECT COUNT(*) as total FROM appointments")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f1f1f1;
    }

    .main {
      flex-grow: 1;
      padding: 20px;
    }

    .sidebar {
      width: 250px;
      /* or whatever your sidebar width is */
      flex-shrink: 0;
    }

    .main {
      margin-left: 250px;
      /* same width as the sidebar */
    }


    .cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .card {
      padding: 20px;
      color: white;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .card i {
      font-size: 30px;
      margin-bottom: 10px;
    }

    .card h3 {
      font-size: 22px;
      margin: 0;
    }

    .card span {
      font-size: 28px;
      font-weight: bold;
    }

    /* Gradient backgrounds */
    .doc-card {
      background: linear-gradient(to right, #4facfe, #00f2fe);
    }

    .city-card {
      background: linear-gradient(to right, #43e97b, #38f9d7);
    }

    .patient-card {
      background: linear-gradient(to right, #fa709a, #fee140);
    }

    .appointment-card {
      background: linear-gradient(to right, #667eea, #764ba2);
    }

    footer {
      margin-top: 50px;
      text-align: center;
      padding: 10px;
      color: #777;
      font-size: 14px;
    }
  </style>
</head>

<body>

<!-- Sidebar Toggle Button (visible only on small screens) -->
<button class="btn btn-danger m-3 d-md-none" id="toggleSidebar">
  <i class="fas fa-bars"></i>
</button>

  <div class="d-flex">
    <?php include '../components/adminComp/sidebar.php'; ?>

    <div class="main">
      <h1 class="mb-4">Dashboard</h1>
      <div class="cards-container">
        <div class="card doc-card">
          <i class="fas fa-user-md"></i>
          <h3>Total Doctors</h3>
          <span><?= $doctor_count ?></span>
        </div>
        <div class="card city-card">
          <i class="fas fa-city"></i>
          <h3>Total Cities</h3>
          <span><?= $city_count ?></span>
        </div>
        <div class="card patient-card">
          <i class="fas fa-procedures"></i>
          <h3>Total Patients</h3>
          <span><?= $patient_count ?></span>
        </div>
        <div class="card appointment-card">
          <i class="fas fa-calendar-check"></i>
          <h3>Total Appointments</h3>
          <span><?= $appointment_count ?></span>
        </div>
      </div>

      <?php include '../components/adminComp/footer.php'; ?>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.querySelector('.sidebar');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('d-none');
  });
</script>

</body>

</html>