<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Add city
if (isset($_POST['add_city'])) {
    $city_name = trim($_POST['city_name']);
    if (!empty($city_name)) {
        $stmt = $conn->prepare("INSERT INTO cities (name) VALUES (?)");
        $stmt->bind_param("s", $city_name);
        $stmt->execute();
    }
}

// Delete city
if (isset($_GET['delete'])) {
    $city_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM cities WHERE id = ?");
    $stmt->bind_param("i", $city_id);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Cities | Admin Panel</title>
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

    .add-city-card {
      background: #ffffff;
      border-radius: var(--card-border-radius);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      border: 1px solid #e2e8f0;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .add-city-card .form-control {
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
      transition: all var(--transition-speed) ease;
    }

    .add-city-card .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(34, 70, 210, 0.1);
    }

    .add-city-card .form-control::placeholder {
      color: #94a3b8;
    }

    .btn {
      padding: 0.75rem 1.5rem;
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
      border: 1px solid #e2e8f0;
      overflow: hidden;
    }

    .table-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #e2e8f0;
    }

    .table-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 0;
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
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #e2e8f0;
    }

    .table td {
      padding: 1rem 1.5rem;
      color: #475569;
      vertical-align: middle;
    }

    .city-id {
      font-family: 'JetBrains Mono', monospace;
      color: #64748b;
      font-size: 0.875rem;
    }

    .city-name {
      font-weight: 500;
      color: #1e293b;
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
      background: #fee2e2;
      color: #991b1b;
      border: none;
    }

    .btn-action:hover {
      background: #fecaca;
      transform: translateY(-1px);
    }

    .empty-state {
      padding: 3rem 1.5rem;
      text-align: center;
    }

    .empty-state i {
      font-size: 2.5rem;
      color: #cbd5e1;
      margin-bottom: 1rem;
    }

    .empty-state p {
      color: #64748b;
      margin-bottom: 0;
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
      <h2>Manage Cities</h2>
      <p>Add and manage cities in your healthcare network</p>
    </div>

    <!-- Add City -->
    <div class="add-city-card">
      <form method="POST" class="d-flex gap-3">
        <input type="text" name="city_name" class="form-control" placeholder="Enter city name" required>
        <button type="submit" name="add_city" class="btn btn-primary">
          <i class="fas fa-plus me-2"></i>Add City
        </button>
      </form>
    </div>

    <!-- City Table -->
    <div class="table-container">
      <div class="table-header">
        <h5 class="table-title">City List</h5>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th style="width: 100px">ID</th>
            <th>City Name</th>
            <th style="width: 100px">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $cities = $conn->query("SELECT * FROM cities ORDER BY id DESC");
          if ($cities->num_rows > 0) {
              while ($city = $cities->fetch_assoc()) {
                  echo "<tr>
                          <td><span class='city-id'>#" . str_pad($city['id'], 3, '0', STR_PAD_LEFT) . "</span></td>
                          <td>
                            <div class='d-flex align-items-center'>
                              <i class='fas fa-city me-2 text-primary'></i>
                              <span class='city-name'>" . htmlspecialchars($city['name']) . "</span>
                            </div>
                          </td>
                          <td>
                            <a href='?delete={$city['id']}' 
                               onclick='return confirm(\"Are you sure you want to delete this city? This action cannot be undone.\")' 
                               class='btn-action' 
                               title='Delete City'>
                              <i class='fas fa-trash-alt'></i>
                            </a>
                          </td>
                        </tr>";
              }
          } else {
              echo "<tr>
                      <td colspan='3'>
                        <div class='empty-state'>
                          <i class='fas fa-city'></i>
                          <p>No cities have been added yet</p>
                        </div>
                      </td>
                    </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../components/adminComp/footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sidebar.js"></script> <!-- your sidebar toggle logic -->
</body>
</html>
