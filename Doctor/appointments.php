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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --primary-color: #2246d2;
            --secondary-color: #6366f1;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --text-color: #334155;
            --border-radius: 16px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-color);
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
            padding: 2rem;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid #e2e8f0;
        }
        
        h2 {
            color: var(--dark-color);
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
            font-size: 1.875rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        h2 i {
            color: var(--primary-color);
            font-size: 1.75rem;
        }
        
        .filter-btns {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 2rem;
            background: #f8fafc;
            padding: 1rem;
            border-radius: var(--border-radius);
            border: 1px solid #e2e8f0;
        }
        
        .filter-btns a {
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--transition);
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-align: center;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .filter-btns a[href*="status=all"] {
            background-color: #1e293b;
            color: white;
        }
        
        .filter-btns a[href*="status=pending"] {
            background-color: #fff;
            color: #92400e;
            border-color: #fef3c7;
        }
        
        .filter-btns a[href*="status=confirmed"] {
            background-color: #fff;
            color: var(--primary-color);
            border-color: #e0e7ff;
        }
        
        .filter-btns a[href*="status=completed"] {
            background-color: #fff;
            color: #166534;
            border-color: #dcfce7;
        }
        
        .filter-btns a[href*="status=cancelled"] {
            background-color: #fff;
            color: #991b1b;
            border-color: #fee2e2;
        }
        
        .filter-btns a:hover {
            transform: translateY(-1px);
            box-shadow: var(--box-shadow);
            background-color: #fff;
        }

        .filter-btns a[href*="status=all"]:hover {
            background-color: #334155;
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1.5rem;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
            background: white;
            border: 1px solid #e2e8f0;
        }
        
        th, td {
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.875rem;
        }
        
        th {
            background: #f8fafc;
            color: #1e293b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        tr:hover {
            background-color: #f1f5f9;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }
        
        select {
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background-color: white;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            max-width: 150px;
            font-size: 0.875rem;
            color: #475569;
            font-weight: 500;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
        }
        
        select:focus {
            outline: none;
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(34, 70, 210, 0.1);
        }
        
        button {
            padding: 0.625rem 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            font-size: 0.875rem;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 4px rgba(34, 70, 210, 0.1);
        }
        
        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(34, 70, 210, 0.15);
        }

        button:active {
            transform: translateY(0);
        }
        
        .status-pending {
            color: #92400e;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #fef3c7;
            border-radius: 9999px;
            font-size: 0.75rem;
        }
        
        .status-confirmed {
            color: var(--primary-color);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #e0e7ff;
            border-radius: 9999px;
            font-size: 0.75rem;
        }
        
        .status-completed {
            color: #166534;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #dcfce7;
            border-radius: 9999px;
            font-size: 0.75rem;
        }
        
        .status-cancelled {
            color: #991b1b;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #fee2e2;
            border-radius: 9999px;
            font-size: 0.75rem;
        }

        .status-pending i,
        .status-confirmed i,
        .status-completed i,
        .status-cancelled i {
            font-size: 0.625rem;
        }
        
        .action-form {
            display: flex;
            gap: 0.75rem;
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