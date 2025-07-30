<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "care_group");
$doctor_id = $_SESSION['doctor_id'];

$slots = ["MWF 9–5", "MWF 5–2am", "TTS 9–5", "TTS 5–2am", "Emergency"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $availability = implode(',', $_POST['availability'] ?? []);
    $conn->query("UPDATE doctors SET availability='$availability' WHERE id=$doctor_id");
    header("Location: availability.php");
    exit;
}

$current = $conn->query("SELECT availability FROM doctors WHERE id=$doctor_id")->fetch_assoc()['availability'];
$current_availability = explode(',', $current);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Availability</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --dark-color: #34495e;
            --light-color: #ecf0f1;
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
            z-index: 1000;
            transition: var(--transition);
        }
        
        /* Main content area with sidebar offset */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px;
            width: calc(100% - var(--sidebar-width));
            transition: var(--transition);
        }
        
        .availability-container {
            max-width: 600px;
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
        
        .slot-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .slot-item {
            position: relative;
        }
        
        .slot-checkbox {
            position: absolute;
            opacity: 0;
            height: 0;
            width: 0;
        }
        
        .slot-label {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: var(--border-radius);
            border: 2px solid #ddd;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }
        
        .slot-label:hover {
            background: #e9ecef;
            border-color: var(--primary-color);
        }
        
        .slot-checkbox:checked + .slot-label {
            background-color: rgba(52, 152, 219, 0.1);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .slot-checkbox:checked + .slot-label::before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .submit-btn {
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
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .submit-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--box-shadow);
        }
        
        /* Mobile menu toggle button */
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
            
            .availability-container {
                padding: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px 15px;
            }
            
            .slot-list {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            h2 {
                font-size: 1.5rem;
            }
            
            .slot-label {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile menu toggle button (shown only on small screens) -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Your existing sidebar -->
    <?php include '../components/doctorComp/sidebar.php'; ?>
    
    <!-- Main content area -->
    <div class="main-content">
        <div class="availability-container">
            <h2><i class="fas fa-calendar-check me-2"></i>Update Availability</h2>
            
            <form method="post">
                <div class="slot-list">
                    <?php foreach ($slots as $slot): ?>
                        <div class="slot-item">
                            <input type="checkbox" 
                                   id="slot-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $slot))) ?>" 
                                   name="availability[]" 
                                   value="<?= htmlspecialchars($slot) ?>" 
                                   class="slot-checkbox"
                                   <?= in_array($slot, $current_availability) ? 'checked' : '' ?>>
                            <label for="slot-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $slot))) ?>" 
                                   class="slot-label">
                                <?= htmlspecialchars($slot) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i> Save Availability
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle functionality
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
            
            // Change icon between bars and times
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
        
        // Reset layout when window is resized
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.remove('active');
                const icon = document.querySelector('#mobileMenuBtn i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    </script>
</body>
</html>