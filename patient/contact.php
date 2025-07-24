<?php
$title = "Add Appointment";
require_once '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      background: linear-gradient(to right, #f8f9fa, #ffffff);
    }

    .card-custom {
      background: #fff;
      border: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-radius: 1rem;
    }

    .card-custom .card-header {
      background: linear-gradient(135deg, #2246d2, #041a83);
      color: white;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
    }

    .form-control:focus {
      border-color: #2246d2;
      box-shadow: 0 0 0 0.2rem rgba(34, 70, 210, 0.25);
    }

    .btn-primary {
      background: linear-gradient(135deg, #2246d2, #041a83);
      border: none;
      box-shadow: 0 0 10px #041A83;
    }

    .btn-primary:hover {
      transform: scale(1.03);
    }
  </style>
</head>

<body>

  <!-- Your Navbar will be here -->
  <?php include '../components/PatientComp/navbar.php'; ?>
<div class="container my-5 ">
  <h2 class="text-center mb-4">Contact Us</h2>
  
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action="contact_submit.php" method="POST">
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

  <!-- Your Footer will be here -->
  <?php include '../components/PatientComp/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
