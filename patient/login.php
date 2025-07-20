<?php
session_start();
require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['patient_id'] = $user['id'];
            $_SESSION['patient_name'] = $user['name'];
            header("Location: dashboard.php");
            echo "Login successful. Redirecting...";
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Patient Portal</title>
    <link rel="stylesheet" href="../assets/css/glass.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <form method="POST" class="glass-card">
        <h2>Patient Login</h2>

        <?php if ($error): ?>
            <div class="error-box"><?= $error ?></div>

        <?php endif; ?>

        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
        <div class="footer-link">
            <span>Don’t have an account?</span>
            <a href="register.php">Register here</a>
        </div>

    </form>
</body>

</html>