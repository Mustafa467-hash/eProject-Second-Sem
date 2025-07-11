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

  <!-- Bootstrap + Theme -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<?php include '../components/adminComp/header.php'; ?>

<div class="container py-5">
  <h2 class="text-primary mb-4 fw-bold">Manage Cities</h2>

  <!-- Add City Form -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <form method="POST" class="d-flex gap-2">
        <input type="text" name="city_name" class="form-control" placeholder="Enter new city name" required>
        <button type="submit" name="add_city" class="btn btn-primary">Add City</button>
      </form>
    </div>
  </div>

  <!-- City List -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">City List</h5>
      <table class="table table-bordered table-hover mt-3">
        <thead class="table-primary">
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
                              <a href='?delete={$city['id']}' onclick='return confirm(\"Delete this city?\")' class='btn btn-sm btn-danger'>Delete</a>
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

<?php include '../components/adminComp/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
