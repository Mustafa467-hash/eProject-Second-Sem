<?php
require_once '../includes/db.php';

$sql = "SELECT d.*, c.name AS city_name 
        FROM doctors d 
        LEFT JOIN cities c ON d.city_id = c.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Our Doctors</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(to right, #2196F3, #64B5F6);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #fff;
    }

    h1 {
      font-weight: bold;
      margin-top: 30px;
      color: #ffffff;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }

    .doctor-card {
      border: none;
      border-radius: 20px;
      background-color: #ffffff;
      color: #333;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .doctor-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
    }

    .card-title {
      font-weight: 600;
      color: #1565C0;
    }

    .badge {
      background-color: #1976D2;
    }

    .card-body p {
      margin-bottom: 5px;
    }
  </style>
</head>

<body>

  <?php include '../components/PatientComp/navbar.php'; ?>

  <div class="container py-5">
    <h1 class="text-center mb-5">Meet Our Doctors</h1>
    <div class="row g-4">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4">
          <a href="doctor-profile.php?id=<?= $row['id'] ?>" style="text-decoration: none;">
            <div class="card doctor-card h-100">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                <p><span class="badge text-white"><?= htmlspecialchars($row['specialization']) ?></span></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                <p><strong>City:</strong> <?= htmlspecialchars($row['city_name']) ?></p>
                <p><strong>Availability:</strong><br> <?= nl2br(htmlspecialchars($row['availability'])) ?></p>
              </div>
            </div>
          </a>

        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <?php include '../components/PatientComp/footer.php'; ?>

</body>

</html>