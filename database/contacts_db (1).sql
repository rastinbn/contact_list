-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2025 at 11:35 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `contacts_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts_user`
--

CREATE TABLE `contacts_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contacts_user`
--

INSERT INTO `contacts_user` (`id`, `username`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(8, 'rastinbn', 'rastinbanitaba2007@gmail.com', '$2y$10$TZ8pk6V.bXBV3Abkh3AWJe.5.Z9GKgrKC35Pcd4QMpYXFhRVRauyG', NULL, '2025-07-27 09:17:04', '2025-07-27 09:17:04'),
(9, 'amirreza', 'amirreza@gamil.com', '$2y$10$MMP4IwRuLlkc.Ln6Kd1uauRndbbCLaf/EgUhkesdMLSirhTWjVcIK', NULL, '2025-07-27 09:32:13', '2025-07-27 09:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `contacts_info`
--

CREATE TABLE `contacts_info` (
  `id_contact` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstname_contact` varchar(100) NOT NULL,
  `lastname_contact` varchar(100) NOT NULL,
  `photo_contact` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contacts_info`
--

INSERT INTO `contacts_info` (`id_contact`, `user_id`, `firstname_contact`, `lastname_contact`, `photo_contact`, `created_at`, `updated_at`) VALUES
(37, 8, 'rastin', 'asd', 'uploads/contact_687f50d6553ec1.33508033.png', '2025-07-27 09:35:00', '2025-07-27 09:35:00'),
(38, 8, 'rastin', 'asd', NULL, '2025-07-27 09:35:00', '2025-07-27 09:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `contact_numbers`
--

CREATE TABLE `contact_numbers` (
  `id_number` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `number_contact` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact_numbers`
--

INSERT INTO `contact_numbers` (`id_number`, `contact_id`, `number_contact`, `created_at`) VALUES
(43, 37, '9129998855', '2025-07-27 09:35:00'),
(45, 38, '09995552366', '2025-07-27 09:35:00'),
(46, 38, '09145552233', '2025-07-27 09:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `contact_social_media`
--

CREATE TABLE `contact_social_media` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `platform` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts_user`
--
ALTER TABLE `contacts_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `remember_token` (`remember_token`);

--
-- Indexes for table `contacts_info`
--
ALTER TABLE `contacts_info`
  ADD PRIMARY KEY (`id_contact`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_numbers`
--
ALTER TABLE `contact_numbers`
  ADD PRIMARY KEY (`id_number`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `contact_social_media`
--
ALTER TABLE `contact_social_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts_user`
--
ALTER TABLE `contacts_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contacts_info`
--
ALTER TABLE `contacts_info`
  MODIFY `id_contact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `contact_numbers`
--
ALTER TABLE `contact_numbers`
  MODIFY `id_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `contact_social_media`
--
ALTER TABLE `contact_social_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts_info`
--
ALTER TABLE `contacts_info`
  ADD CONSTRAINT `contacts_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `contacts_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_numbers`
--
ALTER TABLE `contact_numbers`
  ADD CONSTRAINT `contact_numbers_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts_info` (`id_contact`) ON DELETE CASCADE;

--
-- Constraints for table `contact_social_media`
--
ALTER TABLE `contact_social_media`
  ADD CONSTRAINT `contact_social_media_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts_info` (`id_contact`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
