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
        // ✅ Validate JPG file
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'jpg' && $ext !== 'jpeg') {
            $error = 'Only JPG images are allowed.';
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $error = "Image must be less than 2MB.";
        } else {
            $password = password_hash($password_raw, PASSWORD_DEFAULT);

            // ✅ Insert doctor first (image blank for now)
            $stmt = $conn->prepare("INSERT INTO doctors (name, email, phone, city_id, specialization, availability, password, image) VALUES (?, ?, ?, ?, ?, ?, ?, '')");
            $stmt->bind_param("sssisss", $name, $email, $phone, $city_id, $specialization, $availability, $password);

            if ($stmt->execute()) {
                $doctor_id = $stmt->insert_id; // ✅ Get auto ID

                // ✅ Save image with doctor ID
                $imageName = "doctor_" . $doctor_id . ".jpg";
                $uploadPath = "../assets/images/" . $imageName;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);

                // ✅ Update DB with image name
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/common.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Intel+One+Mono:ital,wght@0,300..700;1,300..700&display=swap');

        body {
                font-family: 'intel one mono', sans-serif !important;
           background: linear-gradient(to right, #c5e3f9ff, #3897e6ff);
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
            color: #1565c0;
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
            border-color: #1565c0;
            outline: none;
        }

        .form-container button {
            width: 100%;
            background: #1565c0;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .form-container button:hover {
            background: #0d47a1;
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

    <div class="form-container ">
        <h2>Doctor Registration</h2>

        <?php if ($error): ?>
            <div class="msg"><?= $error ?></div>
        <?php elseif ($success): ?>
            <div class="msg success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name) ?>">
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
            <input type="text" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($phone) ?>">

            <input type="text" name="specialization" placeholder="Specialization"
                value="<?= htmlspecialchars($specialization) ?>">

            <select name="availability">
                <option value="">Select Availability</option>
                <option value="MWF 9AM-5PM">MWF 9AM-5PM</option>
                <option value="MWF 5PM-2AM">MWF 5PM-2AM</option>
                <option value="TTS 9AM-5PM">TTS 9AM-5PM</option>
                <option value="TTS 5PM-2AM">TTS 5PM-2AM</option>
                <option value="Emergency">Emergency</option>
            </select>

            <!-- ✅ Cities Dropdown -->
            <select name="city_id">
                <option value="">Select City</option>
                <?php while ($row = $cities->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= $city_id == $row['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="password" name="password" placeholder="Password">

            <label>Upload Profile Image (Max 2MB)</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">Register</button>
        </form>
    </div>
</body>

</html>