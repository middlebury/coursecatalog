-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 12, 2010 at 11:21 AM
-- Server version: 5.0.77
-- PHP Version: 5.2.12

--
-- Database: `afranco_courses_banner`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_savedcourses`
--

CREATE TABLE IF NOT EXISTS `user_savedcourses` (
  `user_id` varchar(100) NOT NULL,
  `course_id_keyword` varchar(40) NOT NULL,
  `course_id_authority` varchar(20) NOT NULL,
  `course_id_namespace` varchar(10) NOT NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`user_id`,`course_id_keyword`,`course_id_authority`,`course_id_namespace`),
  KEY `updated` (`updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Courses bookmarked by users.';

-- --------------------------------------------------------

--
-- Table structure for table `user_schedules`
--

CREATE TABLE IF NOT EXISTS `user_schedules` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` varchar(100) NOT NULL,
  `term_id_keyword` varchar(40) NOT NULL,
  `term_id_authority` varchar(20) NOT NULL,
  `term_id_namespace` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_schedule_offerings`
--

CREATE TABLE IF NOT EXISTS `user_schedule_offerings` (
  `schedule_id` int(10) unsigned NOT NULL,
  `offering_id_keyword` varchar(40) NOT NULL,
  `offering_id_authority` varchar(20) NOT NULL,
  `offering_id_namespace` varchar(10) NOT NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`schedule_id`,`offering_id_keyword`,`offering_id_authority`,`offering_id_namespace`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Offerings attached to a schedule';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_schedule_offerings`
--
ALTER TABLE `user_schedule_offerings`
  ADD CONSTRAINT `user_schedule_offerings_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `user_schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
