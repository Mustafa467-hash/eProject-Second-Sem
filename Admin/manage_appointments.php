<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Handle filters
$filter_doctor = isset($_GET['doctor']) ? $_GET['doctor'] : '';
$filter_patient = isset($_GET['patient']) ? $_GET['patient'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Fetch doctors and patients for filter dropdowns
$doctors = $conn->query("SELECT id, name FROM doctors ORDER BY name ASC");
$patients = $conn->query("SELECT id, name FROM patients ORDER BY name ASC");

// Build SQL with filters
$sql = "
SELECT 
  a.id, 
  d.name AS doctor_name, 
  p.name AS patient_name, 
  a.appointment_date, 
  a.appointment_time, 
  a.status, 
  a.created_at
FROM 
  appointments a
JOIN doctors d ON a.doctor_id = d.id
JOIN patients p ON a.patient_id = p.id
WHERE 1=1
";

if (!empty($filter_doctor)) {
    $sql .= " AND a.doctor_id = " . intval($filter_doctor);
}
if (!empty($filter_patient)) {
    $sql .= " AND a.patient_id = " . intval($filter_patient);
}
if (!empty($filter_status)) {
    $sql .= " AND a.status = '" . $conn->real_escape_string($filter_status) . "'";
}

$sql .= " ORDER BY a.appointment_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Appointments | Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #2246d2;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --card-border-radius: 16px;
      --transition-speed: 0.3s;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #f8fafc !important;
    }

    .page-header {
      margin-bottom: 2rem;
    }

    .page-header h2 {
      font-size: 1.875rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.5rem;
    }

    .page-header p {
      color: #64748b;
      font-size: 1.1rem;
      margin-bottom: 0;
    }

    .filter-card {
      background: #ffffff;
      border-radius: var(--card-border-radius);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      padding: 1.5rem;
      margin-bottom: 2rem;
      border: 1px solid #e2e8f0;
    }

    .filter-card label {
      font-weight: 500;
      color: #475569;
      margin-bottom: 0.5rem;
    }

    .form-select {
      border-radius: 8px;
      border-color: #e2e8f0;
      padding: 0.625rem;
      font-size: 0.95rem;
      box-shadow: none;
      transition: all var(--transition-speed) ease;
    }

    .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(34, 70, 210, 0.1);
    }

    .btn {
      padding: 0.625rem 1.25rem;
      font-weight: 500;
      border-radius: 8px;
      transition: all var(--transition-speed) ease;
    }

    .btn-primary {
      background: var(--primary-color);
      border: none;
    }

    .btn-primary:hover {
      background: #1e40af;
      transform: translateY(-1px);
    }

    .table-container {
      background: #ffffff;
      border-radius: var(--card-border-radius);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      border: 1px solid #e2e8f0;
    }

    .table {
      margin-bottom: 0;
    }

    .table thead {
      background: #f8fafc;
    }

    .table th {
      font-weight: 600;
      color: #475569;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.05em;
      padding: 1rem;
      border-bottom: 1px solid #e2e8f0;
    }

    .table td {
      padding: 1rem;
      color: #475569;
      vertical-align: middle;
    }

    .badge {
      padding: 0.5rem 0.75rem;
      font-weight: 500;
      font-size: 0.75rem;
      border-radius: 6px;
    }

    .badge.bg-warning {
      background: #fef3c7 !important;
      color: #92400e;
    }

    .badge.bg-primary {
      background: #dbeafe !important;
      color: #1e40af;
    }

    .badge.bg-success {
      background: #dcfce7 !important;
      color: #166534;
    }

    .badge.bg-danger {
      background: #fee2e2 !important;
      color: #991b1b;
    }

    .btn-action {
      width: 32px;
      height: 32px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      transition: all var(--transition-speed) ease;
    }

    .btn-action:hover {
      transform: translateY(-1px);
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        position: fixed;
        height: 100%;
        z-index: 1000;
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .toggle-btn {
        display: inline-block;
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 8px 12px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 8px;
        margin-bottom: 15px;
      }
    }
  </style>
