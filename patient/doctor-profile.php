<?php
require_once '../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      padding: 2rem;
      margin-top: 2rem;
      text-align: center;
    }

    .doctor-img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      margin-bottom: 1rem;
    }

    h3 {
      font-weight: 700;
      color: #1e293b;
    }

    .btn-book {
      background: linear-gradient(to right, #1976D2, #2196F3);
      color: white;
      border: none;
      padding: 0.75rem 2rem;
      font-size: 1rem;
      border-radius: 50px;
      transition: 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-book:hover {
      background: linear-gradient(to right, #1565C0, #1976D2);
      transform: translateY(-2px);
    }

    .badge-spec {
      background-color: #2196F3;
      font-size: 0.85rem;
      border-radius: 8px;
      padding: 0.3rem 0.6rem;
    }

    @media (max-width: 576px) {
      .profile-card {
        padding: 1.5rem;
        margin-top: 1rem;
      }
      .doctor-img {
        width: 120px;
        height: 120px;
      }
    }
  </style>
</head>
<body>

<?php include '../components/PatientComp/navbar.php'; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">
      <div class="profile-card">
        <img src="<?= $doctor['image'] ? '../assets/images/' . htmlspecialchars($doctor['image']) : '../assets/images/placeholder.png' ?>" alt="Doctor Image" class="doctor-img">
        <h3 class="mb-2"><?= htmlspecialchars($doctor['name']) ?></h3>
        <p><span class="badge badge-spec text-white"><?= htmlspecialchars($doctor['specialization']) ?></span></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($doctor['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($doctor['phone']) ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($doctor['city_name']) ?></p>
        <p><strong>Availability:</strong><br><?= nl2br(htmlspecialchars($doctor['availability'])) ?></p>

        <a href="book-appointment.php?doctor_id=<?= $doctor['id'] ?>" class="btn btn-book mt-3">
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
