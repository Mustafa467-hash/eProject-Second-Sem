<style>
  .navbar {
    background: linear-gradient(to right, #f8f9fa, #ffffff);
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .navbar .nav-link {
    font-weight: 500;
    transition: all 0.4s ease-in;
    margin-left: 10px;
  }

  .navbar .nav-link:hover {
    background: linear-gradient(135deg, #2246d2, #041a83);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .btn-danger {
    background: linear-gradient(135deg, #2246d2, #041a83);
    border: none;
    box-shadow: 0 0 10px #041A83;
    transition: transform 0.2s ease;
  }

  .btn-danger:hover {
    transform: scale(1.05);
  }

  /* Scroll effect (optional) */
  .navbar.scrolled {
    background-color: white !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm" id="mainNavbar">
  <div class="container">
    <a href="dashboard.php" class="navbar-brand fw-bold text-primary d-flex align-items-center">
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