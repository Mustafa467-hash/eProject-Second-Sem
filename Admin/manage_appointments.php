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
  <style>
    .toggle-btn {
      display: none;
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
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 12px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
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

<form method="GET" class="mb-4 d-flex flex-wrap gap-2 align-items-end">
  <div>
    <label>Doctor:</label>
    <select name="doctor" class="form-select">
      <option value="">All Doctors</option>
      <?php while ($doc = $doctors->fetch_assoc()) { ?>
        <option value="<?php echo $doc['id']; ?>" <?php if ($filter_doctor == $doc['id']) echo 'selected'; ?>>
          <?php echo htmlspecialchars($doc['name']); ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <div>
    <label>Patient:</label>
    <select name="patient" class="form-select">
      <option value="">All Patients</option>
      <?php while ($pat = $patients->fetch_assoc()) { ?>
        <option value="<?php echo $pat['id']; ?>" <?php if ($filter_patient == $pat['id']) echo 'selected'; ?>>
          <?php echo htmlspecialchars($pat['name']); ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <div>
    <label>Status:</label>
    <select name="status" class="form-select">
      <option value="">All Statuses</option>
      <option value="pending" <?php if ($filter_status == 'pending') echo 'selected'; ?>>Pending</option>
      <option value="confirmed" <?php if ($filter_status == 'confirmed') echo 'selected'; ?>>Confirmed</option>
      <option value="completed" <?php if ($filter_status == 'completed') echo 'selected'; ?>>Completed</option>
      <option value="cancelled" <?php if ($filter_status == 'cancelled') echo 'selected'; ?>>Cancelled</option>
    </select>
  </div>

  <div>
    <button type="submit" class="btn btn-primary">Apply Filters</button>
    <a href="manage_appointments.php" class="btn btn-secondary">Reset</a>
  </div>
</form>

<!-- Appointments Table -->
<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Doctor</th>
      <th>Patient</th>
      <th>Date</th>
      <th>Time</th>
      <th>Status</th>
      <th>Created At</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
          <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
          <td><?php echo $row['appointment_date']; ?></td>
          <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
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
          <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
          <td>
            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this appointment?')" class="btn btn-sm btn-danger">
              <i class="fas fa-trash-alt"></i>
            </a>
          </td>
        </tr>
      <?php } ?>
    <?php else: ?>
      <tr><td colspan="8" class="text-center">No appointments found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
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
