-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: estock
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `catalogs`
--

DROP TABLE IF EXISTS `catalogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogs` (
  `cat_id` int(13) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(250) NOT NULL,
  `cat_detail` varchar(250) DEFAULT NULL,
  `cat_sort` int(13) DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogs`
--

LOCK TABLES `catalogs` WRITE;
/*!40000 ALTER TABLE `catalogs` DISABLE KEYS */;
INSERT INTO `catalogs` VALUES (1,'วัสดุสำนักงาน',NULL,1),(2,'วัสดุคอมพิวเตอร์',NULL,2),(3,'วัสดุไฟฟ้า',NULL,3);
/*!40000 ALTER TABLE `catalogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ord_lists`
--

DROP TABLE IF EXISTS `ord_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ord_lists` (
  `ord_list_id` int(13) NOT NULL AUTO_INCREMENT,
  `ord_id` int(13) NOT NULL,
  `pro_id` int(13) NOT NULL,
  `pro_name` varchar(250) DEFAULT NULL,
  `unit_name` varchar(250) NOT NULL,
  `qua` int(10) NOT NULL,
  `ord_own` varchar(250) NOT NULL,
  `ord_app` varchar(250) NOT NULL,
  `st` int(10) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ord_list_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ord_lists`
--

LOCK TABLES `ord_lists` WRITE;
/*!40000 ALTER TABLE `ord_lists` DISABLE KEYS */;
INSERT INTO `ord_lists` VALUES (1,1652153022,1,'กระดาษ A4','รีม',10,'administartor','administartor',1,'2022-05-10 10:23:42','2022-05-10 10:23:46'),(2,1652153064,1,'กระดาษ A4','รีม',5,'administartor','administartor',1,'2022-05-10 10:24:24','2022-05-10 10:24:29'),(3,1652153122,1,'กระดาษ A4','รีม',10,'administartor','administartor',1,'2022-05-10 10:25:22','2022-05-10 10:25:28'),(4,1652153202,1,'กระดาษ A4','รีม',5,'administartor','administartor',1,'2022-05-10 10:26:42','2022-05-10 10:26:47'),(5,1652153291,1,'กระดาษ A4','รีม',10,'administartor','administartor',1,'2022-05-10 10:28:11','2022-05-10 10:28:15'),(6,1652153648,1,'กระดาษ A4','รีม',10,'administartor','administartor',1,'2022-05-10 10:34:08','2022-05-10 10:34:20'),(9,1652170172,1,'กระดาษ A4','รีม',25,'administartor','administartor',1,'2022-05-10 15:09:32','2022-05-10 15:13:37'),(10,1652170172,2,'ปากกาน้ำเงิน','แท่ง',8,'administartor','administartor',1,'2022-05-10 15:09:32','2022-05-10 15:13:37'),(13,1652350256,1,'กระดาษ A4','รีม',10,'administartor','administartor',1,'2022-05-13 09:13:53','2022-05-13 09:14:03'),(22,1652425826,1,'กระดาษ A4','รีม',15,'administartor','administartor',1,'2022-05-13 14:10:26','2022-05-13 14:10:42'),(23,1652425972,1,'กระดาษ A4','รีม',25,'นายก','administartor',1,'2022-05-13 14:12:52','2022-05-13 14:14:55');
/*!40000 ALTER TABLE `ord_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ords`
--

DROP TABLE IF EXISTS `ords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ords` (
  `ord_id` int(13) NOT NULL AUTO_INCREMENT,
  `ord_own` varchar(250) DEFAULT NULL,
  `ord_date` date DEFAULT current_timestamp(),
  `ord_app` varchar(250) DEFAULT NULL,
  `ord_pay` datetime DEFAULT NULL,
  `ord_pay_own` varchar(250) DEFAULT NULL,
  `comment` varchar(250) DEFAULT NULL,
  `st` int(10) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ord_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1652425973 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ords`
--

LOCK TABLES `ords` WRITE;
/*!40000 ALTER TABLE `ords` DISABLE KEYS */;
INSERT INTO `ords` VALUES (1652153022,'administartor','2022-05-10','administartor',NULL,'administartor','',1,'2022-05-10 10:23:42','2022-05-10 10:23:46'),(1652153064,'administartor','2022-05-10','administartor',NULL,'administartor','',1,'2022-05-10 10:24:24','2022-05-10 10:24:29'),(1652153122,'administartor','2022-05-10','administartor',NULL,'administartor','',1,'2022-05-10 10:25:22','2022-05-10 10:25:28'),(1652153202,'administartor','2022-05-10','administartor',NULL,'administartor','',1,'2022-05-10 10:26:42','2022-05-10 10:26:47'),(1652153291,'administartor','2022-05-10','administartor',NULL,'administartor','',1,'2022-05-10 10:28:11','2022-05-10 10:28:15'),(1652153648,'administartor','2022-05-10','administartor',NULL,'administartor','',1,'2022-05-10 10:34:08','2022-05-10 10:34:20'),(1652170172,'administartor','2022-05-10','administartor',NULL,'administartor','',1,'2022-05-10 15:09:32','2022-05-10 15:13:37'),(1652350256,'administartor','2022-05-12','administartor',NULL,'administartor',NULL,1,'2022-05-12 17:10:56','2022-05-13 09:14:03'),(1652425826,'administartor','2022-05-13','administartor',NULL,'administartor',NULL,1,'2022-05-13 14:10:26','2022-05-13 14:10:42'),(1652425972,'นายก','2022-05-13','administartor',NULL,'administartor',NULL,1,'2022-05-13 14:12:52','2022-05-13 14:14:55');
/*!40000 ALTER TABLE `ords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `pro_id` int(13) NOT NULL AUTO_INCREMENT,
  `pro_name` varchar(250) NOT NULL,
  `pro_detail` text DEFAULT NULL,
  `cat_name` varchar(250) NOT NULL,
  `unit_name` varchar(250) NOT NULL,
  `instock` int(10) DEFAULT 0,
  `locat` varchar(250) DEFAULT '1',
  `lower` int(10) DEFAULT 1,
  `min` int(10) DEFAULT 1,
  `st` int(10) DEFAULT 1,
  `img` varchar(250) DEFAULT NULL,
  `own` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'กระดาษ A4','ทดสอบ','วัสดุสำนักงาน','รีม',10,'1',1,5,1,'1652234176.jpg','administartor','2022-05-10 10:22:31','2022-05-13 14:37:48'),(2,'ปากกาน้ำเงิน',NULL,'วัสดุสำนักงาน','แท่ง',50,'1',1,5,1,'1652155150.png','administartor','2022-05-10 10:22:31','2022-05-12 14:19:13'),(5,'ช้อน','','วัสดุคอมพิวเตอร์','ขวด',0,'',1,1,1,'1652234309.jpg','administartor','2022-05-10 11:11:35','2022-05-11 08:58:29');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rec_lists`
--

DROP TABLE IF EXISTS `rec_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rec_lists` (
  `rec_list_id` int(13) NOT NULL AUTO_INCREMENT,
  `rec_id` int(13) NOT NULL,
  `rec_date` date DEFAULT NULL,
  `pro_id` int(13) NOT NULL,
  `pro_name` varchar(250) DEFAULT NULL,
  `unit_name` varchar(250) NOT NULL,
  `qua` int(10) NOT NULL,
  `qua_for_ord` int(10) NOT NULL,
  `price_one` varchar(250) NOT NULL,
  `price` varchar(250) NOT NULL,
  `rec_own` varchar(250) NOT NULL,
  `rec_app` varchar(250) NOT NULL,
  `st` int(10) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`rec_list_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rec_lists`
--

LOCK TABLES `rec_lists` WRITE;
/*!40000 ALTER TABLE `rec_lists` DISABLE KEYS */;
INSERT INTO `rec_lists` VALUES (1,1652152983,'2022-05-10',1,'กระดาษ A4','รีม',10,0,'144','1440','administartor','administartor',1,'2022-05-10 10:23:03','2022-05-10 10:23:46'),(2,1652153009,'2022-05-10',1,'กระดาษ A4','รีม',10,0,'102','1020','administartor','administartor',1,'2022-05-10 10:23:29','2022-05-10 10:25:28'),(3,1652153100,'2022-05-10',1,'กระดาษ A4','รีม',10,0,'145','1450','administartor','administartor',1,'2022-05-10 10:25:00','2022-05-10 10:26:47'),(4,1652153264,'2022-05-10',1,'กระดาษ A4','รีม',10,0,'147','1470','administartor','administartor',1,'2022-05-10 10:27:44','2022-05-10 10:28:15'),(5,1652153396,'2022-05-10',1,'กระดาษ A4','รีม',10,0,'230','2300','administartor','administartor',1,'2022-05-10 10:29:56','2022-05-10 10:34:20'),(6,1652153396,'2022-05-10',2,'ปากกาน้ำเงิน','แท่ง',8,0,'5','40','administartor','administartor',1,'2022-05-10 10:29:56','2022-05-10 15:13:37'),(9,1652169306,'2022-05-10',1,'กระดาษ A4','รีม',25,0,'115','2875','administartor','administartor',1,'2022-05-10 14:55:06','2022-05-10 15:13:37'),(10,1652169306,'2022-05-10',2,'ปากกาน้ำเงิน','แท่ง',50,50,'5','250','administartor','administartor',1,'2022-05-10 14:55:06','2022-05-10 14:56:27'),(11,1652342400,'2022-05-12',1,'กระดาษ A4','รีม',25,0,'115','2875','administartor','administartor',1,'2022-05-12 15:00:00','2022-05-13 14:10:42'),(12,1652413100,'2022-05-13',1,'กระดาษ A4','รีม',10,0,'125','1250','administartor','administartor',1,'2022-05-13 10:38:20','2022-05-13 14:14:55'),(13,1652425793,'2022-05-13',1,'กระดาษ A4','รีม',25,10,'100','2500','administartor','administartor',1,'2022-05-13 14:09:53','2022-05-13 14:14:55');
/*!40000 ALTER TABLE `rec_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recs`
--

DROP TABLE IF EXISTS `recs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recs` (
  `rec_id` int(13) NOT NULL AUTO_INCREMENT,
  `rec_own` varchar(250) DEFAULT NULL,
  `rec_app` varchar(250) DEFAULT NULL,
  `rec_date` date DEFAULT NULL,
  `str_id` int(13) NOT NULL,
  `price_total` varchar(250) DEFAULT NULL,
  `comment` varchar(250) DEFAULT NULL,
  `st` int(10) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1652425794 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recs`
--

LOCK TABLES `recs` WRITE;
/*!40000 ALTER TABLE `recs` DISABLE KEYS */;
INSERT INTO `recs` VALUES (1652152983,'administartor','administartor','2022-05-10',1,'1440','',1,'2022-05-10 10:23:03','2022-05-10 10:23:07'),(1652153009,'administartor','administartor','2022-05-10',2,'1020','',1,'2022-05-10 10:23:29','2022-05-10 10:23:33'),(1652153100,'administartor','administartor','2022-05-10',1,'1450','',1,'2022-05-10 10:25:00','2022-05-10 10:25:05'),(1652153264,'administartor','administartor','2022-05-10',1,'1470','',1,'2022-05-10 10:27:44','2022-05-10 10:27:49'),(1652153396,'administartor','administartor','2022-05-10',2,'2340','',1,'2022-05-10 10:29:56','2022-05-10 10:30:01'),(1652169306,'administartor','administartor','2022-05-10',1,'3125','',1,'2022-05-10 14:55:06','2022-05-10 14:56:27'),(1652342400,'administartor','administartor','2022-05-12',1,'2875','',1,'2022-05-12 15:00:00','2022-05-12 15:00:04'),(1652413100,'administartor','administartor','2022-05-13',1,'1250','',1,'2022-05-13 10:38:20','2022-05-13 10:38:26'),(1652425793,'administartor','administartor','2022-05-13',2,'2500','',1,'2022-05-13 14:09:53','2022-05-13 14:11:17');
/*!40000 ALTER TABLE `recs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sta`
--

DROP TABLE IF EXISTS `sta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sta` (
  `st_id` int(13) NOT NULL AUTO_INCREMENT,
  `st_name` varchar(250) NOT NULL,
  PRIMARY KEY (`st_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sta`
--

LOCK TABLES `sta` WRITE;
/*!40000 ALTER TABLE `sta` DISABLE KEYS */;
/*!40000 ALTER TABLE `sta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock` (
  `stck_id` int(13) NOT NULL AUTO_INCREMENT,
  `pro_id` int(13) NOT NULL,
  `unit_name` varchar(250) DEFAULT NULL,
  `price_one` varchar(100) DEFAULT NULL,
  `bf` int(10) NOT NULL,
  `stck_in` int(10) DEFAULT NULL,
  `stck_out` int(10) DEFAULT NULL,
  `bal` int(10) NOT NULL,
  `rec_ord_id` int(10) DEFAULT NULL,
  `rec_ord_list_id` int(10) DEFAULT NULL,
  `comment` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`stck_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
INSERT INTO `stock` VALUES (1,1,'รีม','144',0,10,0,10,1652152983,1,'','2022-05-10 10:23:07','2022-05-10 10:23:07'),(2,1,'รีม','102',10,10,0,20,1652153009,2,'','2022-05-10 10:23:33','2022-05-10 10:23:33'),(3,1,'รีม','144',20,0,10,10,1652153022,1,'','2022-05-10 10:23:46','2022-05-10 10:23:46'),(4,1,'รีม','102',10,0,5,5,1652153064,2,'','2022-05-10 10:24:29','2022-05-10 10:24:29'),(5,1,'รีม','145',5,10,0,15,1652153100,3,'','2022-05-10 10:25:05','2022-05-10 10:25:05'),(6,1,'รีม','102',15,0,5,10,1652153122,3,'','2022-05-10 10:25:28','2022-05-10 10:25:28'),(7,1,'รีม','145',10,0,5,5,1652153122,3,'','2022-05-10 10:25:28','2022-05-10 10:25:28'),(8,1,'รีม','145',5,0,5,0,1652153202,4,'','2022-05-10 10:26:47','2022-05-10 10:26:47'),(9,1,'รีม','147',0,10,0,10,1652153264,4,'','2022-05-10 10:27:49','2022-05-10 10:27:49'),(10,1,'รีม','147',10,0,10,0,1652153291,5,'','2022-05-10 10:28:15','2022-05-10 10:28:15'),(11,1,'รีม','230',0,10,0,10,1652153396,5,'','2022-05-10 10:30:01','2022-05-10 10:30:01'),(12,2,'แท่ง','5',0,8,0,8,1652153396,6,'','2022-05-10 10:30:01','2022-05-10 10:30:01'),(13,1,'รีม','230',10,0,10,0,1652153648,6,'','2022-05-10 10:34:20','2022-05-10 10:34:20'),(14,1,'รีม','115',0,25,0,25,1652169306,9,'','2022-05-10 14:56:27','2022-05-10 14:56:27'),(15,2,'แท่ง','5',8,50,0,58,1652169306,10,'','2022-05-10 14:56:27','2022-05-10 14:56:27'),(16,1,'รีม','115',25,0,25,0,1652170172,9,'','2022-05-10 15:13:37','2022-05-10 15:13:37'),(17,2,'แท่ง','5',58,0,8,50,1652170172,10,'','2022-05-10 15:13:37','2022-05-10 15:13:37'),(18,1,'รีม','115',0,25,0,25,1652342400,11,'','2022-05-12 15:00:04','2022-05-12 15:00:04'),(19,1,'รีม','115',25,0,10,15,1652350256,13,NULL,'2022-05-13 09:14:03','2022-05-13 09:14:03'),(20,1,'รีม','125',15,10,0,25,1652413100,12,'','2022-05-13 10:38:26','2022-05-13 10:38:26'),(21,1,'รีม','115',25,0,15,10,1652425826,22,NULL,'2022-05-13 14:10:42','2022-05-13 14:10:42'),(22,1,'รีม','100',10,25,0,35,1652425793,13,'','2022-05-13 14:11:17','2022-05-13 14:11:17'),(23,1,'รีม','125',35,0,10,25,1652425972,23,NULL,'2022-05-13 14:14:55','2022-05-13 14:14:55'),(24,1,'รีม','100',25,0,15,10,1652425972,23,NULL,'2022-05-13 14:14:55','2022-05-13 14:14:55');
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS `store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store` (
  `str_id` int(13) NOT NULL AUTO_INCREMENT,
  `str_name` varchar(250) NOT NULL,
  `str_detail` varchar(250) DEFAULT NULL,
  `str_phone` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`str_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store`
--

LOCK TABLES `store` WRITE;
/*!40000 ALTER TABLE `store` DISABLE KEYS */;
INSERT INTO `store` VALUES (1,'อุ่นรุ่งกิจ ','67/1-4 ถ.พิทักษ์ชาติ ต.ประจวบคีรีขันธ์ อ.เมือง จ.ประจวบคีรีขันธ์','032-602150','2022-05-10 10:22:31','2022-05-10 13:25:36'),(2,'บริษัท อาร์.เอส.ที.ออโตเมชั่น จำกัด (สำนักงานใหญ่)','227/16 ม.4 ถ.ชนเกษม ต.มะขามเตี้ย อ.เมือง จ.สุราษฎร์ธานี','077-218-6934','2022-05-10 10:22:31','2022-05-10 13:30:21'),(3,'บริษัท มิสเตอร์ อิ๊งค์ คอมพิวเตอร์ เซอร์วิส จำกัด (สำนักงานใหญ่)','6 ซ.วัดสุขใจ 5 แขวงทรายกองดิน เขตคลองสามวา กรุงเทพมหานคร','02-914-5200/ 02-914-5300/ 02-543-6926-30/ 086-345-5960-1','2022-05-10 10:22:31','2022-05-10 13:35:58'),(4,'พีเค ซัพพลาย','55/276 ม.6 ซ.เจริญใจ ถ.เทพารักษ์ ต.บางเมือง อ.เมือง จ.สมุทรปราการ','091-043-6653/ 062-069-9664/ 095-016-7019','2022-05-10 10:22:31','2022-05-10 13:29:37'),(5,'ร้าน ทีพีพี พริ้นติ้ง','264/95 ม.4 ถ.อำเภอ ต.มะขามเตี้ย อ.เมือง จ.สุราษฎร์ธานี','077-310137','2022-05-10 13:32:34','2022-05-10 13:32:34'),(6,'ร้านทิพย์รัตน์','51/2 ถ.ประจวบ อ.เมือง จ.ประจวบคีรีขันธ์','-','2022-05-10 13:34:06','2022-05-10 13:34:06');
/*!40000 ALTER TABLE `store` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `unit_id` int(13) NOT NULL AUTO_INCREMENT,
  `unit_name` varchar(250) NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'รีม'),(2,'ใบ'),(3,'กล่อง'),(4,'อัน'),(5,'ม้วน'),(6,'ซอง'),(7,'แท่ง'),(8,'ตลับ'),(9,'ด้าม'),(10,'คู่'),(11,'เล่ม'),(12,'ขวด'),(13,'ก้อน'),(14,'ไม้'),(15,'แผ่น');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(13) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(100) NOT NULL,
  `fullname` varchar(250) NOT NULL,
  `dep` varchar(250) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$UHeHPjGUOa9yAP1soZzNoeK3x3Uwia8emDQDiW5gr351eDRTYWKrm','admin@example.com','admin','administartor','-','0123456789','2022-05-10 10:22:31','2022-05-10 10:22:31'),(6,'4565','$2y$13$3IobWJTpkUfbYiyBsmr5Wut8A/x8kc3RM5m1ZMsrrsMWyYQGLkryW','donlaya.y@coj.go.th','','นางสาวดลยา เยาวหลี','เจ้าพนักงานศาลยุติธรรมชำนาญการ','0895213842','2022-05-13 15:56:25','2022-05-13 15:56:25'),(7,'5971','$2y$13$NmWazAcGEYx5LLclTVWLmO1sDKEBEarMCdojdICRzJNYSCPvrnLzy','roopoopoop@gmail.com','','นายเอกชวัทธน์  สาระเกตุ','เจ้าพนักงานศาลยุติธรรมชำนาญการ','0867526064','2022-05-13 15:56:27','2022-05-13 15:56:27'),(8,'id1817','$2y$13$Ne/g.lNnrMNSpkD/19Dx9ugTy.Sq/uoBmy/lacUQcHO2PcVL8e0Ya','chotikar.d@gmail.com','','นางสาวโชติกา ดีดอนกลาย ','เจ้าหน้าที่ศาลยุติธรรมชำนาญงาน','0868862701','2022-05-13 15:56:30','2022-05-13 15:56:30');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-15 20:09:30
