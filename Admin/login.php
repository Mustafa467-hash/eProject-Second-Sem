<?php
session_start();
require_once "../includes/db.php";

// Redirect to dashboard if already logged in
if (isset($_SESSION['isadmin']) && $_SESSION['isadmin'] === true) {
  header("Location: dashboard.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $loginInput = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $query = "SELECT * FROM admins WHERE username='$loginInput' OR email='$loginInput' LIMIT 1";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);

    if ($password === $admin['password']) {
      $_SESSION['username'] = $admin['username'];
      $_SESSION['isadmin'] = true;
      header("Location: dashboard.php");  // This is where you go
      exit();
    } else {
      $error = "Invalid password";
    }
  } else {
    $error = "Admin not found";
  }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>


  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />


  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #8e0e00, #e52e71);

      margin: 0;
      padding: 0;
    }

    .login-wrapper {
      background-size: cover;
      background-repeat: no-repeat;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      background-color: #ffffffd9;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      border-radius: 20px;
    }

    .form-control {
      border-radius: 10px;
      padding: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .login-btn {
      background: linear-gradient(135deg, #8e0e00, #e52e71);
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 500;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .login-btn:hover {
      background: linear-gradient(135deg #e52e71, #8e0e00);
    }

    .login-btn:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
  </style>
</head>

<body>
  <div class="login-wrapper d-flex justify-content-center align-items-center vh-100">
    <div class="login-card shadow p-4 rounded-4 bg-white">
      <h2 class="text-center mb-4 fw-bold">Admin Login</h2>

      <form method="POST" action="">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter password"
            required />
        </div>
        <button type="submit" name="submit" class="btn btn-danger w-100 login-btn">Login</button>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>