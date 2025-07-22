<?php

require_once '../includes/db.php';

$success = '';
$error = '';

$patient_id = $_SESSION['patient_id'] ?? null;
$doctor_id = $_GET['doctor_id'] ?? $_POST['doctor_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';

    if ($doctor_id && $patient_id && $date && $time) {
        $datetime = "$date $time";
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE doctor_id = ? AND appointment_time = ?");
        $stmt->bind_param("is", $doctor_id, $datetime);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['total'] > 0) {
            $error = "That slot is already taken! Choose another.";
        } else {
            $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_time) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $patient_id, $doctor_id, $datetime);
            $success = $stmt->execute() ? "Appointment booked! 🎉" : "Something went wrong. Try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dateInput = document.getElementById("date");
            const timeSelect = document.getElementById("time");
            const doctorId = <?= json_encode($doctor_id) ?>;

            dateInput.addEventListener("change", () => {
                const selectedDate = dateInput.value;
                if (!selectedDate) return;

                // Clear previous options
                timeSelect.innerHTML = '<option value="">-- Select Time --</option>';

                // Fetch available slots
                fetch(`../ajax/fetch_slots.php?date=${selectedDate}&doctor_id=${doctorId}`)
                    .then(res => res.json())
                    .then(slots => {
                        if (slots.length === 0) {
                            timeSelect.innerHTML += '<option disabled>No slots available</option>';
                        } else {
                            slots.forEach(slot => {
                                const option = document.createElement('option');
                                option.value = slot;
                                option.textContent = slot;
                                timeSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(err => {
                        console.error("Error fetching slots:", err);
                        timeSelect.innerHTML += '<option disabled>Error loading times</option>';
                    });
            });
        });
    </script>


    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            background-color: white;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-danger {
            background: linear-gradient(135deg, #2246d2, #041a83);
            border: none;
            box-shadow: 0 0 10px #041A83;
            border-radius: 50px;
            transition: transform 0.2s ease;
        }

        .btn-danger:hover {
            transform: scale(1.05);
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(33, 57, 224, 0.25);
        }

        .card h3 {
            font-weight: bold;
            color: #041a83;
        }
    </style>
</head>

<body>

    <?php include('../components/patientcomp/navbar.php'); ?>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 w-100" style="max-width: 500px;">
            <h3 class="text-center mb-4">Book Your Appointment</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" id="appointmentForm">
                <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($doctor_id) ?>">

                <div class="mb-3">
                    <label for="date" class="form-label">Choose a Date</label>
                    <input type="date" id="date" name="date" class="form-control" required min="<?= date('Y-m-d') ?>">
                </div>

                <div class="mb-3">
                    <label for="time" class="form-label">Choose a Time Slot</label>
                    <select name="time" id="time" class="form-control" required>
                        <option value="">-- Select Time --</option>
                    </select>





                    </select>
                </div>

                <button type="submit" class="btn btn-danger w-100">Book Now</button>
            </form>

            <a href="dashboard.php" class="btn btn-link text-center mt-3">⬅ Back to Dashboard</a>
        </div>
    </div>

    <?php include('../components/patientcomp/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>