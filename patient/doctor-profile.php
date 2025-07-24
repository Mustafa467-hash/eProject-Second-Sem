<?php
require_once '../includes/db.php';

// Get doctor ID from query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch doctor data
$stmt = $conn->prepare("SELECT d.*, c.name AS city_name FROM doctors d LEFT JOIN cities c ON d.city_id = c.id WHERE d.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
    echo "Doctor not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #E3F2FD, #BBDEFB);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      animation: fadeIn 0.6s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .profile-card {
      background: white;
      border-radius: 25px;
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
      overflow: hidden;
      padding: 30px;
      margin-top: 50px;
    }

    .doctor-img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      margin-bottom: 20px;
    }

    .btn-book {
      background: linear-gradient(to right, #1976D2, #2196F3);
      color: white;
      border: none;
      padding: 12px 30px;
      font-size: 16px;
      border-radius: 50px;
      transition: 0.3s ease;
    }

    .btn-book:hover {
      background: linear-gradient(to right, #1565C0, #1976D2);
      transform: scale(1.05);
    }

    .badge-spec {
      background-color: #2196F3;
      font-size: 14px;
    }
  </style>
</head>
<body>

<?php include '../components/PatientComp/navbar.php'; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="profile-card text-center">
        <img src="<?= $doctor['image'] ? '../assets/images/' . htmlspecialchars($doctor['image']) : '../assets/images/placeholder.png' ?>" alt="Doctor Image" class="doctor-img">
        <h3 class="mb-2"><?= htmlspecialchars($doctor['name']) ?></h3>
        <p><span class="badge badge-spec text-white"><?= htmlspecialchars($doctor['specialization']) ?></span></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($doctor['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($doctor['phone']) ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($doctor['city_name']) ?></p>
        <p><strong>Availability:</strong><br><?= nl2br(htmlspecialchars($doctor['availability'])) ?></p>

        <a href="book-appointment.php?doctor_id=<?= $doctor['id'] ?>" class="btn btn-book mt-4">
          <i class="fas fa-calendar-plus me-2"></i>Book Appointment
        </a>
      </div>
    </div>
  </div>
</div>

<?php include '../components/PatientComp/footer.php'; ?>

       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
