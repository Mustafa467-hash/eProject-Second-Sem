<?php
require_once '../includes/db.php';

$sql = "SELECT d.*, c.name AS city_name 
        FROM doctors d 
        LEFT JOIN cities c ON d.city_id = c.id";
$result = $conn->query($sql);

// Collect unique cities and specializations for filter options
$cities = [];
$specializations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
        $cities[] = $row['city_name'];
        $specializations[] = $row['specialization'];
    }
    $cities = array_unique($cities);
    $specializations = array_unique($specializations);
} else {
    $doctors = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Doctors</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      background: linear-gradient(to right, #2196F3, #64B5F6);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #fff;
    }

    h1 {
      font-weight: bold;
      margin-top: 30px;
      color: #ffffff;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }

    .doctor-card {
      border: none;
      border-radius: 20px;
      background-color: #ffffff;
      color: #333;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .doctor-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
    }

    .card-title {
      font-weight: 600;
      color: #1565C0;
    }

    .badge {
      background-color: #1976D2;
    }

    .card-body p {
      margin-bottom: 5px;
    }

    .filter-bar {
      margin-bottom: 30px;
    }

    .form-select, .form-control {
      border-radius: 12px;
    }
  </style>
</head>

<body>
<?php include '../components/PatientComp/navbar.php'; ?>

<div class="container py-5">
  <h1 class="text-center mb-4">Meet Our Doctors</h1>

  <!-- Filter Bar -->
  <div class="row filter-bar justify-content-center mb-4">
    <div class="col-md-4 mb-2">
      <input type="text" id="searchInput" class="form-control" placeholder="Search by name or specialization">
    </div>
    <div class="col-md-3 mb-2">
      <select id="cityFilter" class="form-select">
        <option value="">Filter by City</option>
        <?php foreach ($cities as $city): ?>
          <option value="<?= htmlspecialchars($city) ?>"><?= htmlspecialchars($city) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 mb-2">
      <select id="specializationFilter" class="form-select">
        <option value="">Filter by Specialization</option>
        <?php foreach ($specializations as $spec): ?>
          <option value="<?= htmlspecialchars($spec) ?>"><?= htmlspecialchars($spec) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Doctor Cards -->
  <div class="row g-4" id="doctorCards">
    <?php foreach ($doctors as $row): ?>
      <div class="col-md-4 doctor-card-container" 
           data-name="<?= strtolower($row['name']) ?>"
           data-specialization="<?= strtolower($row['specialization']) ?>"
           data-city="<?= strtolower($row['city_name']) ?>">
        <a href="doctor-profile.php?id=<?= $row['id'] ?>" style="text-decoration: none;">
          <div class="card doctor-card h-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
              <p><span class="badge text-white"><?= htmlspecialchars($row['specialization']) ?></span></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
              <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
              <p><strong>City:</strong> <?= htmlspecialchars($row['city_name']) ?></p>
              <p><strong>Availability:</strong><br><?= nl2br(htmlspecialchars($row['availability'])) ?></p>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include '../components/PatientComp/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const searchInput = document.getElementById('searchInput');
  const cityFilter = document.getElementById('cityFilter');
  const specializationFilter = document.getElementById('specializationFilter');
  const doctorCards = document.querySelectorAll('.doctor-card-container');

  function filterCards() {
    const search = searchInput.value.toLowerCase();
    const city = cityFilter.value.toLowerCase();
    const spec = specializationFilter.value.toLowerCase();

    doctorCards.forEach(card => {
      const name = card.dataset.name;
      const specialization = card.dataset.specialization;
      const cityName = card.dataset.city;

      const matchSearch = name.includes(search) || specialization.includes(search);
      const matchCity = !city || cityName === city;
      const matchSpec = !spec || specialization === spec;

      if (matchSearch && matchCity && matchSpec) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  searchInput.addEventListener('input', filterCards);
  cityFilter.addEventListener('change', filterCards);
  specializationFilter.addEventListener('change', filterCards);
</script>
</body>
</html>
