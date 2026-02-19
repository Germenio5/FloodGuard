-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2026 at 01:17 PM
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
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(150) NOT NULL,
  `current_level` decimal(5,2) NOT NULL DEFAULT 0.00,
  `max_level` decimal(5,2) NOT NULL DEFAULT 0.00,
  `speed` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` enum('normal','alert','danger') NOT NULL DEFAULT 'normal',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `status` enum('Safe','In Danger','Alert','Danger') NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `post_news` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Admin', 'User', 'admin@floodguard.com', '0000000000', 'Headquarters', 'admin1234', 'admin', '2026-02-13 12:21:15'),
(2, 'User', 'User', 'user@floodguard.com', '09123456789', 'Brgy. Banago', 'user1234', 'user', '2026-02-13 12:22:56'),
(4, 'Tim', 'Chavez', 'timchavez@gmail.com', '09676767676', 'Brgy. Mansilingan, Bacolod City', '$2y$10$Tsk.4/nWelsiGhyVSoueiuTioqfas9IEQvWLmrOL0DU/AFAJaAM8e', 'user', '2026-02-15 12:14:56');

-- --------------------------------------------------------

--
-- Table structure for table `water_level_history`
--

CREATE TABLE `water_level_history` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `area` varchar(100) NOT NULL,
  `trend` enum('steady','rising','falling') NOT NULL DEFAULT 'steady',
  `record_time` datetime NOT NULL,
  `height` decimal(5,2) NOT NULL DEFAULT 0.00,
  `speed` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` enum('normal','alert','danger') NOT NULL DEFAULT 'normal',
  PRIMARY KEY (`id`),
  KEY `area` (`area`),
  KEY `record_time` (`record_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
