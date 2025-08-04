
<style>
  :root {
    --footer-bg: #0f172a;
    --footer-text: rgba(255, 255, 255, 0.9);
    --footer-muted: rgba(255, 255, 255, 0.6);
    --footer-hover: #16a34a;
    --footer-border: rgba(255, 255, 255, 0.1);
    --transition: all 0.3s ease;
  }

  .footer {
    background: var(--footer-bg);
    color: var(--footer-text);
    padding: 4rem 0 2rem;
    position: relative;
    overflow: hidden;
  }

  .footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 1px;
    background: linear-gradient(to right,
        transparent,
        var(--footer-hover),
        transparent);
  }

  .footer-brand {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
    color: var(--footer-text);
    letter-spacing: -0.5px;
  }

  .footer-brand i {
    color: var(--footer-hover);
  }

  .footer-social {
    display: flex;
    gap: 1rem;
    margin: 1.5rem 0;
  }

  .footer-social a {
    color: var(--footer-text);
    background: rgba(255, 255, 255, 0.05);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: var(--transition);
    border: 1px solid var(--footer-border);
  }

  .footer-social a:hover {
    background: var(--footer-hover);
    border-color: var(--footer-hover);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(22, 163, 74, 0.3);
  }

  .footer-links li {
    margin-bottom: 0.875rem;
    transition: var(--transition);
  }

  .footer-links a {
    color: var(--footer-muted);
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
  }

  .footer-links a i {
    font-size: 0.75rem;
    transition: var(--transition);
    opacity: 0;
    transform: translateX(-8px);
  }

  .footer-links a:hover {
    color: var(--footer-hover);
  }

  .footer-links a:hover i {
    opacity: 1;
    transform: translateX(0);
  }

  .footer-contact li {
    display: flex;
    align-items: center;
    margin-bottom: 1.25rem;
    color: var(--footer-muted);
  }

  .footer-contact i {
    background: rgba(22, 163, 74, 0.1);
    color: var(--footer-hover);
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 1rem;
    transition: var(--transition);
  }

  .footer-contact li:hover i {
    background: var(--footer-hover);
    color: white;
    transform: scale(1.1);
  }

  .footer-bottom {
    text-align: center;
    padding-top: 2rem;
    margin-top: 3rem;
    border-top: 1px solid var(--footer-border);
    color: var(--footer-muted);
  }

  @media (max-width: 768px) {
    .footer {
      padding: 3rem 0 1.5rem;
    }

    .footer-section {
      margin-bottom: 2.5rem;
      text-align: center;
    }

    .footer-social {
      justify-content: center;
    }

    .footer-links li {
      margin-bottom: 0.75rem;
    }

    .footer-contact {
      display: inline-block;
      text-align: left;
    }

    .footer-bottom {
      margin-top: 1.5rem;
      padding-top: 1.5rem;
    }
  }
</style>

<footer class="footer">
  <div class="container">
    <div class="row g-4">
      <!-- Brand + Social -->
      <div class="col-lg-4 col-md-6 footer-section">
        <div class="footer-brand">
          <i class="fas fa-stethoscope me-2"></i>Care Group
        </div>
        <p>Providing Quality Healthcare Solutions</p>
        <div class="footer-social">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="col-lg-4  footer-section">
        <h5 class="fw-bold mb-3">Quick Links</h5>
        <ul class="list-unstyled footer-links">
          <li><a href="dashboard.php"><i class="fas fa-chevron-right me-2"></i>Home</a></li>
          <li><a href="doctors.php"><i class="fas fa-chevron-right me-2"></i>Doctors</a></li>
          <li><a href="appointments.php"><i class="fas fa-chevron-right me-2"></i>Appointments</a></li>
          <li><a href="contact.php"><i class="fas fa-chevron-right me-2"></i>Contact Us</a></li>
        </ul>
      </div>


      <!-- Contact Info -->
      <div class="col-lg-4 footer-section">
        <h5 class="fw-bold mb-3">Contact Info</h5>
        <ul class="list-unstyled footer-contact">
          <li>
            <i class="fas fa-map-marker-alt"></i>
            <span>123 Main St, Karachi, PK</span>
          </li>
          <li>
            <i class="fas fa-phone-alt"></i>
            <span>+92 123 456 7890</span>
          </li>
          <li>
            <i class="fas fa-envelope"></i>
            <span>care@group.com</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <small>&copy; <?= date('Y') ?> Care Group — All Rights Reserved.</small>
    </div>
  </div>
</footer>