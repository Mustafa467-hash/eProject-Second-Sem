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

                header("Location: book-appointment.php");
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Healthcare Portal - Patient Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

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
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 2rem;
            margin: 1rem;
        }

        .login-card {
            background: var(--card-background);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .brand-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-md);
            position: relative;
        }

        .brand-icon::after {
            content: '';
            position: absolute;
            inset: 3px;
            background: var(--card-background);
            border-radius: 15px;
        }

        .brand-icon i {
            color: var(--primary-color);
            font-size: 32px;
            position: relative;
            z-index: 2;
        }

        .brand-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .brand-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 400;
            margin-bottom: 0.5rem;
        }

        .brand-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 300;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1rem;
            z-index: 2;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 1.5px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.95rem;
            background-color: var(--card-background);
            transition: all 0.2s ease;
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgb(22 163 74 / 0.1);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .login-btn {
            width: 100%;
            padding: 1rem 1.5rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
        }

        .login-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: 12px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .error-icon {
            color: #dc2626;
            font-size: 1rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        .register-section {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .register-text {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .register-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .register-link:hover {
            color: var(--primary-hover);
            text-decoration: none;
        }

        .features-section {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
        }

        .features-title {
            color: var(--primary-color);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .features-list li {
            color: var(--text-secondary);
            font-size: 0.8rem;
            line-height: 1.4;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .features-list li:last-child {
            margin-bottom: 0;
        }

        .features-list i {
            color: var(--primary-color);
            font-size: 0.75rem;
            width: 12px;
            flex-shrink: 0;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .brand-title {
                font-size: 1.5rem;
            }

            .brand-icon {
                width: 64px;
                height: 64px;
            }

            .brand-icon i {
                font-size: 28px;
            }
        }

        /* Loading state */
        .login-btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .login-btn.loading .btn-text {
            opacity: 0;
        }

        .login-btn .spinner {
            display: none;
        }

        .login-btn.loading .spinner {
            display: inline-block;
            position: absolute;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Input focus animations */
        .input-wrapper:focus-within .input-icon {
            color: var(--primary-color);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="brand-section">
                <div class="brand-icon">
                    <i class="fas fa-heart-pulse"></i>
                </div>
                <h1 class="brand-title">Patient Portal</h1>
                <p class="brand-subtitle">Your Health, Our Priority</p>
                <p class="brand-description">Access your medical records and book appointments</p>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control" 
                            placeholder="Enter your email address"
                            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                            required 
                        />
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control" 
                            placeholder="Enter your password"
                            required 
                        />
                    </div>
                </div>

                <button type="submit" class="login-btn" id="loginButton">
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In to Portal
                    </span>
                    <div class="spinner"></div>
                </button>
            </form>

            <div class="register-section">
                <p class="register-text">New patient or don't have an account?</p>
                <a href="register.php" class="register-link">
                    <i class="fas fa-user-plus"></i>
                    Register as New Patient
                </a>
            </div>

            <div class="features-section">
                <div class="features-title">
                    <i class="fas fa-check-circle"></i>
                    What you can do:
                </div>
                <ul class="features-list">
                    <li><i class="fas fa-calendar-plus"></i>Book and manage appointments</li>
                    <li><i class="fas fa-file-medical"></i>View your medical history</li>
                    <li><i class="fas fa-prescription-bottle"></i>Access prescriptions</li>
                    <li><i class="fas fa-bell"></i>Receive appointment reminders</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add loading state to form submission
        document.getElementById('loginForm').addEventListener('submit', function() {
            const button = document.getElementById('loginButton');
            button.classList.add('loading');
        });

        // Focus on first input on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                const form = document.getElementById('loginForm');
                const inputs = form.querySelectorAll('input[required]');
                const currentInput = document.activeElement;
                
                if (currentInput.tagName === 'INPUT') {
                    const currentIndex = Array.from(inputs).indexOf(currentInput);
                    if (currentIndex < inputs.length - 1) {
                        event.preventDefault();
                        inputs[currentIndex + 1].focus();
                    }
                }
            }
        });

        // Email validation feedback
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = '#dc2626';
            } else {
                this.style.borderColor = '';
            }
        });

        emailInput.addEventListener('input', function() {
            this.style.borderColor = '';
        });

        // Form enhancement - remember email
        const form = document.getElementById('loginForm');
        const emailField = document.getElementById('email');
        
        // Load saved email on page load
        const savedEmail = localStorage.getItem('patientEmail');
        if (savedEmail && !emailField.value) {
            emailField.value = savedEmail;
        }

        // Save email on input
        emailField.addEventListener('input', function() {
            if (this.value) {
                localStorage.setItem('patientEmail', this.value);
            }
        });
    </script>
</body>

</html>