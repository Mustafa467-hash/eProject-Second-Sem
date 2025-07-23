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

  // Validation (runs before hashing password)
  if (empty($name) || empty($email) || empty($password_raw) || empty($phone) || empty($age) || empty($gender) || empty($city_id)) {
    $error = 'Please fill in all fields.';
  } else {
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO patients (name, email, password, phone, age, gender, city_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisi", $name, $email, $password, $phone, $age, $gender, $city_id);

    if ($stmt->execute()) {
      header("Location: login.php"); // redirect to login page after register
      exit; // stop script after redirect
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
  <title>Register</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #ffebee, #ffcdd2);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .form-container {
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 400px;
    }

    .form-container h2 {
      margin-bottom: 20px;
      color: #e53935;
      text-align: center;
    }

    .form-container input,
    .form-container select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: border-color 0.3s;
    }

    .form-container input:focus,
    .form-container select:focus {
      border-color: #e53935;
      outline: none;
    }

    .form-container button {
      width: 100%;
      background: #e53935;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .form-container button:hover {
      background: #c62828;
    }

    .msg {
      text-align: center;
      margin-bottom: 15px;
      color: red;
    }

    .msg.success {
      color: green;
    }
  </style>
</head>

<body>
  <div class="form-container">
    <h2>Patient Registration</h2>

    <?php if ($error): ?>
      <div class="msg"><?= $error ?></div>
    <?php elseif ($success): ?>
      <div class="msg success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name) ?>">
      <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
      <input type="text" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($phone) ?>">

      <input type="password" name="password" placeholder="Password">

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
    </form>
  </div>
</body>

</html>