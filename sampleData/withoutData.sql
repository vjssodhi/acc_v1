-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 17, 2016 at 03:48 AM
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
