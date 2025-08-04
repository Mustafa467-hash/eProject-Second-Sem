<style>
  :root {
    --primary-color: #16a34a;
    --primary-hover: #15803d;
    --navbar-bg: rgba(255, 255, 255, 0.95);
    --text-color: #333;
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
  }

  .navbar {
    background: var(--navbar-bg);
    backdrop-filter: blur(10px);
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    padding: 0.75rem 0;
  }

  .name {
    font-size: 1.25rem;
    color: var(--primary-color);
    font-weight: 600;
    letter-spacing: -0.5px;
  }

  .navbar .nav-link {
    font-weight: 500;
    color: var(--text-color) !important;
    transition: var(--transition);
    margin-left: 1.5rem;
    position: relative;
    padding: 0.5rem 0;
  }

  .navbar .nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: var(--primary-color);
    transition: var(--transition);
  }

  .navbar .nav-link:hover::after,
  .navbar .nav-link.active::after {
    width: 100%;
  }

  .navbar .nav-link:hover {
    color: var(--primary-color) !important;
  }

  .emergency-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    font-weight: 600;
    transition: var(--transition);
    box-shadow: 0 2px 8px rgba(22, 163, 74, 0.2);
  }

  .emergency-btn:hover {
    background: var(--primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
  }

  .navbar.scrolled {
    background: white;
    box-shadow: var(--shadow-md);
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm" id="mainNavbar">
  <div class="container">
    <a href="dashboard.php" class="navbar-brand fw-bold d-flex justify-content-center name align-items-center">
      <i class="fas fa-stethoscope me-2"></i>
      <span class="sidebar-text">Care Group</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active text-dark" href="dashboard.php"><i class="fas fa-home me-1"></i>Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="doctor.php"><i class="fas fa-user-md me-1"></i>Doctors</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="book-appointment.php"><i
              class="fas fa-calendar-check me-1"></i>Appointments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="contact.php"><i class="fas fa-envelope me-1"></i>Contact</a>
        </li>
      </ul>

      <a href="tel:102" class="btn btn-danger ms-lg-3 fw-semibold">
        Emergency
      </a>
    </div>
  </div>
</nav>

<script>
  window.addEventListener('scroll', function () {
    const navbar = document.getElementById('mainNavbar');
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  });
</script>