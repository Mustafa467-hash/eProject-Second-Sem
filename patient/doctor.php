<?php
require_once '../includes/db.php';

$sql = "SELECT d.*, c.name AS city_name 
        FROM doctors d 
        LEFT JOIN cities c ON d.city_id = c.id";
$result = $conn->query($sql);

$cities = [];
$specializations = [];
$doctors = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
        $cities[] = $row['city_name'];
        $specializations[] = $row['specialization'];
    }
    $cities = array_unique($cities);
    $specializations = array_unique($specializations);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ✅ Responsive meta -->
  <title>Our Doctors</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: #f6f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h1 {
      font-weight: 700;
      margin-top: 30px;
      color: #222;
    }

    /* Doctor Card */
    .doctor-card {
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 16px;
      padding: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      height: 100%;
    }

    .doctor-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .doctor-img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 15px;
      flex-shrink: 0;
    }

    .doctor-info h5 {
      font-weight: 600;
      margin-bottom: 5px;
      color: #222;
    }

    .doctor-info p {
      margin: 0;
      color: #555;
      font-size: 14px;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
      h1 {
        font-size: 1.8rem;
      }
    }

    @media (max-width: 768px) {
      .doctor-card {
        flex-direction: column;
        text-align: center;
        padding: 20px;
      }
      .doctor-img {
        margin: 0 0 10px 0;
        width: 100px;
        height: 100px;
      }
    }

    @media (max-width: 576px) {
      .filter-bar .form-control,
      .filter-bar .form-select {
        font-size: 14px;
        padding: 8px;
      }
      .doctor-info p {
        font-size: 13px;
      }
    }

    /* Filter */
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
    <div class="col-12 col-md-4 mb-2">
      <input type="text" id="searchInput" class="form-control" placeholder="Search by name or specialization">
    </div>
    <div class="col-6 col-md-3 mb-2">
      <select id="cityFilter" class="form-select">
        <option value="">Filter by City</option>
        <?php foreach ($cities as $city): ?>
          <option value="<?= htmlspecialchars($city) ?>"><?= htmlspecialchars($city) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-6 col-md-3 mb-2">
      <select id="specializationFilter" class="form-select">
        <option value="">Filter by Specialization</option>
        <?php foreach ($specializations as $spec): ?>
          <option value="<?= htmlspecialchars($spec) ?>"><?= htmlspecialchars($spec) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Doctor Cards -->
  <div class="row g-3" id="doctorCards">
    <?php foreach ($doctors as $row): ?>
      <div class="col-12 col-sm-6 col-lg-4 doctor-card-container" 
           data-name="<?= strtolower($row['name']) ?>"
           data-specialization="<?= strtolower($row['specialization']) ?>"
           data-city="<?= strtolower($row['city_name']) ?>">
        <a href="doctor-profile.php?id=<?= $row['id'] ?>" style="text-decoration: none;">
          <div class="doctor-card">
            <img src="<?= $row['image'] ?? 'default-doctor.jpg' ?>" alt="Doctor" class="doctor-img">
            <div class="doctor-info">
              <h5><?= htmlspecialchars($row['name']) ?></h5>
              <p><strong><?= htmlspecialchars($row['specialization']) ?></strong></p>
              <p><?= htmlspecialchars($row['city_name']) ?></p>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include '../components/PatientComp/footer.php'; ?>

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

      card.style.display = (matchSearch && matchCity && matchSpec) ? 'block' : 'none';
    });
  }

  searchInput.addEventListener('input', filterCards);
  cityFilter.addEventListener('change', filterCards);
  specializationFilter.addEventListener('change', filterCards);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
