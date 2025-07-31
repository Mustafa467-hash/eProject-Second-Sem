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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f7f8fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            width: 650px;
        }

        h2 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 25px;
            color: #222;
        }

        .form-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .form-row .form-group {
            flex: 1 1 48%;
            display: flex;
            flex-direction: column;
        }

        .form-container input,
        .form-container select {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: #fdfdfd;
            margin-bottom: 15px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-container input:focus,
        .form-container select:focus {
            border-color: #444;
            outline: none;
            background: #fff;
        }

        .form-container button {
            width: 100%;
            background: #222;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .form-container button:hover {
            background: #000;
            transform: translateY(-1px);
        }

        .msg {
            text-align: center;
            margin-bottom: 12px;
            font-weight: 500;
            color: #d9534f;
        }

        .msg.success {
            color: #28a745;
        }

        .text-center a {
            color: #222;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s;
        }

        .text-center a:hover {
            color: #000;
        }

        label {
            font-size: 13px;
            color: #555;
            margin-bottom: 5px;
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
            <div class="msg"><?= $error ?></div>
        <?php elseif ($success): ?>
            <div class="msg success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name) ?>">
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
                </div>

                <div class="form-group">
                    <input type="text" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($phone) ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="specialization" placeholder="Specialization"
                        value="<?= htmlspecialchars($specialization) ?>">
                </div>

                <div class="form-group">
                    <select name="availability">
                        <option value="">Select Availability</option>
                        <option value="MWF 9AM-5PM">MWF 9AM-5PM</option>
                        <option value="MWF 5PM-2AM">MWF 5PM-2AM</option>
                        <option value="TTS 9AM-5PM">TTS 9AM-5PM</option>
                        <option value="TTS 5PM-2AM">TTS 5PM-2AM</option>
                        <option value="Emergency">Emergency</option>
                    </select>
                </div>

                <div class="form-group">
                    <select name="city_id">
                        <option value="">Select City</option>
                        <?php while ($row = $cities->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= $city_id == $row['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Password">
                </div>

                <div class="form-group">
                    <label>Upload Profile Image (Max 2MB)</label>
                    <input type="file" name="image" accept="image/*">
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
