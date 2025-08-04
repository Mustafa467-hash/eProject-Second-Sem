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
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
            line-height: 1.6;
            padding: 0;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        

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
        

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px;
            width: calc(100% - var(--sidebar-width));
            transition: var(--transition);
        }
        
        .availability-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid #e2e8f0;
        }
        
        h2 {
            color: var(--dark-color);
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            font-size: 1.875rem;
            font-weight: 700;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        
        h2 i {
            color: var(--primary-color);
            font-size: 1.75rem;
        }
        
        .slot-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
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
            padding: 1rem 1.25rem;
            background: #fff;
            border-radius: var(--border-radius);
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.875rem;
            color: #64748b;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .slot-label:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateY(-1px);
            box-shadow: var(--box-shadow);
        }
        
        .slot-checkbox:checked + .slot-label {
            background-color: #f1f5f9;
            border-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .slot-checkbox:checked + .slot-label::before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            margin-right: 0.75rem;
            color: var(--primary-color);
        }
        
        .slot-label i {
            margin-right: 0.75rem;
            color: #94a3b8;
            transition: var(--transition);
        }
        
        .slot-checkbox:checked + .slot-label i {
            color: var(--primary-color);
        }
        
        .submit-btn {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(34, 70, 210, 0.2);
        }
        
        .submit-btn::before {
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
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px -2px rgba(34, 70, 210, 0.25);
        }
        
        .submit-btn:hover::before {
            opacity: 1;
        }
        
        .submit-btn i,
        .submit-btn span {
            position: relative;
            z-index: 1;
        }
        

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
            <h2>
                <i class="fas fa-calendar-check"></i>
                <span>Update Availability</span>
            </h2>
            
            <form method="post">
                <div class="slot-list">
                    <?php 
                    $slotIcons = [
                        'MWF 9–5' => 'fas fa-sun',
                        'MWF 5–2am' => 'fas fa-moon',
                        'TTS 9–5' => 'fas fa-sun',
                        'TTS 5–2am' => 'fas fa-moon',
                        'Emergency' => 'fas fa-ambulance'
                    ];
                    foreach ($slots as $slot): ?>
                        <div class="slot-item">
                            <input type="checkbox" 
                                   id="slot-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $slot))) ?>" 
                                   name="availability[]" 
                                   value="<?= htmlspecialchars($slot) ?>" 
                                   class="slot-checkbox"
                                   <?= in_array($slot, $current_availability) ? 'checked' : '' ?>>
                            <label for="slot-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $slot))) ?>" 
                                   class="slot-label">
                                <i class="<?= $slotIcons[$slot] ?>"></i>
                                <?= htmlspecialchars($slot) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-clock"></i>
                    <span>Update Schedule</span>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
            

            const icon = this.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        

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