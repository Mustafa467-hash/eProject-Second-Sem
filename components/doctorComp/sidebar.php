<?php
if (!isset($_SESSION)) session_start();
$doctor_name = $_SESSION['doctor_name'] ?? 'Doctor';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar d-flex flex-column">
  <div class="sidebar-header">
    <div class="logo-container">
      <i class="fas fa-hospital-user"></i>
      <span>Care Group</span>
    </div>
  </div>

  <div class="doctor-profile">
    <div class="doctor-avatar">
      <i class="fas fa-user-md"></i>
    </div>
    <div class="doctor-info">
      <div class="doctor-name"><?= htmlspecialchars($doctor_name) ?></div>
      <div class="doctor-role">Medical Professional</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <a href="dashboard.php" class="nav-link <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
      <div class="nav-link-icon">
        <i class="fas fa-chart-line"></i>
      </div>
      <span>Dashboard</span>
    </a>

    <a href="appointments.php" class="nav-link <?= $current_page === 'appointments.php' ? 'active' : '' ?>">
      <div class="nav-link-icon">
        <i class="fas fa-calendar-check"></i>
      </div>
      <span>Appointments</span>
    </a>

    <a href="profile.php" class="nav-link <?= $current_page === 'profile.php' ? 'active' : '' ?>">
      <div class="nav-link-icon">
        <i class="fas fa-user-circle"></i>
      </div>
      <span>My Profile</span>
    </a>

    <a href="availability.php" class="nav-link <?= $current_page === 'availability.php' ? 'active' : '' ?>">
      <div class="nav-link-icon">
        <i class="fas fa-clock"></i>
      </div>
      <span>Availability</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="logout.php" class="logout-button">
      <i class="fas fa-sign-out-alt"></i>
      <span>Sign Out</span>
    </a>
  </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

.sidebar {
  width: 280px;
  min-height: 100vh;
  background: #ffffff;
  position: fixed;
  left: 0;
  top: 0;
  border-right: 1px solid #e2e8f0;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.sidebar-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.logo-container {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.25rem;
  font-weight: 700;
  color: #1e293b;
}

.logo-container i {
  color: #2563eb;
  font-size: 1.5rem;
}

.doctor-profile {
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  border-bottom: 1px solid #e2e8f0;
}

.doctor-avatar {
  width: 48px;
  height: 48px;
  background: #eff6ff;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #2563eb;
  font-size: 1.25rem;
}

.doctor-info {
  flex: 1;
}

.doctor-name {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.doctor-role {
  font-size: 0.875rem;
  color: #64748b;
}

.sidebar-nav {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  color: #64748b;
  text-decoration: none;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.nav-link:hover {
  background: #f8fafc;
  color: #1e293b;
}

.nav-link.active {
  background: #eff6ff;
  color: #2563eb;
}

.nav-link-icon {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
}

.nav-link span {
  font-weight: 500;
}

.sidebar-footer {
  margin-top: auto;
  padding: 1.5rem;
  border-top: 1px solid #e2e8f0;
}

.logout-button {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  background: #fef2f2;
  color: #dc2626;
  border-radius: 12px;
  text-decoration: none;
  transition: all 0.3s ease;
}

.logout-button:hover {
  background: #fee2e2;
  color: #b91c1c;
  transform: translateY(-1px);
}

.logout-button span {
  font-weight: 500;
}

@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 1000;
  }

  .sidebar.active {
    transform: translateX(0);
  }
}
</style>
