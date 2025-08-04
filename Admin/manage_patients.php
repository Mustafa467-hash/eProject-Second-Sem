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

    .add-patient-card {
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

    .patient-info {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .patient-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #64748b;
      font-size: 1.25rem;
    }

    .patient-name {
      font-weight: 600;
      color: #1e293b;
      display: block;
    }

    .patient-email {
      color: #2563eb;
      font-size: 0.875rem;
    }

    .patient-contact {
      font-family: monospace;
      color: #475569;
    }

    .gender-badge {
      padding: 0.375rem 0.75rem;
      border-radius: 6px;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .gender-male {
      background: #dbeafe;
      color: #1e40af;
    }

    .gender-female {
      background: #fce7f3;
      color: #9d174d;
    }

    .gender-other {
      background: #f1f5f9;
      color: #475569;
    }

    .age-badge {
      background: #f1f5f9;
      color: #475569;
      padding: 0.375rem 0.75rem;
      border-radius: 6px;
      font-size: 0.875rem;
    }

    .city-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: #f8fafc;
      color: #475569;
      padding: 0.375rem 0.75rem;
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
      <h2>Manage Patients</h2>
      <p>Add and manage patient records in your healthcare system</p>
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

    <!-- Add Patient Form -->
    <div class="add-patient-card">
      <form method="POST" class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Patient Name</label>
          <input type="text" name="name" class="form-control" placeholder="Enter patient's full name" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select" required>
            <option value="">Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Age</label>
          <input type="number" name="age" class="form-control" placeholder="Enter age" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">City</label>
          <select name="city_id" class="form-select" required>
            <option value="">Select city</option>
            <?php while ($row = $cities->fetch_assoc()): ?>
              <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-user-plus me-2"></i>Add New Patient
          </button>
        </div>
      </form>
    </div>

    <!-- Patients Table -->
    <div class="table-container">
      <div class="table-header">
        <h5 class="table-title">Patient Records</h5>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Patient Details</th>
              <th>Contact Info</th>
              <th>Demographics</th>
              <th>Location</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($p = $patients->fetch_assoc()): ?>
            <tr>
              <td>
                <div class="patient-info">
                  <div class="patient-avatar">
                    <i class="fas fa-user"></i>
                  </div>
                  <div>
                    <span class="patient-name"><?= htmlspecialchars($p['name']) ?></span>
                    <span class="patient-email"><?= htmlspecialchars($p['email']) ?></span>
                  </div>
                </div>
              </td>
              <td>
                <span class="patient-contact">
                  <i class="fas fa-phone-alt me-2"></i>
                  <?= htmlspecialchars($p['phone']) ?>
                </span>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <span class="gender-badge gender-<?= strtolower($p['gender']) ?>">
                    <i class="fas fa-<?= $p['gender'] === 'Male' ? 'mars' : ($p['gender'] === 'Female' ? 'venus' : 'genderless') ?> me-1"></i>
                    <?= $p['gender'] ?>
                  </span>
                  <span class="age-badge">
                    <i class="fas fa-birthday-cake me-1"></i>
                    <?= $p['age'] ?> years
                  </span>
                </div>
              </td>
              <td>
                <span class="city-badge">
                  <i class="fas fa-map-marker-alt"></i>
                  <?= htmlspecialchars($p['city_name']) ?>
                </span>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a href="?delete=<?= $p['id'] ?>" 
                     class="btn-action" 
                     onclick="return confirm('Are you sure you want to remove this patient from the system? This action cannot be undone.')"
                     title="Delete Patient">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($patients->num_rows == 0): ?>
            <tr>
              <td colspan="5">
                <div class="text-center py-4">
                  <i class="fas fa-user-injured text-muted mb-2" style="font-size: 2rem;"></i>
                  <p class="text-muted mb-0">No patients have been registered yet</p>
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
