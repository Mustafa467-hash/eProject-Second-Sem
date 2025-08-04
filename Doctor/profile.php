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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --primary-color: #2246d2;
            --secondary-color: #6366f1;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --success-color: #10b981;
            --border-radius: 16px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-color);
            color: #334155;
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
            padding: 2.5rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid #e2e8f0;
        }
        
        h2 {
            color: var(--dark-color);
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            font-size: 1.875rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        h2 i {
            color: var(--primary-color);
            font-size: 1.75rem;
        }
        
        .profile-img-container {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .profile-img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            background: #f8fafc;
        }
        
        .profile-img:hover {
            transform: scale(1.05);
        }
        
        .upload-icon {
            position: absolute;
            bottom: 0;
            right: 32%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--box-shadow);
            border: 3px solid white;
            transition: var(--transition);
        }

        .upload-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 16px -4px rgba(34, 70, 210, 0.2);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        label i {
            color: var(--primary-color);
            font-size: 1rem;
        }
        
        input[type="text"],
        input[type="file"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            transition: var(--transition);
            color: #1e293b;
            background-color: #fff;
        }
        
        input:focus,
        textarea:focus {
            outline: none;
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(34, 70, 210, 0.1);
        }
        
        .file-input {
            display: none;
        }
        
        .file-label {
            display: flex;
            padding: 1rem;
            background: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: var(--border-radius);
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }
        
        .file-label:hover {
            background: #f1f5f9;
            border-color: #93c5fd;
            color: var(--primary-color);
        }

        .file-label i {
            font-size: 1.25rem;
            color: var(--primary-color);
        }
        
        button[type="submit"] {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            transition: var(--transition);
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(34, 70, 210, 0.2);
        }
        
        button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            opacity: 0;
            transition: var(--transition);
        }
        
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px -2px rgba(34, 70, 210, 0.25);
        }

        button[type="submit"]:hover::before {
            opacity: 1;
        }

        button[type="submit"] i,
        button[type="submit"] span {
            position: relative;
            z-index: 1;
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
            <h2>
                <i class="fas fa-user-md"></i>
                <span>My Profile</span>
            </h2>
            
            <div class="profile-img-container">
                <img src="assets/images/doctor_<?= $doctor_id ?>.jpg" class="profile-img" onerror="this.src='default.jpg'" alt="Doctor Profile">
                <label for="image-upload" class="upload-icon" title="Change photo">
                    <i class="fas fa-camera"></i>
                </label>
            </div>
            
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i>
                        <span>Full Name</span>
                    </label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($doctor['name']) ?>" 
                           required placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i>
                        <span>Phone Number</span>
                    </label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($doctor['phone']) ?>" 
                           required placeholder="Enter your phone number">
                </div>
                
                <div class="form-group">
                    <label for="specialization">
                        <i class="fas fa-stethoscope"></i>
                        <span>Specialization</span>
                    </label>
                    <input type="text" id="specialization" name="specialization" 
                           value="<?= htmlspecialchars($doctor['specialization']) ?>"
                           placeholder="Enter your specialization">
                </div>
                
                <div class="form-group">
                    <label for="image-upload">
                        <i class="fas fa-image"></i>
                        <span>Profile Image</span>
                    </label>
                    <input type="file" id="image-upload" name="image" accept="image/*" class="file-input">
                    <label for="image-upload" class="file-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Choose a new profile image</span>
                    </label>
                </div>
                
                <button type="submit">
                    <i class="fas fa-save"></i>
                    <span>Save Changes</span>
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