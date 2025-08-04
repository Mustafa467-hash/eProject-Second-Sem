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
      --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
      background: var(--light-color);
      font-family: 'Plus Jakarta Sans', sans-serif;
      animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
      color: #334155;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .profile-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: var(--border-radius);
      box-shadow: 0 10px 25px -5px rgba(34, 70, 210, 0.15);
      padding: 3.5rem 2.5rem;
      margin-top: 3rem;
      text-align: center;
      border: 1px solid rgba(226, 232, 240, 0.8);
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(10px);
      transition: var(--transition);
    }

    .profile-card::before,
    .profile-card::after {
      content: '';
      position: absolute;
      inset: 0;
    }

    .profile-card::before {
      background: linear-gradient(135deg, 
        rgba(34, 70, 210, 0.1),
        rgba(99, 102, 241, 0.1)
      );
      opacity: 0;
      transition: var(--transition);
    }

    .profile-card::after {
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(135deg, 
        var(--primary-color),
        var(--secondary-color)
      );
    }

    .profile-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 35px -10px rgba(34, 70, 210, 0.2);
    }

    .profile-card:hover::before {
      opacity: 1;
    }

    .doctor-img {
      width: 180px;
      height: 180px;
      object-fit: cover;
      border-radius: 50%;
      box-shadow: 0 8px 16px -4px rgba(34, 70, 210, 0.2);
      margin-bottom: 2rem;
      border: 4px solid white;
      transition: var(--transition);
    }

    .doctor-img:hover {
      transform: scale(1.05);
    }

    h3 {
      font-weight: 700;
      color: var(--dark-color);
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    .btn-book {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border: none;
      padding: 1rem 2.5rem;
      font-size: 1rem;
      border-radius: 9999px;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(34, 70, 210, 0.2);
    }

    .btn-book::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
      opacity: 0;
      transition: var(--transition);
    }

    .btn-book:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px -4px rgba(34, 70, 210, 0.3);
    }

    .btn-book:hover::before {
      opacity: 1;
    }

    .btn-book i,
    .btn-book span {
      position: relative;
      z-index: 1;
    }

    .badge-spec {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      font-size: 0.875rem;
      font-weight: 600;
      border-radius: 9999px;
      padding: 0.5rem 1rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
    }

    .profile-info {
      position: relative;
      z-index: 1;
    }

    .profile-info p {
      margin-bottom: 0.75rem;
      font-size: 1.05rem;
      color: #475569;
      transition: var(--transition);
    }

    .profile-info p strong {
      color: var(--dark-color);
      font-weight: 600;
      display: inline-block;
      margin-right: 0.5rem;
      position: relative;
    }

    .profile-info p strong::after {
      content: ':';
      color: #94a3b8;
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
      .profile-info p {
        font-size: 1rem;
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
        <div class="profile-info">
          <h3 class="mb-2"><?= htmlspecialchars($doctor['name']) ?></h3>
          <p><span class="badge badge-spec text-white"><i class="fas fa-stethoscope me-1"></i><?= htmlspecialchars($doctor['specialization']) ?></span></p>
          <p><strong>Email</strong><?= htmlspecialchars($doctor['email']) ?></p>
          <p><strong>Phone</strong><?= htmlspecialchars($doctor['phone']) ?></p>
          <p><strong>City</strong><?= htmlspecialchars($doctor['city_name']) ?></p>
          <p><strong>Availability</strong><?= nl2br(htmlspecialchars($doctor['availability'])) ?></p>

          <a href="book-appointment.php?doctor_id=<?= $doctor['id'] ?>" class="btn btn-book mt-4">
            <span><i class="fas fa-calendar-plus me-2"></i>Book Appointment</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../components/PatientComp/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
