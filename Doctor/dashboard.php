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

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: #ffffff;
      padding: 1.5rem;
      border-radius: var(--card-border-radius);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      border: 1px solid #e2e8f0;
      transition: all var(--transition-speed) ease;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
    }

    .stat-card:nth-child(1)::before {
      background: linear-gradient(to right, #2246d2, #6366f1);
    }

    .stat-card:nth-child(2)::before {
      background: linear-gradient(to right, #06b6d4, #0ea5e9);
    }

    .stat-card:nth-child(3)::before {
      background: linear-gradient(to right, #f59e0b, #fbbf24);
    }

    .stat-card:nth-child(4)::before {
      background: linear-gradient(to right, #ec4899, #f472b6);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px -10px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
      color: white;
    }

    .appointments-icon {
      background: linear-gradient(135deg, #2246d2, #6366f1);
    }

    .today-icon {
      background: linear-gradient(135deg, #06b6d4, #0ea5e9);
    }

    .availability-icon {
      background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }

    .profile-icon {
      background: linear-gradient(135deg, #ec4899, #f472b6);
    }

    .stat-icon i {
      font-size: 1.5rem;
    }

    .stat-card h3 {
      font-size: 1rem;
      font-weight: 600;
      color: #64748b;
      margin-bottom: 0.5rem;
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.25rem;
    }

    .stat-label {
      font-size: 0.875rem;
      color: #64748b;
    }

    .schedule-card {
      background: #ffffff;
      padding: 1.5rem;
      border-radius: var(--card-border-radius);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      border: 1px solid #e2e8f0;
    }

    .schedule-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .schedule-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: #1e293b;
      margin: 0;
    }

    .schedule-date {
      color: #64748b;
      font-size: 0.875rem;
    }

    .schedule-table {
      width: 100%;
      border-collapse: collapse;
    }

    .schedule-table th {
      background: #f8fafc;
      color: #1e293b;
      font-weight: 600;
      padding: 1rem;
      text-align: left;
      border-bottom: 2px solid #e2e8f0;
    }

    .schedule-table td {
      padding: 1rem;
      border-bottom: 1px solid #e2e8f0;
      color: #64748b;
    }

    .patient-info {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .patient-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #64748b;
    }

    .appointment-time {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .status-badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .status-confirmed {
      background: #dcfce7;
      color: #166534;
    }

    .status-pending {
      background: #fef3c7;
      color: #92400e;
    }

    .status-cancelled {
      background: #fee2e2;
      color: #991b1b;
    }

    .empty-schedule {
      text-align: center;
      padding: 3rem 0;
      color: #64748b;
    }

    .empty-schedule i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #cbd5e1;
    }

    footer {
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
    }

    @media (max-width: 768px) {
      .main {
        margin-left: 0;
        width: 100%;
      }

      footer {
        margin-left: 0;
        width: 100%;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<?php include '../components/doctorComp/sidebar.php'; ?>

<div class="main">
  <div class="dashboard-header">
    <h1>Doctor Dashboard</h1>
    <p>View your appointments and practice overview</p>
  </div>

  <div class="welcome-card">
   
    <div class="welcome-text">
      <h2>Welcome back, <?= htmlspecialchars($doctor_name) ?></h2>
      <p>Here's what's happening with your practice today</p>
    </div>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon appointments-icon">
        <i class="fas fa-calendar-check"></i>
      </div>
      <h3>Total Appointments</h3>
      <div class="stat-value"><?= $totalAppointments ?></div>
      <div class="stat-label">Scheduled patients</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon today-icon">
        <i class="fas fa-calendar-day"></i>
      </div>
      <h3>Today's Schedule</h3>
      <div class="stat-value"><?= $todayAppointments->num_rows ?></div>
      <div class="stat-label">Appointments today</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon availability-icon">
        <i class="fas fa-clock"></i>
      </div>
      <h3>Availability</h3>
      <div class="stat-value">Active</div>
      <div class="stat-label">Ready for appointments</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon profile-icon">
        <i class="fas fa-user-check"></i>
      </div>
      <h3>Profile Status</h3>
      <div class="stat-value">Complete</div>
      <div class="stat-label">Profile information</div>
    </div>
  </div>

  <div class="schedule-card">
    <div class="schedule-header">
      <h5 class="schedule-title">Today's Schedule</h5>
      <span class="schedule-date"><?= date('l, F j, Y') ?></span>
    </div>

    <?php if ($todayAppointments->num_rows > 0): ?>
      <table class="schedule-table">
        <thead>
          <tr>
            <th>Patient</th>
            <th>Appointment Time</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $todayAppointments->fetch_assoc()): ?>
            <tr>
              <td>
                <div class="patient-info">
                  <div class="patient-avatar">
                    <i class="fas fa-user"></i>
                  </div>
                  <?= htmlspecialchars($row['patient_name']) ?>
                </div>
              </td>
              <td>
                <span class="appointment-time">
                  <i class="far fa-clock me-2"></i>
                  <?= date('h:i A', strtotime($row['appointment_time'])) ?>
                </span>
              </td>
              <td>
                <span class="status-badge status-<?= strtolower($row['status']) ?>">
                  <?= htmlspecialchars(ucfirst($row['status'])) ?>
                </span>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="empty-schedule">
        <i class="far fa-calendar-check"></i>
        <p>No appointments scheduled for today</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<footer>
  <div class="container">
    <p class="mb-0">&copy; <?= date('Y') ?> Care Group. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
