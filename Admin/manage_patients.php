<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

$success = '';
$error = '';

// Add patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $age = intval($_POST['age']);
    $city_id = intval($_POST['city_id']);

    if ($name && $email && $phone && $gender && $age && $city_id) {
        $stmt = $conn->prepare("INSERT INTO patients (name, email, phone, gender, age, city_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $name, $email, $phone, $gender, $age, $city_id);
        $stmt->execute();
        $stmt->close();
        $success = "Patient added successfully!";
    } else {
        $error = "All fields are required.";
    }
}

// Delete patient
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM patients WHERE id = $id");
    header("Location: manage_patients.php");
    exit();
}

// Fetch patients and cities
$patients = $conn->query("SELECT patients.*, cities.name AS city_name FROM patients JOIN cities ON patients.city_id = cities.id ORDER BY patients.id DESC");
$cities = $conn->query("SELECT * FROM cities");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Patients</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
     <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="d-flex">
  <?php include '../components/adminComp/sidebar.php'; ?>

  <div id="page-content" class="flex-grow-1 p-4">
    <h2 class="text-danger fw-bold mb-4">Manage Patients</h2>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add Patient Form -->
    <form method="POST" class="row g-3 mb-5">
      <div class="col-md-4">
        <input type="text" name="name" class="form-control" placeholder="Patient Name" required>
      </div>
      <div class="col-md-4">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="col-md-4">
        <input type="text" name="phone" class="form-control" placeholder="Phone" required>
      </div>
      <div class="col-md-4">
        <select name="gender" class="form-select" required>
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
          <option>Other</option>
        </select>
      </div>
      <div class="col-md-4">
        <input type="number" name="age" class="form-control" placeholder="Age" required>
      </div>
      <div class="col-md-4">
        <select name="city_id" class="form-select" required>
          <option value="">Select City</option>
          <?php while ($row = $cities->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-danger w-100">Add Patient</button>
      </div>
    </form>

    <!-- Patients Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-danger">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Age</th>
            <th>City</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; while ($p = $patients->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= htmlspecialchars($p['email']) ?></td>
            <td><?= htmlspecialchars($p['phone']) ?></td>
            <td><?= htmlspecialchars($p['gender']) ?></td>
            <td><?= htmlspecialchars($p['age']) ?></td>
            <td><?= htmlspecialchars($p['city_name']) ?></td>
            <td>
              <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete patient?')">
                <i class="fas fa-trash-alt"></i>
              </a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
