-- =====================================================
-- KISTI CA Local Development Database Setup
-- Run this script in phpMyAdmin or MySQL CLI
-- =====================================================

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS kistica_v3 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE kistica_v3;

-- =====================================================
-- Table: cert (Issued Certificates)
-- =====================================================
DROP TABLE IF EXISTS `cert`;
CREATE TABLE `cert` (
  `certid` int(11) NOT NULL AUTO_INCREMENT,
  `cert` mediumtext,
  `serial` int(11) DEFAULT NULL,
  `subject` char(255) DEFAULT NULL,
  `notbefore` char(30) DEFAULT NULL,
  `notafter` char(30) DEFAULT NULL,
  `vfrom` datetime DEFAULT NULL,
  `vuntil` datetime DEFAULT NULL,
  `ctype` char(100) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `idate` datetime DEFAULT NULL,
  `csrid` int(11) DEFAULT NULL,
  `status` char(100) DEFAULT NULL,
  PRIMARY KEY (`certid`),
  KEY `certid` (`certid`)
) ENGINE=InnoDB AUTO_INCREMENT=100000 DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table: counter
-- =====================================================
DROP TABLE IF EXISTS `counter`;
CREATE TABLE `counter` (
  `certid` int(11) DEFAULT '0',
  `csrid` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table: csr (Certificate Signing Requests)
-- =====================================================
DROP TABLE IF EXISTS `csr`;
CREATE TABLE `csr` (
  `csrid` int(11) NOT NULL AUTO_INCREMENT,
  `csr` text,
  `forminfo` char(255) DEFAULT NULL,
  `idate` datetime DEFAULT NULL,
  `status` char(100) DEFAULT NULL,
  `csrtype` char(10) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `certid` int(11) DEFAULT NULL,
  PRIMARY KEY (`csrid`),
  KEY `csrid` (`csrid`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table: subscriber (Registered Users)
-- =====================================================
DROP TABLE IF EXISTS `subscriber`;
CREATE TABLE `subscriber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` char(100) DEFAULT NULL,
  `lastname` char(100) DEFAULT NULL,
  `perid` char(10) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `country` char(20) DEFAULT NULL,
  `org` char(100) DEFAULT NULL,
  `orgunit` char(100) DEFAULT NULL,
  `position` char(100) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `pin` char(100) DEFAULT NULL,
  `ra_cn` char(100) DEFAULT NULL,
  `memo` char(200) DEFAULT NULL,
  `idate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table: webcert (Web Access Client Certificates)
-- =====================================================
DROP TABLE IF EXISTS `webcert`;
CREATE TABLE `webcert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subscid` int(11) DEFAULT '-1',
  `dn` char(255) DEFAULT NULL,
  `cn` char(255) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `authz` char(10) DEFAULT NULL,
  `serial` int(11) DEFAULT NULL,
  `idate` datetime DEFAULT NULL,
  `udate` datetime DEFAULT NULL,
  `pin` char(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Sample Data for Local Testing
-- =====================================================

-- Insert test subscriber
INSERT INTO `subscriber` (`id`, `firstname`, `lastname`, `perid`, `gender`, `country`, `org`, `orgunit`, `position`, `email`, `pin`, `ra_cn`, `memo`, `idate`) VALUES
(1, 'Test', 'User', '001', 'M', 'KR', 'KISTI', 'GSDC', 'Researcher', 'test@kisti.re.kr', '12345678', 'KISTI CA', 'Test user for local development', NOW());

-- Insert test webcert (WACC)
INSERT INTO `webcert` (`id`, `subscid`, `dn`, `cn`, `email`, `authz`, `serial`, `idate`, `udate`, `pin`) VALUES
(1, 1, '/CN=Test User/O=KISTI/C=KR', 'Test User', 'test@kisti.re.kr', 'user', 1, NOW(), NOW(), '12345678');

-- Insert sample certificate
INSERT INTO `cert` (`certid`, `cert`, `serial`, `subject`, `notbefore`, `notafter`, `vfrom`, `vuntil`, `ctype`, `email`, `idate`, `csrid`, `status`) VALUES
(100000, '-----BEGIN CERTIFICATE-----\nMIIEXXXXXXXXXXXXXXXX\n-----END CERTIFICATE-----', 100000, 'CN=Test User 001,O=KISTI,C=KR', 'Jan 1 00:00:00 2026 GMT', 'Jan 1 00:00:00 2027 GMT', '2026-01-01 00:00:00', '2027-01-01 00:00:00', 'user', 'test@kisti.re.kr', NOW(), 1000, 'issued');

-- Insert sample CSR
INSERT INTO `csr` (`csrid`, `csr`, `forminfo`, `idate`, `status`, `csrtype`, `email`, `certid`) VALUES
(1000, '-----BEGIN CERTIFICATE REQUEST-----\nMIIBXXXXXXXXXXXXXXXX\n-----END CERTIFICATE REQUEST-----', 'O=KISTI|CN=Test User 001', NOW(), 'issued', 'user', 'test@kisti.re.kr', 100000);

-- Initialize counter
INSERT INTO `counter` (`certid`, `csrid`) VALUES (100001, 1001);

-- =====================================================
-- Verify setup
-- =====================================================
SELECT 'Database setup complete!' AS Status;
SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'kistica_v3';
