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
    body {
      background: #f8fafc;
      color: #334155;
      font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    .contact-form {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-label {
      font-weight: 500;
      color: #1e293b;
    }

    .form-control {
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 0.75rem 1rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #2246d2;
      box-shadow: 0 0 0 3px rgba(34, 70, 210, 0.1);
      outline: none;
    }

    .btn-primary {
      background: #2246d2;
      border: none;
      padding: 0.75rem 2rem;
      font-weight: 500;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: #1a37a7;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(34, 70, 210, 0.15);
    }

    .contact-heading {
      color: #1e293b;
      font-weight: 700;
      margin-bottom: 2rem;
    }

    
    @media (max-width: 768px) {
      .contact-form {
        padding: 1.5rem;
      }
      .contact-heading {
        font-size: 1.5rem;
      }
    }

    @media (max-width: 576px) {
      .contact-form {
        padding: 1.2rem;
      }
      .contact-heading {
        font-size: 1.3rem;
      }
      .form-control {
        font-size: 14px;
        padding: 0.6rem 0.8rem;
      }
      button {
        font-size: 15px;
      }
    }
  </style>
</head>

<body>

  <?php include '../components/PatientComp/navbar.php'; ?>

  <div class="container my-5">
    <h2 class="text-center contact-heading">Get in Touch</h2>
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-12"> 
        <form action="contact_submit.php" method="POST" class="contact-form">
          <div class="mb-3">
            <label for="name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Your Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>

          <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100">Send Message</button>
        </form>
      </div>
    </div>
  </div>

  <?php include '../components/PatientComp/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
