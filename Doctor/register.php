<?php
require_once '../includes/db.php';

$error = '';
$success = '';

$name = $email = $phone = $password_raw = $city_id = $specialization = $availability = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password_raw = trim($_POST['password'] ?? '');
    $city_id = trim($_POST['city_id'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $availability = trim($_POST['availability'] ?? '');

    if (empty($name) || empty($email) || empty($password_raw) || empty($phone) || empty($city_id) || empty($specialization) || empty($availability)) {
        $error = 'Please fill in all fields.';
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        $error = 'Please upload an image.';
    } else {
   
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'jpg' && $ext !== 'jpeg') {
            $error = 'Only JPG images are allowed.';
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $error = "Image must be less than 2MB.";
        } else {
            $password = password_hash($password_raw, PASSWORD_DEFAULT);

           
            $stmt = $conn->prepare("INSERT INTO doctors (name, email, phone, city_id, specialization, availability, password, image) VALUES (?, ?, ?, ?, ?, ?, ?, '')");
            $stmt->bind_param("sssisss", $name, $email, $phone, $city_id, $specialization, $availability, $password);

            if ($stmt->execute()) {
                $doctor_id = $stmt->insert_id; 

              
                $imageName = "doctor_" . $doctor_id . ".jpg";
                $uploadPath = "../assets/images/" . $imageName;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);

         
                $update = $conn->prepare("UPDATE doctors SET image = ? WHERE id = ?");
                $update->bind_param("si", $imageName, $doctor_id);
                $update->execute();

                $success = "Doctor registered successfully!";
                $name = $email = $phone = $password_raw = $city_id = $specialization = $availability = '';
                echo "<script> alert('$success'); window.location.href = 'login.php'; </script>";
            } else {
                $error = 'Registration failed: ' . $stmt->error;
            }
        }
    }
}

$cities = $conn->query("SELECT id, name FROM cities ORDER BY id ASC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Doctor Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --primary-color: #2246d2;
            --secondary-color: #6366f1;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-radius: 16px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: var(--light-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1.5rem;
        }

        .form-container {
            background: #fff;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 700px;
            border: 1px solid #e2e8f0;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 2rem;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-size: 1.875rem;
        }

        h2 i {
            color: var(--primary-color);
            font-size: 1.75rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .form-row .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-container input,
        .form-container select {
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            font-size: 0.875rem;
            color: #1e293b;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-container input::placeholder {
            color: #94a3b8;
        }

        .form-container input:focus,
        .form-container select:focus {
            border-color: #93c5fd;
            outline: none;
            box-shadow: 0 0 0 3px rgba(34, 70, 210, 0.1);
        }

        .form-container select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
        }

        .form-container button {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(34, 70, 210, 0.2);
        }

        .form-container button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .form-container button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px -2px rgba(34, 70, 210, 0.25);
        }

        .form-container button:hover::before {
            opacity: 1;
        }

        .form-container button i,
        .form-container button span {
            position: relative;
            z-index: 1;
        }

        .msg {
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 1rem;
            border-radius: 0.75rem;
            background: #fee2e2;
            color: #991b1b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .msg.success {
            background: #dcfce7;
            color: #166534;
        }

        .msg i {
            font-size: 1rem;
        }

        .text-center {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .text-center a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-left: 0.25rem;
        }

        .text-center a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        label i {
            color: var(--primary-color);
            font-size: 1rem;
        }

        input[type="file"] {
            padding: 0.75rem;
            background: #f8fafc;
            font-size: 0.875rem;
        }

        @media(max-width: 768px) {
            .form-row .form-group {
                flex: 1 1 100%;
            }
            .form-container {
                width: 95%;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2><i class="fa-solid fa-user-doctor"></i> Doctor Registration</h2>

        <?php if ($error): ?>
            <div class="msg">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= $error ?></span>
            </div>
        <?php elseif ($success): ?>
            <div class="msg success">
                <i class="fas fa-check-circle"></i>
                <span><?= $success ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i>Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" 
                           value="<?= htmlspecialchars($name) ?>">
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i>Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" 
                           value="<?= htmlspecialchars($email) ?>">
                </div>

                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i>Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="Enter your phone number" 
                           value="<?= htmlspecialchars($phone) ?>">
                </div>
                <div class="form-group">
                    <label for="specialization"><i class="fas fa-stethoscope"></i>Specialization</label>
                    <input type="text" id="specialization" name="specialization" 
                           placeholder="Enter your specialization"
                           value="<?= htmlspecialchars($specialization) ?>">
                </div>

                <div class="form-group">
                    <label for="availability"><i class="fas fa-clock"></i>Availability</label>
                    <select id="availability" name="availability">
                        <option value="">Select your availability</option>
                        <option value="MWF 9AM-5PM">MWF 9AM-5PM</option>
                        <option value="MWF 5PM-2AM">MWF 5PM-2AM</option>
                        <option value="TTS 9AM-5PM">TTS 9AM-5PM</option>
                        <option value="TTS 5PM-2AM">TTS 5PM-2AM</option>
                        <option value="Emergency">Emergency</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="city"><i class="fas fa-map-marker-alt"></i>City</label>
                    <select id="city" name="city_id">
                        <option value="">Select your city</option>
                        <?php while ($row = $cities->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= $city_id == $row['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i>Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                </div>

                <div class="form-group">
                    <label for="image"><i class="fas fa-image"></i>Profile Image (Max 2MB)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            <button type="submit"><i class="fa-solid fa-user-plus"></i> Register</button>

            <div class="text-center mt-3">
                <span>Have an account? </span>
                <a href="login.php">Login here</a>
            </div>
        </form>
    </div>
</body>

</html>
