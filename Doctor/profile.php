<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "care_group");
$doctor_id = $_SESSION['doctor_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $specialization = $_POST['specialization'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        move_uploaded_file($_FILES['image']['tmp_name'], "assets/images/doctor_{$doctor_id}.jpg");
    }

    $conn->query("UPDATE doctors SET name='$name', phone='$phone', specialization='$specialization' WHERE id=$doctor_id");
    header("Location: profile.php");
    exit;
}

$doctor = $conn->query("SELECT * FROM doctors WHERE id=$doctor_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --success-color: #2ecc71;
            --border-radius: 8px;
            --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 0;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--dark-color);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: var(--transition);
            z-index: 1000;
        }
        
        /* Main content area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: var(--transition);
            width: calc(100% - var(--sidebar-width));
        }
        
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        h2 {
            color: var(--dark-color);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-color);
            text-align: center;
        }
        
        .profile-img-container {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
        }
        
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .profile-img:hover {
            transform: scale(1.05);
        }
        
        .upload-icon {
            position: absolute;
            bottom: 20px;
            right: calc(50% - 85px);
            background: var(--primary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--box-shadow);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        input[type="text"],
        input[type="file"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }
        
        input:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .file-input {
            display: none;
        }
        
        .file-label {
            display: block;
            padding: 12px;
            background: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: var(--border-radius);
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .file-label:hover {
            background: #e9ecef;
            border-color: var(--primary-color);
        }
        
        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: var(--border-radius);
            cursor: pointer;
            width: 100%;
            font-weight: 500;
            transition: var(--transition);
            margin-top: 10px;
        }
        
        button[type="submit"]:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--box-shadow);
        }
        
        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 20px;
            cursor: pointer;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .profile-container {
                padding: 20px;
            }
            
            .profile-img {
                width: 120px;
                height: 120px;
            }
            
            .upload-icon {
                right: calc(50% - 70px);
                width: 35px;
                height: 35px;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px 15px;
            }
        }
        
        @media (max-width: 576px) {
            h2 {
                font-size: 1.5rem;
            }
            
            .profile-img {
                width: 100px;
                height: 100px;
            }
            
            .upload-icon {
                right: calc(50% - 60px);
                width: 30px;
                height: 30px;
                bottom: 15px;
            }
            
            input[type="text"],
            input[type="file"],
            input[type="tel"],
            textarea {
                padding: 10px 12px;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>
    
    <?php include '../components/doctorComp/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="profile-container">
            <h2><i class="fas fa-user-md me-2"></i>My Profile</h2>
            
            <div class="profile-img-container">
                <img src="assets/images/doctor_<?= $doctor_id ?>.jpg" class="profile-img" onerror="this.src='default.jpg'">
                <label for="image-upload" class="upload-icon" title="Change photo">
                    <i class="fas fa-camera"></i>
                </label>
            </div>
            
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name"><i class="fas fa-signature me-2"></i>Full Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($doctor['name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone me-2"></i>Phone Number:</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($doctor['phone']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="specialization"><i class="fas fa-stethoscope me-2"></i>Specialization:</label>
                    <input type="text" id="specialization" name="specialization" value="<?= htmlspecialchars($doctor['specialization']) ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-image me-2"></i>Profile Image:</label>
                    <input type="file" id="image-upload" name="image" accept="image/*" class="file-input">
                    <label for="image-upload" class="file-label">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Choose a new profile image
                    </label>
                </div>
                
                <button type="submit">
                    <i class="fas fa-save me-2"></i>Update Profile
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
            
            // Change icon
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const mobileBtn = document.getElementById('mobileMenuBtn');
            
            if (window.innerWidth <= 992 && 
                !sidebar.contains(event.target) && 
                event.target !== mobileBtn && 
                !mobileBtn.contains(event.target)) {
                sidebar.classList.remove('active');
                const icon = mobileBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Adjust layout on resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.remove('active');
                const icon = document.querySelector('#mobileMenuBtn i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Show selected file name
        document.getElementById('image-upload').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file selected';
            document.querySelector('.file-label').innerHTML = 
                `<i class="fas fa-cloud-upload-alt me-2"></i>${fileName}`;
            
            // Preview image
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('.profile-img').src = event.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>