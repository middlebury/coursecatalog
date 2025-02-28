-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Dec 20, 2024 at 08:41 PM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `symfony`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive_configurations`
--

CREATE TABLE `archive_configurations` (
  `id` int(11) NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `catalog_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_configuration_revisions`
--

CREATE TABLE `archive_configuration_revisions` (
  `id` int(11) NOT NULL,
  `arch_conf_id` int(11) DEFAULT NULL,
  `note` varchar(100) DEFAULT NULL,
  `last_saved` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` varchar(100) DEFAULT NULL,
  `user_disp_name` varchar(100) DEFAULT NULL,
  `json_data` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_export_progress`
--

CREATE TABLE `archive_export_progress` (
  `pid` int(11) NOT NULL,
  `progress` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_jobs`
--

CREATE TABLE `archive_jobs` (
  `id` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `export_path` varchar(100) DEFAULT NULL,
  `config_id` int(11) DEFAULT NULL,
  `revision_id` int(11) DEFAULT NULL,
  `terms` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive_configurations`
--
ALTER TABLE `archive_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archive_configuration_revisions`
--
ALTER TABLE `archive_configuration_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arch_conf_id` (`arch_conf_id`);

--
-- Indexes for table `archive_export_progress`
--
ALTER TABLE `archive_export_progress`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `archive_jobs`
--
ALTER TABLE `archive_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `config_id` (`config_id`),
  ADD KEY `revision_id` (`revision_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive_configurations`
--
ALTER TABLE `archive_configurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archive_configuration_revisions`
--
ALTER TABLE `archive_configuration_revisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archive_jobs`
--
ALTER TABLE `archive_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `archive_configuration_revisions`
--
ALTER TABLE `archive_configuration_revisions`
  ADD CONSTRAINT `__archive_configuration_revisions_ibfk_1` FOREIGN KEY (`arch_conf_id`) REFERENCES `archive_configurations` (`id`);

--
-- Constraints for table `archive_jobs`
--
ALTER TABLE `archive_jobs`
  ADD CONSTRAINT `__archive_jobs_ibfk_1` FOREIGN KEY (`config_id`) REFERENCES `archive_configurations` (`id`),
  ADD CONSTRAINT `__archive_jobs_ibfk_2` FOREIGN KEY (`revision_id`) REFERENCES `archive_configuration_revisions` (`id`);
COMMIT;