</head>
<body class="bg-light">

<!-- Sidebar Layout -->
<div class="d-flex">
  <?php include '../components/adminComp/sidebar.php'; ?>

  <!-- Page Content -->
  <div id="page-content" class="flex-grow-1 p-4">
    <div class="page-header">
      <h2>Manage Appointments</h2>
      <p>View and manage all appointment records</p>
    </div>
    
    <div class="filter-card">
      <form method="GET" class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Doctor</label>
          <select name="doctor" class="form-select">
            <option value="">All Doctors</option>
            <?php while ($doc = $doctors->fetch_assoc()) { ?>
              <option value="<?php echo $doc['id']; ?>" <?php if ($filter_doctor == $doc['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($doc['name']); ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Patient</label>
          <select name="patient" class="form-select">
            <option value="">All Patients</option>
            <?php while ($pat = $patients->fetch_assoc()) { ?>
              <option value="<?php echo $pat['id']; ?>" <?php if ($filter_patient == $pat['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($pat['name']); ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="">All Statuses</option>
            <option value="pending" <?php if ($filter_status == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="confirmed" <?php if ($filter_status == 'confirmed') echo 'selected'; ?>>Confirmed</option>
            <option value="completed" <?php if ($filter_status == 'completed') echo 'selected'; ?>>Completed</option>
            <option value="cancelled" <?php if ($filter_status == 'cancelled') echo 'selected'; ?>>Cancelled</option>
          </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-filter me-2"></i>Apply Filters
            </button>
            <a href="manage_appointments.php" class="btn btn-secondary">
              <i class="fas fa-undo me-2"></i>Reset
            </a>
          </div>
        </div>
      </form>
    </div>

<!-- Appointments Table -->
<div class="table-container">
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Doctor</th>
        <th>Patient</th>
        <th>Schedule</th>
        <th>Status</th>
        <th>Created</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td>#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
            <td>
              <div class="d-flex align-items-center">
                <i class="fas fa-user-md me-2 text-primary"></i>
                <?php echo htmlspecialchars($row['doctor_name']); ?>
              </div>
            </td>
            <td>
              <div class="d-flex align-items-center">
                <i class="fas fa-user me-2 text-secondary"></i>
                <?php echo htmlspecialchars($row['patient_name']); ?>
              </div>
            </td>
            <td>
              <div class="d-flex flex-column">
                <span class="fw-medium"><?php echo date('D, M d, Y', strtotime($row['appointment_date'])); ?></span>
                <span class="text-muted small"><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></span>
              </div>
            </td>
            <td>
              <span class="badge bg-<?php
                switch ($row['status']) {
                  case 'pending': echo 'warning'; break;
                  case 'confirmed': echo 'primary'; break;
                  case 'completed': echo 'success'; break;
                  case 'cancelled': echo 'danger'; break;
                }
              ?>">
                <?php echo ucfirst($row['status']); ?>
              </span>
            </td>
            <td>
              <div class="d-flex flex-column">
                <span class="fw-medium"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                <span class="text-muted small"><?php echo date('h:i A', strtotime($row['created_at'])); ?></span>
              </div>
            </td>
            <td>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-action btn-light" title="View Details">
                  <i class="fas fa-eye"></i>
                </button>
                <a href="?delete=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this appointment?')" 
                   class="btn btn-action btn-danger" 
                   title="Delete Appointment">
                  <i class="fas fa-trash-alt"></i>
                </a>
              </div>
            </td>
          </tr>
        <?php } ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center py-4">
            <div class="d-flex flex-column align-items-center">
              <i class="fas fa-calendar-times text-muted mb-2" style="font-size: 2rem;"></i>
              <p class="text-muted mb-0">No appointments found</p>
            </div>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
  }
</script>
</body>
</html>
