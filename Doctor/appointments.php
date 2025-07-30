<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}
$doctor_id = $_SESSION['doctor_id'];
$conn = new mysqli("localhost", "root", "", "care_group");

$status_filter = $_GET['status'] ?? 'all';
$where = $status_filter !== 'all' ? "AND status='$status_filter'" : "";

$sql = "SELECT a.id, p.name AS patient_name, a.appointment_date, a.appointment_time, a.status
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        WHERE a.doctor_id=$doctor_id $where
        ORDER BY a.appointment_date DESC";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['status'];
    $conn->query("UPDATE appointments SET status='$new_status' WHERE id=$appointment_id AND doctor_id=$doctor_id");
    header("Location: appointments.php?status=$status_filter");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --text-color: #333;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
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
        
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: var(--transition);
            width: calc(100% - var(--sidebar-width));
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        h2 {
            color: var(--dark-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-color);
        }
        
        .filter-btns {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }
        
        .filter-btns a {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            border: 2px solid transparent;
            display: inline-block;
            text-align: center;
        }
        
        .filter-btns a[href*="status=all"] {
            background-color: var(--dark-color);
            color: white;
        }
        
        .filter-btns a[href*="status=pending"] {
            background-color: var(--warning-color);
            color: white;
        }
        
        .filter-btns a[href*="status=confirmed"] {
            background-color: var(--primary-color);
            color: white;
        }
        
        .filter-btns a[href*="status=completed"] {
            background-color: var(--success-color);
            color: white;
        }
        
        .filter-btns a[href*="status=cancelled"] {
            background-color: var(--danger-color);
            color: white;
        }
        
        .filter-btns a:hover {
            transform: translateY(-2px);
            box-shadow: var(--box-shadow);
            opacity: 0.9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border-radius: var(--border-radius);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9f7fe;
        }
        
        select {
            padding: 8px 12px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            max-width: 150px;
        }
        
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        button {
            padding: 8px 16px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            white-space: nowrap;
        }
        
        button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: var(--box-shadow);
        }
        
        .status-pending {
            color: var(--warning-color);
            font-weight: 500;
        }
        
        .status-confirmed {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .status-completed {
            color: var(--success-color);
            font-weight: 500;
        }
        
        .status-cancelled {
            color: var(--danger-color);
            font-weight: 500;
        }
        
        .action-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
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
        
        /* Responsive adjustments */
        @media (max-width: 1199px) {
            .container {
                padding: 15px;
            }
            
            th, td {
                padding: 10px 12px;
            }
        }
        
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
        }
        
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .filter-btns {
                justify-content: center;
            }
            
            .filter-btns a {
                flex: 1 1 120px;
                padding: 8px 12px;
                font-size: 14px;
            }
            
            .action-form {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            select {
                max-width: 100%;
            }
            
            button {
                width: 100%;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding-top: 0;
            }
            
            .main-content {
                padding: 15px 10px;
            }
            
            .container {
                padding: 15px 10px;
            }
            
            h2 {
                font-size: 1.5rem;
                margin-top: 10px;
            }
            
            th, td {
                padding: 8px 10px;
                font-size: 14px;
            }
            
            .filter-btns a {
                flex: 1 1 100px;
                padding: 6px 10px;
                font-size: 13px;
            }
            
            select, button {
                padding: 6px 10px;
                font-size: 14px;
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
        <div class="container">
            <h2><i class="fas fa-calendar-alt me-2"></i>Appointments</h2>
            <div class="filter-btns">
                <a href="?status=all"><i class="fas fa-list me-1"></i> All</a>
                <a href="?status=pending"><i class="fas fa-clock me-1"></i> Pending</a>
                <a href="?status=confirmed"><i class="fas fa-check-circle me-1"></i> Confirmed</a>
                <a href="?status=completed"><i class="fas fa-check-double me-1"></i> Completed</a>
                <a href="?status=cancelled"><i class="fas fa-times-circle me-1"></i> Cancelled</a>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user me-1"></i> Patient</th>
                            <th><i class="fas fa-calendar-day me-1"></i> Date</th>
                            <th><i class="fas fa-clock me-1"></i> Time</th>
                            <th><i class="fas fa-info-circle me-1"></i> Status</th>
                            <th><i class="fas fa-edit me-1"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td><?= date('M j, Y', strtotime($row['appointment_date'])) ?></td>
                            <td><?= date('h:i A', strtotime($row['appointment_time'])) ?></td>
                            <td class="status-<?= $row['status'] ?>">
                                <i class="fas fa-circle me-1"></i> <?= ucfirst($row['status']) ?>
                            </td>
                            <td>
                                <form method="post" class="action-form">
                                    <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                                    <select name="status" class="form-select">
                                        <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>Pending</option>
                                        <option value="confirmed" <?= $row['status']=='confirmed'?'selected':'' ?>>Confirmed</option>
                                        <option value="completed" <?= $row['status']=='completed'?'selected':'' ?>>Completed</option>
                                        <option value="cancelled" <?= $row['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save me-1"></i> Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
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
    </script>
</body>
</html>