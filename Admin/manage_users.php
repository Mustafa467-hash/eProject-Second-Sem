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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<div class="d-flex">
  <?php include '../components/adminComp/sidebar.php'; ?>

  <div id="page-content" class="flex-grow-1 p-4">
    <h2 class="text-danger fw-bold mb-4">Manage Admin Users</h2>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add Admin Form -->
    <form method="POST" class="row g-3 mb-5">
      <input type="hidden" name="add_admin" value="1" />
      <div class="col-md-4">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>
      <div class="col-md-4">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="col-md-4">
        <input type="text" name="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-danger w-100">Add Admin</button>
      </div>
    </form>

    <!-- Admins Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-danger">
          <tr>
            <th>#</th>
            <th>Username</th>
            <th>Email</th>
            <th>Password</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; while ($admin = $admins->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($admin['username']) ?></td>
            <td><?= htmlspecialchars($admin['email']) ?></td>
            <td><?= htmlspecialchars($admin['password']) ?></td>
            <td>
              <a href="?delete=<?= $admin['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this admin?')">
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
