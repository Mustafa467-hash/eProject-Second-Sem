-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2025 at 08:29 PM
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
(1, 18, 1, '2025-07-27', '14:00:00', 'completed', '2025-07-14 19:00:00'),
(3, 1, 3, '2025-07-21', '09:00:00', 'confirmed', '2025-07-12 19:00:00'),
(4, 7, 4, '2025-07-23', '09:30:00', 'cancelled', '2025-07-14 19:00:00'),
(6, 7, 6, '2025-07-25', '09:30:00', 'confirmed', '2025-07-12 19:00:00'),
(7, 5, 7, '2025-07-26', '12:00:00', 'pending', '2025-07-12 19:00:00'),
(10, 8, 10, '2025-07-21', '15:15:00', 'completed', '2025-07-16 19:00:00'),
(11, 15, 11, '2025-07-27', '13:00:00', 'completed', '2025-07-13 19:00:00'),
(12, 17, 12, '2025-07-19', '11:00:00', 'completed', '2025-07-16 19:00:00'),
(13, 6, 13, '2025-07-19', '16:45:00', 'pending', '2025-07-15 19:00:00'),
(16, 4, 16, '2025-07-20', '14:45:00', 'confirmed', '2025-07-13 19:00:00'),
(17, 3, 17, '2025-07-21', '12:30:00', 'completed', '2025-07-14 19:00:00'),
(18, 16, 18, '2025-07-21', '11:30:00', 'cancelled', '2025-07-12 19:00:00'),
(19, 11, 19, '2025-07-23', '13:15:00', 'confirmed', '2025-07-12 19:00:00'),
(20, 2, 20, '2025-07-20', '15:30:00', 'completed', '2025-07-15 19:00:00'),
(21, 13, 21, '2025-07-27', '11:30:00', 'confirmed', '2025-07-15 19:00:00'),
(22, 13, 22, '2025-07-24', '14:30:00', 'confirmed', '2025-07-12 19:00:00'),
(24, 4, 24, '2025-07-25', '12:00:00', 'completed', '2025-07-13 19:00:00'),
(25, 21, 25, '2025-07-24', '13:30:00', 'completed', '2025-07-13 19:00:00'),
(26, 20, 26, '2025-07-20', '16:45:00', 'cancelled', '2025-07-14 19:00:00'),
(27, 24, 27, '2025-07-19', '09:00:00', 'confirmed', '2025-07-14 19:00:00'),
(28, 22, 28, '2025-07-22', '14:15:00', 'confirmed', '2025-07-16 19:00:00'),
(30, 24, 30, '2025-07-23', '14:15:00', 'cancelled', '2025-07-14 19:00:00'),
(32, 3, 32, '2025-07-23', '11:00:00', 'pending', '2025-07-12 19:00:00'),
(35, 22, 35, '2025-07-19', '12:00:00', 'completed', '2025-07-13 19:00:00'),
(36, 9, 36, '2025-07-28', '14:30:00', 'cancelled', '2025-07-16 19:00:00'),
(37, 25, 37, '2025-07-20', '11:45:00', 'completed', '2025-07-12 19:00:00'),
(38, 4, 38, '2025-07-22', '13:00:00', 'pending', '2025-07-13 19:00:00'),
(39, 14, 39, '2025-07-23', '13:15:00', 'pending', '2025-07-13 19:00:00'),
(41, 13, 41, '2025-07-20', '09:00:00', 'completed', '2025-07-14 19:00:00'),
(42, 5, 42, '2025-07-19', '14:30:00', 'pending', '2025-07-15 19:00:00'),
(44, 23, 44, '2025-07-20', '10:45:00', 'confirmed', '2025-07-14 19:00:00'),
(48, 4, 48, '2025-07-19', '16:00:00', 'confirmed', '2025-07-15 19:00:00'),
(49, 10, 49, '2025-07-22', '10:30:00', 'pending', '2025-07-15 19:00:00'),
(52, 19, 52, '2025-07-23', '10:15:00', 'cancelled', '2025-07-14 19:00:00'),
(53, 7, 53, '2025-07-21', '11:15:00', 'pending', '2025-07-13 19:00:00'),
(55, 12, 55, '2025-07-25', '12:00:00', 'pending', '2025-07-14 19:00:00'),
(56, 20, 56, '2025-07-22', '09:00:00', 'cancelled', '2025-07-12 19:00:00'),
(57, 6, 57, '2025-07-23', '09:45:00', 'completed', '2025-07-16 19:00:00'),
(59, 21, 59, '2025-07-22', '14:45:00', 'cancelled', '2025-07-12 19:00:00'),
(60, 2, 60, '2025-07-27', '13:00:00', 'completed', '2025-07-14 19:00:00'),
(61, 3, 61, '2025-07-27', '16:00:00', 'confirmed', '2025-07-13 19:00:00'),
(62, 19, 62, '2025-07-23', '14:45:00', 'completed', '2025-07-16 19:00:00'),
(65, 15, 65, '2025-07-25', '12:00:00', 'completed', '2025-07-16 19:00:00'),
(66, 1, 22, '2025-07-04', '00:00:00', 'pending', '2025-07-24 16:48:59'),
(67, 1, 22, '2025-07-25', '13:00:00', 'pending', '2025-07-24 16:55:28'),
(68, 19, 22, '2025-06-11', '12:15:00', 'pending', '2025-07-24 17:07:35'),
(69, 19, 22, '2025-06-11', '12:15:00', 'pending', '2025-07-24 17:21:37'),
(70, 25, 22, '2025-07-31', '14:25:00', 'pending', '2025-07-24 17:21:56'),
(71, 21, 22, '2025-07-29', '10:22:00', 'pending', '2025-07-24 17:23:02');

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
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Numaish Thakur', 'tamer13381@ahaks.com', 'Overdue Bills', 'Skibidi toilet', '2025-07-24 18:26:05'),
(2, 'Numaish Thakur', 'tamer13381@ahaks.com', 'Overdue Bills', 'Skibidi toilet', '2025-07-24 18:28:02');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `email`, `phone`, `city_id`, `specialization`, `availability`, `password`, `created_at`, `image`) VALUES
(1, 'Dr. Sana Khan', 'sana.k@caregroup.pk', '0311-1234567', 1, 'Cardiologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_1.jpg'),
(2, 'Dr. Usman Ghani', 'usman.g@caregroup.pk', '0322-9876543', 1, 'Neurologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_2.jpg'),
(3, 'Dr. Mahnoor Ali', 'mahnoor.a@caregroup.pk', '0345-4567890', 1, 'Dermatologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_3.jpg'),
(4, 'Dr. Hamza Bashir', 'hamza.b@caregroup.pk', '0300-9988776', 2, 'General Physician', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_4.jpg'),
(5, 'Dr. Laiba Tariq', 'laiba.t@caregroup.pk', '0312-3344556', 2, 'Pediatrician', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_5.jpg'),
(6, 'Dr. Kamran Riaz', 'kamran.r@caregroup.pk', '0333-1122445', 2, 'Oncologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_6.jpg'),
(7, 'Dr. Sumbul Javed', 'sumbul.j@caregroup.pk', '0346-8899776', 2, 'ENT Specialist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_7.jpg'),
(8, 'Dr. Faisal Malik', 'faisal.m@caregroup.pk', '0301-2299884', 2, 'Urologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_8.jpg'),
(9, 'Dr. Amna Asif', 'amna.a@caregroup.pk', '0320-6655443', 2, 'Psychiatrist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_9.jpg'),
(10, 'Dr. Bilal Zahid', 'bilal.z@caregroup.pk', '0341-5544332', 3, 'Orthopedic Surgeon', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_10.jpg'),
(11, 'Dr. Hira Fatima', 'hira.f@caregroup.pk', '0309-7711223', 3, 'Endocrinologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_11.jpg'),
(12, 'Dr. Taimoor Iqbal', 'taimoor.i@caregroup.pk', '0317-7766554', 4, 'Pulmonologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_12.jpg'),
(13, 'Dr. Rabia Saeed', 'rabia.s@caregroup.pk', '0335-3344112', 4, 'Gynecologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_25.jpg'),
(14, 'Dr. Zain Abbas', 'zain.a@caregroup.pk', '0321-9988776', 4, 'Nephrologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_13.jpg'),
(15, 'Dr. Nadia Imran', 'nadia.i@caregroup.pk', '0302-1122112', 4, 'Hematologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_14.jpg'),
(16, 'Dr. Shahbaz Ali', 'shahbaz.a@caregroup.pk', '0344-5511223', 5, 'Ophthalmologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_15.jpg'),
(17, 'Dr. Maria Qureshi', 'maria.q@caregroup.pk', '0315-8877665', 5, 'Immunologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_16.jpg'),
(18, 'Dr. Ahsan Rafi', 'ahsan.r@caregroup.pk', '0308-3344556', 5, 'Radiologist', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:15:11', 'doctor_17.jpg'),
(19, 'Dr. Areeba Khalid', 'areeba.khalid@example.com', '0300-9900887', 1, 'Pulmonologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08', 'doctor_18.jpg'),
(20, 'Dr. Faizan Malik', 'faizan.malik@example.com', '0310-2233445', 1, 'General Physician', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08', 'doctor_19.jpg'),
(21, 'Dr. Mahnoor Rizvi', 'mahnoor.rizvi@example.com', '0331-5556677', 1, 'Endocrinologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08', 'doctor_20.jpg'),
(22, 'Dr. Shayan Ahmed', 'shayan.ahmed@example.com', '0323-4567890', 1, 'Pediatrician', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08', 'doctor_21.jpg'),
(23, 'Dr. Nadia Baig', 'nadia.baig@example.com', '0308-3344556', 1, 'Cardiologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08', 'doctor_22.jpg'),
(24, 'Dr. Osama Zubair', 'osama.zubair@example.com', '0311-1122334', 1, 'Orthopedic Surgeon', 'Mon, Wed, Fri: 9AM–5PM', '', '2025-07-11 13:19:08', 'doctor_23.jpg'),
(25, 'Dr. Zeeshan Ahmed', 'zeeshan.ahmed@example.com', '0345-7654321', 3, 'Rheumatologist', 'Tue, Thu, Sat: 9AM–5PM', '', '2025-07-11 13:19:08', 'doctor_24.jpg');

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
  `city_id` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `email`, `phone`, `gender`, `age`, `city_id`, `password`, `created_at`) VALUES
(1, 'Areeba Khalid', 'areeba.khalid@example.com', '0321-9483726', 'Female', 27, 2, 'MintFalcon#89', '2025-03-14 05:22:00'),
(2, 'Hamza Farooq', 'hamza.farooq@example.com', '0345-7263819', 'Male', 31, 1, 'SteelTiger!42', '2025-02-01 08:08:45'),
(3, 'Zara Shah', 'zara.shah@example.com', '0302-6683912', 'Female', 22, 3, 'GhostCobra$22', '2025-04-19 12:45:10'),
(4, 'Ali Haider', 'ali.haider@example.com', '0313-9847620', 'Male', 45, 4, 'ShadowDuck#71', '2025-01-10 04:16:33'),
(5, 'Mehwish Iqbal', 'mehwish.iqbal@example.com', '0331-7728391', 'Female', 34, 5, 'TwilightWolf$53', '2025-05-05 09:50:01'),
(6, 'Rehan Siddiqui', 'rehan.sid@example.com', '0300-1245789', 'Male', 39, 1, 'CobaltLion@99', '2025-04-23 07:12:12'),
(7, 'Sara Naveed', 'sara.naveed@example.com', '0320-5519932', 'Female', 26, 3, 'NeonFox#57', '2025-06-12 03:40:33'),
(8, 'Bilal Khan', 'bilal.khan@example.com', '0342-9988776', 'Male', 50, 2, 'LunarBear$64', '2025-03-28 11:30:00'),
(9, 'Emaan Ali', 'emaan.ali@example.com', '0301-8771223', 'Female', 29, 4, 'CrimsonPanda@45', '2025-02-17 06:05:05'),
(10, 'Usman Rauf', 'usman.rauf@example.com', '0333-6655443', 'Male', 38, 5, 'VelvetHawk$77', '2025-06-02 05:00:00'),
(11, 'Hiba Munir', 'hiba.munir@example.com', '0346-1122334', 'Female', 21, 3, 'GalaxyCat#31', '2025-05-30 14:41:26'),
(12, 'Junaid Qureshi', 'junaid.qureshi@example.com', '0315-2468101', 'Male', 28, 1, 'StormTiger!98', '2025-04-04 03:22:45'),
(13, 'Anaya Sohail', 'anaya.sohail@example.com', '0306-9090909', 'Female', 35, 2, 'BlazingKoala$21', '2025-05-15 10:13:00'),
(14, 'Taha Rizwan', 'taha.rizwan@example.com', '0348-3344556', 'Male', 44, 5, 'FrozenShark@69', '2025-03-01 02:55:13'),
(15, 'Nimra Shabbir', 'nimra.shabbir@example.com', '0322-8822113', 'Female', 40, 4, 'ElectricGoat#11', '2025-02-25 15:18:30'),
(16, 'Numaish Thakur', 'tamer13381@ahaks.com', '03311209631', NULL, NULL, 1, '$2y$10$xBMPTienEbw.bhRtwKUZ3.eUkHLYRc65crdRRwlqG9s97hQl2OP/i', '2025-07-18 20:58:42'),
(17, 'Syed Ibrahim', 'syedibrahim@ahaks.com', '03663245691', 'Male', 19, 8, '$2y$10$./YEs2arSpUE2T84NRb6p.gCe/9SRmtZpeyjx3I2F5aduV9JgLItK', '2025-07-20 19:29:31'),
(18, 'Muhammad Chadhary', 'mChaduary@gmail.com', '036645691', 'Male', 48, 1, '$2y$10$z0jw2B59r5tXkBmdQrZ3jurnT.GJpcpNTRS53tqoPMFY/wqMN3SS.', '2025-07-20 19:33:39'),
(19, 'Chat Gpt', 'Chat@gmail.com', '0101010101001', 'Other', 5, 4, '$2y$10$ShFsd125JZ7nx7/k9zlOeOu1vTNYGDo5Ot06CDRycqk.Glx55e4qS', '2025-07-20 19:35:09'),
(20, 'Asif Ali Zardari', 'Asifalizardari@gmail.com', '03665621462', 'Male', 69, 1, '$2y$10$Re3blEZnSEDobn1HRecLj.WW7r9gF2l5rZsh/JmtYKg1xvB9IIjuC', '2025-07-21 14:07:52'),
(21, 'Noman Raza', 'nomanraza123@gmail.com', '03310264958', 'Male', 35, 7, '$2y$10$kM2xtKeqvbkDjGtHU8n/WufoOIfzG4FMFGLT6RqrmODuNE46fGsDq', '2025-07-23 09:56:45'),
(22, 'Raza Ibrahim', 'IbrahimRaza@gmail.com', '03562497831', 'Male', 24, 4, '$2y$10$2hxkllTVl4gwD2IKeIR6BuiLvGxcCi/U/oWZu84EGYmxIqnS2dW1q', '2025-07-24 16:27:00');

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
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
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
  ADD UNIQUE KEY `email` (`email`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
