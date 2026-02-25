-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 08:54 AM
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
-- Database: `floodguard_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `affected_areas`
--

CREATE TABLE `affected_areas` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(150) NOT NULL,
  `current_level` decimal(5,2) NOT NULL DEFAULT 0.00,
  `max_level` decimal(5,2) NOT NULL DEFAULT 0.00,
  `speed` decimal(5,2) NOT NULL DEFAULT 0.00,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `affected_areas`
--

INSERT INTO `affected_areas` (`id`, `name`, `location`, `current_level`, `max_level`, `speed`, `updated_at`) VALUES
(1, 'Alangilan Bridge', 'Barangay Alangilan', 6.50, 26.40, 0.80, '2026-02-19 15:09:51'),
(2, 'Banago Bridge I', 'Barangay Banago', 2.00, 9.00, 0.30, '2026-02-19 14:18:40'),
(3, 'Cabalagnan Bridge I', 'Barangay Tangub', 5.50, 12.30, 0.50, '2026-02-19 14:18:40'),
(4, 'Lupit Bridge I', 'Barangay Singcang', 20.00, 46.65, 1.20, '2026-02-19 14:18:40'),
(5, 'Magsungay Bridge I', 'Barangay 10', 22.50, 28.70, 1.50, '2026-02-19 14:18:40'),
(6, 'Mambuloc Bridge II', 'Barangay 11', 3.00, 12.40, 0.40, '2026-02-19 14:18:40'),
(7, 'Mandalagan Bridge I', 'Barangay Mandalagan', 10.50, 27.20, 0.90, '2026-02-19 14:18:40'),
(8, 'Pahanocoy Bridge I', 'Barangay Pahanocoy', 20.00, 25.50, 1.80, '2026-02-19 14:18:40'),
(9, 'Pahanocoy Bridge II', 'Barangay Pahanocoy', 6.50, 8.50, 2.10, '2026-02-19 14:18:40'),
(10, 'Sum-ag Bridge I', 'Barangay Sum-ag', 18.00, 70.20, 0.70, '2026-02-19 14:18:40'),
(11, 'Sum-ag Bridge II', 'Barangay Sum-ag', 55.00, 71.65, 2.50, '2026-02-19 14:18:40'),
(12, 'Tangub Bridge I', 'Barangay Tangub', 3.00, 7.15, 0.20, '2026-02-19 14:18:40');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `status` enum('Safe','In Danger','Alert','Danger') NOT NULL,
  `description` text DEFAULT NULL,
  `image` mediumblob DEFAULT NULL,
  `post_news` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_email`, `location`, `status`, `description`, `image`, `post_news`, `created_at`) VALUES
