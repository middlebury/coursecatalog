-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: hammer
-- Generation Time: Jan 29, 2018 at 05:45 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 5.6.32

--
-- Database: `gselover_catalog`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive_configurations`
--

CREATE TABLE `archive_configurations` (
  `id` int(11) NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `catalog_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for table `archive_configurations`
--
ALTER TABLE `archive_configurations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `archive_configurations`
--
ALTER TABLE `archive_configurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Table structure for table `archive_configuration_revisions`
--

CREATE TABLE `archive_configuration_revisions` (
  `id` int(11) NOT NULL auto_increment,
  `arch_conf_id` int(11) DEFAULT NULL,
  `note` varchar(100) DEFAULT NULL,
  `last_saved` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` varchar(100) DEFAULT NULL,
  `user_disp_name` varchar(100) DEFAULT NULL,
  `json_data` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for table `archive_configuration_revisions`
--
ALTER TABLE `archive_configuration_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arch_conf_id` (`arch_conf_id`);

--
-- AUTO_INCREMENT for table `archive_configuration_revisions`
--
ALTER TABLE `archive_configuration_revisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Constraints for table `archive_configuration_revisions`
--
ALTER TABLE `archive_configuration_revisions`
  ADD CONSTRAINT `archive_configuration_revisions_ibfk_1` FOREIGN KEY (`arch_conf_id`) REFERENCES `archive_configurations` (`id`);

--
-- Table structure for table `archive_jobs`
--

CREATE TABLE `archive_jobs` (
  `id` int(11) NOT NULL auto_increment,
  `active` tinyint(1) DEFAULT NULL,
  `export_path` varchar(100) DEFAULT NULL,
  `config_id` int(11) DEFAULT NULL,
  `revision_id` int(11) DEFAULT NULL,
  `terms` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `archive_export_progress`
--

CREATE TABLE `archive_export_progress` (
  `pid` int(11) NOT NULL,
  `progress` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- AUTO_INCREMENT for table `archive_jobs`
--
ALTER TABLE `archive_configuration_revisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Constraints for table `archive_jobs`
--
ALTER TABLE `archive_jobs`
  ADD CONSTRAINT `archive_jobs_ibfk_1` FOREIGN KEY (`config_id`) REFERENCES `archive_configurations` (`id`),
  ADD CONSTRAINT `archive_jobs_ibfk_2` FOREIGN KEY (`revision_id`) REFERENCES `archive_configuration_revisions` (`id`);
