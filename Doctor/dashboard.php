<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}

$doctor_id = $_SESSION['doctor_id'];
$doctor_name = $_SESSION['doctor_name'] ?? 'Doctor';

// Fetch total appointments for this doctor
$totalAppointments = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = $doctor_id")->fetch_assoc()['total'];

// Fetch today's appointments
$today = date('Y-m-d');
$todayAppointments = $conn->query("
    SELECT p.name AS patient_name, a.appointment_date, a.appointment_time, a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today'
    ORDER BY a.appointment_time ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }

    /* Top welcome bar */
    .welcome-bar {
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      color: white;
      padding: 15px 20px;
      border-radius: 12px;
      margin-bottom: 25px;
      font-weight: 500;
      font-size: 18px;
    }

    /* Main content wrapper */
    .main {
      margin-left: 250px;
      padding: 20px;
    }

    /* Stat cards */
    .cards-container {
      display: flex;
      gap: 20px;
      margin-bottom: 25px;
    }
    .card-stat {
      flex: 1;
      border-radius: 12px;
      padding: 20px;
      color: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
    }
    .card-stat {
      background: linear-gradient(to right, #4facfe, #00f2fe);
    }

    .availability-card {
      background: linear-gradient(to right, #43e97b, #38f9d7);
    }

    .status-card {
      background: linear-gradient(to right, #fa709a, #fee140);
    }

    .appointment-card {
      background: linear-gradient(to right, #667eea, #764ba2);
    }
    .card-stat i {
      font-size: 32px;
      margin-bottom: 10px;
    }
    .card-stat h3 {
      font-size: 20px;
      margin: 0;
    }
    .card-stat span {
      font-size: 26px;
      font-weight: bold;
      display: block;
      margin-top: 5px;
    }

    /* Appointments table */
    table {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      width: 100%;
      border-collapse: collapse;
      overflow: hidden;
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #eee;
    }
    th {
      background: #1e3c72;
      color: white;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 10px;
      color: #777;
      font-size: 14px;
      background-color: #fff;
      border-top: 1px solid #ddd;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<?php include '../components/doctorComp/sidebar.php'; ?>

<div class="main">
  <div class="welcome-bar">
    Logged in as <strong>Dr. <?= htmlspecialchars($doctor_name) ?></strong>
  </div>

  <div class="cards-container">
    <div class="card-stat">
      <i class="fas fa-calendar-check"></i>
      <h3>Total Appointments</h3>
      <span><?= $totalAppointments ?></span>
    </div>
    <div class="card-stat appointment-card">
      <i class="fas fa-calendar-day"></i>
      <h3>Today's Appointments</h3>
      <span><?= $todayAppointments->num_rows ?></span>
    </div>
    <div class="card-stat availability-card">
      <i class="fas fa-clock"></i>
      <h3>Availability</h3>
      <span>Active</span>
    </div>
    <div class="card-stat status-card">
      <i class="fas fa-user-md"></i>
      <h3>Profile Status</h3>
      <span>Complete</span>
    </div>
  </div>

  <h4 class="mb-3">Today's Schedule</h4>
  <?php if ($todayAppointments->num_rows > 0): ?>
    <table>
      <tr>
        <th>Patient</th>
        <th>Time</th>
        <th>Status</th>
      </tr>
      <?php while ($row = $todayAppointments->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['patient_name']) ?></td>
          <td><?= htmlspecialchars($row['appointment_time']) ?></td>
          <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <div class="p-3 bg-white rounded shadow-sm">No appointments scheduled for today.</div>
  <?php endif; ?>
</div>

<footer>
  &copy; <?= date('Y') ?> Care Group. All rights reserved.
</footer>

</body>
</html>