(5, 'germenio123@gmail.com', 'Brgy. Banago, Prk. Kawayan, St. Patricio Street', 'Safe', 'The water is rising rapidly here', NULL, 0, '2026-02-20 00:00:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` varchar(32) DEFAULT 'Safe'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `password_hash`, `role`, `created_at`, `profile_photo`, `status`) VALUES
(68, 'User', 'User', 'user@floodguard.com', '09123456789', 'Bacolod City', '$2y$10$ZbOb1XDHI/eezWx0yzck.e6eWWFODbz.zEybV5MmxDohQOiv/.BfG', 'user', '2026-02-18 14:57:09', NULL, 'Safe'),
(71, 'Admin', 'User', 'admin@floodguard.com', '', '', '$2y$10$I3gw6xTm35wsTdUqlmcpEuDhZiE9gxvOwUejnMiUVMiHXw2BeQq1K', 'admin', '2026-02-18 14:57:57', NULL, 'Safe'),
(361, 'Germenio', 'Dalida', 'germenio123@gmail.com', '09928512979', 'Banago, Prk. Kawayan, St. Patricio Street', '$2y$10$8bHw/alqp9hfreN7HuQ9j.7.3aIyajiml.RyRK.D3BJGsIAjsLMri', 'user', '2026-02-19 09:17:35', NULL, 'Safe'),
(363, 'Tim', 'Chavez', 'timchavez@gmail.com', '09292282431', 'Mansilingan, Blk. 10', '$2y$10$Yrf.1BY4XCrU3wky34DC5uFbrcdns0t3IFA5P55h3zmrAnfcBiDqW', 'user', '2026-02-19 09:30:47', NULL, 'Safe'),
(1031, 'Germenio III', 'Dalida', 'germenio111@gmail.com', '09292282431', 'Brgy. Banago, Prk. Kawayan, St. Patricio Street', '$2y$10$GHJR3.ZiWGdwNdLXrRP5quj5a32mANt95h9Q2IFXnIA4L83yaB4ka', 'user', '2026-02-25 07:51:59', NULL, 'Safe');

-- --------------------------------------------------------

--
-- Table structure for table `water_level_history`
--

CREATE TABLE `water_level_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `area` varchar(100) NOT NULL,
  `trend` enum('steady','rising','falling') NOT NULL DEFAULT 'steady',
  `record_time` datetime NOT NULL,
  `height` decimal(5,2) NOT NULL DEFAULT 0.00,
  `speed` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` enum('warning','danger','critical') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `water_level_history`
--

INSERT INTO `water_level_history` (`id`, `area`, `trend`, `record_time`, `height`, `speed`, `status`) VALUES
(1, 'Alangilan Bridge', 'steady', '2026-02-19 12:45:09', 4.80, 0.60, 'warning'),
(2, 'Alangilan Bridge', 'rising', '2026-02-19 13:45:09', 5.00, 0.70, 'warning'),
(3, 'Alangilan Bridge', 'rising', '2026-02-19 14:45:09', 5.20, 0.70, 'warning'),
(4, 'Alangilan Bridge', 'rising', '2026-02-19 15:45:09', 5.40, 0.80, 'warning'),
(5, 'Alangilan Bridge', 'steady', '2026-02-19 16:45:09', 5.40, 0.80, 'warning'),
(6, 'Alangilan Bridge', 'rising', '2026-02-19 17:45:09', 5.50, 0.80, 'warning'),
(7, 'Alangilan Bridge', 'steady', '2026-02-19 18:45:09', 5.50, 0.80, 'warning'),
(8, 'Alangilan Bridge', 'falling', '2026-02-19 19:45:09', 5.30, 0.70, 'warning'),
(9, 'Alangilan Bridge', 'falling', '2026-02-19 20:45:09', 5.10, 0.70, 'warning'),
(10, 'Alangilan Bridge', 'steady', '2026-02-19 21:45:09', 5.10, 0.70, 'warning'),
(11, 'Banago Bridge I', 'steady', '2026-02-19 12:45:09', 1.80, 0.20, 'warning'),
(12, 'Banago Bridge I', 'rising', '2026-02-19 13:45:09', 1.90, 0.30, 'warning'),
(13, 'Banago Bridge I', 'rising', '2026-02-19 14:45:09', 2.00, 0.30, 'warning'),
(14, 'Banago Bridge I', 'steady', '2026-02-19 15:45:09', 2.00, 0.30, 'warning'),
(15, 'Banago Bridge I', 'rising', '2026-02-19 16:45:09', 2.10, 0.30, 'warning'),
(16, 'Banago Bridge I', 'rising', '2026-02-19 17:45:09', 2.20, 0.40, 'warning'),
(17, 'Banago Bridge I', 'steady', '2026-02-19 18:45:09', 2.20, 0.40, 'warning'),
(18, 'Banago Bridge I', 'falling', '2026-02-19 19:45:09', 2.10, 0.30, 'warning'),
(19, 'Banago Bridge I', 'falling', '2026-02-19 20:45:09', 2.00, 0.30, 'warning'),
(20, 'Banago Bridge I', 'steady', '2026-02-19 21:45:09', 2.00, 0.30, 'warning'),
(21, 'Cabalagnan Bridge I', 'rising', '2026-02-19 12:45:09', 4.80, 0.40, 'danger'),
(22, 'Cabalagnan Bridge I', 'rising', '2026-02-19 13:45:09', 5.00, 0.40, 'danger'),
(23, 'Cabalagnan Bridge I', 'rising', '2026-02-19 14:45:09', 5.20, 0.50, 'danger'),
(24, 'Cabalagnan Bridge I', 'steady', '2026-02-19 15:45:09', 5.20, 0.50, 'danger'),
(25, 'Cabalagnan Bridge I', 'rising', '2026-02-19 16:45:09', 5.40, 0.50, 'danger'),
(26, 'Cabalagnan Bridge I', 'steady', '2026-02-19 17:45:09', 5.50, 0.50, 'danger'),
(27, 'Cabalagnan Bridge I', 'falling', '2026-02-19 18:45:09', 5.30, 0.50, 'danger'),
(28, 'Cabalagnan Bridge I', 'falling', '2026-02-19 19:45:09', 5.10, 0.40, 'danger'),
(29, 'Cabalagnan Bridge I', 'steady', '2026-02-19 20:45:09', 5.10, 0.40, 'danger'),
(30, 'Cabalagnan Bridge I', 'rising', '2026-02-19 21:45:09', 5.30, 0.50, 'danger'),
(31, 'Lupit Bridge I', 'rising', '2026-02-19 12:45:09', 18.00, 1.00, 'danger'),
(32, 'Lupit Bridge I', 'rising', '2026-02-19 13:45:09', 18.50, 1.10, 'danger'),
(33, 'Lupit Bridge I', 'rising', '2026-02-19 14:45:09', 19.00, 1.10, 'danger'),
(34, 'Lupit Bridge I', 'steady', '2026-02-19 15:45:09', 19.00, 1.10, 'danger'),
(35, 'Lupit Bridge I', 'rising', '2026-02-19 16:45:09', 19.50, 1.20, 'danger'),
(36, 'Lupit Bridge I', 'rising', '2026-02-19 17:45:09', 20.00, 1.20, 'danger'),
(37, 'Lupit Bridge I', 'steady', '2026-02-19 18:45:09', 20.00, 1.20, 'danger'),
(38, 'Lupit Bridge I', 'falling', '2026-02-19 19:45:09', 19.50, 1.10, 'danger'),
(39, 'Lupit Bridge I', 'falling', '2026-02-19 20:45:09', 19.00, 1.10, 'danger'),
(40, 'Lupit Bridge I', 'steady', '2026-02-19 21:45:09', 19.00, 1.10, 'danger'),
(41, 'Magsungay Bridge I', 'rising', '2026-02-19 12:45:09', 21.00, 1.30, 'critical'),
(42, 'Magsungay Bridge I', 'rising', '2026-02-19 13:45:09', 21.50, 1.40, 'critical'),
(43, 'Magsungay Bridge I', 'rising', '2026-02-19 14:45:09', 22.00, 1.40, 'critical'),
(44, 'Magsungay Bridge I', 'rising', '2026-02-19 15:45:09', 22.50, 1.50, 'critical'),
(45, 'Magsungay Bridge I', 'steady', '2026-02-19 16:45:09', 22.50, 1.50, 'critical'),
(46, 'Magsungay Bridge I', 'rising', '2026-02-19 17:45:09', 22.80, 1.50, 'critical'),
(47, 'Magsungay Bridge I', 'steady', '2026-02-19 18:45:09', 22.80, 1.50, 'critical'),
(48, 'Magsungay Bridge I', 'falling', '2026-02-19 19:45:09', 22.50, 1.40, 'critical'),
(49, 'Magsungay Bridge I', 'falling', '2026-02-19 20:45:09', 22.20, 1.40, 'critical'),
(50, 'Magsungay Bridge I', 'steady', '2026-02-19 21:45:09', 22.20, 1.40, 'critical'),
(51, 'Mambuloc Bridge II', 'steady', '2026-02-19 12:45:09', 2.80, 0.30, 'warning'),
(52, 'Mambuloc Bridge II', 'rising', '2026-02-19 13:45:09', 2.90, 0.40, 'warning'),
(53, 'Mambuloc Bridge II', 'rising', '2026-02-19 14:45:09', 3.00, 0.40, 'warning'),
(54, 'Mambuloc Bridge II', 'steady', '2026-02-19 15:45:09', 3.00, 0.40, 'warning'),
(55, 'Mambuloc Bridge II', 'rising', '2026-02-19 16:45:09', 3.10, 0.40, 'warning'),
(56, 'Mambuloc Bridge II', 'steady', '2026-02-19 17:45:09', 3.10, 0.40, 'warning'),
(57, 'Mambuloc Bridge II', 'falling', '2026-02-19 18:45:09', 3.00, 0.40, 'warning'),
(58, 'Mambuloc Bridge II', 'falling', '2026-02-19 19:45:09', 2.90, 0.30, 'warning'),
(59, 'Mambuloc Bridge II', 'steady', '2026-02-19 20:45:09', 2.90, 0.30, 'warning'),
(60, 'Mambuloc Bridge II', 'rising', '2026-02-19 21:45:09', 3.00, 0.40, 'warning'),
(61, 'Mandalagan Bridge I', 'rising', '2026-02-19 12:45:09', 9.50, 0.80, 'danger'),
(62, 'Mandalagan Bridge I', 'rising', '2026-02-19 13:45:09', 9.80, 0.80, 'danger'),
(63, 'Mandalagan Bridge I', 'rising', '2026-02-19 14:45:09', 10.00, 0.90, 'danger'),
(64, 'Mandalagan Bridge I', 'steady', '2026-02-19 15:45:09', 10.00, 0.90, 'danger'),
(65, 'Mandalagan Bridge I', 'rising', '2026-02-19 16:45:09', 10.20, 0.90, 'danger'),
(66, 'Mandalagan Bridge I', 'rising', '2026-02-19 17:45:09', 10.50, 0.90, 'danger'),
(67, 'Mandalagan Bridge I', 'steady', '2026-02-19 18:45:09', 10.50, 0.90, 'danger'),
(68, 'Mandalagan Bridge I', 'falling', '2026-02-19 19:45:09', 10.20, 0.80, 'danger'),
(69, 'Mandalagan Bridge I', 'falling', '2026-02-19 20:45:09', 10.00, 0.80, 'danger'),
(70, 'Mandalagan Bridge I', 'steady', '2026-02-19 21:45:09', 10.00, 0.90, 'danger'),
(71, 'Pahanocoy Bridge I', 'rising', '2026-02-19 12:45:09', 18.50, 1.60, 'critical'),
(72, 'Pahanocoy Bridge I', 'rising', '2026-02-19 13:45:09', 19.00, 1.60, 'critical'),
(73, 'Pahanocoy Bridge I', 'rising', '2026-02-19 14:45:09', 19.50, 1.70, 'critical'),
(74, 'Pahanocoy Bridge I', 'steady', '2026-02-19 15:45:09', 19.50, 1.70, 'critical'),
(75, 'Pahanocoy Bridge I', 'rising', '2026-02-19 16:45:09', 20.00, 1.80, 'critical'),
(76, 'Pahanocoy Bridge I', 'steady', '2026-02-19 17:45:09', 20.00, 1.80, 'critical'),
(77, 'Pahanocoy Bridge I', 'rising', '2026-02-19 18:45:09', 20.50, 1.80, 'critical'),
(78, 'Pahanocoy Bridge I', 'falling', '2026-02-19 19:45:09', 20.20, 1.70, 'critical'),
(79, 'Pahanocoy Bridge I', 'falling', '2026-02-19 20:45:09', 20.00, 1.70, 'critical'),
(80, 'Pahanocoy Bridge I', 'steady', '2026-02-19 21:45:09', 20.00, 1.80, 'critical'),
(81, 'Pahanocoy Bridge II', 'rising', '2026-02-19 12:45:09', 6.00, 1.90, 'critical'),
(82, 'Pahanocoy Bridge II', 'rising', '2026-02-19 13:45:09', 6.10, 1.90, 'critical'),
(83, 'Pahanocoy Bridge II', 'rising', '2026-02-19 14:45:09', 6.20, 2.00, 'critical'),
(84, 'Pahanocoy Bridge II', 'steady', '2026-02-19 15:45:09', 6.20, 2.00, 'critical'),
(85, 'Pahanocoy Bridge II', 'rising', '2026-02-19 16:45:09', 6.40, 2.00, 'critical'),
(86, 'Pahanocoy Bridge II', 'rising', '2026-02-19 17:45:09', 6.50, 2.10, 'critical'),
(87, 'Pahanocoy Bridge II', 'steady', '2026-02-19 18:45:09', 6.50, 2.10, 'critical'),
(88, 'Pahanocoy Bridge II', 'falling', '2026-02-19 19:45:09', 6.30, 2.00, 'critical'),
(89, 'Pahanocoy Bridge II', 'falling', '2026-02-19 20:45:09', 6.20, 2.00, 'critical'),
(90, 'Pahanocoy Bridge II', 'steady', '2026-02-19 21:45:09', 6.20, 2.00, 'critical'),
(91, 'Sum-ag Bridge I', 'steady', '2026-02-19 12:45:09', 16.00, 0.60, 'warning'),
(92, 'Sum-ag Bridge I', 'rising', '2026-02-19 13:45:09', 16.50, 0.60, 'warning'),
(93, 'Sum-ag Bridge I', 'rising', '2026-02-19 14:45:09', 17.00, 0.70, 'warning'),
(94, 'Sum-ag Bridge I', 'steady', '2026-02-19 15:45:09', 17.00, 0.70, 'warning'),
(95, 'Sum-ag Bridge I', 'rising', '2026-02-19 16:45:09', 17.50, 0.70, 'warning'),
(96, 'Sum-ag Bridge I', 'steady', '2026-02-19 17:45:09', 17.50, 0.70, 'warning'),
(97, 'Sum-ag Bridge I', 'falling', '2026-02-19 18:45:09', 17.20, 0.60, 'warning'),
(98, 'Sum-ag Bridge I', 'falling', '2026-02-19 19:45:09', 17.00, 0.60, 'warning'),
(99, 'Sum-ag Bridge I', 'steady', '2026-02-19 20:45:09', 17.00, 0.60, 'warning'),
(100, 'Sum-ag Bridge I', 'rising', '2026-02-19 21:45:09', 18.00, 0.70, 'warning'),
(101, 'Sum-ag Bridge II', 'rising', '2026-02-19 12:45:09', 52.00, 2.20, 'critical'),
(102, 'Sum-ag Bridge II', 'rising', '2026-02-19 13:45:09', 53.00, 2.30, 'critical'),
(103, 'Sum-ag Bridge II', 'rising', '2026-02-19 14:45:09', 54.00, 2.30, 'critical'),
(104, 'Sum-ag Bridge II', 'steady', '2026-02-19 15:45:09', 54.00, 2.30, 'critical'),
(105, 'Sum-ag Bridge II', 'rising', '2026-02-19 16:45:09', 55.00, 2.40, 'critical'),
(106, 'Sum-ag Bridge II', 'rising', '2026-02-19 17:45:09', 55.50, 2.40, 'critical'),
(107, 'Sum-ag Bridge II', 'steady', '2026-02-19 18:45:09', 55.50, 2.50, 'critical'),
(108, 'Sum-ag Bridge II', 'falling', '2026-02-19 19:45:09', 55.00, 2.40, 'critical'),
(109, 'Sum-ag Bridge II', 'falling', '2026-02-19 20:45:09', 54.50, 2.40, 'critical'),
(110, 'Sum-ag Bridge II', 'steady', '2026-02-19 21:45:09', 54.50, 2.40, 'critical'),
(111, 'Tangub Bridge I', 'rising', '2026-02-19 12:45:09', 2.70, 0.20, 'danger'),
(112, 'Tangub Bridge I', 'rising', '2026-02-19 13:45:09', 2.80, 0.20, 'danger'),
(113, 'Tangub Bridge I', 'rising', '2026-02-19 14:45:09', 2.90, 0.20, 'danger'),
(114, 'Tangub Bridge I', 'steady', '2026-02-19 15:45:09', 2.90, 0.20, 'danger'),
(115, 'Tangub Bridge I', 'rising', '2026-02-19 16:45:09', 3.00, 0.20, 'danger'),
(116, 'Tangub Bridge I', 'steady', '2026-02-19 17:45:09', 3.00, 0.20, 'danger'),
(117, 'Tangub Bridge I', 'falling', '2026-02-19 18:45:09', 2.90, 0.20, 'danger'),
(118, 'Tangub Bridge I', 'falling', '2026-02-19 19:45:09', 2.80, 0.20, 'danger'),
(119, 'Tangub Bridge I', 'steady', '2026-02-19 20:45:09', 2.80, 0.20, 'danger'),
(120, 'Tangub Bridge I', 'rising', '2026-02-19 21:45:09', 3.00, 0.20, 'danger');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `affected_areas`
--
ALTER TABLE `affected_areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `water_level_history`
--
ALTER TABLE `water_level_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area` (`area`),
  ADD KEY `record_time` (`record_time`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `affected_areas`
--
ALTER TABLE `affected_areas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1049;

--
-- AUTO_INCREMENT for table `water_level_history`
--
ALTER TABLE `water_level_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
