<?php
$title = "Dashboard";
require_once '../includes/db.php';
require_once '../includes/auth-patient.php';


$doctor_count = $conn->query("SELECT COUNT(*) as total FROM doctors")->fetch_assoc()['total'];
$city_count = $conn->query("SELECT COUNT(*) as total FROM cities")->fetch_assoc()['total'];
$patient_count = $conn->query("SELECT COUNT(*) as total FROM patients")->fetch_assoc()['total'];
$appointment_count = $conn->query("SELECT COUNT(*) as total FROM appointments")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
        }

        .hero {

            padding: 120px 0;
            color: white;
            text-align: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            animation: slideInDown 1s ease forwards;
        }

        .hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin: auto;
            margin-top: 25px;
            animation: fadeIn 2s ease-in-out;
        }

        .hero .btn-primary {
            background-color: #fff;
            color: #041a83;
            border: none;
            font-size: 1.1rem;
            padding: 12px 30px;
            margin-top: 30px;
            transition: 0.3s ease;
        }

        .hero .btn-primary:hover {
            background-color: #ffd6e0;
            color: #041a83;
        }

        .gradient-text {
            background: linear-gradient(135deg, #2246d2, #041a83);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stats-card {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
            padding: 40px 20px;
            text-align: center;
            transition: all 0.4s ease;
            animation: zoomIn 0.8s ease forwards;
        }

        .stats-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-weight: 800;
            font-size: 2.5rem;
            color: #041a83;
            margin-bottom: 20px;
            animation: slideInLeft 1s ease forwards;
        }

        .btn-outline-primary {
            border-color: #041a83;
            color: #041a83;
            font-weight: 500;
            padding: 10px 24px;

            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #041a83;
            color: white;
        }

        footer {
            background: #2c2c2c;
            color: #ccc;
            animation: fadeIn 2s ease;
        }

        /* Animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>

    <?php include '../components/patientcomp/navbar.php'; ?>

    <!-- Page Content -->
    <div id="page-content" class="">
        <!-- Hero Section -->
        <div class="hero">
            <div class="container bg-white p-5 rounded shadow text-center">
                
                <h1 class="display-4 fw-bold gradient-text">Save yourself. <br> Save each other.</h1>
                <p class="text-muted">Your health is our priority. Book appointments, get updates and consult with
                    doctors—all in one place.</p>
                <a href="#" class="btn btn-danger btn-lg my-5">Learn More</a>
                <p class="mt-3 fw-semibold gradient-text">+92 331 0003430</p>
            </div>

            <!-- Stats -->
            <div class="container my-5">
                <div class="row g-4 text-center">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h3 class="text-danger">
                               <?= $city_count ?>
                            </h3>
                            <p class="text-muted">Registered Cities</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h3 class="text-primary">
                            <?= $patient_count ?>
                            </h3>
                            <p class="text-muted">Total Patients</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h3 class="text-success">
                                <?= $doctor_count ?>
                            </h3>
                            <p class="text-muted">Active Doctors</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h3 class="text-warning">
                                <?= $appointment_count ?>
                            </h3>
                            <p class="text-muted">Pending Requests</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About -->
            <div class="container my-5">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="bg-light shadow rounded-4 overflow-hidden" style="height: 320px;">
                            <img src="../assets/images/picture1.avif"
                                class="img-fluid h-100 w-100 object-fit-cover" alt="Medical care image">
                        </div>

                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                        <h2 class="section-title">What is Our Platform?</h2>
                        <p class="text-muted">Our platform connects you with certified medical professionals. Book
                            appointments, get test results, and consult online. Our goal is to provide reliable and
                            accessible care for everyone.</p>
                        <a href="#" class="btn btn-outline-primary my-3">Check Services</a>
                    </div>
                </div>
            </div>

            <!-- Video -->
            <div class="container mb-5">
                <div class="ratio ratio-16x9 shadow rounded-4 overflow-hidden">
                    <iframe src="https://www.youtube.com/embed/EBgyBMimBr4" title="Informative Video"
                        allowfullscreen></iframe>
                </div>
            </div>


        </div>
        <!-- Footer -->
        <?php
        include '../components/patientcomp/footer.php';
        ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>