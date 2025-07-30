
<style>
  .footer {
    background: linear-gradient(135deg, #2246d2, #041a83);
    color: white;
    padding: 3rem 0 1.5rem;
  }

  .footer-brand {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
  }

  .footer-social {
    display: flex;
    gap: 1rem;
    margin: 1.5rem 0;
  }

  .footer-social a {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
  }

  .footer-social a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
  }

  .footer-links li {
    margin-bottom: 0.75rem;
  }

  .footer-links a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
  }

  .footer-links a:hover {
    color: white;
    transform: translateX(5px);
  }

  .footer-contact li {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    color: rgba(255, 255, 255, 0.8);
  }

  .footer-contact i {
    background: rgba(255, 255, 255, 0.1);
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 1rem;
  }

  .footer-bottom {
    text-align: center;
    padding-top: 2rem;
    margin-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.6);
  }

  @media (max-width: 768px) {
    .footer {
      padding: 2rem 0 1rem;
    }

    .footer-section {
      margin-bottom: 2rem;
      text-align: center;
    }

    .footer-social {
      justify-content: center;
    }

    .footer-links li {
      margin-bottom: 0.5rem;
    }

    .footer-contact {
      display: inline-block;
      text-align: left;
    }

    .footer-bottom {
      margin-top: 1rem;
      padding-top: 1rem;
    }
  }
</style>

<footer class="footer">
  <div class="container">
    <div class="row g-4">
      <!-- Brand + Social -->
      <div class="col-lg-4 col-md-6 footer-section">
        <div class="footer-brand">
          <i class="fas fa-virus me-2"></i>Care Group
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