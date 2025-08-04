<?php
require_once '../includes/db.php';

$error = '';
$success = '';

$name = $email = $phone = $password_raw = $gender = $age = $city_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $password_raw = trim($_POST['password'] ?? '');
  $gender = trim($_POST['gender'] ?? '');
  $age = trim($_POST['age'] ?? '');
  $city_id = trim($_POST['city_id'] ?? '');

  if (empty($name) || empty($email) || empty($password_raw) || empty($phone) || empty($age) || empty($gender) || empty($city_id)) {
    $error = 'Please fill in all fields.';
  } else {
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO patients (name, email, password, phone, age, gender, city_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisi", $name, $email, $password, $phone, $age, $gender, $city_id);

    if ($stmt->execute()) {
      header("Location: login.php");
      exit; 
    } else {
      $error = 'Registration failed. Try again.';
    }
  }
}

$cities = $conn->query("SELECT id, name FROM cities");
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Patient Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #16a34a;
      --primary-hover: #15803d;
      --secondary-color: #64748b;
      --accent-color: #22c55e;
      --background-color: #f8fafc;
      --card-background: #ffffff;
      --text-primary: #1e293b;
      --text-secondary: #64748b;
      --border-color: #e2e8f0;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
      min-height: 100vh;
      margin: 0;
      padding: 2rem 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-primary);
    }

    .form-container {
      background: rgba(255, 255, 255, 0.95);
      padding: 2.5rem;
      border-radius: 24px;
      box-shadow: 0 10px 25px -5px rgba(34, 70, 210, 0.15);
      width: 100%;
      max-width: 480px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(226, 232, 240, 0.8);
      position: relative;
      overflow: hidden;
      transition: var(--transition);
    }

    .form-container::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, 
        rgba(34, 70, 210, 0.05),
        rgba(99, 102, 241, 0.05)
      );
      opacity: 0;
      transition: var(--transition);
    }

    .form-container:hover::before {
      opacity: 1;
    }

    .form-container h2 {
      margin-bottom: 2rem;
      color: var(--dark-color);
      text-align: center;
      font-weight: 800;
      font-size: 2rem;
      position: relative;
      padding-bottom: 1rem;
    }

    .form-container h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 4px;
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      border-radius: 2px;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .form-group i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      transition: var(--transition);
      pointer-events: none;
    }

    .form-container input,
    .form-container select {
      width: 100%;
      padding: 0.875rem 1rem 0.875rem 2.5rem;
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: 12px;
      transition: var(--transition);
      font-size: 0.95rem;
      font-family: inherit;
      background: rgba(255, 255, 255, 0.9);
      color: var(--dark-color);
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .form-container input:focus,
    .form-container select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.1);
      outline: none;
    }

    .form-container input:focus + i,
    .form-container select:focus + i {
      color: var(--primary-color);
    }

    .form-container input:hover,
    .form-container select:hover {
      border-color: var(--accent-color);
    }

    .form-container button {
      width: 100%;
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      color: white;
      padding: 1rem;
      border: none;
      border-radius: 9999px;
      cursor: pointer;
      font-weight: 600;
      font-size: 1rem;
      font-family: inherit;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.2);
    }

    .form-container button::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
      opacity: 0;
      transition: var(--transition);
    }

    .form-container button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px -4px rgba(22, 163, 74, 0.3);
      background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
    }

    .form-container button:hover::before {
      opacity: 1;
    }

    .form-container button span {
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .msg {
      text-align: center;
      margin-bottom: 1.5rem;
      padding: 1rem;
      border-radius: 12px;
      font-weight: 500;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .msg.error {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger-color);
    }

    .msg.success {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success-color);
    }

    .login-link {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.95rem;
      color: var(--text-secondary);
    }

    .login-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
      margin-left: 0.25rem;
      transition: var(--transition);
    }

    .login-link a:hover {
      color: var(--accent-color);
    }
  </style>
</head>

<body>
  <div class="form-container">
    <h2>Patient Registration</h2>

    <?php if ($error): ?>
      <div class="msg error"><i class="fas fa-exclamation-circle"></i><?= $error ?></div>
    <?php elseif ($success): ?>
      <div class="msg success"><i class="fas fa-check-circle"></i><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <input type="text" name="name" placeholder="Enter your full name" value="<?= htmlspecialchars($name) ?>" required>
        <i class="fas fa-user"></i>
      </div>

      <div class="form-group">
        <input type="email" name="email" placeholder="Enter your email address" value="<?= htmlspecialchars($email) ?>" required>
        <i class="fas fa-envelope"></i>
      </div>

      <div class="form-group">
        <input type="tel" name="phone" placeholder="Enter your phone number" value="<?= htmlspecialchars($phone) ?>" required>
        <i class="fas fa-phone"></i>
      </div>

      <div class="form-group">
        <input type="password" name="password" placeholder="Create a strong password" required>
        <i class="fas fa-lock"></i>
      </div>

      <select name="gender">
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>

      </select>
      <select name="age">
        <option value="">Select Age</option>
        <?php for ($i = 1; $i <= 100; $i++)
          echo "<option value='$i'>$i</option>"; ?>
      </select>
      <select name="city_id">
        <option value="">Select City</option>
        <?php while ($row = $cities->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>
      <button type="submit">Register</button>
      <div class="text-center mt-3">
            <span>Don’t have an account or new patient></span>
            <a class="text-secondary text-decoration-none" href="register.php">Register here</a>
        </div>
    </form>
  </div>
</body>

</html>