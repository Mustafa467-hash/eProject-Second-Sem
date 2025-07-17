<style>
  :root {
    --sidebar-width: 250px;
  }

  #sidebar {
    min-height: 100vh;
    width: var(--sidebar-width);
    background: linear-gradient(135deg, #8e0e00, #e52e71);
    color: white;
    transition: all 0.3s ease;
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
    overflow-x: hidden;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    padding: 1.5rem 1rem;
  }

  #sidebar.collapsed {
    width: 70px;
    padding: 1.5rem 0.5rem;
  }

  #sidebar .navbar-brand {
    font-size: 1.25rem;
    white-space: nowrap;
    overflow: hidden;
  }

  #sidebar .nav-link {
    color: white;
    padding: 10px 12px;
    border-radius: 10px;
    white-space: nowrap;
    overflow: hidden;
    transition: background 0.2s ease;
  }

  #sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #ffd700;
  }

  #sidebar .nav-link.active {
    font-weight: bold;
    background-color: rgba(255, 255, 255, 0.2);
    color: #ffd700;
  }

  #toggle-btn {
    background-color: #ff416c;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 6px 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 1rem;
    display: none;
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
  @media (max-width: 768px) {
    #sidebar {
      position: absolute;
      width: 100%;
      height: auto;
      min-height: unset;
      top: 0;
      left: 0;
      border-radius: 0;
      transform: translateY(-100%);
      transition: transform 0.3s ease-in-out;
    }

    #sidebar.open {
      transform: translateY(0);
    }

    #toggle-btn {
      display: block;
      position: fixed;
      top: 10px;
      right: 10px;
      z-index: 1100;
    }

    #page-content {
      margin-left: 0 !important;
      margin-top: 60px;
    }
  }
</style>

<!-- Toggle button (mobile only) -->
<button id="toggle-btn"><i class="fas fa-bars"></i></button>

<!-- SIDEBAR / NAVBAR -->
<nav id="sidebar" class="d-flex flex-column">
  <a href="dashboard.php" class="navbar-brand text-white fw-bold mb-4">
    <i class="fas fa-stethoscope me-2"></i><span class="sidebar-text">Care Group</span>
  </a>

  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
        <i class="fas fa-tachometer-alt me-2"></i><span class="sidebar-text">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_doctors.php' ? 'active' : '' ?>"
        href="manage_doctors.php">
        <i class="fas fa-user-md me-2"></i><span class="sidebar-text">Doctors</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_patients.php' ? 'active' : '' ?>"
        href="manage_patients.php">
        <i class="fas fa-users me-2"></i><span class="sidebar-text">Patients</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_cities.php' ? 'active' : '' ?>"
        href="manage_cities.php">
        <i class="fas fa-city me-2"></i><span class="sidebar-text">Cities</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : '' ?>"
        href="manage_users.php">
        <i class="fas fa-user-cog me-2"></i><span class="sidebar-text">Users</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_appointments.php' ? 'active' : '' ?>"
        href="manage_appointments.php">
        <i class="fas fa-user-cog me-2"></i><span class="sidebar-text">Appointments</span>
      </a>
    </li>

    <li class="nav-item mt-3">
      <a class="nav-link text-warning fw-bold" href="logout.php">
        <i class="fas fa-sign-out-alt me-2"></i><span class="sidebar-text">Logout</span>
      </a>
    </li>
  </ul>
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