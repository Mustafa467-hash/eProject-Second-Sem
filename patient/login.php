<?php
session_start();
require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $user = $res->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['patient_id'] = $user['id'];
                $_SESSION['patient_name'] = $user['name'];
                $_SESSION['ispatient'] = true;

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No account found with that email.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/glass.css"> <!-- Optional if you have glass.css -->
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            background: linear-gradient(to right, #ffebee, #ffcdd2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .login-card h2 {
            margin-bottom: 1rem;
            font-weight: bold;
            color: #c62828;
        }

        .login-card input {
            width: 100%;
            padding: 0.75rem;
            margin: 0.5rem 0;
            border: none;
            border-radius: 8px;
            background-color: #f5f5f5;
        }

        .login-card button {
            width: 100%;
            padding: 0.75rem;
            background-color: #c62828;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-card button:hover {
            background-color: #b71c1c;
        }

        .footer-link {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .footer-link a {
            color: #c62828;
            text-decoration: none;
        }

        .error-box {
            background-color: #ffcdd2;
            color: #b71c1c;
            padding: 0.5rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <form method="POST" class="login-card">
        <h2>Patient Login</h2>

        <?php if ($error): ?>
            <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

        <div class="text-center mt-3">
            <span>Don’t have an account or new patient?</span>
            <a class="text-secondary text-decoration-none" href="register.php">Register here</a>
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>