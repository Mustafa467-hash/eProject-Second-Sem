<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Delete appointment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
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


    <h2 class="text-danger fw-bold mb-4">Manage Appointments</h2>

    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title fw-semibold">Appointment List</h5>
        <table class="table table-bordered table-hover mt-3">
          <thead class="table-danger">
            <tr>
              <th>ID</th>
              <th>Patient Name</th>
              <th>Age</th>
              <th>Appointment Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT a.id, p.name AS patient_name, p.age, a.appointment_date
                    FROM appointments a
                    JOIN patients p ON a.patient_id = p.id
                    ORDER BY a.id DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['patient_name']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['appointment_date']}</td>
                            <td>
                              <a href='?delete={$row['id']}' onclick='return confirm(\"Delete this appointment?\")' class='btn btn-sm btn-danger'>
                                <i class='fas fa-trash'></i>
                              </a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center text-muted'>No appointments found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
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
