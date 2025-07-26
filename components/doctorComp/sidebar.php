<?php
if (!isset($_SESSION)) session_start();
$doctor_name = $_SESSION['doctor_name'] ?? 'Doctor';
?>

<div class="sidebar d-flex flex-column p-3">
  <h2 class="text-white mb-4">Doctor Panel</h2>
  <ul class="nav flex-column mb-auto">
    <li class="nav-item mb-2">
      <a href="dashboard.php" class="nav-link text-white">
        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="appointments.php" class="nav-link text-white">
        <i class="fas fa-calendar-check me-2"></i> Appointments
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="profile.php" class="nav-link text-white">
        <i class="fas fa-user-md me-2"></i> Profile
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="availability.php" class="nav-link text-white">
        <i class="fas fa-clock me-2"></i> Availability
      </a>
    </li>
  </ul>

  <div class="mt-auto">
    <div class="text-white mb-2 small">Logged in as</div>
    <div class="fw-bold text-white mb-3"><?= htmlspecialchars($doctor_name) ?></div>
    <a href="logout.php" class="btn btn-light btn-sm w-100">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
</div>

<style>
.sidebar {
  width: 250px;
  min-height: 100vh;
  background: linear-gradient(135deg, #1e3c72, #2a5298); /* deep rich blue */
  color: white;
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  position: fixed;
  left: 0;
  top: 0;
}
.sidebar .nav-link {
  font-weight: 500;
  transition: background 0.2s;
  border-radius: 8px;
  padding: 8px 12px;
}
.sidebar .nav-link:hover,
.sidebar .nav-link.active {
  background: rgba(255, 255, 255, 0.15);
}
</style>
