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
  <link rel="stylesheet" href="../assets/css/sidebar.css"> <!-- your custom sidebar styles -->
</head>
<body class="bg-light">

<!-- Sidebar Layout -->
<div class="d-flex">
  <?php include '../components/adminComp/sidebar.php'; ?>

  <!-- Page Content -->
  <div id="page-content" class="flex-grow-1 p-4">
    <!-- Mobile Toggle -->

    <h2 class="text-danger fw-bold mb-4">Manage Cities</h2>

    <!-- Add City -->
    <div class="card mb-4 shadow-sm">
      <div class="card-body">
        <form method="POST" class="d-flex flex-column flex-md-row gap-2">
          <input type="text" name="city_name" class="form-control" placeholder="Enter new city name" required>
          <button type="submit" name="add_city" class="btn btn-danger">Add City</button>
        </form>
      </div>
    </div>

    <!-- City Table -->
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title fw-semibold">City List</h5>
        <table class="table table-bordered table-hover mt-3">
          <thead class="table-danger">
            <tr>
              <th>ID</th>
              <th>City Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $cities = $conn->query("SELECT * FROM cities ORDER BY id DESC");
            if ($cities->num_rows > 0) {
                while ($city = $cities->fetch_assoc()) {
                    echo "<tr>
                            <td>{$city['id']}</td>
                            <td>{$city['name']}</td>
                            <td>
                              <a href='?delete={$city['id']}' onclick='return confirm(\"Delete this city?\")' class='btn btn-sm btn-danger'>
                                <i class='fas fa-trash'></i>
                              </a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='text-center text-muted'>No cities found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../components/adminComp/footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sidebar.js"></script> <!-- your sidebar toggle logic -->
</body>
</html>
