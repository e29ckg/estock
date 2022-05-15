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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogs`
--

LOCK TABLES `catalogs` WRITE;
/*!40000 ALTER TABLE `catalogs` DISABLE KEYS */;
INSERT INTO `catalogs` VALUES (1,'วัสดุสำนักงาน',NULL,1),(2,'วัสดุคอมพิวเตอร์',NULL,2),(3,'วัสดุไฟฟ้า',NULL,3),(4,'วัสดุงานบ้านงานครัว',NULL,4);
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
  `qua` int(10) DEFAULT 0,
  `qua_pay` int(10) DEFAULT 0,
  `ord_own` varchar(250) NOT NULL,
  `ord_app` varchar(250) NOT NULL,
  `st` int(10) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ord_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ord_lists`
--

LOCK TABLES `ord_lists` WRITE;
/*!40000 ALTER TABLE `ord_lists` DISABLE KEYS */;
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
  `ord_pay_date` datetime DEFAULT NULL,
  `ord_pay_own` varchar(250) DEFAULT NULL,
  `comment` varchar(250) DEFAULT NULL,
  `st` int(10) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ords`
--

LOCK TABLES `ords` WRITE;
/*!40000 ALTER TABLE `ords` DISABLE KEYS */;
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
  `st` int(10) DEFAULT 0,
  `img` varchar(250) DEFAULT NULL,
  `own` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'กระดาษ A4',NULL,'วัสดุสำนักงาน','รีม',0,'1',1,5,0,NULL,NULL,'2022-05-15 21:04:44','2022-05-15 21:04:44');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rec_lists`
--

LOCK TABLES `rec_lists` WRITE;
/*!40000 ALTER TABLE `rec_lists` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recs`
--

LOCK TABLES `recs` WRITE;
/*!40000 ALTER TABLE `recs` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
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
  `token` varchar(250) DEFAULT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(100) NOT NULL,
  `fullname` varchar(250) NOT NULL,
  `dep` varchar(250) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `st` int(13) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$z4Fh7UMhjqRNEmxSrXEkielr4vUvBo8reOPue5iKLGdmeJG2acbMi',NULL,'admin@example.com','admin','administartor','-','0123456789',10,'2022-05-15 21:04:44','2022-05-15 21:04:44');
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

-- Dump completed on 2022-05-15 21:04:50
