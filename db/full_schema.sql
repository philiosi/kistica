-- MySQL dump 10.14  Distrib 5.5.68-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: kistica_v3
-- ------------------------------------------------------
-- Server version	5.5.68-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cert`
--

DROP TABLE IF EXISTS `cert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=100000 DEFAULT CHARSET=euckr;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `counter`
--

DROP TABLE IF EXISTS `counter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `counter` (
  `certid` int(11) DEFAULT '0',
  `csrid` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=euckr;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `csr`
--

DROP TABLE IF EXISTS `csr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=604 DEFAULT CHARSET=euckr;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subscriber`
--

DROP TABLE IF EXISTS `subscriber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=417 DEFAULT CHARSET=euckr;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `webcert`
--

DROP TABLE IF EXISTS `webcert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=816 DEFAULT CHARSET=euckr;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-02 17:13:09
