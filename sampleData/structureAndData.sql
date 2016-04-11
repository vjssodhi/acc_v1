-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 17, 2016 at 03:47 AM
-- Server version: 10.1.12-MariaDB-1~trusty
-- PHP Version: 5.6.18-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `amrit`
--

-- --------------------------------------------------------

--
-- Table structure for table `itfi_finmgmt_agent`
--

CREATE TABLE IF NOT EXISTS `itfi_finmgmt_agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `emailId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` bigint(20) unsigned DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commissionPercentage` int(10) unsigned NOT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `updatedOn` bigint(20) unsigned NOT NULL,
  `createdOn` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inst_agnt_mbl_unq` (`mobile`),
  UNIQUE KEY `inst_agnt_email_unq` (`emailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `itfi_finmgmt_agent`
--

INSERT INTO `itfi_finmgmt_agent` (`id`, `name`, `emailId`, `mobile`, `address`, `commissionPercentage`, `enabled`, `updatedOn`, `createdOn`) VALUES
(7, 'Agent One', 'agent@one.com', 1111111111, 'Address One', 25, 1, 1458156270, 1458156270),
(8, 'Agent Two', 'agenttwo@two.com', 2222222222, 'Address Agent Two', 36, 1, 1458156298, 1458156298),
(9, 'Agent Three', 'agentthree@three.com', 3333333333, 'Three Agent Address', 45, 1, 1458156331, 1458156331);

-- --------------------------------------------------------

--
-- Table structure for table `itfi_finmgmt_agent_payment`
--

