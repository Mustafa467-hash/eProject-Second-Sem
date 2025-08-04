<?php
$title = "Add Appointment";
require_once '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ✅ Mobile Scaling -->
  <title><?= $title ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
      --primary-color: #2246d2;
      --secondary-color: #6366f1;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --dark-color: #1e293b;
      --light-color: #f8fafc;
      --border-radius: 16px;
      --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
      background: var(--light-color);
      color: #334155;
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
    }

    .contact-form {
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: var(--border-radius);
      padding: 2.5rem;
      box-shadow: 0 10px 25px -5px rgba(34, 70, 210, 0.15);
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
      transition: var(--transition);
    }

    .contact-form::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, 
        rgba(34, 70, 210, 0.05),
        rgba(99, 102, 241, 0.05)
      );
      opacity: 0;
      transition: var(--transition);
    }

    .contact-form:hover::before {
      opacity: 1;
    }

    .form-label {
      font-weight: 600;
      color: var(--dark-color);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
    }

    .form-control {
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: var(--border-radius);
      padding: 0.875rem 1rem;
      transition: var(--transition);
      font-size: 0.95rem;
      background: rgba(255, 255, 255, 0.9);
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(34, 70, 210, 0.1);
      outline: none;
    }

    .form-control:hover {
      border-color: var(--secondary-color);
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      padding: 1rem 2rem;
      font-weight: 600;
      border-radius: 9999px;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
      font-size: 1rem;
      box-shadow: 0 4px 6px -1px rgba(34, 70, 210, 0.2);
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
      opacity: 0;
      transition: var(--transition);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px -4px rgba(34, 70, 210, 0.3);
    }

    .btn-primary:hover::before {
      opacity: 1;
    }

    .btn-primary span {
      position: relative;
      z-index: 1;
    }

    .contact-heading {
      color: var(--dark-color);
      font-weight: 800;
      margin-bottom: 2.5rem;
      font-size: 2.5rem;
      text-align: center;
      position: relative;
      padding-bottom: 1rem;
    }

    .contact-heading::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 2px;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
    }

    textarea.form-control {
      min-height: 120px;
      resize: vertical;
    }

    .contact-info {
      text-align: center;
      margin-bottom: 2rem;
      color: #475569;
      font-size: 1.1rem;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }
    
    @media (max-width: 768px) {
      .contact-form {
        padding: 2rem;
      }
      .contact-heading {
        font-size: 2rem;
        margin-bottom: 2rem;
      }
      .contact-info {
        font-size: 1rem;
        margin-bottom: 1.5rem;
      }
    }

    @media (max-width: 576px) {
      .contact-form {
        padding: 1.5rem;
      }
      .contact-heading {
        font-size: 1.75rem;
      }
      .form-control {
        font-size: 0.9375rem;
        padding: 0.75rem;
      }
      .contact-info {
        font-size: 0.95rem;
        margin-bottom: 1.25rem;
      }
    }
  </style>
</head>

<body>

  <?php include '../components/PatientComp/navbar.php'; ?>

  <div class="container my-5">
    <h2 class="contact-heading">Get in Touch</h2>
    <p class="contact-info">We're here to help and answer any questions you might have. We look forward to hearing from you and will respond as soon as possible.</p>
    
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-12"> 
        <form action="contact_submit.php" method="POST" class="contact-form">
          <div class="form-group">
            <label for="name" class="form-label">
              <i class="fas fa-user"></i>Your Name
            </label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
          </div>

          <div class="form-group">
            <label for="email" class="form-label">
              <i class="fas fa-envelope"></i>Your Email
            </label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
          </div>

          <div class="form-group">
            <label for="subject" class="form-label">
              <i class="fas fa-tag"></i>Subject
            </label>
            <input type="text" class="form-control" id="subject" name="subject" placeholder="What is this about?" required>
          </div>

          <div class="form-group">
            <label for="message" class="form-label">
              <i class="fas fa-comment-alt"></i>Message
            </label>
            <textarea class="form-control" id="message" name="message" placeholder="Type your message here..." required></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <span><i class="fas fa-paper-plane me-2"></i>Send Message</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <?php include '../components/PatientComp/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
