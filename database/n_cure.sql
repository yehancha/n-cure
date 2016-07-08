-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2016 at 06:20 PM
-- Server version: 5.6.16-log
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `n_cure`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE IF NOT EXISTS `appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(4) NOT NULL,
  `time` datetime NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `user_id`, `time`, `description`) VALUES
(1, '1111', '2016-05-28 00:10:00', 'Test3'),
(2, '1111', '2016-05-28 00:10:00', 'Test3'),
(3, '1111', '2016-05-28 00:10:00', 'Test4'),
(4, '1111', '2016-05-28 00:10:00', 'Test4'),
(5, '1111', '2016-05-28 00:10:00', 'Test5');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE IF NOT EXISTS `patient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `description` text,
  `disease` varchar(255) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `name`, `address`, `city`, `description`, `disease`, `last_updated`) VALUES
(1, 'Navin', 'Chathura', 'Divulapitiya', 'No illness', 'Nothing', '2016-05-29 09:14:05'),
(2, 'Yehan', 'Chathura', 'Divulapitiya', 'No illness', 'Nothing', '2016-05-29 09:12:43'),
(3, 'Yehan', 'Chathura', 'Divulapitiya', 'No illness', 'Nothing', '2016-05-29 09:13:33'),
(4, 'Yehan', 'Chathura', 'Divulapitiya', 'No illness', 'Nothing', '2016-05-29 09:14:05'),
(5, 'Yehan', 'Chathura', 'Divulapitiya', 'No illness', 'Nothing', '2016-05-29 09:14:22'),
(6, 'Yehan', 'Chathura', '', 'No illness', 'Nothing', '2016-05-29 09:25:36'),
(7, 'Yehan', 'Chathura', 'Divulapitiya', 'No illness', 'Nothing', '2016-05-29 09:18:37'),
(8, 'Yehan', 'Chathura', 'Divulapitiya', 'No illness', 'Nothing', '2016-05-29 09:20:08'),
(9, 'Yehan', 'Chathura', 'Divulapitiya', 'No illness', 'Nothing', '2016-05-29 09:23:08'),
(10, 'Navin', 'Chathura', 'div', 'illness', 'Nothing', '2016-05-29 09:25:24'),
(11, 'Navin', 'Chathura', 'div', 'illness', 'Nothing', '2016-05-29 09:25:36'),
(12, 'Navin', 'Chathura', 'div', 'illness', 'Nothing', '2016-05-29 09:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `patient_id` int(11) NOT NULL,
  `photo_index` int(11) NOT NULL,
  `image` mediumblob NOT NULL,
  PRIMARY KEY (`patient_id`,`photo_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `last_logged_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `last_logged_in`) VALUES
('1111', 'Yehan', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
