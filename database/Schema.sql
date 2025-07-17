-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2025 at 12:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `care_group`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Rafay', 'rafay@example.com', 'Rafay@123', '2025-07-11 12:05:37'),
(2, 'Furqan', 'furqan@example.com', 'Furqan@123', '2025-07-11 12:05:37'),
(3, 'Umm-e-Hani', 'ummehani@example.com', 'Ummehani@123', '2025-07-11 12:05:37'),
(4, 'Hamza', 'hamza@example.com', 'Hamza@123', '2025-07-11 12:05:37'),
(5, 'Talha', 'talha@example.com', 'Talha@123', '2025-07-11 12:05:37'),
(6, 'Syed-Hamza', 'syedhamza@example.com', 'SyedHamza@123', '2025-07-11 12:05:37'),
(7, 'Mustafa', 'mustafa@example.com', 'Mustafa@123', '2025-07-11 12:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `doctor_id`, `patient_id`, `appointment_date`, `appointment_time`, `status`, `created_at`) VALUES
(1, 1, 1, '2025-07-20', '14:30:00', 'confirmed', '2025-07-17 21:33:24'),
(2, 2, 2, '2025-07-21', '10:00:00', 'pending', '2025-07-17 21:33:24'),
(3, 1, 3, '2025-07-22', '16:45:00', 'completed', '2025-07-17 21:33:24');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(1, 'Karachi'),
(2, 'Lahore'),
(3, 'Islamabad'),
(4, 'Rawalpindi'),
(5, 'Faislabad'),
(6, 'Peshawar'),
(7, 'Quetta'),
(8, 'Multan'),
(9, 'Hyderabad'),
(10, 'Gujranwala');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `specialization` varchar(100) NOT NULL,
  `availability` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `email`, `phone`, `city_id`, `specialization`, `availability`, `password`, `created_at`) VALUES
