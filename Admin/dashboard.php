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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    :root {
      --card-border-radius: 16px;
      --transition-speed: 0.3s;
    }

    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #f8fafc;
    }

    body {
      display: flex;
      flex-direction: column;
    }

    .main-wrapper {
      flex: 1;
      display: flex;
      flex-direction: row;
      background: #f8fafc;
    }

    .main {
      flex-grow: 1;
      padding: 2rem;
      margin-left: 280px;
    }

    .dashboard-header {
      margin-bottom: 2rem;
    }

    .dashboard-header h1 {
      font-size: 1.875rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.5rem;
    }

    .welcome-text {
      color: #64748b;
      font-size: 1.1rem;
    }

    .cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .card {
      background: #ffffff;
      padding: 1.5rem;
      border-radius: var(--card-border-radius);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      border: 1px solid #e2e8f0;
      transition: all var(--transition-speed) ease;
      position: relative;
      overflow: hidden;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(to right, var(--start-color), var(--end-color));
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px -10px rgba(0, 0, 0, 0.1);
    }

    .card-content {
      position: relative;
      z-index: 1;
    }

    .card-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, var(--start-color), var(--end-color));
    }

    .card-icon i {
      font-size: 1.5rem;
      color: white;
    }

    .card h3 {
      font-size: 1rem;
      font-weight: 600;
      color: #64748b;
      margin-bottom: 0.5rem;
    }

    .card span {
      font-size: 2rem;
      font-weight: 700;
      color: #1e293b;
      display: block;
    }

    .doc-card {
      --start-color: #2246d2;
      --end-color: #6366f1;
    }

    .city-card {
      --start-color: #06b6d4;
      --end-color: #0ea5e9;
    }

    .patient-card {
      --start-color: #f59e0b;
      --end-color: #fbbf24;
    }

    .appointment-card {
      --start-color: #ec4899;
      --end-color: #f472b6;
    }

    .footer {
      margin-left: 280px;
      width: calc(100% - 280px);
      text-align: center;
      padding: 1.5rem;
      color: #64748b;
      font-size: 0.875rem;
      background-color: #fff;
      border-top: 1px solid #e2e8f0;
      position: relative;
      z-index: 1;
      background: #ffffff;
      padding: 1rem;
      text-align: center;
      color: #64748b;
      font-size: 0.875rem;
      border-top: 1px solid #e2e8f0;
    }

    .toggle-btn {
      display: none;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        position: fixed;
        height: 100%;
        z-index: 1000;
        background-color: white;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .main {
        margin-left: 0;
        padding-top: 60px;
      }

      .toggle-btn {
        display: inline-block;
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 12px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
        margin: 15px;
        z-index: 1100;
        position: relative;
      }
    }
  </style>
</head>

<body>



<div class="main-wrapper">
  <?php include '../components/adminComp/sidebar.php'; ?>

  <div class="main">
    <div class="dashboard-header">
      <h1>Dashboard Overview</h1>
      <p class="welcome-text">Welcome back! Here's what's happening with your medical practice.</p>
    </div>
    
    <div class="cards-container">
      <div class="card doc-card">
        <div class="card-content">
          <div class="card-icon">
            <i class="fas fa-user-md"></i>
          </div>
          <h3>Total Doctors</h3>
          <span><?= $doctor_count ?></span>
        </div>
      </div>
      <div class="card city-card">
        <div class="card-content">
          <div class="card-icon">
            <i class="fas fa-city"></i>
          </div>
          <h3>Total Cities</h3>
          <span><?= $city_count ?></span>
        </div>
      </div>
      <div class="card patient-card">
        <div class="card-content">
          <div class="card-icon">
            <i class="fas fa-procedures"></i>
          </div>
          <h3>Total Patients</h3>
          <span><?= $patient_count ?></span>
        </div>
      </div>
      <div class="card appointment-card">
        <div class="card-content">
          <div class="card-icon">
            <i class="fas fa-calendar-check"></i>
          </div>
          <h3>Total Appointments</h3>
          <span><?= $appointment_count ?></span>
        </div>
      </div>
    </div>

   
  </div>
</div>

<footer class="footer text-center py-3">
  <div class="container">
    <p class="mb-0">&copy; <?= date('Y') ?> Care Group. All rights reserved.</p>
  </div>
</footer>
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
  }
</script>

</body>
</html>
