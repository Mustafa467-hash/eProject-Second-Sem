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
  <title>Admin Portal - Login</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <style>
    :root {
      --primary-color: #2563eb;
      --primary-hover: #1d4ed8;
      --secondary-color: #64748b;
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
      background: var(--background-color);
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
      max-width: 420px;
      padding: 2rem;
      margin: 1rem;
    }

    .login-card {
      background: var(--card-background);
      border-radius: 16px;
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
      background: linear-gradient(90deg, var(--primary-color), #3b82f6);
    }

    .brand-section {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .brand-icon {
      width: 64px;
      height: 64px;
      background: linear-gradient(135deg, var(--primary-color), #3b82f6);
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
      box-shadow: var(--shadow-md);
    }

    .brand-icon i {
      color: white;
      font-size: 28px;
    }

    .brand-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
      letter-spacing: -0.025em;
    }

    .brand-subtitle {
      color: var(--text-secondary);
      font-size: 0.95rem;
      font-weight: 400;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .form-control {
      width: 100%;
      padding: 0.875rem 1rem;
      border: 1.5px solid var(--border-color);
      border-radius: 8px;
      font-size: 0.95rem;
      background-color: var(--card-background);
      transition: all 0.2s ease;
      color: var(--text-primary);
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgb(37 99 235 / 0.1);
    }

    .form-control::placeholder {
      color: var(--text-secondary);
    }

    .login-btn {
      width: 100%;
      padding: 0.875rem 1rem;
      background: var(--primary-color);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
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
      padding: 0.875rem 1rem;
      border-radius: 8px;
      font-size: 0.875rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .footer-text {
      text-align: center;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid var(--border-color);
      color: var(--text-secondary);
      font-size: 0.875rem;
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
    }

    /* Loading state */
    .login-btn.loading {
      pointer-events: none;
      opacity: 0.7;
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
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      <div class="brand-section">
        <div class="brand-icon">
          <i class="fas fa-shield-alt"></i>
        </div>
        <h1 class="brand-title">Admin Portal</h1>
        <p class="brand-subtitle">Sign in to access your dashboard</p>
      </div>

      <?php if (isset($error)): ?>
        <div class="error-message">
          <i class="fas fa-exclamation-triangle"></i>
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="" id="loginForm">
        <div class="form-group">
          <label for="username" class="form-label">Username or Email</label>
          <input 
            type="text" 
            name="username" 
            id="username" 
            class="form-control" 
            placeholder="Enter your username or email"
            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
            required 
          />
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <input 
            type="password" 
            name="password" 
            id="password" 
            class="form-control" 
            placeholder="Enter your password"
            required 
          />
        </div>

        <button type="submit" name="submit" class="login-btn" id="loginButton">
          <span class="btn-text">
            <i class="fas fa-sign-in-alt"></i>
            Sign In
          </span>
          <div class="spinner"></div>
        </button>
      </form>

      <div class="footer-text">
        <i class="fas fa-lock"></i>
        Your session is secured with enterprise-grade encryption
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add loading state to form submission
    document.getElementById('loginForm').addEventListener('submit', function() {
      const button = document.getElementById('loginButton');
      button.classList.add('loading');
    });

    // Focus on first input on page load
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('username').focus();
    });

    // Enter key handling for better UX
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
  </script>
</body>

</html>