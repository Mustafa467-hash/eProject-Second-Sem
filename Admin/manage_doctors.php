<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

$success = '';
$error = '';

// Add doctor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $specialization = trim($_POST['specialization']);
    $city_id = intval($_POST['city_id']);
    $availability = trim($_POST['availability']);

    if ($name && $email && $phone && $specialization && $city_id && $availability) {
        $stmt = $conn->prepare("INSERT INTO doctors (name, email, phone, specialization, city_id, availability) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis", $name, $email, $phone, $specialization, $city_id, $availability);
        $stmt->execute();
        $stmt->close();
        $success = "Doctor added successfully!";
    } else {
        $error = "All fields are required.";
    }
}

// Delete doctor
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM doctors WHERE id = $id");
    header("Location: manage_doctors.php");
    exit();
}

// Fetch doctors and cities
$doctors = $conn->query("SELECT doctors.*, cities.name AS city_name FROM doctors JOIN cities ON doctors.city_id = cities.id ORDER BY doctors.id DESC");
$cities = $conn->query("SELECT * FROM cities");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Doctors</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Custom -->
  <link rel="stylesheet" href="../assets/css/theme.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../components/adminComp/header.php'; ?>

<div class="container py-5">
  <h2 class="text-primary fw-bold mb-4">Manage Doctors</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <!-- Add Doctor Form -->
  <form method="POST" class="row g-3 mb-5">
    <div class="col-md-4">
      <input type="text" name="name" class="form-control" placeholder="Doctor Name" required>
    </div>
    <div class="col-md-4">
      <input type="email" name="email" class="form-control" placeholder="Doctor Email" required>
    </div>
    <div class="col-md-4">
      <input type="text" name="phone" class="form-control" placeholder="Phone" required>
    </div>
    <div class="col-md-6">
      <input type="text" name="specialization" class="form-control" placeholder="Specialization" required>
    </div>
    <div class="col-md-6">
      <select name="city_id" class="form-select" required>
        <option value="">Select City</option>
        <?php while ($row = $cities->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-12">
      <input type="text" name="availability" class="form-control" placeholder="Availability (e.g. Mon, Wed, Fri: 9AM–5PM)" required>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-success w-100">Add Doctor</button>
    </div>
  </form>

  <!-- Doctors Table -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-primary">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Specialization</th>
          <th>City</th>
          <th>Availability</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; while ($doc = $doctors->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($doc['name']) ?></td>
          <td><?= htmlspecialchars($doc['email']) ?></td>
          <td><?= htmlspecialchars($doc['phone']) ?></td>
          <td><?= htmlspecialchars($doc['specialization']) ?></td>
          <td><?= htmlspecialchars($doc['city_name']) ?></td>
          <td><?= htmlspecialchars($doc['availability']) ?></td>
          <td>
            <a href="?delete=<?= $doc['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete doctor?')">
              <i class="fas fa-trash-alt"></i>
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
