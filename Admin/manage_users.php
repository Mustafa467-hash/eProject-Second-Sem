<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

$success = '';
$error = '';

// Add Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($username && $email && $password) {
        $hashed = $password; // Plaintext for now (your call bro, but you should hash it in production)
        $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed);
        $stmt->execute();
        $stmt->close();
        $success = "Admin added successfully!";
    } else {
        $error = "All fields are required.";
    }
}

// Delete Admin
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM admins WHERE id = $id");
    header("Location: manage_users.php");
    exit();
}

// Fetch Admins
$admins = $conn->query("SELECT * FROM admins ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Admin Users</title>
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

    .add-admin-card {
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

    .form-control {
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
      transition: all var(--transition-speed) ease;
    }

    .form-control:focus {
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
      display: flex;
      justify-content: space-between;
      align-items: center;
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

    .admin-info {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .admin-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #64748b;
    }

    .admin-name {
      font-weight: 600;
      color: #1e293b;
    }

    .admin-email {
      color: #2563eb;
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

    .password-field {
      font-family: monospace;
      color: #64748b;
      background: #f1f5f9;
      padding: 0.25rem 0.75rem;
      border-radius: 6px;
      font-size: 0.875rem;
    }
  </style>
</head>
<body class="bg-light">

<div class="d-flex">
  <?php include '../components/adminComp/sidebar.php'; ?>

  <div id="page-content" class="flex-grow-1 p-4">
    <div class="page-header">
      <h2>Manage Admin Users</h2>
      <p>Add and manage administrator accounts for the system</p>
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

    <!-- Add Admin Form -->
    <div class="add-admin-card">
      <form method="POST" class="row g-3">
        <input type="hidden" name="add_admin" value="1" />
        <div class="col-md-4">
          <label class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-user"></i>
            </span>
            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
          </div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Email Address</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-envelope"></i>
            </span>
            <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
          </div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
          </div>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-user-plus me-2"></i>Add New Administrator
          </button>
        </div>
      </form>
    </div>

    <!-- Admins Table -->
    <div class="table-container">
      <div class="table-header">
        <h5 class="table-title">Administrator List</h5>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Admin Details</th>
              <th>Contact Info</th>
              <th>Password</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($admin = $admins->fetch_assoc()): ?>
            <tr>
              <td>
                <div class="admin-info">
                  <div class="admin-avatar">
                    <i class="fas fa-user-shield"></i>
                  </div>
                  <span class="admin-name"><?= htmlspecialchars($admin['username']) ?></span>
                </div>
              </td>
              <td>
                <span class="admin-email">
                  <i class="fas fa-envelope me-2"></i>
                  <?= htmlspecialchars($admin['email']) ?>
                </span>
              </td>
              <td>
                <span class="password-field">
                  <i class="fas fa-key me-2"></i>
                  <?= str_repeat('•', strlen($admin['password'])) ?>
                </span>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a href="?delete=<?= $admin['id'] ?>" 
                     class="btn-action" 
                     onclick="return confirm('Are you sure you want to remove this administrator? This action cannot be undone.')"
                     title="Delete Administrator">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($admins->num_rows == 0): ?>
            <tr>
              <td colspan="4">
                <div class="text-center py-4">
                  <i class="fas fa-user-shield text-muted mb-2" style="font-size: 2rem;"></i>
                  <p class="text-muted mb-0">No administrators have been added yet</p>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
