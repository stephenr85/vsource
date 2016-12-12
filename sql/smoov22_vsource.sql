-- phpMyAdmin SQL Dump
-- version 4.3.7
-- http://www.phpmyadmin.net
--
-- Host: 10.123.0.61:3309
-- Generation Time: Dec 10, 2016 at 03:41 PM
-- Server version: 5.5.38
-- PHP Version: 5.4.45-0+deb7u4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `smoov22_vsource`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `userID` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `validation_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registrationdate` date DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userID`, `first_name`, `last_name`, `email`, `password`, `validation_code`, `status`, `registrationdate`) VALUES
(63, 'Aaa', 'Aaaa', 'ooo@ooo.com', 'ooo', '310991', 'inactive', '2016-12-08'),
(62, 'Aaa', 'Aaaa', 'bbb@bbb.com', 'bbb', '400141', 'inactive', '2016-12-08'),
(60, 'demo', 'demo', 'demo@veolia.com', 'demo', '123456', 'active', '2016-11-23'),
(61, 'Fly', 'Flyerson', 'flyflerson@hotmail.com', 'app123', '690259', 'inactive', '2016-11-30'),
(59, 'Matt ', 'Demo', 'mpgdemo@gmail.com', 'test test!', '298782', 'active', '2016-11-23'),
(57, 'aaa', 'Aaa', 'aaa@aaa.com', '962193278', '424730', 'inactive', '2016-11-22'),
(56, 'Ira', 'McCray II', 'imccray@incitegraphics.com', 'aaa', '190910', 'inactive', '2016-11-22'),
(55, 'Ira', 'McCray II', 'mccray.ira@gmail.com', 'aaa', '577804', 'active', '2016-11-22'),
(58, 'Matt ', 'Demo', 'matt.demo@veolia.com', 'test test!', '113389', 'active', '2016-11-23'),
(54, 'Ira', 'McCray II', 'ira.mccray@veolia.com', 'aaa', '845641', 'active', '2016-11-22'),
(64, 'Elinor', 'Haider', 'elinor.haider@veolia.com', '3boysRoss!', '997075', 'active', '2016-12-08'),
(65, 'Ira', 'McCray ', 'mccray.ira@comcast.net', 'diamondpony8', '702047', 'inactive', '2016-12-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=66;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
