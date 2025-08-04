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
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #2246d2;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --card-border-radius: 16px;
      --transition-speed: 0.3s;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #f8fafc !important;
    }

    .page-header {
      margin-bottom: 2rem;
    }

    .page-header h2 {
      font-size: 1.875rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.5rem;
    }

    .page-header p {
      color: #64748b;
      font-size: 1.1rem;
      margin-bottom: 0;
    }

    .alert {
      border: none;
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1.5rem;
    }

    .alert-success {
      background: #f0fdf4;
      color: #166534;
    }

    .alert-danger {
      background: #fef2f2;
      color: #991b1b;
    }

    .add-doctor-card {
      background: #ffffff;
      border-radius: var(--card-border-radius);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      border: 1px solid #e2e8f0;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .form-label {
      font-weight: 500;
      color: #475569;
      margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
      transition: all var(--transition-speed) ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(34, 70, 210, 0.1);
    }

    .form-control::placeholder {
      color: #94a3b8;
    }

    .btn {
      padding: 0.75rem 1.5rem;
      font-weight: 500;
      border-radius: 8px;
      transition: all var(--transition-speed) ease;
    }

    .btn-primary {
      background: var(--primary-color);
      border: none;
    }

    .btn-primary:hover {
      background: #1e40af;
      transform: translateY(-1px);
    }

    .table-container {
      background: #ffffff;
      border-radius: var(--card-border-radius);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      border: 1px solid #e2e8f0;
      overflow: hidden;
    }

    .table-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #e2e8f0;
    }

    .table-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 0;
    }

    .table {
      margin-bottom: 0;
    }

    .table thead {
      background: #f8fafc;
    }

    .table th {
      font-weight: 600;
      color: #475569;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.05em;
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #e2e8f0;
    }

    .table td {
      padding: 1rem 1.5rem;
      color: #475569;
      vertical-align: middle;
    }

    .doctor-name {
      font-weight: 600;
      color: #1e293b;
    }

    .doctor-email {
      color: #2563eb;
    }

    .doctor-phone {
      font-family: monospace;
      color: #475569;
    }

    .doctor-spec {
      color: #059669;
      font-weight: 500;
    }

    .city-badge {
      background: #f1f5f9;
      color: #475569;
      padding: 0.25rem 0.75rem;
      border-radius: 6px;
      font-size: 0.875rem;
    }

    .availability-badge {
      background: #eff6ff;
      color: #1e40af;
      padding: 0.25rem 0.75rem;
      border-radius: 6px;
      font-size: 0.875rem;
    }

    .btn-action {
      width: 32px;
      height: 32px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      transition: all var(--transition-speed) ease;
      background: #fee2e2;
      color: #991b1b;
      border: none;
    }

    .btn-action:hover {
      background: #fecaca;
      transform: translateY(-1px);
    }
  </style>
</head>
<body class="bg-light">

<div class="d-flex">
  <?php include '../components/adminComp/sidebar.php'; ?>

  <div id="page-content" class="flex-grow-1 p-4">
    <div class="page-header">
      <h2>Manage Doctors</h2>
      <p>Add and manage healthcare professionals in your network</p>
    </div>

    <?php if ($success): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i><?= $success ?>
      </div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
      </div>
    <?php endif; ?>

    <!-- Add Doctor Form -->
    <div class="add-doctor-card">
      <form method="POST" class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Doctor Name</label>
          <input type="text" name="name" class="form-control" placeholder="Enter doctor's full name" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Specialization</label>
          <input type="text" name="specialization" class="form-control" placeholder="Enter medical specialization" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">City</label>
          <select name="city_id" class="form-select" required>
            <option value="">Select practice location</option>
            <?php while ($row = $cities->fetch_assoc()): ?>
              <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Availability Schedule</label>
          <input type="text" name="availability" class="form-control" placeholder="e.g. Mon, Wed, Fri: 9AM–5PM" required>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-user-md me-2"></i>Add New Doctor
          </button>
        </div>
      </form>
    </div>

    <!-- Doctors Table -->
    <div class="table-container">
      <div class="table-header">
        <h5 class="table-title">Doctor List</h5>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Doctor Details</th>
              <th>Contact Info</th>
              <th>Specialization</th>
              <th>Location</th>
              <th>Schedule</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($doc = $doctors->fetch_assoc()): ?>
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <i class="fas fa-user-md me-2 text-primary"></i>
                  <span class="doctor-name"><?= htmlspecialchars($doc['name']) ?></span>
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <span class="doctor-email"><?= htmlspecialchars($doc['email']) ?></span>
                  <span class="doctor-phone"><?= htmlspecialchars($doc['phone']) ?></span>
                </div>
              </td>
              <td>
                <span class="doctor-spec"><?= htmlspecialchars($doc['specialization']) ?></span>
              </td>
              <td>
                <span class="city-badge">
                  <i class="fas fa-map-marker-alt me-1"></i>
                  <?= htmlspecialchars($doc['city_name']) ?>
                </span>
              </td>
              <td>
                <span class="availability-badge">
                  <i class="fas fa-clock me-1"></i>
                  <?= htmlspecialchars($doc['availability']) ?>
                </span>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a href="?delete=<?= $doc['id'] ?>" 
                     class="btn-action" 
                     onclick="return confirm('Are you sure you want to remove this doctor from the system? This action cannot be undone.')"
                     title="Delete Doctor">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($doctors->num_rows == 0): ?>
            <tr>
              <td colspan="6">
                <div class="text-center py-4">
                  <i class="fas fa-user-md text-muted mb-2" style="font-size: 2rem;"></i>
                  <p class="text-muted mb-0">No doctors have been added yet</p>
                </div>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