CREATE TABLE IF NOT EXISTS `itfi_finmgmt_agent_payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emailId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `totalCommission` bigint(20) unsigned NOT NULL,
  `paidAmmount` bigint(20) unsigned NOT NULL,
  `updatedOn` bigint(20) unsigned NOT NULL,
  `createdOn` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `itfi_finmgmt_agent_payment`
--

INSERT INTO `itfi_finmgmt_agent_payment` (`id`, `emailId`, `totalCommission`, `paidAmmount`, `updatedOn`, `createdOn`) VALUES
(17, 'agenttwo@two.com', 27924, 2000, 1458162078, 1458162078),
(18, 'agenttwo@two.com', 27924, 2000, 1458162088, 1458162088),
(19, 'agent@one.com', 34530, 34530, 1458166611, 1458166611);

-- --------------------------------------------------------

--
-- Table structure for table `itfi_finmgmt_institute`
--

CREATE TABLE IF NOT EXISTS `itfi_finmgmt_institute` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailIdTwo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneNumber` bigint(20) unsigned DEFAULT NULL,
  `phoneNumberTwo` bigint(20) unsigned DEFAULT NULL,
  `phoneNumberThree` bigint(20) unsigned DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pincode` bigint(20) unsigned DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `updatedOn` bigint(20) unsigned NOT NULL,
  `createdOn` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `itfi_finmgmt_institute`
--

INSERT INTO `itfi_finmgmt_institute` (`id`, `name`, `emailId`, `emailIdTwo`, `phoneNumber`, `phoneNumberTwo`, `phoneNumberThree`, `country`, `pincode`, `enabled`, `updatedOn`, `createdOn`) VALUES
(5, 'Imperial', 'info@imperial.com', NULL, 1733223362, NULL, NULL, 'AU', 125463, 1, 1458156173, 1458156173),
(6, 'Test Institute', 'testinstitue@info.com', NULL, 3698521470, NULL, NULL, 'AU', 223366, 1, 1458156374, 1458156374);

-- --------------------------------------------------------

--
-- Table structure for table `itfi_finmgmt_institute_agent`
--

CREATE TABLE IF NOT EXISTS `itfi_finmgmt_institute_agent` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL,
  `updatedOn` bigint(20) unsigned NOT NULL,
  `createdOn` bigint(20) unsigned NOT NULL,
  `instituteId` bigint(20) unsigned NOT NULL,
  `agentId` int(10) unsigned NOT NULL,
  `commissionPercentage` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inst_agnt_unq` (`instituteId`,`agentId`),
  KEY `IDX_2086BB17108E0BBB` (`instituteId`),
  KEY `IDX_2086BB1717EB4E41` (`agentId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `itfi_finmgmt_institute_agent`
--

INSERT INTO `itfi_finmgmt_institute_agent` (`id`, `enabled`, `updatedOn`, `createdOn`, `instituteId`, `agentId`, `commissionPercentage`) VALUES
(10, 1, 1458156428, 1458156428, 5, 7, 25),
(11, 1, 1458156522, 1458156522, 5, 8, 26),
(12, 1, 1458156848, 1458156848, 6, 7, 66),
(13, 1, 1458157212, 1458157212, 6, 8, 25);

-- --------------------------------------------------------

--
-- Table structure for table `itfi_finmgmt_institute_fee_structure`
--

CREATE TABLE IF NOT EXISTS `itfi_finmgmt_institute_fee_structure` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `updatedOn` bigint(20) unsigned NOT NULL,
  `createdOn` bigint(20) unsigned NOT NULL,
  `instituteId` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inst_cmp_name_unq` (`instituteId`,`name`),
  KEY `IDX_A38E49C108E0BBB` (`instituteId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `itfi_finmgmt_institute_fee_structure`
--

INSERT INTO `itfi_finmgmt_institute_fee_structure` (`id`, `name`, `amount`, `enabled`, `updatedOn`, `createdOn`, `instituteId`) VALUES
(16, 'Medical', 2563, 1, 1458156554, 1458156554, 5),
(17, 'Infra', 2514, 1, 1458156574, 1458156574, 5),
(18, 'TestStr1', 256, 1, 1458156586, 1458156586, 6),
(19, 'TestStr2', 236, 1, 1458156596, 1458156596, 6);

-- --------------------------------------------------------

--
-- Table structure for table `itfi_finmgmt_programme`
--

CREATE TABLE IF NOT EXISTS `itfi_finmgmt_programme` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `feeAmount` bigint(20) unsigned NOT NULL,
  `feeCurrency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `updatedOn` bigint(20) unsigned NOT NULL,
  `createdOn` bigint(20) unsigned NOT NULL,
  `instituteId` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inst_prog_unq` (`instituteId`,`name`),
  UNIQUE KEY `inst_proga_unq` (`instituteId`,`abbreviation`),
  KEY `IDX_BED3FFDD108E0BBB` (`instituteId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `itfi_finmgmt_programme`
--

INSERT INTO `itfi_finmgmt_programme` (`id`, `name`, `abbreviation`, `feeAmount`, `feeCurrency`, `enabled`, `updatedOn`, `createdOn`, `instituteId`) VALUES
(4, 'Imp Prog One', NULL, 45000, 'AUD', 1, 1458156206, 1458156206, 5),
(5, 'Imp Prog Two', NULL, 52143, 'AUD', 1, 1458156221, 1458156221, 5),
(6, 'Test Programme One', NULL, 47841, 'AUD', 1, 1458156395, 1458156395, 6),
(7, 'Test Programme Two', NULL, 25361, 'AUD', 1, 1458156410, 1458156410, 6);

-- --------------------------------------------------------

--
-- Table structure for table `itfi_finmgmt_student`
--

CREATE TABLE IF NOT EXISTS `itfi_finmgmt_student` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `emailId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateOfBirth` bigint(20) unsigned DEFAULT NULL,
  `mobile` bigint(20) unsigned DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `feeAmount` int(10) unsigned DEFAULT NULL,
  `feeCurrency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commissionToBePaidByInstitute` bigint(20) unsigned DEFAULT NULL,
  `commissionStatus` tinyint(1) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `updatedOn` bigint(20) unsigned NOT NULL,
  `createdOn` bigint(20) unsigned NOT NULL,
  `programmeId` int(10) unsigned DEFAULT NULL,
  `agentId` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inst_stu_unq` (`programmeId`,`id`),
  UNIQUE KEY `inst_stu_email_unq` (`programmeId`,`emailId`),
  KEY `IDX_A2CB771DB248874` (`programmeId`),
  KEY `IDX_A2CB771D17EB4E41` (`agentId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `itfi_finmgmt_student`
--

INSERT INTO `itfi_finmgmt_student` (`id`, `name`, `emailId`, `dateOfBirth`, `mobile`, `address`, `gender`, `feeAmount`, `feeCurrency`, `commissionToBePaidByInstitute`, `commissionStatus`, `enabled`, `updatedOn`, `createdOn`, `programmeId`, `agentId`) VALUES
(5, 'Student One', 'sone@gmail.com', 831580200, 1000000000, 'Student One Address', 'Male', 42000, 'AUD', 11275, 0, NULL, 1458156663, 1458156663, 4, 7),
(6, 'Student Two', 'stwo@gmail.com', 705263400, 2111111111, 'Student Two Add', 'Male', 52000, 'AUD', 11270, 0, NULL, 1458156734, 1458156734, 4, 7),
(7, 'Student Three', 'sthree@gmail.com', 737663400, 2555555555, 'STudent Three Address', 'Male', 22333, 'AUD', 18771, 0, NULL, 1458156814, 1458156814, 5, 8),
(8, 'Student Four', 'sfour@gmail.com', 725826600, 2333333333, 'Student Four Adress', 'Male', 47000, 'AUD', 11985, 0, NULL, 1458156904, 1458156904, 6, 7),
(9, 'Student Five', 'sfive@gmail.com', 739391400, 1777777777, 'Student Five Adress', 'Male', 25000, 'AUD', 9153, 0, NULL, 1458157267, 1458157267, 7, 8);

-- --------------------------------------------------------

--
-- Table structure for table `itfi_finmgmt_student_fee_breakdown`
--

CREATE TABLE IF NOT EXISTS `itfi_finmgmt_student_fee_breakdown` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `studentId` bigint(20) NOT NULL,
  `componentId` bigint(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `updatedOn` bigint(20) unsigned NOT NULL,
  `createdOn` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inst_stu_cmp_unq` (`studentId`,`componentId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Dumping data for table `itfi_finmgmt_student_fee_breakdown`
--

INSERT INTO `itfi_finmgmt_student_fee_breakdown` (`id`, `studentId`, `componentId`, `amount`, `updatedOn`, `createdOn`) VALUES
(19, 5, 17, 2514, 1458156663, 1458156663),
(20, 5, 16, 2563, 1458156663, 1458156663),
(21, 6, 17, 2514, 1458156734, 1458156734),
(22, 6, 16, 2563, 1458156734, 1458156734),
(23, 7, 17, 2514, 1458156814, 1458156814),
(24, 7, 16, 2563, 1458156814, 1458156814),
(25, 8, 18, 256, 1458156904, 1458156904),
(26, 8, 19, 236, 1458156904, 1458156904),
(27, 9, 18, 256, 1458157267, 1458157267),
(28, 9, 19, 236, 1458157267, 1458157267);

-- --------------------------------------------------------

--
-- Table structure for table `itfi_finmgmt_user`
--

CREATE TABLE IF NOT EXISTS `itfi_finmgmt_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fullName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `loginId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accessLevel` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `personalEmailId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateOfBirth` bigint(20) unsigned DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` bigint(20) unsigned DEFAULT NULL,
  `marritalStatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pincode` bigint(20) unsigned DEFAULT NULL,
  `permanentAddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correspondenceAddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imageId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `basicBioComplete` tinyint(1) NOT NULL,
  `accountVerified` tinyint(1) DEFAULT NULL,
  `emailVerified` tinyint(1) DEFAULT NULL,
  `updatedOn` bigint(20) unsigned NOT NULL,
  `createdOn` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_84C277B66CC9E093` (`loginId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `itfi_finmgmt_user`
--

INSERT INTO `itfi_finmgmt_user` (`id`, `fullName`, `loginId`, `accessLevel`, `password`, `personalEmailId`, `dateOfBirth`, `gender`, `mobile`, `marritalStatus`, `nationality`, `state`, `city`, `district`, `pincode`, `permanentAddress`, `correspondenceAddress`, `imageId`, `basicBioComplete`, `accountVerified`, `emailVerified`, `updatedOn`, `createdOn`) VALUES
(2, 'Amrit', 'amritsingh183@gmail.com', 7, '$2y$13$Fp1ywbmpFrcdOTL/SHU6gODkJQy0tvBz7L.ME.HBKe8.psKY0Q8Mq', 'amritsingh183@gmail.com', 642191400, 'Male', 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1458156102, 1458156101);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `itfi_finmgmt_institute_agent`
--
ALTER TABLE `itfi_finmgmt_institute_agent`
  ADD CONSTRAINT `FK_2086BB17108E0BBB` FOREIGN KEY (`instituteId`) REFERENCES `itfi_finmgmt_institute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2086BB1717EB4E41` FOREIGN KEY (`agentId`) REFERENCES `itfi_finmgmt_agent` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `itfi_finmgmt_institute_fee_structure`
--
ALTER TABLE `itfi_finmgmt_institute_fee_structure`
  ADD CONSTRAINT `FK_A38E49C108E0BBB` FOREIGN KEY (`instituteId`) REFERENCES `itfi_finmgmt_institute` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `itfi_finmgmt_programme`
--
ALTER TABLE `itfi_finmgmt_programme`
  ADD CONSTRAINT `FK_BED3FFDD108E0BBB` FOREIGN KEY (`instituteId`) REFERENCES `itfi_finmgmt_institute` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `itfi_finmgmt_student`
--
ALTER TABLE `itfi_finmgmt_student`
  ADD CONSTRAINT `FK_A2CB771D17EB4E41` FOREIGN KEY (`agentId`) REFERENCES `itfi_finmgmt_agent` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A2CB771DB248874` FOREIGN KEY (`programmeId`) REFERENCES `itfi_finmgmt_programme` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
