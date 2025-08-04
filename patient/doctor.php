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
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
      --primary-color: #2246d2;
      --secondary-color: #6366f1;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --dark-color: #1e293b;
      --light-color: #f8fafc;
      --border-radius: 16px;
      --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
      background: var(--light-color);
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #334155;
    }

    h1 {
      font-weight: 800;
      margin-top: 2rem;
      color: var(--dark-color);
      font-size: 2.5rem;
      text-align: center;
      position: relative;
      padding-bottom: 1rem;
    }

    h1::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 2px;
    }

    /* Doctor Card */
    .doctor-card {
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.95);
      border-radius: var(--border-radius);
      padding: 1.5rem;
      box-shadow: 0 10px 25px -5px rgba(34, 70, 210, 0.15);
      transition: var(--transition);
      cursor: pointer;
      height: 100%;
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .doctor-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, 
        rgba(34, 70, 210, 0.05),
        rgba(99, 102, 241, 0.05)
      );
      opacity: 0;
      transition: var(--transition);
    }

    .doctor-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 35px -10px rgba(34, 70, 210, 0.2);
    }

    .doctor-card:hover::before {
      opacity: 1;
    }

    .doctor-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 1.5rem;
      flex-shrink: 0;
      border: 4px solid white;
      box-shadow: 0 8px 16px -4px rgba(34, 70, 210, 0.2);
      transition: var(--transition);
    }

    .doctor-card:hover .doctor-img {
      transform: scale(1.05);
    }

    .doctor-info {
      position: relative;
      z-index: 1;
    }

    .doctor-info h5 {
      font-weight: 700;
      margin-bottom: 0.5rem;
      color: var(--dark-color);
      font-size: 1.25rem;
    }

    .doctor-info p {
      margin: 0;
      color: #475569;
      font-size: 0.95rem;
      line-height: 1.5;
    }

    .doctor-info p strong {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 1rem;
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
      margin-bottom: 2.5rem;
      background: white;
      padding: 1.5rem;
      border-radius: var(--border-radius);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .form-select,
    .form-control {
      border-radius: var(--border-radius);
      padding: 0.75rem 1rem;
      border: 1px solid rgba(226, 232, 240, 0.8);
      font-size: 0.95rem;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      transition: var(--transition);
    }

    .form-select:focus,
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(34, 70, 210, 0.1);
    }

    .form-select:hover,
    .form-control:hover {
      border-color: var(--secondary-color);
    }

    .form-select option {
      font-size: 0.95rem;
      padding: 0.5rem;
    }

    #searchInput {
      padding-left: 2.5rem;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cpath d='M21 21l-4.35-4.35'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: 0.75rem center;
      background-size: 1.25rem;
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
        <input type="text" id="searchInput" class="form-control" placeholder="Search doctors...">
      </div>
      <div class="col-6 col-md-3 mb-2">
        <select id="cityFilter" class="form-select">
          <option value="">All Cities</option>
          <?php foreach ($cities as $city): ?>
            <option value="<?= htmlspecialchars($city) ?>">
              <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($city) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-3 mb-2">
        <select id="specializationFilter" class="form-select">
          <option value="">All Specializations</option>
          <?php foreach ($specializations as $spec): ?>
            <option value="<?= htmlspecialchars($spec) ?>">
              <i class="fas fa-stethoscope"></i> <?= htmlspecialchars($spec) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!-- Doctor Cards -->
    <div class="row g-3" id="doctorCards">
      <?php foreach ($doctors as $row): ?>
        <div class="col-12 col-sm-6 col-lg-4 doctor-card-container" data-name="<?= strtolower($row['name']) ?>"
          data-specialization="<?= strtolower($row['specialization']) ?>"
          data-city="<?= strtolower($row['city_name']) ?>">
          <a href="doctor-profile.php?id=<?= $row['id'] ?>" style="text-decoration: none;">
            <div class="doctor-card">
              <img
                src="../assets/images/<?= !empty($row['image']) ? htmlspecialchars($row['image']) : 'default-doctor.jpg' ?>"
                alt="Doctor" class="doctor-img">

              <div class="doctor-info">
                <h5><?= htmlspecialchars($row['name']) ?></h5>
                <p><strong><i class="fas fa-stethoscope me-1"></i><?= htmlspecialchars($row['specialization']) ?></strong></p>
                <p><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($row['city_name']) ?></p>
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