(1, 'Dr. Sana Khan', 'sana.k@caregroup.pk', '0311-1234567', 1, 'Cardiologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11'),
(2, 'Dr. Usman Ghani', 'usman.g@caregroup.pk', '0322-9876543', 1, 'Neurologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11'),
(3, 'Dr. Mahnoor Ali', 'mahnoor.a@caregroup.pk', '0345-4567890', 1, 'Dermatologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11'),
(4, 'Dr. Hamza Bashir', 'hamza.b@caregroup.pk', '0300-9988776', 2, 'General Physician', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11'),
(5, 'Dr. Laiba Tariq', 'laiba.t@caregroup.pk', '0312-3344556', 2, 'Pediatrician', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11'),
(6, 'Dr. Kamran Riaz', 'kamran.r@caregroup.pk', '0333-1122445', 2, 'Oncologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11'),
(7, 'Dr. Sumbul Javed', 'sumbul.j@caregroup.pk', '0346-8899776', 2, 'ENT Specialist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11'),
(8, 'Dr. Faisal Malik', 'faisal.m@caregroup.pk', '0301-2299884', 2, 'Urologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11'),
(9, 'Dr. Amna Asif', 'amna.a@caregroup.pk', '0320-6655443', 2, 'Psychiatrist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11'),
(10, 'Dr. Bilal Zahid', 'bilal.z@caregroup.pk', '0341-5544332', 3, 'Orthopedic Surgeon', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11'),
(11, 'Dr. Hira Fatima', 'hira.f@caregroup.pk', '0309-7711223', 3, 'Endocrinologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11'),
(12, 'Dr. Taimoor Iqbal', 'taimoor.i@caregroup.pk', '0317-7766554', 4, 'Pulmonologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11'),
(13, 'Dr. Rabia Saeed', 'rabia.s@caregroup.pk', '0335-3344112', 4, 'Gynecologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11'),
(14, 'Dr. Zain Abbas', 'zain.a@caregroup.pk', '0321-9988776', 4, 'Nephrologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11'),
(15, 'Dr. Nadia Imran', 'nadia.i@caregroup.pk', '0302-1122112', 4, 'Hematologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11'),
(16, 'Dr. Shahbaz Ali', 'shahbaz.a@caregroup.pk', '0344-5511223', 5, 'Ophthalmologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11'),
(17, 'Dr. Maria Qureshi', 'maria.q@caregroup.pk', '0315-8877665', 5, 'Immunologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11'),
(18, 'Dr. Ahsan Rafi', 'ahsan.r@caregroup.pk', '0308-3344556', 5, 'Radiologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11'),
(19, 'Dr. Areeba Khalid', 'areeba.khalid@example.com', '0300-9900887', 1, 'Pulmonologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08'),
(20, 'Dr. Faizan Malik', 'faizan.malik@example.com', '0310-2233445', 1, 'General Physician', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08'),
(21, 'Dr. Mahnoor Rizvi', 'mahnoor.rizvi@example.com', '0331-5556677', 1, 'Endocrinologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08'),
(22, 'Dr. Shayan Ahmed', 'shayan.ahmed@example.com', '0323-4567890', 1, 'Pediatrician', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08'),
(23, 'Dr. Nadia Baig', 'nadia.baig@example.com', '0308-3344556', 1, 'Cardiologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08'),
(24, 'Dr. Osama Zubair', 'osama.zubair@example.com', '0311-1122334', 1, 'Orthopedic Surgeon', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08'),
(25, 'Dr. Zeeshan Ahmed', 'zeeshan.ahmed@example.com', '0345-7654321', 3, 'Rheumatologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08'),
(26, 'Dr. Laiba Saeed', 'laiba.saeed@example.com', '0312-3344556', 3, 'Gastroenterologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08'),
(27, 'Dr. Imran Qureshi', 'imran.qureshi@example.com', '0321-9988776', 3, 'ENT Specialist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08'),
(28, 'Dr. Sameer Khan', 'sameer.khan@example.com', '0334-5566778', 3, 'Neurologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08'),
(29, 'Dr. Hina Aslam', 'hina.aslam@example.com', '0301-4433221', 3, 'Dermatologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08'),
(30, 'Dr. Daniyal Farooq', 'daniyal.farooq@example.com', '0348-1122334', 3, 'Hematologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08'),
(31, 'Dr. Mehwish Tariq', 'mehwish.tariq@example.com', '0366-7744882', 3, 'Psychiatrist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08'),
(32, 'Dr. Adeel Rafique', 'adeel.rafique@example.com', '0316-2277889', 3, 'Orthopedic Surgeon', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08'),
(33, 'Dr. Fatima Riaz', 'fatima.riaz@example.com', '0355-6600991', 3, 'Nephrologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08'),
(34, 'Dr. Zubair Nawaz', 'zubair.nawaz@example.com', '0341-6677885', 3, 'Urologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08'),
(35, 'Dr. Rabia Qamar', 'rabia.qamar@example.com', '0330-8877665', 3, 'Eye Specialist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08'),
(36, 'Dr. Ahsan Javed', 'ahsan.javed@example.com', '0304-6677001', 3, 'Oncologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08'),
(37, 'Dr. Maria Shams', 'maria.shams@example.com', '0320-5588997', 3, 'Gynecologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `email`, `phone`, `gender`, `age`, `city_id`) VALUES
(1, 'Ayesha Khan', 'ayesha.khan@example.com', '03001234567', 'Female', 28, 9),
(2, 'Ali Raza', 'ali.raza@example.com', '03111234567', 'Male', 34, 6),
(3, 'Fatima Noor', 'fatima.noor@example.com', '03211234567', 'Female', 22, 1),
(4, 'Hamza Ahmed', 'hamza.ahmed@example.com', '03331234567', 'Male', 41, 3),
(5, 'Sara Malik', 'sara.malik@example.com', '03451234567', 'Female', 30, 3),
(6, 'Usman Tariq', 'usman.tariq@example.com', '03011239876', 'Male', 29, 7),
(7, 'Zara Ali', 'zara.ali@example.com', '03151239876', 'Female', 24, 6),
(8, 'Bilal Sheikh', 'bilal.sheikh@example.com', '03221239876', 'Male', 38, 1),
(9, 'Hina Shah', 'hina.shah@example.com', '03401239876', 'Female', 26, 8),
(10, 'Kashif Javed', 'kashif.javed@example.com', '03021239876', 'Male', 33, 5),
(11, 'Sana Tariq', 'sana.tariq@example.com', '03034567891', 'Female', 27, 2),
(12, 'Imran Bashir', 'imran.bashir@example.com', '03124567891', 'Male', 36, 4),
(13, 'Nimra Zaid', 'nimra.zaid@example.com', '03214567891', 'Female', 23, 5),
(14, 'Shahzaib Khan', 'shahzaib.khan@example.com', '03454567891', 'Male', 40, 4),
(15, 'Amna Yousuf', 'amna.yousuf@example.com', '03084567891', 'Female', 29, 5),
(16, 'Waleed Anwar', 'waleed.anwar@example.com', '03314567891', 'Male', 31, 2),
(17, 'Iqra Naveed', 'iqra.naveed@example.com', '03104567891', 'Female', 25, 4),
(18, 'Tariq Mehmood', 'tariq.mehmood@example.com', '03464567891', 'Male', 39, 2),
(19, 'Farah Qureshi', 'farah.qureshi@example.com', '03254567891', 'Female', 33, 5),
(20, 'Danish Latif', 'danish.latif@example.com', '03064567891', 'Male', 37, 4),
(21, 'Sana Tariq', 'sana.tariq@example.com', '03034567891', 'Female', 27, 2),
(22, 'Imran Bashir', 'imran.bashir@example.com', '03124567891', 'Male', 36, 4),
(23, 'Nimra Zaid', 'nimra.zaid@example.com', '03214567891', 'Female', 23, 5),
(24, 'Shahzaib Khan', 'shahzaib.khan@example.com', '03454567891', 'Male', 40, 4),
(25, 'Amna Yousuf', 'amna.yousuf@example.com', '03084567891', 'Female', 29, 5),
(26, 'Waleed Anwar', 'waleed.anwar@example.com', '03314567891', 'Male', 31, 2),
(27, 'Iqra Naveed', 'iqra.naveed@example.com', '03104567891', 'Female', 25, 4),
(28, 'Tariq Mehmood', 'tariq.mehmood@example.com', '03464567891', 'Male', 39, 2),
(29, 'Farah Qureshi', 'farah.qureshi@example.com', '03254567891', 'Female', 33, 5),
(30, 'Danish Latif', 'danish.latif@example.com', '03064567891', 'Male', 37, 4),
(31, 'Rabia Jamil', 'rabia.jamil@example.com', '03175678901', 'Female', 28, 2),
(32, 'Adeel Nadeem', 'adeel.nadeem@example.com', '03215678901', 'Male', 34, 4),
(33, 'Zunaira Shah', 'zunaira.shah@example.com', '03415678901', 'Female', 22, 5),
(34, 'Faizan Khalid', 'faizan.khalid@example.com', '03095678901', 'Male', 38, 4),
(35, 'Mahnoor Raza', 'mahnoor.raza@example.com', '03335678901', 'Female', 26, 2),
(36, 'Danish Ahmed', 'danish.ahmed@example.com', '03216543210', 'Male', 40, 1),
(37, 'Maria Khan', 'maria.khan@example.com', '03131234567', 'Female', 29, 3),
(38, 'Ali Raza', 'ali.raza@example.com', '03451239876', 'Male', 33, 2),
(39, 'Hira Qureshi', 'hira.qureshi@example.com', '03019876543', 'Female', 24, 4),
(40, 'Tariq Mehmood', 'tariq.mehmood@example.com', '03131237890', 'Male', 45, 5),
(41, 'Zoya Siddiqui', 'zoya.siddiqui@example.com', '03012349876', 'Female', 31, 1),
(42, 'Kashif Nawaz', 'kashif.nawaz@example.com', '03451237654', 'Male', 36, 2),
(43, 'Areeba Noor', 'areeba.noor@example.com', '03336549876', 'Female', 23, 3),
(44, 'Osman Zubair', 'osman.zubair@example.com', '03217654321', 'Male', 39, 4),
(45, 'Noor Fatima', 'noor.fatima@example.com', '03112345678', 'Female', 30, 5),
(46, 'Shoaib Khan', 'shoaib.khan@example.com', '03029876543', 'Male', 28, 1),
(47, 'Hafsa Iqbal', 'hafsa.iqbal@example.com', '03451230987', 'Female', 35, 2),
(48, 'Imran Javed', 'imran.javed@example.com', '03129874567', 'Male', 42, 3),
(49, 'Sana Baloch', 'sana.baloch@example.com', '03337651234', 'Female', 27, 4),
(50, 'Farhan Zafar', 'farhan.zafar@example.com', '03019872345', 'Male', 32, 5),
(51, 'Danish Ahmed', 'danish.ahmed@example.com', '03216543210', 'Male', 40, 1),
(52, 'Maria Khan', 'maria.khan@example.com', '03131234567', 'Female', 29, 3),
(53, 'Ali Raza', 'ali.raza@example.com', '03451239876', 'Male', 33, 2),
(54, 'Hira Qureshi', 'hira.qureshi@example.com', '03019876543', 'Female', 24, 4),
(55, 'Tariq Mehmood', 'tariq.mehmood@example.com', '03131237890', 'Male', 45, 5),
(56, 'Zoya Siddiqui', 'zoya.siddiqui@example.com', '03012349876', 'Female', 31, 1),
(57, 'Kashif Nawaz', 'kashif.nawaz@example.com', '03451237654', 'Male', 36, 2),
(58, 'Areeba Noor', 'areeba.noor@example.com', '03336549876', 'Female', 23, 3),
(59, 'Osman Zubair', 'osman.zubair@example.com', '03217654321', 'Male', 39, 4),
(60, 'Noor Fatima', 'noor.fatima@example.com', '03112345678', 'Female', 30, 5),
(61, 'Shoaib Khan', 'shoaib.khan@example.com', '03029876543', 'Male', 28, 1),
(62, 'Hafsa Iqbal', 'hafsa.iqbal@example.com', '03451230987', 'Female', 35, 2),
(63, 'Imran Javed', 'imran.javed@example.com', '03129874567', 'Male', 42, 3),
(64, 'Sana Baloch', 'sana.baloch@example.com', '03337651234', 'Female', 27, 4),
(65, 'Farhan Zafar', 'farhan.zafar@example.com', '03019872345', 'Male', 32, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
