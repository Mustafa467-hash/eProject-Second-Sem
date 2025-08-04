<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

  :root {
    --sidebar-width: 280px;
    --primary-color: #2246d2;
    --secondary-color: #6366f1;
    --accent-color: #818cf8;
    --text-primary: #f8fafc;
    --text-secondary: #cbd5e1;
    --transition-speed: 0.3s;
  }

  #sidebar {
    min-height: 100vh;
    width: var(--sidebar-width);
    background: linear-gradient(165deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--text-primary);
    transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
    border-top-right-radius: 24px;
    border-bottom-right-radius: 24px;
    overflow-x: hidden;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    padding: 2rem;
    box-shadow: 5px 0 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
  }

  #sidebar.collapsed {
    width: 80px;
    padding: 2rem 1rem;
  }

  #sidebar .navbar-brand {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    margin-bottom: 2rem;
    padding: 0.5rem;
    transition: all var(--transition-speed) ease;
    opacity: 0.95;
  }

  #sidebar .navbar-brand:hover {
    opacity: 1;
    transform: translateX(5px);
  }

  #sidebar .nav-link {
    color: var(--text-secondary);
    padding: 12px 16px;
    border-radius: 12px;
    white-space: nowrap;
    overflow: hidden;
    transition: all var(--transition-speed) ease;
    margin-bottom: 8px;
    font-weight: 500;
    position: relative;
    display: flex;
    align-items: center;
  }

  #sidebar .nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    transform: translateX(5px);
  }

  #sidebar .nav-link.active {
    background: rgba(255, 255, 255, 0.15);
    color: var(--text-primary);
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  #sidebar .nav-link i {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-speed) ease;
  }

  #sidebar .nav-link .sidebar-text {
    margin-left: 12px;
    opacity: 0.95;
  }

  #toggle-btn {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    border: none;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    transition: all var(--transition-speed) ease;
    margin: 1rem;
    display: none;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  #toggle-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
  }

  #page-content {
    margin-left: var(--sidebar-width);
    transition: margin-left 0.3s ease;
    width: 100%;
  }

  #sidebar.collapsed~#page-content {
    margin-left: 70px;
  }

  /* Mobile View */
  /* Additional Styles */
  .nav-section {
    position: relative;
  }

  .nav-section small {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
  }

  .brand-icon {
    width: 35px;
    height: 35px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
  }

  .logout-link {
    margin-top: 2rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: var(--text-primary) !important;
  }

  .logout-link:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.2);
    color: #fff !important;
  }

  /* Hover Indicators */
  #sidebar .nav-link::before {
    content: '';
    position: absolute;
    left: -2rem;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 0;
    background: var(--accent-color);
    border-radius: 4px;
    transition: height var(--transition-speed) ease;
  }

  #sidebar .nav-link:hover::before,
  #sidebar .nav-link.active::before {
    height: 20px;
  }

  /* Mobile Responsive */
  @media (max-width: 768px) {
    #sidebar {
      transform: translateX(-100%);
      border-radius: 0;
    }

    #sidebar.open {
      transform: translateX(0);
    }

    #toggle-btn {
      display: block;
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 1100;
      background: var(--primary-color);
      padding: 0.8rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    #page-content {
      margin-left: 0 !important;
      padding-top: 4rem;
    }

    .brand-icon {
      width: 30px;
      height: 30px;
      font-size: 1rem;
    }

    #sidebar .nav-link {
      padding: 10px 14px;
    }
  }

  /* Animations */
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(-10px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  #sidebar .nav-link {
    animation: slideIn 0.3s ease forwards;
  }

  #sidebar .nav-link:nth-child(1) { animation-delay: 0.1s; }
  #sidebar .nav-link:nth-child(2) { animation-delay: 0.2s; }
  #sidebar .nav-link:nth-child(3) { animation-delay: 0.3s; }
  #sidebar .nav-link:nth-child(4) { animation-delay: 0.4s; }
  #sidebar .nav-link:nth-child(5) { animation-delay: 0.5s; }
</style>

<!-- Toggle button (mobile only) -->
<button id="toggle-btn"><i class="fas fa-bars"></i></button>

<!-- SIDEBAR / NAVBAR -->
<nav id="sidebar" class="d-flex flex-column">
  <a href="dashboard.php" class="navbar-brand d-flex align-items-center">
    <div class="brand-icon me-3">
      <i class="fas fa-heartbeat"></i>
    </div>
    <span class="sidebar-text">Care Group</span>
  </a>

  <div class="nav-section mb-4">
    <small class="text-uppercase fw-semibold opacity-75 ps-3 mb-2 d-block">Main Menu</small>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
          <i class="fas fa-chart-line"></i>
          <span class="sidebar-text">Dashboard</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="nav-section mb-4">
    <small class="text-uppercase fw-semibold opacity-75 ps-3 mb-2 d-block">Management</small>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_doctors.php' ? 'active' : '' ?>"
          href="manage_doctors.php">
          <i class="fas fa-user-md"></i>
          <span class="sidebar-text">Doctors</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_patients.php' ? 'active' : '' ?>"
          href="manage_patients.php">
          <i class="fas fa-users"></i>
          <span class="sidebar-text">Patients</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_appointments.php' ? 'active' : '' ?>"
          href="manage_appointments.php">
          <i class="fas fa-calendar-check"></i>
          <span class="sidebar-text">Appointments</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="nav-section mb-4">
    <small class="text-uppercase fw-semibold opacity-75 ps-3 mb-2 d-block">Settings</small>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_cities.php' ? 'active' : '' ?>"
          href="manage_cities.php">
          <i class="fas fa-city"></i>
          <span class="sidebar-text">Cities</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : '' ?>"
          href="manage_users.php">
          <i class="fas fa-user-shield"></i>
          <span class="sidebar-text">Users</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="mt-auto">
    <a class="nav-link logout-link" href="logout.php">
      <i class="fas fa-sign-out-alt"></i>
      <span class="sidebar-text">Logout</span>
    </a>
  </div>
</nav>

<!-- Script -->x
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-btn');
    const textItems = document.querySelectorAll('.sidebar-text');

    toggleBtn.addEventListener('click', function () {
      if (window.innerWidth < 768) {
        sidebar.classList.toggle('open');
      } else {
        sidebar.classList.toggle('collapsed');
        textItems.forEach(item => item.classList.toggle('d-none'));
      }
    });
  });
</script>