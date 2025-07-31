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
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ✅ Responsive viewport -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            color: #334155;
            scroll-behavior: smooth; /* Smooth scrolling */
            overflow-x: hidden;
        }

        /* Floating glow blobs */
        .glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 10s infinite alternate ease-in-out;
            z-index: 0;
        }
        .glow.one { background: #ff5f6d; width: 300px; height: 300px; top: 10%; left: -10%; }
        .glow.two { background: #2246d2; width: 250px; height: 250px; bottom: 5%; right: -10%; animation-delay: 3s; }

        @keyframes float {
            from { transform: translateY(0px); }
            to { transform: translateY(-40px); }
        }

        /* Hero */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 60px 20px;
            z-index: 1;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            color: #1e293b;
            animation: fadeDown 1.2s ease forwards;
            margin-bottom: 1.5rem;
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero p {
            max-width: 650px;
            font-size: 1.2rem;
            margin-top: 20px;
            opacity: 0;
            color: #64748b;
            animation: fadeUp 1.2s ease forwards 0.6s;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-fancy {
            background: #2246d2;
            border: none;
            font-size: 1.1rem;
            padding: 12px 32px;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(34, 70, 210, 0.2);
            transition: all 0.3s ease;
            margin-top: 25px;
            font-weight: 500;
        }

        .btn-fancy:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(34, 70, 210, 0.25);
            background: #1a37a7;
        }

        /* Stats Section */
        .stats-section {
            position: relative;
            z-index: 2;
            margin-top: -80px;
            padding: 60px 0;
        }

        .stats-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 20px;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.4s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .stats-card h3 {
            font-size: 2.8rem;
            font-weight: 700;
            color: #2246d2;
            margin-bottom: 8px;
        }

        .stats-card p {
            color: #64748b;
            font-weight: 500;
            margin-top: 8px;
            font-size: 1.1rem;
        }

        /* About Section */
        .about-section {
            background: #ffffff;
            border-radius: 16px;
            padding: 60px;
            margin: 60px auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .about-section h2 {
            color: #1e293b;
            font-weight: 700;
        }

        .about-section p {
            color: black;
            line-height: 1.8;
        }

        /* Testimonials */
        .testimonial {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            transition: 0.3s;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .testimonial:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .testimonial p {
            color: #334155;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .testimonial h6 {
            color: #64748b;
            font-weight: 600;
        }

        /* Video */
        .video-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin-bottom: 80px;
        }

        /* ✅ Responsive tweaks */
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 2.8rem;
            }
            .hero p {
                font-size: 1rem;
            }
            .about-section {
                padding: 30px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 40px 15px;
            }
            .hero h1 {
                font-size: 2.2rem;
            }
            .stats-card h3 {
                font-size: 2rem;
            }
            .stats-section {
                margin-top: -40px;
            }
            .about-section {
                padding: 20px;
                margin: 30px auto;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 1.8rem;
            }
            .hero p {
                font-size: 0.95rem;
            }
            .btn-fancy {
                width: 100%;
                padding: 10px 0;
            }
            .stats-card {
                padding: 20px 10px;
            }
            .about-section img {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include '../components/patientcomp/navbar.php'; ?>

    <div class="glow one"></div>
    <div class="glow two"></div>

    <!-- Hero Section -->
    <div class="hero">
        <h1 data-aos="zoom-in">Save Yourself.<br>Save Each Other.</h1>
        <p data-aos="fade-up">Your health is our priority. Book appointments, get updates and consult with doctors—all in one place.</p>
        <p class="mt-3 fw-semibold" data-aos="fade-up" data-aos-delay="400">📞 +92 331 0003430</p>
    </div>

    <!-- Stats -->
    <div class="container stats-section">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3" data-aos="fade-up">
                <div class="stats-card">
                    <h3><?= $city_count ?></h3>
                    <p>Registered Cities</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card">
                    <h3><?= $patient_count ?></h3>
                    <p>Total Patients</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card">
                    <h3><?= $doctor_count ?></h3>
                    <p>Active Doctors</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stats-card">
                    <h3><?= $appointment_count ?></h3>
                    <p>Pending Requests</p>
                </div>
            </div>
        </div>
    </div>

    <!-- About -->
    <div class="container about-section" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <img src="../assets/images/picture1.avif" class="img-fluid rounded-4" alt="Medical care">
            </div>
            <div class="col-12 col-md-6 mt-4 mt-md-0">
                <h2>What is Our Platform?</h2>
                <p>Our platform connects you with certified medical professionals. Book appointments, get test results, and consult online. Reliable and accessible care for everyone.</p>
                <a href="#about" class="btn btn-outline-primary mt-3">Check About Us</a>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="container my-5">
        <h2 class="text-center mb-4" data-aos="fade-up">What Our Patients Say</h2>
        <div class="row g-4">
            <div class="col-12 col-md-4" data-aos="fade-up">
                <div class="testimonial">
                    <p>"Super easy to book appointments. The doctors were amazing!"</p>
                    <h6 class="mt-3">- Ayesha R.</h6>
                </div>
            </div>
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial">
                    <p>"Clean interface and great support. Highly recommended."</p>
                    <h6 class="mt-3">- Hamza K.</h6>
                </div>
            </div>
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial">
                    <p>"Helped me connect with the right doctor within minutes."</p>
                    <h6 class="mt-3">- Sarah M.</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Video -->
    <div class="container video-container" data-aos="zoom-in">
        <div class="ratio ratio-16x9">
            <iframe src="https://www.youtube.com/embed/EBgyBMimBr4" title="Informative Video" allowfullscreen></iframe>
        </div>
    </div>

    <!-- About Us Section -->
    <div id="about" class="container my-5 text-center" data-aos="fade-up">
        <div class="about-section">
            <div class="row align-items-center">
                <div class="">
                    <h2 class="mb-4 text-black">About Us</h2>
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <p class="text-dark mb-4">
                                Founded in 2023, Care Group has emerged as Pakistan's leading digital healthcare platform. 
                                We are a dedicated team of healthcare professionals, technologists, and innovators united by 
                                a single mission: making quality healthcare accessible to everyone.
                            </p>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h4 class="text-primary mb-3">Our Mission</h4>
                                    <p class="text-dark">
                                        To revolutionize healthcare access in Pakistan by creating a seamless bridge between 
                                        patients and healthcare providers, ensuring everyone has access to quality medical care 
                                        when they need it most.
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-primary mb-3">Our Vision</h4>
                                    <p class="text-dark">
                                        To become the most trusted healthcare platform in Pakistan, where every citizen can 
                                        access world-class medical care with just a few clicks.
                                    </p>
                                </div>
                            </div>
                            <h4 class="text-primary mb-3">What Sets Us Apart</h4>
                            <div class="row g-4 text-start mb-4">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-user-md text-primary me-3 mt-1"></i>
                                        <div>
                                            <h5 class="mb-2">Verified Professionals</h5>
                                            <p class="text-dark mb-0">All our doctors are verified and hold valid medical licenses</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-clock text-primary me-3 mt-1"></i>
                                        <div>
                                            <h5 class="mb-2">24/7 Support</h5>
                                            <p class="text-dark mb-0">Round-the-clock assistance for all your healthcare needs</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-shield-alt text-primary me-3 mt-1"></i>
                                        <div>
                                            <h5 class="mb-2">Secure Platform</h5>
                                            <p class="text-dark mb-0">Your health data is protected with enterprise-grade security</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-dark">
                                Our platform offers seamless appointment booking, real-time updates, and online consultations, 
                                making healthcare more accessible than ever. We've partnered with leading hospitals and clinics 
                                across Pakistan to ensure you receive the best possible care.
                            </p>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    </div>

    <?php include '../components/patientcomp/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>AOS.init({ duration: 1200, once: true });</script>
</body>
</html>
