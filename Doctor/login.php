<?php
session_start();
require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Prepare statement to get doctor by email
        $stmt = $conn->prepare("SELECT id, name, password FROM doctors WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $doctor = $res->fetch_assoc();

            // Verify password hash
            if (password_verify($password, $doctor['password'])) {
                // Login successful
                $_SESSION['doctor_id'] = $doctor['id'];
                $_SESSION['doctor_name'] = $doctor['name'];

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that email.";
        }

        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Login</title>
  <link rel="stylesheet" href="../assets/css/common.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
  <div class="card p-4 shadow-lg border-0 rounded-3" style="width: 400px;">
    <h3 class="text-center fw-bold mb-3">Doctor Login</h3>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
      <button type="submit" class="btn btn-primary w-100">Login</button>
       <div class="text-center mt-3">
            <span>Don’t have an account? </span>
            <a class="text-secondary text-decoration-none" href="register.php">Register here</a>
        </div>
    </form>
  </div>
</body>
</html>
