-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: ucsl_system
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `billings`
--

DROP TABLE IF EXISTS `billings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `billings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `shipment_id` bigint unsigned NOT NULL,
  `amount` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billings`
--

LOCK TABLES `billings` WRITE;
/*!40000 ALTER TABLE `billings` DISABLE KEYS */;
/*!40000 ALTER TABLE `billings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cash_collecteds`
--

DROP TABLE IF EXISTS `cash_collecteds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cash_collecteds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cash_collecteds`
--

LOCK TABLES `cash_collecteds` WRITE;
/*!40000 ALTER TABLE `cash_collecteds` DISABLE KEYS */;
/*!40000 ALTER TABLE `cash_collecteds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cash_credits`
--

DROP TABLE IF EXISTS `cash_credits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cash_credits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cash_credits`
--

LOCK TABLES `cash_credits` WRITE;
/*!40000 ALTER TABLE `cash_credits` DISABLE KEYS */;
/*!40000 ALTER TABLE `cash_credits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Medical','Medical Products and Services',NULL,NULL),(2,'E-Commerce','Online retail and logistics solutions',NULL,NULL),(3,'Manufacturing','Goods production and supply chain',NULL,NULL),(4,'Construction','Building materials and site logistics',NULL,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_categories`
--

DROP TABLE IF EXISTS `client_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_categories_client_id_foreign` (`client_id`),
  KEY `client_categories_category_id_foreign` (`category_id`),
  CONSTRAINT `client_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_categories_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_categories`
--

LOCK TABLES `client_categories` WRITE;
/*!40000 ALTER TABLE `client_categories` DISABLE KEYS */;
INSERT INTO `client_categories` VALUES (1,1,1,'2025-08-14 14:02:47','2025-08-14 14:02:47'),(2,1,2,'2025-08-14 14:02:47','2025-08-14 14:02:47'),(3,1,3,'2025-08-14 14:02:47','2025-08-14 14:02:47'),(4,2,4,'2025-08-14 14:02:47','2025-08-14 14:02:47'),(5,3,3,'2025-08-14 14:02:47','2025-08-14 14:02:47'),(6,3,2,'2025-08-14 14:02:47','2025-08-14 14:02:47'),(7,3,4,'2025-08-14 14:02:47','2025-08-14 14:02:47'),(8,4,4,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(9,5,4,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(10,5,3,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(11,5,2,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(12,6,4,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(13,6,1,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(14,7,4,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(15,7,1,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(16,7,3,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(17,8,3,'2025-08-14 14:02:48','2025-08-14 14:02:48'),(18,9,3,'2025-08-14 14:02:49','2025-08-14 14:02:49'),(19,9,1,'2025-08-14 14:02:49','2025-08-14 14:02:49'),(20,9,4,'2025-08-14 14:02:49','2025-08-14 14:02:49'),(21,10,3,'2025-08-14 14:02:49','2025-08-14 14:02:49'),(22,11,1,'2025-08-17 08:26:36','2025-08-17 08:26:36'),(23,11,2,'2025-08-17 08:26:36','2025-08-17 08:26:36'),(24,12,1,'2025-08-17 08:38:00','2025-08-17 08:38:00'),(25,12,2,'2025-08-17 08:38:00','2025-08-17 08:38:00'),(26,13,1,'2025-08-17 08:43:07','2025-08-17 08:43:07'),(27,13,2,'2025-08-17 08:43:07','2025-08-17 08:43:07'),(28,14,1,'2025-08-17 11:28:03','2025-08-17 11:28:03'),(29,14,2,'2025-08-17 11:28:03','2025-08-17 11:28:03');
/*!40000 ALTER TABLE `client_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_requests`
--

DROP TABLE IF EXISTS `client_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clientId` bigint unsigned NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `sub_category_id` bigint unsigned NOT NULL,
  `requestId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `collectionLocation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parcelDetails` text COLLATE utf8mb4_unicode_ci,
  `dateRequested` datetime DEFAULT NULL,
  `userId` bigint unsigned DEFAULT NULL,
  `vehicleId` bigint unsigned DEFAULT NULL,
  `rate_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `office_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending collection',
  `priority_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadline_date` datetime DEFAULT NULL,
  `collected_by` bigint unsigned DEFAULT NULL,
  `consignment_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `delivery_rider_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_requests_requestid_unique` (`requestId`),
  KEY `client_requests_collected_by_foreign` (`collected_by`),
  CONSTRAINT `client_requests_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_requests`
--

LOCK TABLES `client_requests` WRITE;
/*!40000 ALTER TABLE `client_requests` DISABLE KEYS */;
INSERT INTO `client_requests` VALUES (1,8,3,1,'REQ-10000',NULL,'Waybill No: UCSL0000000001KE, Items: 1, Total Weight: 30kg','2025-08-14 17:18:13',NULL,NULL,NULL,5,'2','verified','normal',NULL,5,'CN-10000','2025-08-14 14:18:13','2025-08-14 14:18:13',NULL),(2,8,3,1,'REQ-10001',NULL,'Waybill No: UCSL0000000002KE, Items: 1, Total Weight: 20kg','2025-08-14 17:47:11',NULL,NULL,NULL,5,'2','verified','normal',NULL,5,'CN-10001','2025-08-14 14:47:11','2025-08-14 14:47:11',NULL),(3,5,2,1,'REQ-10002','Kawangware','Box of electronics','2025-08-15 11:45:00',1,2,NULL,5,'2','verified','normal',NULL,1,'CN-10002','2025-08-15 05:56:19','2025-08-15 05:59:39',NULL),(4,7,1,1,'REQ-10003',NULL,'Waybill No: UCSL0000000005KE, Items: 1, Total Weight: 15kg','2025-08-15 09:31:22',NULL,NULL,NULL,1,'2','verified','normal',NULL,1,'CN-10003','2025-08-15 06:31:22','2025-08-15 06:31:22',NULL),(5,8,3,2,'REQ-10004',NULL,'Waybill No: UCSL0000000006KE, Items: 1, Total Weight: 25kg','2025-08-15 09:35:58',2,1,NULL,1,'2','collected','normal',NULL,1,'CN-10004','2025-08-15 06:35:58','2025-08-15 07:17:53',NULL),(6,8,3,2,'REQ-10005',NULL,'Waybill No: UCSL0000000007KE, Items: 1, Total Weight: 15kg','2025-08-15 10:38:37',NULL,NULL,NULL,5,'2','verified','normal',NULL,5,'CN-10005','2025-08-15 07:38:37','2025-08-15 07:38:37',NULL),(7,8,3,1,'REQ-10006',NULL,'Waybill No: UCSL0000000008KE, Items: 1, Total Weight: 35kg','2025-08-15 12:59:32',NULL,NULL,NULL,5,'2','verified','normal',NULL,5,'CN-10006','2025-08-15 09:59:32','2025-08-15 09:59:32',NULL),(8,8,3,1,'REQ-10007',NULL,'Waybill No: UCSL0000000009KE, Items: 1, Total Weight: 9.6kg','2025-08-15 14:59:01',NULL,NULL,NULL,5,'2','delivered','normal',NULL,5,'CN-10007','2025-08-15 11:59:01','2025-08-18 06:12:20',2),(9,8,3,1,'REQ-10008',NULL,'Waybill No: UCSL0000000010KE, Items: 1, Total Weight: 30kg','2025-08-15 16:26:44',NULL,NULL,NULL,5,'2','verified','normal',NULL,5,'CN-10008','2025-08-15 13:26:44','2025-08-15 13:26:44',NULL),(10,5,2,2,'REQ-10009','Madaraka','Mitumba','2025-08-15 11:50:00',2,1,178,5,'2','delivered','normal',NULL,2,'CN-10010','2025-08-15 14:57:21','2025-08-15 15:49:08',NULL),(11,5,2,2,'REQ-10010','Madaraka','Shoes','2025-08-15 12:00:00',1,2,178,2,'2','delivered','normal',NULL,1,'CN-10009','2025-08-15 15:13:41','2025-08-15 15:25:50',NULL),(12,5,2,2,'REQ-10011','BuruBuru','Shoes','2025-08-17 12:00:00',2,1,199,5,'2','delivered','normal',NULL,2,'CN-10011','2025-08-17 06:47:16','2025-08-17 18:00:28',NULL),(13,11,2,2,'REQ-10012',NULL,'Waybill No: UCSL0000000014KE, Items: 1, Total Weight: 10kg','2025-08-17 11:58:59',1,2,NULL,2,'2','collected','normal',NULL,2,'CN-10012','2025-08-17 08:58:59','2025-08-17 09:12:56',NULL),(14,12,1,2,'REQ-10013',NULL,'Waybill No: UCSL0000000015KE, Items: 2, Total Weight: 45kg','2025-08-17 12:39:20',4,11,NULL,1,'2','delivered','high','2025-08-17 17:30:00',1,'CN-10013','2025-08-17 09:39:20','2025-08-17 10:14:52',NULL),(15,5,2,2,'REQ-10014','Adams Arcade','Box of pharmaceuticals','2025-08-17 12:00:00',2,1,168,4,'2','delivered','normal',NULL,2,'CN-10014','2025-08-17 10:38:34','2025-08-17 10:45:50',NULL),(16,14,1,1,'REQ-10015',NULL,'Waybill No: UCSL0000000017KE, Items: 1, Total Weight: 55kg','2025-08-17 14:31:40',NULL,NULL,NULL,4,'2','verified','normal',NULL,4,'CN-10015','2025-08-17 11:31:40','2025-08-17 11:31:40',NULL),(17,14,1,1,'REQ-10016',NULL,'Waybill No: UCSL0000000018KE, Items: 1, Total Weight: 55kg','2025-08-17 14:52:45',NULL,NULL,NULL,4,'2','verified','normal',NULL,4,'CN-10016','2025-08-17 11:52:45','2025-08-17 11:52:45',NULL),(18,6,4,1,'REQ-10017','Kayole','1 Door','2025-08-17 11:50:00',2,1,NULL,5,'2','verified','normal',NULL,2,'CN-10017','2025-08-17 13:07:12','2025-08-17 13:16:18',NULL),(19,6,1,1,'REQ-10018','Kayole','Pharmaceuticsls','2025-08-17 11:50:00',2,1,NULL,2,'2','verified','normal',NULL,2,'CN-10018','2025-08-17 18:12:34','2025-08-17 18:15:59',NULL),(20,8,3,1,'REQ-10019',NULL,'Waybill No: UCSL0000000023KE, Items: 1, Total Weight: 20kg','2025-08-18 05:25:04',NULL,NULL,NULL,2,'2','delivered','normal',NULL,2,'CN-10019','2025-08-18 02:25:04','2025-08-18 04:12:07',10),(21,8,3,1,'REQ-10020',NULL,'Waybill No: UCSL0000000024KE, Items: 1, Total Weight: 20kg','2025-08-18 10:27:34',NULL,NULL,NULL,2,'2','verified','normal',NULL,2,'CN-10020','2025-08-18 07:27:34','2025-08-18 07:27:34',NULL);
/*!40000 ALTER TABLE `client_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `accountNo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contactPerson` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contactPersonPhone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contactPersonEmail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_id_no` int DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `industry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kraPin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postalCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_rates_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verificationCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sales_person_id` bigint unsigned DEFAULT NULL,
  `id_number` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'UCSL-24782','Twende Ventures','twende-ventures@ucsl.co.ke','$2y$12$3Xj.2taVELQ3nk2zckUXd.o3qMLXOqntwZrsg2bMdB3vHbM34YAzC','0729395605','Tom Mboya Street, Mombasa','Nakuru','Westgate','Kenya','NULL','Blair Roob','0729395605','blair-roob@example.com',21892155,'on_account','dolores','A5QKSMUUF','37447','active',NULL,'LVXCO','2025-08-14 14:02:47','2025-08-14 14:02:47',NULL,NULL),(2,'UCSL-25241','Safari Tech Solutions','safari-tech-solutions@ucsl.co.ke','$2y$12$iUYS.GdLOE9bLLjW14MbMuRkJjO8KaIK1X/UGHEvcCwnWR7WNJc1O','0729395605','Waiyaki Way, Nairobi','Nairobi','Sarit Centre','Kenya','NULL','Prof. Emile Bartell PhD','0729395605','prof-emile-bartell-phd@example.com',37137378,'on_account','illum','ACLTMOYZU','27059','active',NULL,'VCQQP','2025-08-14 14:02:47','2025-08-14 14:02:47',NULL,NULL),(3,'UCSL-43467','Nuru E-Commerce Ltd','nuru-e-commerce-ltd@ucsl.co.ke','$2y$12$FWhNR1UkHER1q6Fg6q4lleahx34Dtg5lTBc0tObyNgl99pyKEk.HS','0729395605','Kenyatta Avenue, Mombasa','Mombasa','Moi Plaza','Kenya','NULL','Macy Ruecker','0729395605','macy-ruecker@example.com',23030043,'on_account','praesentium','AMVIDABQY','21624','active',NULL,'A3A3D','2025-08-14 14:02:47','2025-08-14 14:02:47',NULL,NULL),(4,'UCSL-48217','Wakanda Distributors','wakanda-distributors@ucsl.co.ke','$2y$12$Rsdwx.ShVdbuvFrH1shrc.22ZX4GQ74ZKvGTxCWsWdFwP8k2IovBS','0729395605','Moi Avenue, Mombasa','Eldoret','Thika Road Mall','Kenya','NULL','Prof. Casper Balistreri','0729395605','prof-casper-balistreri@example.com',10945127,'on_account','non','AWOXGWQSI','44270','active',NULL,'10QCW','2025-08-14 14:02:48','2025-08-14 14:02:48',NULL,NULL),(5,'UCSL-76086','Maisha Medcare','jeff.letting@ufanisi.co.ke','$2y$12$DTkpqbiWYIJherMaQGbj4.3U39FFQPt0V.p8RIxH7td3tK6o.QdSK','0747911674','Kenyatta Avenue, Nakuru','Nairobi','Westgate','Kenya','NULL','Davonte Vandervort','0729395605','davonte-vandervort@example.com',34690279,'on_account','dignissimos','AZ8SOUHG5','29451','active',NULL,'BK2SL','2025-08-14 14:02:48','2025-08-15 05:51:50',NULL,NULL),(6,'UCSL-30903','Kenlog Logistics','choroma@ufanisi.co.ke','$2y$12$zC3y1eGefDE7NkGS/AHehOU7KXF8CmmOmIpayqiy3haWPv0vgezri','0725525484','Waiyaki Way, Nakuru','Kisumu','Kenyatta Avenue','Kenya','NULL','Nyasia King','0729395605','nyasia-king@example.com',14915850,'on_account','doloremque','AMS6FS89O','44691','active',NULL,'GKUQW','2025-08-14 14:02:48','2025-08-17 13:04:38',NULL,NULL),(7,'UCSL-60630','Kilimani Pharma','jeff.letting@ufanisi.co.ke','$2y$12$RLxSaQK082fZbDh0IoflTuqUaB1tQ5vF5EzCajZt44OY11eOV9cT6','0729395605','Ngong Road, Eldoret','Kisumu','Kenyatta Avenue','Kenya','NULL','Giuseppe Nitzsche','0729395605','giuseppe-nitzsche@example.com',31861190,'walkin','non','AQNWZZMPV','20306','active',NULL,'ROP7L','2025-08-14 14:02:48','2025-08-15 07:16:08',NULL,NULL),(8,'UCSL-57653','Jumuka Supplies','jeff.letting@ufanisi.co.ke','$2y$12$4mmXuRFqmHemjkVHsTMFYelA/KriReWUUfX/2OJRvYalqJWc2FwUa','0724911674','Moi Avenue, Kisumu','Eldoret','Kenyatta Avenue','Kenya','NULL','Quinton Leuschke','0729395605','quinton-leuschke@example.com',10042264,'walkin','nobis','ALQHPIN57','92029','active',NULL,'KH2CB','2025-08-14 14:02:48','2025-08-14 14:12:07',NULL,NULL),(9,'UCSL-26104','Msingi Manufacturing Co','msingi-manufacturing-co@ucsl.co.ke','$2y$12$YbOfDaDQn1MxwpkWP46tNeR0Y53pqZXlFGCefNZ2NTvmHFeWk3hwm','0729395605','Moi Avenue, Nairobi','Mombasa','Kenyatta Avenue','Kenya','NULL','Meaghan Greenholt','0729395605','meaghan-greenholt@example.com',18096000,'walkin','et','AAS7MIOPM','78341','active',NULL,'AR9TA','2025-08-14 14:02:49','2025-08-14 14:02:49',NULL,NULL),(10,'UCSL-84928','Tujenge Builders','tujenge-builders@ucsl.co.ke','$2y$12$eGD1tme43Bi1MValMxh/n.WkHyNdCWwa.yxGqkYFonLeQgTDGjvjy','0729395605','Kenyatta Avenue, Kisumu','Kisumu','Kenyatta Avenue','Kenya','NULL','Enos Wisozk','0729395605','enos-wisozk@example.com',39264124,'walkin','rerum','AEPQ2TV9P','16147','active',NULL,'FDS1W','2025-08-14 14:02:49','2025-08-14 14:02:49',NULL,NULL),(11,'UCSL-79317','James Opiyo','choroma@ufanisi.co.ke','$2y$12$2QMLWhvvY9Ns0pYGDV5zTuIM1ViY83eyApIYnvGn4UG5yZ3K88cBy','+254725525484','Nairobi','Nairobi','Nairobi','Kenya','NULL','Jeff Letting','0724911674','jeff.letting@ufanisi.co.ke',NULL,'walkin',NULL,'KP00EUE239','80100','active',NULL,NULL,'2025-08-17 08:26:36','2025-08-17 08:26:36',1,55662030),(12,'UCSL-72637','Shadrack Killu','ict-support@ufanisi.co.ke','$2y$12$qXD.LtSqQO6YT3KFCjyBbOSell.eSYivdTXxeWCuzYeQaErP6IXxa','+254782911674','50623','Nairobi','Nairobi','Kenya','NULL','Jeff','0782911674','jeffkip354@gmail.com',NULL,'walkin',NULL,'P0034JNDJ','80100','active',NULL,NULL,'2025-08-17 08:38:00','2025-08-17 08:38:00',2,22115510),(13,'UCSL-96110','Irene Kamau','jeffkip354@gmail.com','$2y$12$QWeGGAbc6BtGpJrGJ9FPE.9uDOuoA8yhqrB3nPOS/KF0zwiIUhRQa','+254724911674','Kawangware','Nairobi','City Mall','Kenya','NULL','Jeff','0724911674','jeffkip354@gmail.com',NULL,'walkin',NULL,'POO93429','00100','active',NULL,NULL,'2025-08-17 08:43:07','2025-08-17 08:43:07',2,31661035),(14,'UCSL-49989','Stephen Kiprono','jeff.letting@ufanisi.co.ke','$2y$12$98sSbaq/opUczWqEV6cElOe.guUwyP5hjdVOrwAQaPbABEofvswIm','+254724911674','Nairobi','Nairobi','-','Kenya','NULL','jeff','0782911674','jeff.letting@ufanisi.co.ke',NULL,'walkin',NULL,'-','80100','active',NULL,NULL,'2025-08-17 11:28:03','2025-08-17 11:29:14',3,21554120);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_infos`
--

DROP TABLE IF EXISTS `company_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_infos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slogan` text COLLATE utf8mb4_unicode_ci,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_infos`
--

LOCK TABLES `company_infos` WRITE;
/*!40000 ALTER TABLE `company_infos` DISABLE KEYS */;
INSERT INTO `company_infos` VALUES (1,'Ufanisi Courier Services Ltd','https://www.ufanisi-courier.co.ke','Nairobi, Kenya','Moi Avenue, Pioneer Building, Nairobi','P012345678A','logos/ufanisi.png','Delivering Efficiency Everywhere','+254712345678','info@ufanisi-courier.co.ke',NULL,NULL),(2,'ExpressGo Logistics','https://www.expressgo.co.ke','Mombasa, Kenya','Digo Road, Mombasa','P987654321B','logos/expressgo.png','Speed Meets Reliability','+254798765432','support@expressgo.co.ke',NULL,NULL),(3,'FastShip Africa','https://www.fastship.africa','Kisumu, Kenya','Jomo Kenyatta Hwy, Kisumu','P112233445C','logos/fastship.png','Your Partner in Rapid Delivery','+254700112233','contact@fastship.africa',NULL,NULL);
/*!40000 ALTER TABLE `company_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deliveries`
--

DROP TABLE IF EXISTS `deliveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `deliveryStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pickup',
  `deliveryNote` text COLLATE utf8mb4_unicode_ci,
  `recievedBy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recieverId` int DEFAULT NULL,
  `receiverIdNumber` int DEFAULT NULL,
  `recieverPhoneNumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recieverSignature` text COLLATE utf8mb4_unicode_ci,
  `servedBy` bigint unsigned DEFAULT NULL,
  `statusAtDelivery` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waybillNo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipment_id` bigint unsigned NOT NULL,
  `picture` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deliveries_servedby_foreign` (`servedBy`),
  CONSTRAINT `deliveries_servedby_foreign` FOREIGN KEY (`servedBy`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deliveries`
--

LOCK TABLES `deliveries` WRITE;
/*!40000 ALTER TABLE `deliveries` DISABLE KEYS */;
/*!40000 ALTER TABLE `deliveries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dispatchers`
--

DROP TABLE IF EXISTS `dispatchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispatchers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature` text COLLATE utf8mb4_unicode_ci,
  `office_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispatchers_office_id_foreign` (`office_id`),
  CONSTRAINT `dispatchers_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispatchers`
--

LOCK TABLES `dispatchers` WRITE;
/*!40000 ALTER TABLE `dispatchers` DISABLE KEYS */;
INSERT INTO `dispatchers` VALUES (1,'Alice Mutua','29561234','0721001001',NULL,1,'active','2025-08-14 14:02:49','2025-08-14 14:02:49'),(2,'Brian Otieno','30889900','0744123123',NULL,2,'inactive','2025-08-14 14:02:49','2025-08-14 14:02:49'),(3,'Jeff Letting','31661035','0724911674','signatures/yNYtstIxZPBn3cgSBkjl6ENRZbk9r7a2FScBm4lr.png',2,'active','2025-08-14 14:21:38','2025-08-14 14:21:38'),(4,'David Kenga','36554125','07829446',NULL,1,'active','2025-08-17 11:56:32','2025-08-17 11:56:32'),(5,'Mark Masaai','22563041','0782911674','signatures/T1nqHPnsNb8bqR7M2QtwFUosW7LdKyz0GAJGXeRH.png',1,'active','2025-08-17 11:57:19','2025-08-17 11:57:19'),(6,'David Obuya','55223020','0782911674','signatures/DgvvoKnWa0JX90HeSgmoqPucHbPD8VpO5fPYJBEm.png',2,'active','2025-08-17 11:58:45','2025-08-17 11:58:45');
/*!40000 ALTER TABLE `dispatchers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `front_offices`
--

DROP TABLE IF EXISTS `front_offices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `front_offices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `front_offices`
--

LOCK TABLES `front_offices` WRITE;
/*!40000 ALTER TABLE `front_offices` DISABLE KEYS */;
/*!40000 ALTER TABLE `front_offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guests`
--

LOCK TABLES `guests` WRITE;
/*!40000 ALTER TABLE `guests` DISABLE KEYS */;
/*!40000 ALTER TABLE `guests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incidents`
--

DROP TABLE IF EXISTS `incidents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `incidents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incidents`
--

LOCK TABLES `incidents` WRITE;
/*!40000 ALTER TABLE `incidents` DISABLE KEYS */;
/*!40000 ALTER TABLE `incidents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `due_date` datetime NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `shipment_collection_id` bigint unsigned NOT NULL,
  `invoiced_by` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Un Paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,'INV-00001',1044,'2025-09-13 17:18:13',8,1,5,'Un Paid','2025-08-14 14:18:13','2025-08-14 14:18:13'),(2,'INV-00002',870,'2025-09-14 08:59:39',5,3,1,'Un Paid','2025-08-15 05:59:39','2025-08-15 05:59:39'),(3,'INV-00003',754,'2025-09-14 09:31:22',7,4,1,'Un Paid','2025-08-15 06:31:22','2025-08-15 06:31:22'),(4,'INV-00004',406,'2025-09-14 09:35:58',8,5,1,'Un Paid','2025-08-15 06:35:58','2025-08-15 06:35:58'),(5,'INV-00005',580,'2025-09-14 14:59:01',8,8,5,'Un Paid','2025-08-15 11:59:01','2025-08-15 11:59:01'),(6,'INV-00006',464,'2025-09-16 11:58:59',11,13,2,'Un Paid','2025-08-17 08:58:59','2025-08-17 08:58:59');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loading_sheet_waybills`
--

DROP TABLE IF EXISTS `loading_sheet_waybills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loading_sheet_waybills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `loading_sheet_id` bigint unsigned NOT NULL,
  `waybill_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipment_item_id` int DEFAULT NULL,
  `shipment_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loading_sheet_waybills`
--

LOCK TABLES `loading_sheet_waybills` WRITE;
/*!40000 ALTER TABLE `loading_sheet_waybills` DISABLE KEYS */;
INSERT INTO `loading_sheet_waybills` VALUES (1,1,'UCSL0000000001KE',1,1,'2025-08-14 14:25:05','2025-08-14 14:25:05'),(2,2,'UCSL0000000002KE',2,2,'2025-08-14 14:49:26','2025-08-14 14:49:26'),(3,3,'UCSL0000000004KE',3,3,'2025-08-15 06:01:43','2025-08-15 06:01:43'),(4,4,'UCSL0000000008KE',7,7,'2025-08-15 10:03:05','2025-08-15 10:03:05'),(5,5,'UCSL0000000009KE',8,8,'2025-08-15 13:04:40','2025-08-15 13:04:40'),(6,6,'UCSL0000000010KE',9,9,'2025-08-15 13:35:59','2025-08-15 13:35:59'),(7,7,'UCSL0000000017KE',17,16,'2025-08-17 12:00:29','2025-08-17 12:00:29'),(8,7,'UCSL0000000018KE',18,17,'2025-08-17 12:00:29','2025-08-17 12:00:29'),(9,8,'UCSL0000000020KE',19,18,'2025-08-17 13:18:19','2025-08-17 13:18:19'),(10,9,'UCSL0000000022KE',20,19,'2025-08-17 18:18:45','2025-08-17 18:18:45'),(11,10,'UCSL0000000023KE',21,20,'2025-08-18 02:29:53','2025-08-18 02:29:53');
/*!40000 ALTER TABLE `loading_sheet_waybills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loading_sheets`
--

DROP TABLE IF EXISTS `loading_sheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loading_sheets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dispatch_date` datetime DEFAULT NULL,
  `dispatcher_id` bigint unsigned NOT NULL,
  `office_id` bigint unsigned NOT NULL,
  `station_id` bigint unsigned NOT NULL,
  `destination_id` bigint unsigned NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_no` int NOT NULL,
  `dispatched_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transported_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transporter_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reg_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transporter_signature` text COLLATE utf8mb4_unicode_ci,
  `vehicle_reg_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `received_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_id_no` int DEFAULT NULL,
  `received_date` datetime DEFAULT NULL,
  `receiver_signature` text COLLATE utf8mb4_unicode_ci,
  `date_closed` datetime DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `offloading_clerk` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loading_sheets_batch_no_unique` (`batch_no`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loading_sheets`
--

LOCK TABLES `loading_sheets` WRITE;
/*!40000 ALTER TABLE `loading_sheets` DISABLE KEYS */;
INSERT INTO `loading_sheets` VALUES (1,'2025-08-14 17:25:12',3,2,2,47,'47',1,'3','3',NULL,NULL,NULL,'11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-14 14:24:45','2025-08-14 14:27:56','Pending',3),(2,'2025-08-14 17:49:37',3,2,2,19,'19',2,'3','2',NULL,NULL,NULL,'4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-14 14:49:17','2025-08-14 14:51:02','Pending',2),(3,'2025-08-15 09:01:56',3,2,2,9,'9',3,'3','2',NULL,NULL,NULL,'6',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-15 06:01:37','2025-08-15 06:16:37','Pending',3),(4,'2025-08-15 13:03:23',3,2,2,47,'47',4,'3','2',NULL,NULL,NULL,'8',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-15 10:02:11','2025-08-15 10:06:30','Pending',3),(5,'2025-08-15 16:04:49',3,2,2,18,'18',5,'3','2',NULL,NULL,NULL,'10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-15 13:04:35','2025-08-15 13:05:39','Pending',3),(6,'2025-08-15 16:36:06',3,2,2,19,'19',6,'3','1',NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-15 13:35:48','2025-08-15 13:37:40','Pending',3),(7,'2025-08-17 15:01:40',3,2,2,9,'9',7,'3','3',NULL,NULL,NULL,'12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-17 11:53:39','2025-08-17 12:14:50','Pending',3),(8,'2025-08-17 16:18:30',3,2,2,9,'9',8,'3','3',NULL,NULL,NULL,'13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-17 13:17:09','2025-08-17 13:35:54','Pending',3),(9,'2025-08-17 21:18:58',3,2,2,9,'9',9,'3','3',NULL,NULL,NULL,'14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-17 18:18:02','2025-08-17 18:52:06','Pending',4),(10,'2025-08-18 05:30:06',3,2,2,9,'9',10,'3','3',NULL,NULL,NULL,'15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-18 02:25:48','2025-08-18 02:30:35','Pending',1);
/*!40000 ALTER TABLE `loading_sheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'Kawangware','2025-08-15 05:56:19','2025-08-15 05:56:19'),(2,'Madaraka','2025-08-15 14:57:21','2025-08-15 14:57:21'),(3,'BuruBuru','2025-08-17 06:47:16','2025-08-17 06:47:16'),(4,'Adams Arcade','2025-08-17 10:38:34','2025-08-17 10:38:34'),(5,'Kayole','2025-08-17 13:07:12','2025-08-17 13:07:12');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (60,'0001_01_01_000000_create_users_table',1),(61,'0001_01_01_000001_create_cache_table',1),(62,'0001_01_01_000002_create_jobs_table',1),(63,'2025_04_04_131210_create_company_infos_table',1),(64,'2025_04_04_131227_create_clients_table',1),(65,'2025_04_04_131247_create_services_table',1),(66,'2025_04_04_131300_create_shipments_table',1),(67,'2025_04_04_131302_create_vehicles_table',1),(68,'2025_04_04_131326_create_offices_table',1),(69,'2025_04_04_131404_create_rates_table',1),(70,'2025_04_04_144038_create_trackings_table',1),(71,'2025_04_04_144103_create_sales_table',1),(72,'2025_04_04_144125_create_payments_table',1),(73,'2025_04_04_144138_create_invoices_table',1),(74,'2025_04_04_144227_create_waybills_table',1),(75,'2025_04_04_144259_create_incidents_table',1),(76,'2025_04_04_144346_create_deliveries_table',1),(77,'2025_04_07_062122_create_billings_table',1),(78,'2025_04_07_072740_create_cash_credits_table',1),(79,'2025_04_07_072754_create_cash_collecteds_table',1),(80,'2025_05-07_120030_create_client_requests_table',1),(81,'2025_05_05_065033_create_stations_table',1),(82,'2025_05_06_031832_create_service_levels_table',1),(83,'2025_05_06_034605_create_shipment_items_table',1),(84,'2025_05_08_131949_create_zones_table',1),(85,'2025_05_12_113929_create_shipment_collections_table',1),(86,'2025_05_12_125343_create_front_offices_table',1),(87,'2025_05_24_070610_create_shipment_sub_items',1),(88,'2025_05_26_132108_create_tracks_table',1),(89,'2025_05_26_132438_create_tracking_infos_table',1),(90,'2025_06_10_120922_create_sent_messages_table',1),(91,'2025_06_13_120407_create_special_rates_table',1),(92,'2025_06_15_114212_create_personal_access_tokens_table',1),(93,'2025_06_16_084554_create_guests_table',1),(94,'2025_06_16_140953_create_user_logs_table',1),(95,'2025_06_23_084552_create_categories_table',1),(96,'2025_06_23_084928_create_sub_categories_table',1),(97,'2025_06_23_085109_create_client_categories_table',1),(98,'2025_06_24_102250_create_locations_table',1),(99,'2025_06_25_090938_create_loading_sheets_table',1),(100,'2025_06_25_111917_create_dispatchers_table',1),(101,'2025_06_25_115855_create_transporters_table',1),(102,'2025_06_25_115918_create_transporter_trucks_table',1),(103,'2025_06_26_084354_create_vehicle_allocations_table',1),(104,'2025_06_26_193722_create_loading_sheet_waybills_table',1),(105,'2025_07_21_061306_create_same_day_rates_table',1),(106,'2025_07_24_150515_create_sales_people_table',1),(107,'2025_07_24_172908_add_sales_person_id_to_clients_table',1),(108,'2025_07_25_141508_create_shipment_deliveries_table',1),(109,'2025_07_28_145246_add_status_to_loading_sheets',1),(110,'2025_07_28_160812_add_agent_approved_to_shipment_collections_table',1),(111,'2025_07_30_151458_add_agent_requested_to_shipment_collections_table',1),(112,'2025_07_30_164820_add_approval_remarks_to_shipment_collections_table',1),(113,'2025_08_01_164307_add_offloading_clerk_to_loading_sheets_table',1),(114,'2025_08_01_171801_add_approval_fields_to_shipment_collections_table',1),(115,'2025_08_02_131330_add_priority_and_deadline_to_client_requests_table',1),(116,'2025_08_11_115242_shipment_arrivals_table',1),(117,'2025_08_11_115319_shipment_arrivals_items_table',1),(118,'2025_08_12_174317_add_delivery_rider_to_shipment_arrivals_table',1),(119,'2025_08_17_114624_create_receiver_details_table',2),(120,'2025_08_17_225751_add_delivery_rider_to_shipment_collections_table',2),(121,'2025_08_18_054558_add_delivery_rider_to_client_requests_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offices`
--

DROP TABLE IF EXISTS `offices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `createdBy` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` text COLLATE utf8mb4_unicode_ci,
  `latitude` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `mpesaTill` int DEFAULT NULL,
  `mpesaPaybill` int DEFAULT NULL,
  `mpesaTillStkCallBack` text COLLATE utf8mb4_unicode_ci,
  `mpesaTillC2bConfirmation` text COLLATE utf8mb4_unicode_ci,
  `mpesaTillC2bValidation` text COLLATE utf8mb4_unicode_ci,
  `mpesaPaybillStkCallBack` text COLLATE utf8mb4_unicode_ci,
  `mpesaPaybillC2bConfirmation` text COLLATE utf8mb4_unicode_ci,
  `mpesaPaybillC2bValidation` text COLLATE utf8mb4_unicode_ci,
  `approvedBy` int DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `offices_createdby_foreign` (`createdBy`),
  CONSTRAINT `offices_createdby_foreign` FOREIGN KEY (`createdBy`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offices`
--

LOCK TABLES `offices` WRITE;
/*!40000 ALTER TABLE `offices` DISABLE KEYS */;
INSERT INTO `offices` VALUES (1,5,'Mombasa Office','MBS','Kenya','Mombasa','39.6682','-4.0435','staff',123456,654321,'https://example.com/mbs/till/stk','https://example.com/mbs/till/confirm','https://example.com/mbs/till/validate','https://example.com/mbs/paybill/stk','https://example.com/mbs/paybill/confirm','https://example.com/mbs/paybill/validate',5,'active',NULL,NULL),(2,5,'Nairobi Office','NRB','Kenya','Nairobi','36.8219','-1.2921','staff',234567,765432,'https://example.com/nrb/till/stk','https://example.com/nrb/till/confirm','https://example.com/nrb/till/validate','https://example.com/nrb/paybill/stk','https://example.com/nrb/paybill/confirm','https://example.com/nrb/paybill/validate',5,'active',NULL,NULL),(3,5,'Kisumu Office','KSM','Kenya','Kisumu','34.7617','-0.0917','agent',345678,876543,'https://example.com/ksm/till/stk','https://example.com/ksm/till/confirm','https://example.com/ksm/till/validate','https://example.com/ksm/paybill/stk','https://example.com/ksm/paybill/confirm','https://example.com/ksm/paybill/validate',5,'active',NULL,NULL),(4,5,'Nakuru Office','NKR','Kenya','Nakuru','36.0800','-0.3031','agent',456789,987654,'https://example.com/nkr/till/stk','https://example.com/nkr/till/confirm','https://example.com/nkr/till/validate','https://example.com/nkr/paybill/stk','https://example.com/nkr/paybill/confirm','https://example.com/nkr/paybill/validate',5,'pending',NULL,NULL),(5,5,'Malindi Office','MLD','Kenya','Malindi','40.1169','-3.2192','staff',567890,198765,'https://example.com/mld/till/stk','https://example.com/mld/till/confirm','https://example.com/mld/till/validate','https://example.com/mld/paybill/stk','https://example.com/mld/paybill/confirm','https://example.com/mld/paybill/validate',5,'active',NULL,NULL);
/*!40000 ALTER TABLE `offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_paid` datetime NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `shipment_collection_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending Verification',
  `paid_by` int NOT NULL,
  `received_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,'M-Pesa',1044,'TRTQ192NJN','2025-08-14 17:31:45',8,1,'Pending Verification',5,'5','5','2025-08-14 14:31:45','2025-08-14 14:31:45'),(2,'M-Pesa',580,'TRTQ747FDJ','2025-08-14 17:47:11',8,2,'Pending Verification',8,'5','5','2025-08-14 14:47:11','2025-08-14 14:47:11'),(3,'M-Pesa',464,'TRTQ837DSN','2025-08-15 10:38:37',8,6,'Pending Verification',8,'5','5','2025-08-15 07:38:37','2025-08-15 07:38:37'),(4,'Invoice',870,'INV-00002','2025-08-15 11:42:03',5,3,'Pending Verification',5,'5','5','2025-08-15 08:42:03','2025-08-15 08:42:03'),(5,'M-Pesa',1334,'TRTQ100YHD','2025-08-15 12:59:32',8,7,'Pending Verification',8,'5','5','2025-08-15 09:59:32','2025-08-15 09:59:32'),(6,'Invoice',580,'INV-00005','2025-08-15 16:14:12',8,8,'Pending Verification',5,'5','5','2025-08-15 13:14:12','2025-08-15 13:14:12'),(7,'M-Pesa',870,'TRTQGSH472','2025-08-15 16:26:44',8,9,'Pending Verification',8,'5','5','2025-08-15 13:26:44','2025-08-15 13:26:44'),(8,'M-Pesa',1566,'TR65HFSDFV','2025-08-17 12:39:20',12,14,'Pending Verification',12,'1','1','2025-08-17 09:39:20','2025-08-17 09:39:20'),(9,'M-Pesa',2320,'TRW6374888','2025-08-17 14:31:40',14,16,'Pending Verification',14,'4','4','2025-08-17 11:31:40','2025-08-17 11:31:40'),(10,'M-Pesa',2320,'TRHDBDDN83','2025-08-17 14:52:45',14,17,'Pending Verification',14,'4','4','2025-08-17 11:52:45','2025-08-17 11:52:45'),(11,'M-Pesa',2030,'DGD47573','2025-08-17 16:16:18',6,18,'Pending Verification',6,'5','5','2025-08-17 13:16:18','2025-08-17 13:16:18'),(12,'M-Pesa',580,'TRTQ928HJK','2025-08-17 21:15:59',6,19,'Pending Verification',6,'2','2','2025-08-17 18:15:59','2025-08-17 18:15:59'),(13,'M-Pesa',580,'TRHDNSS443','2025-08-18 05:25:04',8,20,'Pending Verification',8,'2','2','2025-08-18 02:25:04','2025-08-18 02:25:04'),(14,'M-Pesa',580,'TRTQ882UYN','2025-08-18 10:27:34',8,21,'Pending Verification',8,'2','2','2025-08-18 07:27:34','2025-08-18 07:27:34');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rates`
--

DROP TABLE IF EXISTS `rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `approvedBy` int DEFAULT NULL,
  `added_by` bigint unsigned NOT NULL,
  `office_id` bigint unsigned NOT NULL,
  `zone_id` bigint unsigned DEFAULT NULL,
  `routeFrom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(8,2) NOT NULL DEFAULT '0.00',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `bands` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_cost_per_kg` int DEFAULT NULL,
  `intercity_additional_cost_per_kg` int DEFAULT NULL,
  `applicableFrom` datetime DEFAULT NULL,
  `applicableTo` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `approvalStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `dateApproved` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rates_added_by_foreign` (`added_by`),
  CONSTRAINT `rates_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rates`
--

LOCK TABLES `rates` WRITE;
/*!40000 ALTER TABLE `rates` DISABLE KEYS */;
INSERT INTO `rates` VALUES (1,NULL,9,2,1,'Nairobi - Machakos','Zone 1A','Nairobi','Machakos',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(2,NULL,9,2,1,'Nairobi - Emali','Zone 1A','Nairobi','Emali',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(3,NULL,9,2,1,'Nairobi - Makindu','Zone 1A','Nairobi','Makindu',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(4,NULL,9,2,1,'Nairobi - Kibwezi','Zone 1A','Nairobi','Kibwezi',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(5,NULL,9,2,1,'Nairobi - Mtito','Zone 1A','Nairobi','Mtito',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(6,NULL,9,2,1,'Nairobi - Voi','Zone 1A','Nairobi','Voi',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(7,NULL,9,2,1,'Nairobi - Mariakani','Zone 1A','Nairobi','Mariakani',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(8,NULL,9,2,1,'Nairobi - Mazeras','Zone 1A','Nairobi','Mazeras',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(9,NULL,9,2,1,'Nairobi - Mombasa','Zone 1A','Nairobi','Mombasa',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(10,NULL,9,2,1,'Nairobi - Kitui','Zone 1A','Nairobi','Kitui',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(11,NULL,9,2,2,'Nairobi - Naivasha','Zone 2A','Nairobi','Naivasha',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(12,NULL,9,2,2,'Nairobi - Gilgil','Zone 2A','Nairobi','Gilgil',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(13,NULL,9,2,2,'Nairobi - Nakuru','Zone 2A','Nairobi','Nakuru',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(14,NULL,9,2,2,'Nairobi - Kericho','Zone 2A','Nairobi','Kericho',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(15,NULL,9,2,2,'Nairobi - Kisumu','Zone 2A','Nairobi','Kisumu',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(16,NULL,9,2,2,'Nairobi - Narok','Zone 2A','Nairobi','Narok',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(17,NULL,9,2,2,'Nairobi - Bomet','Zone 2A','Nairobi','Bomet',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(18,NULL,9,2,2,'Nairobi - Eldoret','Zone 2A','Nairobi','Eldoret',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(19,NULL,9,2,2,'Nairobi - Kakamega','Zone 2A','Nairobi','Kakamega',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(20,NULL,9,2,2,'Nairobi - Kisii','Zone 2A','Nairobi','Kisii',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(21,NULL,9,2,2,'Nairobi - Kitale','Zone 2A','Nairobi','Kitale',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(22,NULL,9,2,2,'Nairobi - Litein','Zone 2A','Nairobi','Litein',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(23,NULL,9,2,3,'Nairobi - Thika','Zone 3A','Nairobi','Thika',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(24,NULL,9,2,3,'Nairobi - Sagana','Zone 3A','Nairobi','Sagana',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(25,NULL,9,2,3,'Nairobi - Muranga','Zone 3A','Nairobi','Muranga',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(26,NULL,9,2,3,'Nairobi - Kerugoya','Zone 3A','Nairobi','Kerugoya',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(27,NULL,9,2,3,'Nairobi - Karatina','Zone 3A','Nairobi','Karatina',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(28,NULL,9,2,3,'Nairobi - Nyeri','Zone 3A','Nairobi','Nyeri',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(29,NULL,9,2,3,'Nairobi - Othaya','Zone 3A','Nairobi','Othaya',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(30,NULL,9,2,3,'Nairobi - Nanyuki','Zone 3A','Nairobi','Nanyuki',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(31,NULL,9,2,3,'Nairobi - Embu','Zone 3A','Nairobi','Embu',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(32,NULL,9,2,3,'Nairobi - Matuu','Zone 3A','Nairobi','Matuu',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(33,NULL,9,2,3,'Nairobi - Nkubu','Zone 3A','Nairobi','Nkubu',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(34,NULL,9,2,3,'Nairobi - Nyahururu','Zone 3A','Nairobi','Nyahururu',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(35,NULL,9,2,3,'Nairobi - Chuka','Zone 3A','Nairobi','Chuka',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(36,NULL,9,2,4,'Nairobi - Diani','Zone 1B','Nairobi','Diani',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(37,NULL,9,2,4,'Nairobi - Malindi','Zone 1B','Nairobi','Malindi',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(38,NULL,9,2,4,'Nairobi - Watamu','Zone 1B','Nairobi','Watamu',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(39,NULL,9,2,4,'Nairobi - Kilifi','Zone 1B','Nairobi','Kilifi',900.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(40,NULL,9,2,4,'Nairobi - Mtwapa','Zone 1B','Nairobi','Mtwapa',800.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(41,NULL,9,2,4,'Nairobi - Kwale','Zone 1B','Nairobi','Kwale',800.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(42,NULL,9,2,4,'Nairobi - Lamu','Zone 1B','Nairobi','Lamu',2500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(43,NULL,9,2,4,'Nairobi - Namanga','Zone 1B','Nairobi','Namanga',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(44,NULL,9,2,5,'Nairobi - Bungoma','Zone 2B','Nairobi','Bungoma',600.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(45,NULL,9,2,5,'Nairobi - Kapsabet','Zone 2B','Nairobi','Kapsabet',600.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(46,NULL,9,2,5,'Nairobi - Migori','Zone 2B','Nairobi','Migori',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(47,NULL,9,2,5,'Nairobi - Homabay','Zone 2B','Nairobi','Homabay',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(48,NULL,9,2,5,'Nairobi - Busia','Zone 2B','Nairobi','Busia',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(49,NULL,9,2,5,'Nairobi - Siaya','Zone 2B','Nairobi','Siaya',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(50,NULL,9,2,5,'Nairobi - Awendo','Zone 2B','Nairobi','Awendo',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(51,NULL,9,2,5,'Nairobi - Muhoroni','Zone 2B','Nairobi','Muhoroni',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(52,NULL,9,2,5,'Nairobi - Bondo','Zone 2B','Nairobi','Bondo',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(53,NULL,9,2,6,'Nairobi - Maua','Zone 3B','Nairobi','Maua',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(54,NULL,9,2,6,'Nairobi - Isiolo','Zone 3B','Nairobi','Isiolo',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(55,NULL,9,2,6,'Nairobi - Meru','Zone 3B','Nairobi','Meru',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(56,NULL,9,2,6,'Nairobi - Garissa','Zone 3B','Nairobi','Garissa',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(57,NULL,9,2,6,'Nairobi - Mwingi','Zone 3B','Nairobi','Mwingi',650.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(58,NULL,9,1,1,'Mombasa - Machakos','Zone 1A','Mombasa','Machakos',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(59,NULL,9,1,1,'Mombasa - Emali','Zone 1A','Mombasa','Emali',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(60,NULL,9,1,1,'Mombasa - Makindu','Zone 1A','Mombasa','Makindu',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(61,NULL,9,1,1,'Mombasa - Kibwezi','Zone 1A','Mombasa','Kibwezi',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(62,NULL,9,1,1,'Mombasa - Mtito','Zone 1A','Mombasa','Mtito',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(63,NULL,9,1,1,'Mombasa - Voi','Zone 1A','Mombasa','Voi',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(64,NULL,9,1,1,'Mombasa - Mariakani','Zone 1A','Mombasa','Mariakani',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(65,NULL,9,1,1,'Mombasa - Mazeras','Zone 1A','Mombasa','Mazeras',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(66,NULL,9,1,1,'Mombasa - Nairobi','Zone 1A','Mombasa','Nairobi',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(67,NULL,9,1,2,'Mombasa - Naivasha','Zone 2A','Mombasa','Naivasha',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(68,NULL,9,1,2,'Mombasa - Gilgil','Zone 2A','Mombasa','Gilgil',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(69,NULL,9,1,2,'Mombasa - Nakuru','Zone 2A','Mombasa','Nakuru',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(70,NULL,9,1,2,'Mombasa - Kericho','Zone 2A','Mombasa','Kericho',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(71,NULL,9,1,2,'Mombasa - Kisumu','Zone 2A','Mombasa','Kisumu',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(72,NULL,9,1,2,'Mombasa - Narok','Zone 2A','Mombasa','Narok',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(73,NULL,9,1,2,'Mombasa - Bomet','Zone 2A','Mombasa','Bomet',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(74,NULL,9,1,2,'Mombasa - Eldoret','Zone 2A','Mombasa','Eldoret',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(75,NULL,9,1,2,'Mombasa - Kakamega','Zone 2A','Mombasa','Kakamega',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(76,NULL,9,1,2,'Mombasa - Kisii','Zone 2A','Mombasa','Kisii',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(77,NULL,9,1,2,'Mombasa - Kitale','Zone 2A','Mombasa','Kitale',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(78,NULL,9,1,2,'Mombasa - Litein','Zone 2A','Mombasa','Litein',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(79,NULL,9,1,3,'Mombasa - Thika','Zone 3A','Mombasa','Thika',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(80,NULL,9,1,3,'Mombasa - Sagana','Zone 3A','Mombasa','Sagana',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(81,NULL,9,1,3,'Mombasa - Muranga','Zone 3A','Mombasa','Muranga',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(82,NULL,9,1,3,'Mombasa - Kerugoya','Zone 3A','Mombasa','Kerugoya',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(83,NULL,9,1,3,'Mombasa - Karatina','Zone 3A','Mombasa','Karatina',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(84,NULL,9,1,3,'Mombasa - Nyeri','Zone 3A','Mombasa','Nyeri',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(85,NULL,9,1,3,'Mombasa - Othaya','Zone 3A','Mombasa','Othaya',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(86,NULL,9,1,3,'Mombasa - Nanyuki','Zone 3A','Mombasa','Nanyuki',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(87,NULL,9,1,3,'Mombasa - Embu','Zone 3A','Mombasa','Embu',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(88,NULL,9,1,3,'Mombasa - Matuu','Zone 3A','Mombasa','Matuu',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(89,NULL,9,1,3,'Mombasa - Nkubu','Zone 3A','Mombasa','Nkubu',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(90,NULL,9,1,3,'Mombasa - Nyahururu','Zone 3A','Mombasa','Nyahururu',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(91,NULL,9,1,3,'Mombasa - Chuka','Zone 3A','Mombasa','Chuka',1000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(92,NULL,9,1,4,'Mombasa - Diani','Zone 1B','Mombasa','Diani',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(93,NULL,9,1,4,'Mombasa - Malindi','Zone 1B','Mombasa','Malindi',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(94,NULL,9,1,4,'Mombasa - Watamu','Zone 1B','Mombasa','Watamu',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(95,NULL,9,1,4,'Mombasa - Kilifi','Zone 1B','Mombasa','Kilifi',400.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(96,NULL,9,1,4,'Mombasa - Mtwapa','Zone 1B','Mombasa','Mtwapa',300.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(97,NULL,9,1,4,'Mombasa - Kwale','Zone 1B','Mombasa','Kwale',500.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(98,NULL,9,1,4,'Mombasa - Lamu','Zone 1B','Mombasa','Lamu',2000.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(99,NULL,9,1,5,'Mombasa - Bungoma','Zone 2B','Mombasa','Bungoma',1100.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(100,NULL,9,1,5,'Mombasa - Kapsabet','Zone 2B','Mombasa','Kapsabet',1100.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(101,NULL,9,1,5,'Mombasa - Migori','Zone 2B','Mombasa','Migori',1100.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(102,NULL,9,1,5,'Mombasa - Homabay','Zone 2B','Mombasa','Homabay',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(103,NULL,9,1,5,'Mombasa - Busia','Zone 2B','Mombasa','Busia',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(104,NULL,9,1,5,'Mombasa - Siaya','Zone 2B','Mombasa','Siaya',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(105,NULL,9,1,5,'Mombasa - Muhoroni','Zone 2B','Mombasa','Muhoroni',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(106,NULL,9,1,5,'Mombasa - Awendo','Zone 2B','Mombasa','Awendo',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(107,NULL,9,1,6,'Mombasa - Maua','Zone 3B','Mombasa','Maua',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(108,NULL,9,1,6,'Mombasa - Isiolo','Zone 3B','Mombasa','Isiolo',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(109,NULL,9,1,6,'Mombasa - Meru','Zone 3B','Mombasa','Meru',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(110,NULL,9,1,6,'Mombasa - Garissa','Zone 3B','Mombasa','Garissa',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(111,NULL,9,1,6,'Mombasa - Mwingi','Zone 3B','Mombasa','Mwingi',1150.00,'normal',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(112,NULL,9,2,1,'Nairobi - Machakos','Zone 1A','Nairobi','Machakos',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(113,NULL,9,2,1,'Nairobi - Emali','Zone 1A','Nairobi','Emali',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(114,NULL,9,2,1,'Nairobi - Makindu','Zone 1A','Nairobi','Makindu',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(115,NULL,9,2,1,'Nairobi - Kibwezi','Zone 1A','Nairobi','Kibwezi',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(116,NULL,9,2,1,'Nairobi - Mtito','Zone 1A','Nairobi','Mtito',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(117,NULL,9,2,1,'Nairobi - Voi','Zone 1A','Nairobi','Voi',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(118,NULL,9,2,1,'Nairobi - Mariakani','Zone 1A','Nairobi','Mariakani',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(119,NULL,9,2,1,'Nairobi - Mazeras','Zone 1A','Nairobi','Mazeras',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(120,NULL,9,2,1,'Nairobi - Mombasa','Zone 1A','Nairobi','Mombasa',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(121,NULL,9,2,1,'Nairobi - Kitui','Zone 1A','Nairobi','Kitui',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(122,NULL,9,2,2,'Nairobi - Naivasha','Zone 2A','Nairobi','Naivasha',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(123,NULL,9,2,2,'Nairobi - Gilgil','Zone 2A','Nairobi','Gilgil',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(124,NULL,9,2,2,'Nairobi - Nakuru','Zone 2A','Nairobi','Nakuru',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(125,NULL,9,2,2,'Nairobi - Kericho','Zone 2A','Nairobi','Kericho',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(126,NULL,9,2,2,'Nairobi - Kisumu','Zone 2A','Nairobi','Kisumu',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(127,NULL,9,2,2,'Nairobi - Narok','Zone 2A','Nairobi','Narok',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(128,NULL,9,2,2,'Nairobi - Bomet','Zone 2A','Nairobi','Bomet',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(129,NULL,9,2,2,'Nairobi - Eldoret','Zone 2A','Nairobi','Eldoret',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(130,NULL,9,2,2,'Nairobi - Kakamega','Zone 2A','Nairobi','Kakamega',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(131,NULL,9,2,2,'Nairobi - Kisii','Zone 2A','Nairobi','Kisii',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(132,NULL,9,2,2,'Nairobi - Kitale','Zone 2A','Nairobi','Kitale',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(133,NULL,9,2,2,'Nairobi - Litein','Zone 2A','Nairobi','Litein',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(134,NULL,9,2,3,'Nairobi - Thika','Zone 3A','Nairobi','Thika',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(135,NULL,9,2,3,'Nairobi - Sagana','Zone 3A','Nairobi','Sagana',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(136,NULL,9,2,3,'Nairobi - Muranga','Zone 3A','Nairobi','Muranga',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(137,NULL,9,2,3,'Nairobi - Kerugoya','Zone 3A','Nairobi','Kerugoya',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(138,NULL,9,2,3,'Nairobi - Karatina','Zone 3A','Nairobi','Karatina',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(139,NULL,9,2,3,'Nairobi - Nyeri','Zone 3A','Nairobi','Nyeri',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(140,NULL,9,2,3,'Nairobi - Othaya','Zone 3A','Nairobi','Othaya',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(141,NULL,9,2,3,'Nairobi - Nanyuki','Zone 3A','Nairobi','Nanyuki',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(142,NULL,9,2,3,'Nairobi - Embu','Zone 3A','Nairobi','Embu',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(143,NULL,9,2,3,'Nairobi - Matuu','Zone 3A','Nairobi','Matuu',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(144,NULL,9,2,3,'Nairobi - Nkubu','Zone 3A','Nairobi','Nkubu',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(145,NULL,9,2,3,'Nairobi - Nyahururu','Zone 3A','Nairobi','Nyahururu',431.03,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(146,NULL,9,2,4,'Nairobi - Diani','Zone 1B','Nairobi','Diani',862.06,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(147,NULL,9,2,4,'Nairobi - Malindi','Zone 1B','Nairobi','Malindi',862.06,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(148,NULL,9,2,4,'Nairobi - Watamu','Zone 1B','Nairobi','Watamu',862.06,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(149,NULL,9,2,4,'Nairobi - Kilifi','Zone 1B','Nairobi','Kilifi',862.06,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(150,NULL,9,2,4,'Nairobi - Mtwapa','Zone 1B','Nairobi','Mtwapa',862.06,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(151,NULL,9,2,4,'Nairobi - Kwale','Zone 1B','Nairobi','Kwale',862.06,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(152,NULL,9,2,5,'Nairobi - Bungoma','Zone 2B','Nairobi','Bungoma',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(153,NULL,9,2,5,'Nairobi - Kapsabet','Zone 2B','Nairobi','Kapsabet',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(154,NULL,9,2,5,'Nairobi - Migori','Zone 2B','Nairobi','Migori',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(155,NULL,9,2,5,'Nairobi - Homabay','Zone 2B','Nairobi','Homabay',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(156,NULL,9,2,5,'Nairobi - Busia','Zone 2B','Nairobi','Busia',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(157,NULL,9,2,5,'Nairobi - Siaya','Zone 2B','Nairobi','Siaya',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(158,NULL,9,2,5,'Nairobi - Awendo','Zone 2B','Nairobi','Awendo',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(159,NULL,9,2,5,'Nairobi - Muhoroni','Zone 2B','Nairobi','Muhoroni',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(160,NULL,9,2,5,'Nairobi - Bondo','Zone 2B','Nairobi','Bondo',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(161,NULL,9,2,6,'Nairobi - Maua','Zone 3B','Nairobi','Maua',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(162,NULL,9,2,6,'Nairobi - Isiolo','Zone 3B','Nairobi','Isiolo',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(163,NULL,9,2,6,'Nairobi - Meru','Zone 3B','Nairobi','Meru',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(164,NULL,9,2,6,'Nairobi - Garissa','Zone 3B','Nairobi','Garissa',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(165,NULL,9,2,6,'Nairobi - Mwingi','Zone 3B','Nairobi','Mwingi',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(166,NULL,9,2,6,'Nairobi - Kwale','Zone 3B','Nairobi','Kwale',560.34,'pharmaceutical',NULL,NULL,NULL,'2025-08-14 17:02:46',NULL,'active','approved',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(167,NULL,9,2,NULL,'Nairobi - CBD','WITHIN CBD','Nairobi','CBD',300.00,'same_day','WITHIN CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(168,NULL,9,2,NULL,'Nairobi - Adams Arcade','UP TO 5KM FROM CBD','Nairobi','Adams Arcade',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(169,NULL,9,2,NULL,'Nairobi - Bahati','UP TO 5KM FROM CBD','Nairobi','Bahati',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(170,NULL,9,2,NULL,'Nairobi - Chiromo','UP TO 5KM FROM CBD','Nairobi','Chiromo',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(171,NULL,9,2,NULL,'Nairobi - Eastlands','UP TO 5KM FROM CBD','Nairobi','Eastlands',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(172,NULL,9,2,NULL,'Nairobi - Eastleigh','UP TO 5KM FROM CBD','Nairobi','Eastleigh',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(173,NULL,9,2,NULL,'Nairobi - Highridge','UP TO 5KM FROM CBD','Nairobi','Highridge',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(174,NULL,9,2,NULL,'Nairobi - Industrial Area','UP TO 5KM FROM CBD','Nairobi','Industrial Area',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(175,NULL,9,2,NULL,'Nairobi - Kileleshwa','UP TO 5KM FROM CBD','Nairobi','Kileleshwa',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(176,NULL,9,2,NULL,'Nairobi - Kilimani','UP TO 5KM FROM CBD','Nairobi','Kilimani',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(177,NULL,9,2,NULL,'Nairobi - Lavington','UP TO 5KM FROM CBD','Nairobi','Lavington',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(178,NULL,9,2,NULL,'Nairobi - Madaraka','UP TO 5KM FROM CBD','Nairobi','Madaraka',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(179,NULL,9,2,NULL,'Nairobi - Makongeni','UP TO 5KM FROM CBD','Nairobi','Makongeni',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(180,NULL,9,2,NULL,'Nairobi - Ngara','UP TO 5KM FROM CBD','Nairobi','Ngara',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(181,NULL,9,2,NULL,'Nairobi - Muthaiga','UP TO 5KM FROM CBD','Nairobi','Muthaiga',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(182,NULL,9,2,NULL,'Nairobi - Ngummo','UP TO 5KM FROM CBD','Nairobi','Ngummo',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(183,NULL,9,2,NULL,'Nairobi - Nyayo Highrise','UP TO 5KM FROM CBD','Nairobi','Nyayo Highrise',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(184,NULL,9,2,NULL,'Nairobi - Pangani','UP TO 5KM FROM CBD','Nairobi','Pangani',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(185,NULL,9,2,NULL,'Nairobi - Parklands','UP TO 5KM FROM CBD','Nairobi','Parklands',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(186,NULL,9,2,NULL,'Nairobi - Yaya Center','UP TO 5KM FROM CBD','Nairobi','Yaya Center',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(187,NULL,9,2,NULL,'Nairobi - Riverside Park','UP TO 5KM FROM CBD','Nairobi','Riverside Park',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(188,NULL,9,2,NULL,'Nairobi - South B','UP TO 5KM FROM CBD','Nairobi','South B',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(189,NULL,9,2,NULL,'Nairobi - South C','UP TO 5KM FROM CBD','Nairobi','South C',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(190,NULL,9,2,NULL,'Nairobi - Starehe','UP TO 5KM FROM CBD','Nairobi','Starehe',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(191,NULL,9,2,NULL,'Nairobi - Upper Hill','UP TO 5KM FROM CBD','Nairobi','Upper Hill',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(192,NULL,9,2,NULL,'Nairobi - Village Market','UP TO 5KM FROM CBD','Nairobi','Village Market',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(193,NULL,9,2,NULL,'Nairobi - Westlands','UP TO 5KM FROM CBD','Nairobi','Westlands',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(194,NULL,9,2,NULL,'Nairobi - Woodley','UP TO 5KM FROM CBD','Nairobi','Woodley',350.00,'Same Day','UP TO 5KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(195,NULL,9,2,NULL,'Nairobi - Avenue Park','6 TO 15KM FROM CBD','Nairobi','Avenue Park',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(196,NULL,9,2,NULL,'Nairobi - Baba Dogo','6 TO 15KM FROM CBD','Nairobi','Baba Dogo',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(197,NULL,9,2,NULL,'Nairobi - Banda','6 TO 15KM FROM CBD','Nairobi','Banda',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(198,NULL,9,2,NULL,'Nairobi - Bomas','6 TO 15KM FROM CBD','Nairobi','Bomas',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(199,NULL,9,2,NULL,'Nairobi - BuruBuru','6 TO 15KM FROM CBD','Nairobi','BuruBuru',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(200,NULL,9,2,NULL,'Nairobi - Continental','6 TO 15KM FROM CBD','Nairobi','Continental',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(201,NULL,9,2,NULL,'Nairobi - Donholm','6 TO 15KM FROM CBD','Nairobi','Donholm',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(202,NULL,9,2,NULL,'Nairobi - Drive-in','6 TO 15KM FROM CBD','Nairobi','Drive-in',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(203,NULL,9,2,NULL,'Nairobi - Evergreen','6 TO 15KM FROM CBD','Nairobi','Evergreen',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(204,NULL,9,2,NULL,'Nairobi - Fedha','6 TO 15KM FROM CBD','Nairobi','Fedha',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(205,NULL,9,2,NULL,'Nairobi - Greenfield','6 TO 15KM FROM CBD','Nairobi','Greenfield',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(206,NULL,9,2,NULL,'Nairobi - Hill View','6 TO 15KM FROM CBD','Nairobi','Hill View',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(207,NULL,9,2,NULL,'Nairobi - Lang\'ata','6 TO 15KM FROM CBD','Nairobi','Lang\'ata',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(208,NULL,9,2,NULL,'Nairobi - Jacaranda','6 TO 15KM FROM CBD','Nairobi','Jacaranda',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(209,NULL,9,2,NULL,'Nairobi - Kasarani','6 TO 15KM FROM CBD','Nairobi','Kasarani',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(210,NULL,9,2,NULL,'Nairobi - Kangemi','6 TO 15KM FROM CBD','Nairobi','Kangemi',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(211,NULL,9,2,NULL,'Nairobi - Karura','6 TO 15KM FROM CBD','Nairobi','Karura',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(212,NULL,9,2,NULL,'Nairobi - New Runda','6 TO 15KM FROM CBD','Nairobi','New Runda',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(213,NULL,9,2,NULL,'Nairobi - Lenana','6 TO 15KM FROM CBD','Nairobi','Lenana',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(214,NULL,9,2,NULL,'Nairobi - Loresho','6 TO 15KM FROM CBD','Nairobi','Loresho',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(215,NULL,9,2,NULL,'Nairobi - Lower Kabete','6 TO 15KM FROM CBD','Nairobi','Lower Kabete',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(216,NULL,9,2,NULL,'Nairobi - Lumumba','6 TO 15KM FROM CBD','Nairobi','Lumumba',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(217,NULL,9,2,NULL,'Nairobi - Zimmerman','6 TO 15KM FROM CBD','Nairobi','Zimmerman',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(218,NULL,9,2,NULL,'Nairobi - Makadara','6 TO 15KM FROM CBD','Nairobi','Makadara',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(219,NULL,9,2,NULL,'Nairobi - Mbagathi','6 TO 15KM FROM CBD','Nairobi','Mbagathi',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(220,NULL,9,2,NULL,'Nairobi - Mountain View','6 TO 15KM FROM CBD','Nairobi','Mountain View',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(221,NULL,9,2,NULL,'Nairobi - Mimosa','6 TO 15KM FROM CBD','Nairobi','Mimosa',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(222,NULL,9,2,NULL,'Nairobi - Nyari','6 TO 15KM FROM CBD','Nairobi','Nyari',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(223,NULL,9,2,NULL,'Nairobi - Racecourse','6 TO 15KM FROM CBD','Nairobi','Racecourse',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(224,NULL,9,2,NULL,'Nairobi - Riara','6 TO 15KM FROM CBD','Nairobi','Riara',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(225,NULL,9,2,NULL,'Nairobi - Ridgeways','6 TO 15KM FROM CBD','Nairobi','Ridgeways',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(226,NULL,9,2,NULL,'Nairobi - Rosslyn','6 TO 15KM FROM CBD','Nairobi','Rosslyn',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(227,NULL,9,2,NULL,'Nairobi - Roysambu','6 TO 15KM FROM CBD','Nairobi','Roysambu',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(228,NULL,9,2,NULL,'Nairobi - Savannah','6 TO 15KM FROM CBD','Nairobi','Savannah',400.00,'Same Day','6 TO 15KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(229,NULL,9,2,NULL,'Nairobi - Banana','16 TO 25KM FROM CBD','Nairobi','Banana',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(230,NULL,9,2,NULL,'Nairobi - Kahawa West','16 TO 25KM FROM CBD','Nairobi','Kahawa West',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(231,NULL,9,2,NULL,'Nairobi - Karen','16 TO 25KM FROM CBD','Nairobi','Karen',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(232,NULL,9,2,NULL,'Nairobi - Kiambu','16 TO 25KM FROM CBD','Nairobi','Kiambu',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(233,NULL,9,2,NULL,'Nairobi - Kikuyu','16 TO 25KM FROM CBD','Nairobi','Kikuyu',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(234,NULL,9,2,NULL,'Nairobi - Kimbo','16 TO 25KM FROM CBD','Nairobi','Kimbo',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(235,NULL,9,2,NULL,'Nairobi - Kiambaa','16 TO 25KM FROM CBD','Nairobi','Kiambaa',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(236,NULL,9,2,NULL,'Nairobi - KU Referral Hospital','16 TO 25KM FROM CBD','Nairobi','KU Referral Hospital',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(237,NULL,9,2,NULL,'Nairobi - KU University','16 TO 25KM FROM CBD','Nairobi','KU University',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(238,NULL,9,2,NULL,'Nairobi - Mlolongo','16 TO 25KM FROM CBD','Nairobi','Mlolongo',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(239,NULL,9,2,NULL,'Nairobi - Ngong','16 TO 25KM FROM CBD','Nairobi','Ngong',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(240,NULL,9,2,NULL,'Nairobi - Ongata Rongai','16 TO 25KM FROM CBD','Nairobi','Ongata Rongai',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(241,NULL,9,2,NULL,'Nairobi - JKIA','16 TO 25KM FROM CBD','Nairobi','JKIA',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(242,NULL,9,2,NULL,'Nairobi - Kahawa Sukari','16 TO 25KM FROM CBD','Nairobi','Kahawa Sukari',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(243,NULL,9,2,NULL,'Nairobi - Ruiru','16 TO 25KM FROM CBD','Nairobi','Ruiru',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(244,NULL,9,2,NULL,'Nairobi - Utawala','16 TO 25KM FROM CBD','Nairobi','Utawala',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(245,NULL,9,2,NULL,'Nairobi - Embakasi','16 TO 25KM FROM CBD','Nairobi','Embakasi',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(246,NULL,9,2,NULL,'Nairobi - Riruta','16 TO 25KM FROM CBD','Nairobi','Riruta',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(247,NULL,9,2,NULL,'Nairobi - Ruaka','16 TO 25KM FROM CBD','Nairobi','Ruaka',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(248,NULL,9,2,NULL,'Nairobi - Imara Daima','16 TO 25KM FROM CBD','Nairobi','Imara Daima',500.00,'Same Day','16 TO 25KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(249,NULL,9,2,NULL,'Nairobi - 25 TO 50KM ZONE','25 TO 50KM FROM CBD','Nairobi','25 TO 50KM ZONE',600.00,'same_day','25 TO 50KM FROM CBD',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(250,NULL,9,2,NULL,'Nairobi - Mombasa','INTERCITY EXPRESS DELIVERY','Nairobi','Mombasa',3500.00,'same_day','INTERCITY EXPRESS DELIVERY',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(251,NULL,9,2,NULL,'Nairobi - Kisumu','INTERCITY EXPRESS DELIVERY','Nairobi','Kisumu',3500.00,'same_day','INTERCITY EXPRESS DELIVERY',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(252,NULL,9,2,NULL,'Nairobi - Eldoret','INTERCITY EXPRESS DELIVERY','Nairobi','Eldoret',2000.00,'same_day','INTERCITY EXPRESS DELIVERY',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(253,NULL,9,2,NULL,'Nairobi - Nakuru','INTERCITY EXPRESS DELIVERY','Nairobi','Nakuru',1500.00,'same_day','INTERCITY EXPRESS DELIVERY',50,100,'2025-08-14 17:02:46',NULL,'active','pending',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46');
/*!40000 ALTER TABLE `rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receiver_details`
--

DROP TABLE IF EXISTS `receiver_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `receiver_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `town` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receiver_details`
--

LOCK TABLES `receiver_details` WRITE;
/*!40000 ALTER TABLE `receiver_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `receiver_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_people`
--

DROP TABLE IF EXISTS `sales_people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales_people` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  `office_id` bigint unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_people`
--

LOCK TABLES `sales_people` WRITE;
/*!40000 ALTER TABLE `sales_people` DISABLE KEYS */;
INSERT INTO `sales_people` VALUES (1,'Robert Gitonga','0724911674','22661035','Sales','Standard Rates','Active',2,'jeff.letting@ufanisi.co.ke','2','2025-08-17 08:18:13','2025-08-17 08:18:13'),(2,'Martin Kingangai','0724555221','55662302','Marketing','Standard Rates','Active',2,'martin@ufanisi.co.ke','2','2025-08-17 08:19:53','2025-08-17 08:19:53'),(3,'Neddy Sakura','0782911674','55226341','Marketing','Standard rates','Active',2,'neddy@ufanisi.co.ke','2','2025-08-17 08:20:50','2025-08-17 08:20:50'),(4,'Domitila Waema','0724666551','44552620','Marketing','Standard Rates','Active',2,'waema@ufanisi.co.ke','2','2025-08-17 08:22:20','2025-08-17 08:22:20'),(5,'Christabell Kamola','0724955622','55662030','Marketing','Standard Rates','Active',2,'christabell@ufanisi.co.ke','2','2025-08-17 08:23:30','2025-08-17 08:23:30');
/*!40000 ALTER TABLE `sales_people` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `same_day_rates`
--

DROP TABLE IF EXISTS `same_day_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `same_day_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `office_id` bigint unsigned NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bands` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_cost_per_kg` int DEFAULT NULL,
  `intercity_additional_cost_per_kg` int DEFAULT NULL,
  `rate` int NOT NULL,
  `applicableFrom` datetime DEFAULT NULL,
  `applicableTo` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `approvalStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `dateApproved` datetime DEFAULT NULL,
  `approvedBy` int DEFAULT NULL,
  `added_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `same_day_rates`
--

LOCK TABLES `same_day_rates` WRITE;
/*!40000 ALTER TABLE `same_day_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `same_day_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sent_messages`
--

DROP TABLE IF EXISTS `sent_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sent_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `request_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  `rider_id` bigint unsigned DEFAULT NULL,
  `recipient_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sent_messages_client_id_foreign` (`client_id`),
  KEY `sent_messages_rider_id_foreign` (`rider_id`),
  CONSTRAINT `sent_messages_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sent_messages_rider_id_foreign` FOREIGN KEY (`rider_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sent_messages`
--

LOCK TABLES `sent_messages` WRITE;
/*!40000 ALTER TABLE `sent_messages` DISABLE KEYS */;
INSERT INTO `sent_messages` VALUES (1,'REQ-10000',8,NULL,'receiver','Jeff Kip','0782911674','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000001KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-14 14:18:14','2025-08-14 14:18:14'),(2,'REQ-10000',8,NULL,'receiver','Jeff Kip','0782911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000001KE) has been dispatched successfully. Request ID: REQ-10000. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-14 14:25:11','2025-08-14 14:25:11'),(3,'REQ-10000',8,NULL,'sender','Jeff Kip','0782911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000001KE) has been dispatched successfully. Request ID: REQ-10000. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-14 14:25:12','2025-08-14 14:25:12'),(4,'REQ-10000',8,5,'receiver','Jeff Kip','0782911674','Parcel Arrived','Hello Jeff Kip, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000001KE. Thank you for choosing UCSL.','2025-08-14 14:28:30','2025-08-14 14:28:30'),(5,'REQ-10001',8,NULL,'receiver','JKL','0724911674','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000002KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-14 14:47:11','2025-08-14 14:47:11'),(6,'REQ-10001',8,NULL,'receiver','JKL','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000002KE) has been dispatched successfully. Request ID: REQ-10001. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-14 14:49:36','2025-08-14 14:49:36'),(7,'REQ-10001',8,NULL,'sender','JKL','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000002KE) has been dispatched successfully. Request ID: REQ-10001. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-14 14:49:37','2025-08-14 14:49:37'),(8,'REQ-10001',8,5,'receiver','JKL','0724911674','Parcel Arrived','Hello JKL, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000002KE. Thank you for choosing UCSL.','2025-08-14 14:51:16','2025-08-14 14:51:16'),(9,'REQ-10001',8,NULL,'client','Jumuka Supplies','0724911674','Parcel Issued Alert','Dear Jumuka Supplies, Your parcel Request ID: REQ-10001 has been issued to the receiver/agent.','2025-08-14 14:52:12','2025-08-14 14:52:12'),(10,'REQ-10002',5,1,'rider','Mwaele Emmanuel','0747911672','Client Collections Alert','Dear Mwaele Emmanuel, Collect Parcel for client (Maisha Medcare) 0747911674 Request ID: REQ-10002 at Kawangware.','2025-08-15 05:57:35','2025-08-15 05:57:35'),(11,'REQ-10002',5,1,'client','Maisha Medcare','0747911674','Parcel Collection Alert','Dear Maisha Medcare, We have allocated Mwaele Emmanuel 0747911672 to collect your parcel Request ID: REQ-10002.','2025-08-15 05:57:42','2025-08-15 05:57:42'),(12,'REQ-10002',5,1,'sender','Maisha Medcare','0747911674','Parcel Verified','Hello Maisha Medcare, your parcel has been verified. Total cost: KES 870. Waybill No: UCSL0000000004KE. Thank you for choosing UCSL.','2025-08-15 05:59:41','2025-08-15 05:59:41'),(13,'REQ-10002',5,1,'receiver','JKL','0724911674','Parcel Booked','Hello JKL, your parcel has been booked with UCSL. We will notify when the parcel arrives. Waybill No: UCSL0000000004KE.','2025-08-15 05:59:41','2025-08-15 05:59:41'),(14,'REQ-10002',5,NULL,'receiver','JKL','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000004KE) has been dispatched successfully. Request ID: REQ-10002. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 06:01:56','2025-08-15 06:01:56'),(15,'REQ-10002',5,NULL,'sender','JKL','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000004KE) has been dispatched successfully. Request ID: REQ-10002. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-15 06:01:56','2025-08-15 06:01:56'),(16,'REQ-10002',5,1,'receiver','JKL','0724911674','Parcel Arrived','Hello JKL, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000004KE. Thank you for choosing UCSL.','2025-08-15 06:17:20','2025-08-15 06:17:20'),(17,'REQ-10003',7,NULL,'receiver','Henry Olunga','0782911674','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000005KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 06:31:23','2025-08-15 06:31:23'),(18,'REQ-10004',8,NULL,'receiver','Fortune Maina','0757532990','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000006KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 06:35:59','2025-08-15 06:35:59'),(19,'REQ-10005',8,NULL,'receiver','Mwaele Emmanuel','0729395505','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000007KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 07:38:38','2025-08-15 07:38:38'),(20,'REQ-10002',5,NULL,'client','Maisha Medcare','0747911674','Parcel Issued Alert','Dear Maisha Medcare, Your parcel Request ID: REQ-10002 has been issued to the receiver/agent.','2025-08-15 08:42:14','2025-08-15 08:42:14'),(21,'REQ-10000',8,NULL,'client','Jumuka Supplies','0724911674','Parcel Issued Alert','Dear Jumuka Supplies, Your parcel Request ID: REQ-10000 has been issued to the receiver/agent.','2025-08-15 08:45:29','2025-08-15 08:45:29'),(22,'REQ-10006',8,NULL,'receiver','Jeff Letting','0782911674','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000008KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 09:59:33','2025-08-15 09:59:33'),(23,'REQ-10006',8,NULL,'receiver','Jeff Letting','0782911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000008KE) has been dispatched successfully. Request ID: REQ-10006. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 10:03:22','2025-08-15 10:03:22'),(24,'REQ-10006',8,NULL,'sender','Jeff Letting','0782911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000008KE) has been dispatched successfully. Request ID: REQ-10006. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-15 10:03:23','2025-08-15 10:03:23'),(25,'REQ-10006',8,5,'receiver','Jeff Letting','0782911674','Parcel Arrived','Hello Jeff Letting, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000008KE. Thank you for choosing UCSL.','2025-08-15 10:06:49','2025-08-15 10:06:49'),(26,'REQ-10006',8,NULL,'client','Jumuka Supplies','0724911674','Parcel Issued Alert','Dear Jumuka Supplies, Your parcel Request ID: REQ-10006 has been issued to the receiver/agent.','2025-08-15 10:07:49','2025-08-15 10:07:49'),(27,'REQ-10007',8,NULL,'receiver','Fortune Maina','0747911672','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000009KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 11:59:02','2025-08-15 11:59:02'),(28,'REQ-10007',8,NULL,'receiver','Fortune Maina','0747911672','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000009KE) has been dispatched successfully. Request ID: REQ-10007. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 13:04:48','2025-08-15 13:04:48'),(29,'REQ-10007',8,NULL,'sender','Fortune Maina','0747911672','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000009KE) has been dispatched successfully. Request ID: REQ-10007. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-15 13:04:49','2025-08-15 13:04:49'),(30,'REQ-10007',8,5,'receiver','Fortune Maina','0747911672','Parcel Arrived','Hello Fortune Maina, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000009KE. Thank you for choosing UCSL.','2025-08-15 13:10:51','2025-08-15 13:10:51'),(31,'REQ-10007',8,NULL,'client','Jumuka Supplies','0724911674','Parcel Issued Alert','Dear Jumuka Supplies, Your parcel Request ID: REQ-10007 has been issued to the receiver/agent.','2025-08-15 13:14:23','2025-08-15 13:14:23'),(32,'REQ-10008',8,NULL,'receiver','Jeff Kip','0724911674','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000010KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 13:26:45','2025-08-15 13:26:45'),(33,'REQ-10008',8,NULL,'receiver','Jeff Kip','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000010KE) has been dispatched successfully. Request ID: REQ-10008. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-15 13:36:05','2025-08-15 13:36:05'),(34,'REQ-10008',8,NULL,'sender','Jeff Kip','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000010KE) has been dispatched successfully. Request ID: REQ-10008. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-15 13:36:06','2025-08-15 13:36:06'),(35,'REQ-10008',8,5,'receiver','Jeff Kip','0724911674','Parcel Arrived','Hello Jeff Kip, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000010KE. Thank you for choosing UCSL.','2025-08-15 14:40:20','2025-08-15 14:40:20'),(36,'REQ-10009',5,2,'rider','Jeff Kip','0724911674','Client Collections Alert','Dear Jeff Kip, Collect Parcel for client (Maisha Medcare) 0747911674 Request ID: REQ-10009 at Madaraka.','2025-08-15 14:57:24','2025-08-15 14:57:24'),(37,'REQ-10009',5,2,'client','Maisha Medcare','0747911674','Parcel Collection Alert','Dear Maisha Medcare, We have allocated Jeff Kip 0724911674 to collect your parcel Request ID: REQ-10009.','2025-08-15 14:57:30','2025-08-15 14:57:30'),(38,'REQ-10010',5,1,'rider','Mwaele Emmanuel','0747911672','Client Collections Alert','Dear Mwaele Emmanuel, Collect Parcel for client (Maisha Medcare) 0747911674 Request ID: REQ-10010 at Madaraka.','2025-08-15 15:13:42','2025-08-15 15:13:42'),(39,'REQ-10010',5,1,'client','Maisha Medcare','0747911674','Parcel Collection Alert','Dear Maisha Medcare, We have allocated Mwaele Emmanuel 0747911672 to collect your parcel Request ID: REQ-10010.','2025-08-15 15:13:47','2025-08-15 15:13:47'),(40,'REQ-10010',5,1,'staff','Jeff Kip','0724911674','Parcel Delivered','Parcel has been Delivered by Mwaele Emmanuel at client premises. Details: Request ID: REQ-10010;','2025-08-15 15:25:44','2025-08-15 15:25:44'),(41,'REQ-10008',8,NULL,'client','Jumuka Supplies','0724911674','Parcel Issued Alert','Dear Jumuka Supplies, Your parcel Request ID: REQ-10008 has been issued to the receiver/agent.','2025-08-15 15:34:48','2025-08-15 15:34:48'),(42,'REQ-10009',5,2,'staff','Geff Letting','0724911674','Parcel Delivered','Parcel has been Delivered by Jeff Kip at client premises. Details: Request ID: REQ-10009;','2025-08-15 15:49:03','2025-08-15 15:49:03'),(43,'REQ-10011',5,2,'rider','Jeff Kip','0724911674','Client Collections Alert','Dear Jeff Kip, Collect Parcel for client (Maisha Medcare) 0747911674 Request ID: REQ-10011 at BuruBuru.','2025-08-17 06:47:17','2025-08-17 06:47:17'),(44,'REQ-10011',5,2,'client','Maisha Medcare','0747911674','Parcel Collection Alert','Dear Maisha Medcare, We have allocated Jeff Kip 0724911674 to collect your parcel Request ID: REQ-10011.','2025-08-17 06:47:25','2025-08-17 06:47:25'),(45,'REQ-10012',11,NULL,'receiver','Hassan Omar','0724911674','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000014KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-17 08:59:00','2025-08-17 08:59:00'),(46,'REQ-10013',12,NULL,'receiver','Jeff Letting','0724911674','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000015KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-17 09:39:21','2025-08-17 09:39:21'),(47,'REQ-10013',12,4,'staff','Mwaele Emmanuel','0747911672','Parcel Delivered','Parcel has been Delivered by Neddy Sakura at client premises. Details: Request ID: REQ-10013;','2025-08-17 10:14:46','2025-08-17 10:14:46'),(48,'REQ-10014',5,2,'rider','Jeff Kip','0724911674','Client Collections Alert','Dear Jeff Kip, Collect Parcel for client (Maisha Medcare) 0747911674 Request ID: REQ-10014 at Adams Arcade.','2025-08-17 10:38:38','2025-08-17 10:38:38'),(49,'REQ-10014',5,2,'client','Maisha Medcare','0747911674','Parcel Collection Alert','Dear Maisha Medcare, We have allocated Jeff Kip 0724911674 to collect your parcel Request ID: REQ-10014.','2025-08-17 10:38:44','2025-08-17 10:38:44'),(50,'REQ-10014',5,2,'staff','Neddy Sakura','0726526020','Parcel Delivered','Parcel has been Delivered by Jeff Kip at client premises. Details: Request ID: REQ-10014;','2025-08-17 10:45:45','2025-08-17 10:45:45'),(51,'REQ-10015',14,NULL,'receiver','Bethwel Sang','0747911672','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000017KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-17 11:31:40','2025-08-17 11:31:40'),(52,'REQ-10016',14,NULL,'receiver','Bethwel Sang','0747911672','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000018KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-17 11:52:46','2025-08-17 11:52:46'),(53,'REQ-10015',14,NULL,'receiver','Bethwel Sang','0747911672','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000017KE) has been dispatched successfully. Request ID: REQ-10015. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-17 12:01:39','2025-08-17 12:01:39'),(54,'REQ-10015',14,NULL,'sender','Bethwel Sang','0747911672','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000017KE) has been dispatched successfully. Request ID: REQ-10015. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-17 12:01:39','2025-08-17 12:01:39'),(55,'REQ-10016',14,NULL,'receiver','Bethwel Sang','0747911672','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000018KE) has been dispatched successfully. Request ID: REQ-10016. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-17 12:01:40','2025-08-17 12:01:40'),(56,'REQ-10016',14,NULL,'sender','Bethwel Sang','0747911672','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000018KE) has been dispatched successfully. Request ID: REQ-10016. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-17 12:01:40','2025-08-17 12:01:40'),(57,'REQ-10016',14,4,'receiver','Bethwel Sang','0747911672','Parcel Arrived','Hello Bethwel Sang, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000018KE. Thank you for choosing UCSL.','2025-08-17 12:20:44','2025-08-17 12:20:44'),(58,'REQ-10015',14,4,'receiver','Bethwel Sang','0747911672','Parcel Arrived','Hello Bethwel Sang, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000017KE. Thank you for choosing UCSL.','2025-08-17 12:21:07','2025-08-17 12:21:07'),(59,'REQ-10017',6,2,'rider','Jeff Kip','0724911674','Client Collections Alert','Dear Jeff Kip, Collect Parcel for client (Kenlog Logistics) 0725525484 Request ID: REQ-10017 at Kayole.','2025-08-17 13:07:15','2025-08-17 13:07:15'),(60,'REQ-10017',6,2,'client','Kenlog Logistics','0725525484','Parcel Collection Alert','Dear Kenlog Logistics, We have allocated Jeff Kip 0724911674 to collect your parcel Request ID: REQ-10017.','2025-08-17 13:07:22','2025-08-17 13:07:22'),(61,'REQ-10017',6,5,'sender','Kenlog Logistics','0725525484','Parcel Verified','Hello Kenlog Logistics, your parcel has been verified. Total cost: KES 2030. Waybill No: UCSL0000000020KE. Thank you for choosing UCSL.','2025-08-17 13:16:18','2025-08-17 13:16:18'),(62,'REQ-10017',6,5,'receiver','Jeff Letting','0724911674','Parcel Booked','Hello Jeff Letting, your parcel has been booked with UCSL. We will notify when the parcel arrives. Waybill No: UCSL0000000020KE.','2025-08-17 13:16:19','2025-08-17 13:16:19'),(63,'REQ-10017',6,NULL,'receiver','Jeff Letting','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000020KE) has been dispatched successfully. Request ID: REQ-10017. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-17 13:18:28','2025-08-17 13:18:28'),(64,'REQ-10017',6,NULL,'sender','Jeff Letting','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000020KE) has been dispatched successfully. Request ID: REQ-10017. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-17 13:18:30','2025-08-17 13:18:30'),(65,'REQ-10017',6,6,'receiver','Jeff Letting','0724911674','Parcel Arrived','Hello Jeff Letting, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000020KE. Thank you for choosing UCSL.','2025-08-17 13:43:50','2025-08-17 13:43:50'),(66,'REQ-10017',6,NULL,'client','Kenlog Logistics','0725525484','Parcel Issued Alert','Dear Kenlog Logistics, Your parcel Request ID: REQ-10017 has been issued to the receiver/agent.','2025-08-17 13:58:04','2025-08-17 13:58:04'),(67,'REQ-10011',5,2,'staff','Geff Letting','0724911674','Parcel Delivered','Parcel has been Delivered by Jeff Kip at client premises. Details: Request ID: REQ-10011;','2025-08-17 18:00:08','2025-08-17 18:00:08'),(68,'REQ-10018',6,2,'rider','Jeff Kip','0724911674','Client Collections Alert','Dear Jeff Kip, Collect Parcel for client (Kenlog Logistics) 0725525484 Request ID: REQ-10018 at Kayole.','2025-08-17 18:12:57','2025-08-17 18:12:57'),(69,'REQ-10018',6,2,'client','Kenlog Logistics','0725525484','Parcel Collection Alert','Dear Kenlog Logistics, We have allocated Jeff Kip 0724911674 to collect your parcel Request ID: REQ-10018.','2025-08-17 18:13:37','2025-08-17 18:13:37'),(70,'REQ-10018',6,2,'sender','Kenlog Logistics','0725525484','Parcel Verified','Hello Kenlog Logistics, your parcel has been verified. Total cost: KES 580. Waybill No: UCSL0000000022KE. Thank you for choosing UCSL.','2025-08-17 18:16:19','2025-08-17 18:16:19'),(71,'REQ-10018',6,2,'receiver','Jeff Letting','0724911674','Parcel Booked','Hello Jeff Letting, your parcel has been booked with UCSL. We will notify when the parcel arrives. Waybill No: UCSL0000000022KE.','2025-08-17 18:16:39','2025-08-17 18:16:39'),(72,'REQ-10018',6,NULL,'receiver','Jeff Letting','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000022KE) has been dispatched successfully. Request ID: REQ-10018. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-17 18:18:56','2025-08-17 18:18:56'),(73,'REQ-10018',6,NULL,'sender','Jeff Letting','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000022KE) has been dispatched successfully. Request ID: REQ-10018. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-17 18:18:58','2025-08-17 18:18:58'),(74,'REQ-10018',6,6,'receiver','Jeff Letting','0724911674','Parcel Arrived','Hello Jeff Letting, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000022KE. Thank you for choosing UCSL.','2025-08-17 18:52:25','2025-08-17 18:52:25'),(75,'REQ-10019',8,NULL,'receiver','Jeff','0724911674','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000023KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-18 02:25:06','2025-08-18 02:25:06'),(76,'REQ-10019',8,NULL,'receiver','Jeff','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000023KE) has been dispatched successfully. Request ID: REQ-10019. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-18 02:30:04','2025-08-18 02:30:04'),(77,'REQ-10019',8,NULL,'sender','Jeff','0724911674','Parcel Dispatched Alert','Dear Customer, Parcel(#UCSL0000000023KE) has been dispatched successfully. Request ID: REQ-10019. We will notify when the parcel is collected. Thank you for using Ufanisi Courier Services','2025-08-18 02:30:06','2025-08-18 02:30:06'),(78,'REQ-10019',8,6,'receiver','Jeff','0724911674','Parcel Arrived','Hello Jeff, your parcel has arrived and is ready for collection. Waybill No: UCSL0000000023KE. Thank you for choosing UCSL.','2025-08-18 02:31:07','2025-08-18 02:31:07'),(79,'REQ-10019',8,10,'staff','Jeff Kip','0724911674','Parcel Delivered','Parcel has been Delivered by Geoffrey Letting at client premises. Details: Request ID: REQ-10019;','2025-08-18 04:12:00','2025-08-18 04:12:00'),(80,'REQ-10007',8,2,'staff','Geff Letting','0724911674','Parcel Delivered','Parcel has been Delivered by Jeff Kip at client premises. Details: Request ID: REQ-10007;','2025-08-18 06:12:15','2025-08-18 06:12:15'),(81,'REQ-10020',8,NULL,'receiver','Shoes','0782911674','Parcel Booking Confirmation','Dear Customer, Parcel(#UCSL0000000024KE) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services','2025-08-18 07:27:35','2025-08-18 07:27:35');
/*!40000 ALTER TABLE `sent_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_levels`
--

DROP TABLE IF EXISTS `service_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_levels`
--

LOCK TABLES `service_levels` WRITE;
/*!40000 ALTER TABLE `service_levels` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('BPaWDw4kKanhlP8vNZ5OGAUNajtbShxILgMojrgr',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQmVJYjdmQUV4RnRUd3FGb2FtN04wcXFZdTNmMnkzRkxVZ1VvTTBnayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2FtZWRheS9vbi1hY2NvdW50Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1755504614);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_arrivals`
--

DROP TABLE IF EXISTS `shipment_arrivals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_arrivals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_collection_id` bigint unsigned NOT NULL,
  `requestId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_received` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified_by` int NOT NULL,
  `cost` int NOT NULL,
  `vat_cost` int NOT NULL,
  `total_cost` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_reg_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `delivery_rider` int DEFAULT NULL,
  `delivery_rider_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_arrivals`
--

LOCK TABLES `shipment_arrivals` WRITE;
/*!40000 ALTER TABLE `shipment_arrivals` DISABLE KEYS */;
INSERT INTO `shipment_arrivals` VALUES (1,1,'REQ-10000','2025-08-14 17:28:30',5,900,144,1044,'issued','3','11',NULL,'2025-08-14 14:28:30','2025-08-15 08:45:28',NULL,NULL),(2,2,'REQ-10001','2025-08-14 17:51:16',5,500,80,580,'issued','2','4',NULL,'2025-08-14 14:51:16','2025-08-14 14:52:09',NULL,NULL),(3,3,'REQ-10002','2025-08-15 09:17:20',1,750,120,870,'issued','2','6',NULL,'2025-08-15 06:17:20','2025-08-15 08:42:11',NULL,NULL),(4,7,'REQ-10006','2025-08-15 13:06:49',5,1150,184,1334,'issued','2','8',NULL,'2025-08-15 10:06:49','2025-08-15 10:07:47',NULL,NULL),(5,8,'REQ-10007','2025-08-15 16:10:51',5,500,80,580,'issued','2','10',NULL,'2025-08-15 13:10:51','2025-08-18 05:32:49',2,'Allocated'),(6,9,'REQ-10008','2025-08-15 17:40:20',5,750,120,870,'issued','John Logistics Ltd.','KBS 123A',NULL,'2025-08-15 14:40:20','2025-08-17 12:50:29',7,'Allocated'),(7,16,'REQ-10016','2025-08-17 15:20:44',4,2000,320,2320,'Verified','Ufanisi Courier Services Ltd','KDE 900H',NULL,'2025-08-17 12:20:44','2025-08-17 12:41:19',7,'Allocated'),(8,16,'REQ-10015','2025-08-17 15:21:07',4,2000,320,2320,'Verified','Ufanisi Courier Services Ltd','KDE 900H',NULL,'2025-08-17 12:21:07','2025-08-17 12:24:00',7,'Allocated'),(9,18,'REQ-10017','2025-08-17 16:43:50',6,1750,280,2030,'delivered','Ufanisi Courier Services Ltd','KCX 008U',NULL,'2025-08-17 13:43:50','2025-08-17 13:58:02',13,'Allocated'),(10,19,'REQ-10018','2025-08-17 21:52:25',6,500,80,580,'Verified','Ufanisi Courier Services Ltd','KCT 005K',NULL,'2025-08-17 18:52:25','2025-08-17 18:55:30',13,'Allocated'),(11,20,'REQ-10019','2025-08-18 05:31:07',6,500,80,580,'Verified','Ufanisi Courier Services Ltd','KCQ 009Y',NULL,'2025-08-18 02:31:07','2025-08-18 02:56:25',10,'Allocated');
/*!40000 ALTER TABLE `shipment_arrivals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_arrivals_items`
--

DROP TABLE IF EXISTS `shipment_arrivals_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_arrivals_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint unsigned NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actual_quantity` int DEFAULT NULL,
  `actual_weight` int DEFAULT NULL,
  `actual_length` int DEFAULT NULL,
  `actual_width` int DEFAULT NULL,
  `actual_height` int DEFAULT NULL,
  `actual_volume` int DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_arrivals_items`
--

LOCK TABLES `shipment_arrivals_items` WRITE;
/*!40000 ALTER TABLE `shipment_arrivals_items` DISABLE KEYS */;
INSERT INTO `shipment_arrivals_items` VALUES (1,1,'Mitumba',1,30,50,60,50,150000,'OK','2025-08-14 14:28:30','2025-08-14 14:28:30'),(2,2,'itm 1',1,20,50,40,30,60000,'ok','2025-08-14 14:51:16','2025-08-14 14:51:16'),(3,3,'Box of electrronics',1,30,50,60,50,150000,NULL,'2025-08-15 06:17:20','2025-08-15 06:17:20'),(4,7,'Printer',1,35,50,70,50,175000,NULL,'2025-08-15 10:06:49','2025-08-15 10:06:49'),(5,8,'item 1',1,10,20,40,60,48000,NULL,'2025-08-15 13:10:51','2025-08-15 13:10:51'),(6,9,'Mitumba',1,30,50,40,30,60000,NULL,'2025-08-15 14:40:20','2025-08-15 14:40:20'),(7,16,'Mitumba',1,55,0,0,0,0,'received in good order','2025-08-17 12:20:44','2025-08-17 12:20:44'),(8,16,'Mitumba',1,55,0,0,0,0,NULL,'2025-08-17 12:21:07','2025-08-17 12:21:07'),(9,18,'door',1,50,0,0,0,0,'received in good order','2025-08-17 13:43:50','2025-08-17 13:43:50'),(10,19,'item 1',1,15,0,0,0,0,NULL,'2025-08-17 18:52:25','2025-08-17 18:52:25'),(11,20,'itm 1',1,20,10,20,40,8000,'ok','2025-08-18 02:31:07','2025-08-18 02:31:07');
/*!40000 ALTER TABLE `shipment_arrivals_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_collections`
--

DROP TABLE IF EXISTS `shipment_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_collections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `requestId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `origin_id` bigint unsigned NOT NULL,
  `destination_id` bigint unsigned NOT NULL,
  `collected_by` bigint unsigned DEFAULT NULL,
  `consignment_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waybill_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_town` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_id_no` int DEFAULT NULL,
  `receiver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_id_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_town` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_rates_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_cost` int DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `vat` int DEFAULT NULL,
  `total_cost` int DEFAULT NULL,
  `actual_cost` decimal(10,2) DEFAULT NULL,
  `actual_vat` decimal(10,2) DEFAULT NULL,
  `actual_total_cost` decimal(10,2) DEFAULT NULL,
  `total_weight` int DEFAULT NULL,
  `total_quantity` int DEFAULT NULL,
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `billing_party` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manifest_generated_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `agent_approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `agent_requested` tinyint(1) NOT NULL DEFAULT '0',
  `approval_remarks` text COLLATE utf8mb4_unicode_ci,
  `grn_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_rider` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipment_collections_collected_by_foreign` (`collected_by`),
  KEY `shipment_collections_verified_by_foreign` (`verified_by`),
  CONSTRAINT `shipment_collections_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`),
  CONSTRAINT `shipment_collections_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_collections`
--

LOCK TABLES `shipment_collections` WRITE;
/*!40000 ALTER TABLE `shipment_collections` DISABLE KEYS */;
INSERT INTO `shipment_collections` VALUES (1,'REQ-10000',8,2,47,5,'CN-10000','UCSL0000000001KE',NULL,'Jumuka Supplies','0724911674','jeff.letting@ufanisi.co.ke','Moi Avenue, Kisumu','Eldoret',10042264,'Jeff Kip','31661035','0782911674','jeff.letting@ufanisi.co.ke','Homabay','Homabay',NULL,650,900.00,144,1044,900.00,144.00,1044.00,30,NULL,5,'2025-08-14 14:18:13','Sender','Invoice','INV-00001',NULL,'arrived','2025-08-14 14:18:13','2025-08-15 08:45:28',0,NULL,NULL,0,NULL,NULL,NULL),(2,'REQ-10001',8,2,19,5,'CN-10001','UCSL0000000002KE',NULL,'Jumuka Supplies','0724911674','jeff.letting@ufanisi.co.ke','Moi Avenue, Kisumu','Eldoret',10042264,'JKL','31661035','0724911674','jeffkip354@gmail.com','Kakamega','Kakamega',NULL,500,500.00,80,580,500.00,80.00,580.00,20,NULL,5,'2025-08-14 14:47:11','Sender','M-Pesa','TRTQ747FDJ',NULL,'arrived','2025-08-14 14:47:11','2025-08-14 14:52:09',0,NULL,NULL,0,NULL,NULL,NULL),(3,'REQ-10002',5,2,9,1,'CN-10002','UCSL0000000004KE','client','Maisha Medcare','0747911674','jeff.letting@ufanisi.co.ke','Kenyatta Avenue, Nakuru','Nairobi',34690279,'JKL','31661035','0724911674','jeffkip354@gmail.com','Mombasa','Mombasa',NULL,500,750.00,120,870,750.00,120.00,870.00,NULL,NULL,1,'2025-08-15 05:59:39','Sender','Invoice','INV-00002',NULL,'arrived','2025-08-15 05:58:36','2025-08-15 08:42:11',0,NULL,NULL,0,NULL,NULL,NULL),(4,'REQ-10003',7,2,43,1,'CN-10003','UCSL0000000005KE',NULL,'Kilimani Pharma','0729395605','kilimani-pharma@ucsl.co.ke','Ngong Road, Eldoret','Kisumu',31861190,'Henry Olunga','31661035','0782911674','jeffkip354@gmail.com','Namanga','Namanga',NULL,650,650.00,104,754,650.00,104.00,754.00,15,NULL,1,'2025-08-15 06:31:22','Sender','Invoice','INV-00003',NULL,NULL,'2025-08-15 06:31:22','2025-08-15 06:31:22',0,NULL,NULL,0,NULL,NULL,NULL),(5,'REQ-10004',8,2,172,1,'CN-10004','UCSL0000000006KE',NULL,'Jumuka Supplies','0724911674','jeff.letting@ufanisi.co.ke','Moi Avenue, Kisumu','Eldoret',10042264,'Fortune Maina','38996215','0757532990','fortune@ufanisi.co.ke','Eastleigh','Nairobi',NULL,350,350.00,56,406,350.00,56.00,406.00,25,NULL,1,'2025-08-15 06:35:58','Sender','Invoice','INV-00004',NULL,'Delivery Rider Allocated','2025-08-15 06:35:58','2025-08-15 07:17:53',0,NULL,NULL,0,NULL,NULL,NULL),(6,'REQ-10005',8,2,199,5,'CN-10005','UCSL0000000007KE',NULL,'Jumuka Supplies','0724911674','jeff.letting@ufanisi.co.ke','Moi Avenue, Kisumu','Eldoret',10042264,'Mwaele Emmanuel','33662014','0729395505','mwaele@ufanisi.co.ke','Buru','Nairobi',NULL,400,400.00,64,464,400.00,64.00,464.00,15,NULL,5,'2025-08-15 07:38:37','Sender','M-Pesa','TRTQ837DSN',NULL,NULL,'2025-08-15 07:38:37','2025-08-15 07:38:37',0,NULL,NULL,0,NULL,NULL,NULL),(7,'REQ-10006',8,2,47,5,'CN-10006','UCSL0000000008KE',NULL,'Jumuka Supplies','0724911674','jeff.letting@ufanisi.co.ke','Moi Avenue, Kisumu','Eldoret',10042264,'Jeff Letting','31661035','0782911674','jeff.letting@ufanisi.co.ke','Makupa','Mombasa',NULL,650,1150.00,184,1334,1150.00,184.00,1334.00,35,NULL,5,'2025-08-15 09:59:32','Sender','M-Pesa','TRTQ100YHD',NULL,'arrived','2025-08-15 09:59:32','2025-08-15 10:07:47',0,NULL,NULL,0,NULL,NULL,NULL),(8,'REQ-10007',8,2,18,5,'CN-10007','UCSL0000000009KE',NULL,'Jumuka Supplies','0724911674','jeff.letting@ufanisi.co.ke','Moi Avenue, Kisumu','Eldoret',10042264,'Fortune Maina','33116013','0747911672','jeff.letting@ufanisi.co.ke','Eldoret','Eldoret',NULL,500,500.00,80,580,500.00,80.00,580.00,10,NULL,5,'2025-08-15 11:59:01','Sender','Invoice','INV-00005',NULL,'Delivery Rider Allocated','2025-08-15 11:59:01','2025-08-18 06:12:14',0,NULL,NULL,0,NULL,NULL,NULL),(9,'REQ-10008',8,2,19,5,'CN-10008','UCSL0000000010KE',NULL,'Jumuka Supplies','0724911674','jeff.letting@ufanisi.co.ke','Moi Avenue, Kisumu','Eldoret',10042264,'Jeff Kip','31663012','0724911674','jeffkip354@gmail.com','Kakamega','Kakamega',NULL,500,750.00,120,870,750.00,120.00,870.00,30,NULL,5,'2025-08-15 13:26:44','Sender','M-Pesa','TRTQGSH472',NULL,'Delivery Rider Allocated','2025-08-15 13:26:44','2025-08-17 12:50:29',0,NULL,NULL,0,NULL,NULL,NULL),(10,'REQ-10010',5,2,178,1,'CN-10009','UCSL0000000011KE','client','Maisha Medcare','0747911674','jeff.letting@ufanisi.co.ke','Kenyatta Avenue, Nakuru','Nairobi',34690279,'Jeff Letting','31661035','0782911674','jeff.letting@ufanisi.co.ke','Madaraka','Nairobi',NULL,350,350.00,56,406,350.00,56.00,406.00,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-15 15:15:42','2025-08-15 15:25:43',0,NULL,NULL,0,NULL,NULL,NULL),(11,'REQ-10009',5,2,178,2,'CN-10010','UCSL0000000012KE','client','Maisha Medcare','0747911674','jeff.letting@ufanisi.co.ke','Kenyatta Avenue, Nakuru','Nairobi',34690279,'JKL','31661035','0782911674','jeffkip354@gmail.com','Madaraka','Nairobi',NULL,350,350.00,56,406,350.00,56.00,406.00,20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-15 15:47:31','2025-08-15 15:49:02',0,NULL,NULL,0,NULL,NULL,NULL),(12,'REQ-10011',5,2,199,2,'CN-10011','UCSL0000000013KE','client','Maisha Medcare','0747911674','jeff.letting@ufanisi.co.ke','Kenyatta Avenue, Nakuru','Nairobi',34690279,'JKL','31661035','0782911674','jeffkip354@gmail.com','Buruburu','Nairobi',NULL,400,1150.00,184,1334,1150.00,184.00,1334.00,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-17 06:50:21','2025-08-17 17:59:48',0,NULL,NULL,0,NULL,NULL,NULL),(13,'REQ-10012',11,2,201,2,'CN-10012','UCSL0000000014KE',NULL,'James Opiyo','+254725525484','choroma@ufanisi.co.ke','Nairobi','Nairobi',NULL,'Hassan Omar','22116230','0724911674','jeffkip354@gmail.com','Donholm','Nairobi',NULL,400,400.00,64,464,400.00,64.00,464.00,10,NULL,2,'2025-08-17 08:58:59','Receiver','Invoice','INV-00006',NULL,'Delivery Rider Allocated','2025-08-17 08:58:59','2025-08-17 09:12:56',0,NULL,NULL,0,NULL,NULL,NULL),(14,'REQ-10013',12,2,171,1,'CN-10013','UCSL0000000015KE',NULL,'Shadrack Killu','+254782911674','ict-support@ufanisi.co.ke','50623','Nairobi',NULL,'Jeff Letting','3311023','0724911674','jeff.letting@ufanisi.co.ke','Eastlands','Nairobi',NULL,350,1350.00,216,1566,1350.00,216.00,1566.00,45,NULL,1,'2025-08-17 09:39:20','Sender','M-Pesa','TR65HFSDFV',NULL,'Delivery Rider Allocated','2025-08-17 09:39:20','2025-08-17 10:14:46',0,NULL,NULL,1,NULL,NULL,NULL),(15,'REQ-10014',5,2,168,2,'CN-10014','UCSL0000000016KE','client','Maisha Medcare','0747911674','jeff.letting@ufanisi.co.ke','Kenyatta Avenue, Nakuru','Nairobi',34690279,'Maisha Pharmacy','32662132','0724911674','jeffkip354@gmail.com','-','Nairobi',NULL,350,350.00,56,406,350.00,56.00,406.00,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-17 10:43:35','2025-08-17 10:45:44',0,NULL,NULL,0,NULL,NULL,NULL),(16,'REQ-10015',14,2,9,4,'CN-10015','UCSL0000000017KE',NULL,'Stephen Kiprono','+254724911674','jeff.letting@ufanisi.co.ke','Nairobi','Nairobi',NULL,'Bethwel Sang','1211253','0747911672','jeffkip354@gmail.com','Mombasa','Mombasa',NULL,500,2000.00,320,2320,2000.00,320.00,2320.00,55,NULL,4,'2025-08-17 11:31:40','Sender','M-Pesa','TRW6374888',NULL,'Delivery Rider Allocated','2025-08-17 11:31:40','2025-08-17 12:24:00',0,NULL,NULL,0,NULL,NULL,NULL),(17,'REQ-10016',14,2,9,4,'CN-10016','UCSL0000000018KE',NULL,'Stephen Kiprono','+254724911674','jeff.letting@ufanisi.co.ke','Nairobi','Nairobi',NULL,'Bethwel Sang','55446210','0747911672','jeffkip354@gmail.com','Mombasa','Mombasa',NULL,500,2000.00,320,2320,2000.00,320.00,2320.00,55,NULL,4,'2025-08-17 11:52:45','Sender','M-Pesa','TRHDBDDN83',NULL,'Delivery Rider Allocated','2025-08-17 11:52:45','2025-08-17 12:41:19',0,NULL,NULL,0,NULL,NULL,NULL),(18,'REQ-10017',6,2,9,2,'CN-10017','UCSL0000000020KE','client','Kenlog Logistics','0725525484','choroma@ufanisi.co.ke','Waiyaki Way, Nakuru','Kisumu',14915850,'Jeff Letting','22553620','0724911674','jeffkip354@gmail.com','Makupa','Mombasa',NULL,500,1750.00,280,2030,1750.00,280.00,2030.00,NULL,NULL,5,'2025-08-17 13:16:18','Sender','M-Pesa','DGD47573',NULL,'Delivery Rider Allocated','2025-08-17 13:11:14','2025-08-17 13:58:02',0,NULL,NULL,0,NULL,NULL,NULL),(19,'REQ-10018',6,2,9,2,'CN-10018','UCSL0000000022KE','client','Kenlog Logistics','0725525484','choroma@ufanisi.co.ke','Waiyaki Way, Nakuru','Kisumu',14915850,'Jeff Letting','31661035','0724911674','jeffkip354@gmail.com','Makupa','Mombasa',NULL,500,500.00,80,580,500.00,80.00,580.00,NULL,NULL,2,'2025-08-17 18:15:59','Sender','M-Pesa','TRTQ928HJK',NULL,'Delivery Rider Allocated','2025-08-17 18:14:42','2025-08-17 18:55:30',0,NULL,NULL,0,NULL,NULL,NULL),(20,'REQ-10019',8,2,9,2,'CN-10019','UCSL0000000023KE',NULL,'Jumuka Supplies','0724911674','jeff.letting@ufanisi.co.ke','Moi Avenue, Kisumu','Eldoret',10042264,'Jeff','31661035','0724911674','jeffkip354@gmail.com','Makupa','Mombasa',NULL,500,500.00,80,580,500.00,80.00,580.00,20,NULL,2,'2025-08-18 02:25:04','Sender','M-Pesa','TRHDNSS443',NULL,'Delivery Rider Allocated','2025-08-18 02:25:04','2025-08-18 04:11:57',0,NULL,NULL,0,NULL,NULL,NULL),(21,'REQ-10020',8,2,9,2,'CN-10020','UCSL0000000024KE',NULL,'Jumuka Supplies','0724911674','jeff.letting@ufanisi.co.ke','Moi Avenue, Kisumu','Eldoret',10042264,'Shoes','31661035','0782911674','jeffkip354@gmail.com','Makupa','Mombasa',NULL,500,500.00,80,580,500.00,80.00,580.00,20,NULL,2,'2025-08-18 07:27:34','Sender','M-Pesa','TRTQ882UYN',NULL,NULL,'2025-08-18 07:27:34','2025-08-18 07:27:34',0,NULL,NULL,0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `shipment_collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_deliveries`
--

DROP TABLE IF EXISTS `shipment_deliveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `requestId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int NOT NULL,
  `receiver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_id_no` int DEFAULT NULL,
  `receiver_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_id_no` int DEFAULT NULL,
  `delivery_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_datetime` datetime NOT NULL,
  `delivered_by` int NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_deliveries`
--

LOCK TABLES `shipment_deliveries` WRITE;
/*!40000 ALTER TABLE `shipment_deliveries` DISABLE KEYS */;
INSERT INTO `shipment_deliveries` VALUES (1,'REQ-10010',5,'Jeff Letting','0782911674',31661035,'receiver',NULL,NULL,NULL,'Madaraka','2025-08-15 18:25:43',1,NULL,'2025-08-15 15:25:43','2025-08-15 15:25:43'),(2,'REQ-10009',5,'JKL','0782911674',31661035,'receiver',NULL,NULL,NULL,'Madaraka','2025-08-15 18:49:02',2,NULL,'2025-08-15 15:49:02','2025-08-15 15:49:02'),(3,'REQ-10013',12,'Jeff Letting','0724911674',3311023,'receiver',NULL,NULL,NULL,'Eastlands','2025-08-17 13:14:46',4,NULL,'2025-08-17 10:14:46','2025-08-17 10:14:46'),(4,'REQ-10014',5,'Maisha Pharmacy','0724911674',32662132,'receiver',NULL,NULL,NULL,'Adams Arcade','2025-08-17 13:45:44',2,NULL,'2025-08-17 10:45:44','2025-08-17 10:45:44'),(5,'REQ-10011',5,'JKL','0782911674',31661035,'receiver',NULL,NULL,NULL,'BuruBuru','2025-08-17 20:59:48',2,NULL,'2025-08-17 17:59:48','2025-08-17 17:59:48'),(6,'REQ-10019',8,'Jeff','0724911674',31661035,'receiver',NULL,NULL,NULL,'Mombasa','2025-08-18 07:11:57',10,NULL,'2025-08-18 04:11:57','2025-08-18 04:11:57'),(7,'REQ-10007',8,'Fortune Maina','0747911672',33116013,'receiver',NULL,NULL,NULL,'Eldoret','2025-08-18 09:12:14',2,NULL,'2025-08-18 06:12:14','2025-08-18 06:12:14');
/*!40000 ALTER TABLE `shipment_deliveries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_items`
--

DROP TABLE IF EXISTS `shipment_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint unsigned NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `packages_no` int NOT NULL,
  `weight` int NOT NULL,
  `length` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `width` int DEFAULT NULL,
  `volume` int DEFAULT NULL,
  `actual_quantity` int DEFAULT NULL,
  `actual_weight` int DEFAULT NULL,
  `actual_length` int DEFAULT NULL,
  `actual_width` int DEFAULT NULL,
  `actual_height` int DEFAULT NULL,
  `actual_volume` int DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_items`
--

LOCK TABLES `shipment_items` WRITE;
/*!40000 ALTER TABLE `shipment_items` DISABLE KEYS */;
INSERT INTO `shipment_items` VALUES (1,1,'Mitumba',1,30,50,50,60,30,1,30,50,60,50,30,'OK','2025-08-14 14:18:13','2025-08-14 14:18:13'),(2,2,'itm 1',1,20,50,30,40,12,1,20,50,40,30,12,'ok','2025-08-14 14:47:11','2025-08-14 14:47:11'),(3,3,'Box of electrronics',1,30,50,50,60,30,1,30,50,60,50,150000,NULL,'2025-08-15 05:58:36','2025-08-15 05:59:39'),(4,4,'CPU',1,15,30,30,20,4,1,15,30,20,30,4,'ok','2025-08-15 06:31:22','2025-08-15 06:31:22'),(5,5,'Pots',1,25,50,50,50,25,1,25,50,50,50,25,NULL,'2025-08-15 06:35:58','2025-08-15 06:35:58'),(6,6,'Laptop',1,15,30,30,20,18000,1,15,30,20,30,18000,'ok','2025-08-15 07:38:37','2025-08-15 07:38:37'),(7,7,'Printer',1,35,50,50,70,35,1,35,50,70,50,35,NULL,'2025-08-15 09:59:32','2025-08-15 09:59:32'),(8,8,'item 1',1,10,20,60,40,10,1,10,20,40,60,10,NULL,'2025-08-15 11:59:01','2025-08-15 11:59:01'),(9,9,'Mitumba',1,30,50,30,40,12,1,30,50,40,30,12,NULL,'2025-08-15 13:26:44','2025-08-15 13:26:44'),(10,10,'shoes',1,10,20,20,30,2,1,10,20,30,20,2,NULL,'2025-08-15 15:15:42','2025-08-15 15:15:42'),(11,11,'item 1',1,20,20,60,30,7,1,20,20,30,60,7,NULL,'2025-08-15 15:47:31','2025-08-15 15:47:31'),(12,12,'shoes',1,40,20,20,20,2,1,40,20,20,20,2,NULL,'2025-08-17 06:50:21','2025-08-17 06:50:21'),(13,13,'Box of Keyboards',1,10,0,0,0,0,1,10,0,0,0,0,'first time walkin client','2025-08-17 08:58:59','2025-08-17 08:58:59'),(14,14,'Coconuts',1,20,NULL,NULL,NULL,NULL,1,20,NULL,NULL,NULL,NULL,NULL,'2025-08-17 09:39:20','2025-08-17 09:39:20'),(15,14,'Box of books',1,25,50,50,50,25,1,25,50,50,50,25,NULL,'2025-08-17 09:39:20','2025-08-17 09:39:20'),(16,15,'box of pharmaceuticals',1,15,NULL,NULL,NULL,NULL,1,15,NULL,NULL,NULL,NULL,NULL,'2025-08-17 10:43:35','2025-08-17 10:43:35'),(17,16,'Mitumba',1,55,NULL,NULL,NULL,NULL,1,55,NULL,NULL,NULL,NULL,NULL,'2025-08-17 11:31:40','2025-08-17 11:31:40'),(18,17,'Mitumba',1,55,NULL,NULL,NULL,NULL,1,55,NULL,NULL,NULL,NULL,NULL,'2025-08-17 11:52:45','2025-08-17 11:52:45'),(19,18,'door',1,50,NULL,NULL,NULL,NULL,1,50,NULL,NULL,NULL,0,NULL,'2025-08-17 13:11:14','2025-08-17 13:16:18'),(20,19,'item 1',1,15,NULL,NULL,NULL,NULL,1,15,NULL,NULL,NULL,0,NULL,'2025-08-17 18:14:42','2025-08-17 18:15:59'),(21,20,'itm 1',1,20,10,40,20,2,1,20,10,20,40,2,'ok','2025-08-18 02:25:04','2025-08-18 02:25:04'),(22,21,'Shoes',1,20,50,10,30,3,1,20,50,30,10,3,NULL,'2025-08-18 07:27:34','2025-08-18 07:27:34');
/*!40000 ALTER TABLE `shipment_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_sub_items`
--

DROP TABLE IF EXISTS `shipment_sub_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_sub_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_item_id` bigint unsigned NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `length` double DEFAULT NULL,
  `width` double DEFAULT NULL,
  `height` double DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_sub_items`
--

LOCK TABLES `shipment_sub_items` WRITE;
/*!40000 ALTER TABLE `shipment_sub_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipment_sub_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `waybillNo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `loadingSheetId` int DEFAULT NULL,
  `senderName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senderEmail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senderPhone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senderTown` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senderCountry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Kenya',
  `senderStreet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senderBuilding` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senderAccountNo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senderIdNo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senderKraPin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senderContactPerson` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senderContactPersonPhone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiverName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiverEmail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiverPhone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clientBranchOrigin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clientBranchDestination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiverTown` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiverCountry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Kenya',
  `receiverStreet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiverBuilding` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiverContactPerson` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiverContactPersonPhone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionOfGoods` text COLLATE utf8mb4_unicode_ci,
  `origin` text COLLATE utf8mb4_unicode_ci,
  `destination` text COLLATE utf8mb4_unicode_ci,
  `shippedVia` text COLLATE utf8mb4_unicode_ci,
  `deliveryDate` datetime DEFAULT NULL,
  `trackingNo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispatchDate` datetime DEFAULT NULL,
  `dispatchBy` int DEFAULT NULL,
  `service` text COLLATE utf8mb4_unicode_ci,
  `actualWeight` int DEFAULT NULL,
  `length` int DEFAULT NULL,
  `width` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `cbm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numOfPackages` int DEFAULT NULL,
  `expectedDeliveryDate` datetime NOT NULL,
  `shipmentType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `statusAtCollection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not yet collected',
  `cost` int DEFAULT NULL,
  `picture` text COLLATE utf8mb4_unicode_ci,
  `customerNote` text COLLATE utf8mb4_unicode_ci,
  `pickupLocation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deliveryLocation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deliveryOption` enum('doortodoor','officetooffice') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'doortodoor',
  `deliveryStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Delivered',
  `paymentStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Paid',
  `paymentType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COD',
  `clientType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Walkin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shipments_waybillno_unique` (`waybillNo`),
  KEY `shipments_user_id_foreign` (`user_id`),
  KEY `shipments_client_id_foreign` (`client_id`),
  CONSTRAINT `shipments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shipments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipments`
--

LOCK TABLES `shipments` WRITE;
/*!40000 ALTER TABLE `shipments` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `special_rates`
--

DROP TABLE IF EXISTS `special_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `special_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `approvedBy` int DEFAULT NULL,
  `added_by` bigint unsigned NOT NULL,
  `routeFrom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` int NOT NULL DEFAULT '0',
  `applicableFrom` datetime DEFAULT NULL,
  `applicableTo` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `approvalStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `client_id` bigint unsigned NOT NULL,
  `office_id` bigint unsigned NOT NULL,
  `zone_id` bigint unsigned NOT NULL,
  `dateApproved` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `special_rates_added_by_foreign` (`added_by`),
  CONSTRAINT `special_rates_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `special_rates`
--

LOCK TABLES `special_rates` WRITE;
/*!40000 ALTER TABLE `special_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `special_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stations`
--

DROP TABLE IF EXISTS `stations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `station_prefix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stations`
--

LOCK TABLES `stations` WRITE;
/*!40000 ALTER TABLE `stations` DISABLE KEYS */;
/*!40000 ALTER TABLE `stations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sub_category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_categories`
--

LOCK TABLES `sub_categories` WRITE;
/*!40000 ALTER TABLE `sub_categories` DISABLE KEYS */;
INSERT INTO `sub_categories` VALUES (1,'Overnight','Overnight Delivery','2025-08-14 14:02:47','2025-08-14 14:02:47'),(2,'Same Day','Same Day Delivery','2025-08-14 14:02:47','2025-08-14 14:02:47');
/*!40000 ALTER TABLE `sub_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracking_infos`
--

DROP TABLE IF EXISTS `tracking_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tracking_infos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trackId` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `vehicle_id` bigint unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `volume` int DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracking_infos`
--

LOCK TABLES `tracking_infos` WRITE;
/*!40000 ALTER TABLE `tracking_infos` DISABLE KEYS */;
INSERT INTO `tracking_infos` VALUES (1,1,NULL,NULL,'2025-08-14 17:18:13','Parcel received from Walk-in Client',NULL,NULL,NULL,'Geff Letting received 1 item with a total weight of 30kgs from Jumuka Supplies, generated client request ID REQ-10000, with waybill number UCSL0000000001KE and a consignment note with ID CN-10000','2025-08-14 14:18:13','2025-08-14 14:18:13'),(2,1,NULL,NULL,'2025-08-14 17:25:10','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Jumuka Supplies, request ID REQ-10000, with waybill number UCSL0000000001KE and a consignment note with ID CN-10000','2025-08-14 14:25:10','2025-08-14 14:25:10'),(3,1,NULL,NULL,'2025-08-14 17:28:30','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-14 14:28:30','2025-08-14 14:28:30'),(4,2,NULL,NULL,'2025-08-14 17:47:11','Parcel received from Walk-in Client',NULL,NULL,NULL,'Geff Letting received 1 item with a total weight of 20kgs from Jumuka Supplies, generated client request ID REQ-10001, with waybill number UCSL0000000002KE and a consignment note with ID CN-10001','2025-08-14 14:47:11','2025-08-14 14:47:11'),(5,2,NULL,NULL,'2025-08-14 17:49:36','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Jumuka Supplies, request ID REQ-10001, with waybill number UCSL0000000002KE and a consignment note with ID CN-10001','2025-08-14 14:49:36','2025-08-14 14:49:36'),(6,2,NULL,NULL,'2025-08-14 17:51:16','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-14 14:51:16','2025-08-14 14:51:16'),(7,2,5,NULL,'2025-08-14 17:52:09','Parcel issued to receiver/agent',NULL,NULL,NULL,'Issued parcel for request ID REQ-10001 to designated receiver/agent.','2025-08-14 14:52:09','2025-08-14 14:52:09'),(8,3,1,2,'2025-08-15 08:56:19','Client Request Submitted for Collection',NULL,NULL,NULL,'Received client collection request, generated client request ID REQ-10002, allocated Mwaele Emmanuel KCM 456B for collection','2025-08-15 05:56:19','2025-08-15 05:56:19'),(9,3,NULL,NULL,'2025-08-15 08:58:36','Parcel Collected at Client Premises',NULL,NULL,NULL,'Rider arrived at client premises for collection; Collected 1 item with total weight of 30 kgs. Generated Consignment Note Number CN-10002','2025-08-15 05:58:36','2025-08-15 05:58:36'),(10,3,NULL,NULL,'2025-08-15 08:59:39','Parcel Verified and ready for dispatch',NULL,NULL,NULL,'Rider delivered the parcel to the office for verification; Parcel Verified; Waybill Number generated UCSL0000000004KE','2025-08-15 05:59:39','2025-08-15 05:59:39'),(11,3,NULL,NULL,'2025-08-15 09:01:55','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Maisha Medcare, request ID REQ-10002, with waybill number UCSL0000000004KE and a consignment note with ID CN-10002','2025-08-15 06:01:55','2025-08-15 06:01:55'),(12,3,NULL,NULL,'2025-08-15 09:17:20','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-15 06:17:20','2025-08-15 06:17:20'),(13,4,NULL,NULL,'2025-08-15 09:31:22','Parcel received from Walk-in Client',NULL,NULL,NULL,'Mwaele Emmanuel received 1 item with a total weight of 15kgs from Kilimani Pharma, generated client request ID REQ-10003, with waybill number UCSL0000000005KE and a consignment note with ID CN-10003','2025-08-15 06:31:22','2025-08-15 06:31:22'),(14,5,NULL,NULL,'2025-08-15 09:35:58','Parcel received from Walk-in Client',NULL,NULL,NULL,'Mwaele Emmanuel received 1 item with a total weight of 25kgs from Jumuka Supplies, generated client request ID REQ-10004, with waybill number UCSL0000000006KE and a consignment note with ID CN-10004','2025-08-15 06:35:58','2025-08-15 06:35:58'),(15,5,NULL,NULL,'2025-08-15 10:17:53','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Mwaele Emmanuel of phone number { 0747911672 } to deliver your parcel REQ-10004 Waybill No: UCSL0000000006KE.','2025-08-15 07:17:53','2025-08-15 07:17:53'),(16,6,NULL,NULL,'2025-08-15 10:38:37','Parcel received from Walk-in Client',NULL,NULL,NULL,'Geff Letting received 1 item with a total weight of 15kgs from Jumuka Supplies, generated client request ID REQ-10005, with waybill number UCSL0000000007KE and a consignment note with ID CN-10005','2025-08-15 07:38:37','2025-08-15 07:38:37'),(17,3,5,NULL,'2025-08-15 11:42:11','Parcel issued to receiver/agent',NULL,NULL,NULL,'Issued parcel for request ID REQ-10002 to designated receiver/agent.','2025-08-15 08:42:11','2025-08-15 08:42:11'),(18,1,5,NULL,'2025-08-15 11:45:28','Parcel issued to receiver/agent',NULL,NULL,NULL,'Issued parcel for request ID REQ-10000 to designated receiver/agent.','2025-08-15 08:45:28','2025-08-15 08:45:28'),(19,7,NULL,NULL,'2025-08-15 12:59:32','Parcel received from Walk-in Client',NULL,NULL,NULL,'Geff Letting received 1 item with a total weight of 35kgs from Jumuka Supplies, generated client request ID REQ-10006, with waybill number UCSL0000000008KE and a consignment note with ID CN-10006','2025-08-15 09:59:32','2025-08-15 09:59:32'),(20,7,NULL,NULL,'2025-08-15 13:03:22','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Jumuka Supplies, request ID REQ-10006, with waybill number UCSL0000000008KE and a consignment note with ID CN-10006','2025-08-15 10:03:22','2025-08-15 10:03:22'),(21,7,NULL,NULL,'2025-08-15 13:06:49','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-15 10:06:49','2025-08-15 10:06:49'),(22,7,5,NULL,'2025-08-15 13:07:47','Parcel issued to receiver/agent',NULL,NULL,NULL,'Issued parcel for request ID REQ-10006 to designated receiver/agent.','2025-08-15 10:07:47','2025-08-15 10:07:47'),(23,8,NULL,NULL,'2025-08-15 14:59:01','Parcel received from Walk-in Client',NULL,NULL,NULL,'Geff Letting received 1 item with a total weight of 9.6kgs from Jumuka Supplies, generated client request ID REQ-10007, with waybill number UCSL0000000009KE and a consignment note with ID CN-10007','2025-08-15 11:59:01','2025-08-15 11:59:01'),(24,8,NULL,NULL,'2025-08-15 16:04:48','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Jumuka Supplies, request ID REQ-10007, with waybill number UCSL0000000009KE and a consignment note with ID CN-10007','2025-08-15 13:04:48','2025-08-15 13:04:48'),(25,8,NULL,NULL,'2025-08-15 16:10:51','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-15 13:10:51','2025-08-15 13:10:51'),(26,8,5,NULL,'2025-08-15 16:14:21','Parcel issued to receiver/agent',NULL,NULL,NULL,'Issued parcel for request ID REQ-10007 to designated receiver/agent.','2025-08-15 13:14:21','2025-08-15 13:14:21'),(27,9,NULL,NULL,'2025-08-15 16:26:44','Parcel received from Walk-in Client',NULL,NULL,NULL,'Geff Letting received 1 item with a total weight of 30kgs from Jumuka Supplies, generated client request ID REQ-10008, with waybill number UCSL0000000010KE and a consignment note with ID CN-10008','2025-08-15 13:26:44','2025-08-15 13:26:44'),(28,9,NULL,NULL,'2025-08-15 16:36:04','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Jumuka Supplies, request ID REQ-10008, with waybill number UCSL0000000010KE and a consignment note with ID CN-10008','2025-08-15 13:36:04','2025-08-15 13:36:04'),(29,9,NULL,NULL,'2025-08-15 17:40:20','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-15 14:40:20','2025-08-15 14:40:20'),(30,10,2,1,'2025-08-15 17:57:21','Client Request Submitted for Collection',NULL,NULL,NULL,'Received client collection request, generated client request ID REQ-10009, allocated Jeff Kip KDG 123A for collection','2025-08-15 14:57:21','2025-08-15 14:57:21'),(31,11,1,2,'2025-08-15 18:13:41','Client Request Submitted for Collection',NULL,NULL,NULL,'Received client collection request, generated client request ID REQ-10010, allocated Mwaele Emmanuel KCM 456B for collection','2025-08-15 15:13:41','2025-08-15 15:13:41'),(32,11,NULL,NULL,'2025-08-15 18:15:42','Parcel Collected at Client Premises',NULL,NULL,NULL,'Rider arrived at client premises for collection; Collected 1 item with total weight of 10 kgs. Generated Consignment Note Number CN-10009','2025-08-15 15:15:42','2025-08-15 15:15:42'),(33,11,1,NULL,'2025-08-15 18:25:43','Parcel Delivered and GRN Created',NULL,NULL,NULL,'Parcel with request ID REQ-10010 was delivered to Jeff Letting at Madaraka, Nairobi by rider Mwaele Emmanuel. GRN No: ','2025-08-15 15:25:43','2025-08-15 15:25:43'),(34,9,1,NULL,'2025-08-15 18:34:46','Parcel issued to receiver/agent',NULL,NULL,NULL,'Issued parcel for request ID REQ-10008 to designated receiver/agent.','2025-08-15 15:34:46','2025-08-15 15:34:46'),(35,10,NULL,NULL,'2025-08-15 18:47:31','Parcel Collected at Client Premises',NULL,NULL,NULL,'Rider arrived at client premises for collection; Collected 1 item with total weight of 20 kgs. Generated Consignment Note Number CN-10010','2025-08-15 15:47:31','2025-08-15 15:47:31'),(36,10,2,NULL,'2025-08-15 18:49:02','Parcel Delivered and GRN Created',NULL,NULL,NULL,'Parcel with request ID REQ-10009 was delivered to JKL at Madaraka, Nairobi by rider Jeff Kip. GRN No: ','2025-08-15 15:49:02','2025-08-15 15:49:02'),(37,12,2,1,'2025-08-17 09:47:16','Client Request Submitted for Collection',NULL,NULL,NULL,'Received client collection request, generated client request ID REQ-10011, allocated Jeff Kip KDG 123A for collection','2025-08-17 06:47:16','2025-08-17 06:47:16'),(38,12,NULL,NULL,'2025-08-17 09:50:21','Parcel Collected at Client Premises',NULL,NULL,NULL,'Rider arrived at client premises for collection; Collected 1 item with total weight of 40 kgs. Generated Consignment Note Number CN-10011','2025-08-17 06:50:21','2025-08-17 06:50:21'),(39,13,NULL,NULL,'2025-08-17 11:58:59','Parcel received from Walk-in Client',NULL,NULL,NULL,'Jeff Kip received 1 item with a total weight of 10kgs from James Opiyo, generated client request ID REQ-10012, with waybill number UCSL0000000014KE and a consignment note with ID CN-10012','2025-08-17 08:58:59','2025-08-17 08:58:59'),(40,13,NULL,NULL,'2025-08-17 12:12:56','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Jeff Kip of phone number { 0724911674 } to deliver your parcel REQ-10012 Waybill No: UCSL0000000014KE.','2025-08-17 09:12:56','2025-08-17 09:12:56'),(41,14,NULL,NULL,'2025-08-17 12:39:20','Parcel received from Walk-in Client',NULL,NULL,NULL,'Mwaele Emmanuel received 2 items with a total weight of 45kgs from Shadrack Killu, generated client request ID REQ-10013, with waybill number UCSL0000000015KE and a consignment note with ID CN-10013','2025-08-17 09:39:20','2025-08-17 09:39:20'),(42,14,NULL,NULL,'2025-08-17 12:45:43','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Salim Hassan of phone number { 0782911674 } to deliver your parcel REQ-10013 Waybill No: UCSL0000000015KE.','2025-08-17 09:45:43','2025-08-17 09:45:43'),(43,14,4,NULL,'2025-08-17 12:51:57','Agent Approval Request Submitted',NULL,NULL,NULL,'Agent Name: John Kalama, ID: 22114560, Phone: 0782911674, Reason: Authorized by the parcel owner','2025-08-17 09:51:57','2025-08-17 09:51:57'),(44,14,4,NULL,'2025-08-17 12:58:02','Agent Approval Request Submitted',NULL,NULL,NULL,'Agent Name: test, ID: 2228121, Phone: 328838, Reason: dsdnsnsnnsn','2025-08-17 09:58:02','2025-08-17 09:58:02'),(45,14,4,NULL,'2025-08-17 13:01:35','Agent Approval Request Submitted',NULL,NULL,NULL,'Agent Name: Robert James, ID: 33112056, Phone: 0724911674, Reason: Owner approved collection','2025-08-17 10:01:35','2025-08-17 10:01:35'),(46,14,4,NULL,'2025-08-17 13:14:46','Parcel Delivered and GRN Created',NULL,NULL,NULL,'Parcel with request ID REQ-10013 was delivered to Jeff Letting at Eastlands, Nairobi by rider Neddy Sakura. GRN No: ','2025-08-17 10:14:46','2025-08-17 10:14:46'),(47,15,2,1,'2025-08-17 13:38:34','Client Request Submitted for Collection',NULL,NULL,NULL,'Received client collection request, generated client request ID REQ-10014, allocated Jeff Kip KDG 123A for collection','2025-08-17 10:38:34','2025-08-17 10:38:34'),(48,15,NULL,NULL,'2025-08-17 13:43:35','Parcel Collected at Client Premises',NULL,NULL,NULL,'Rider arrived at client premises for collection; Collected 1 item with total weight of 15 kgs. Generated Consignment Note Number CN-10014','2025-08-17 10:43:35','2025-08-17 10:43:35'),(49,15,2,NULL,'2025-08-17 13:45:44','Parcel Delivered and GRN Created',NULL,NULL,NULL,'Parcel with request ID REQ-10014 was delivered to Maisha Pharmacy at Adams Arcade, Nairobi by rider Jeff Kip. GRN No: ','2025-08-17 10:45:44','2025-08-17 10:45:44'),(50,16,NULL,NULL,'2025-08-17 14:31:40','Parcel received from Walk-in Client',NULL,NULL,NULL,'Neddy Sakura received 1 item with a total weight of 55kgs from Stephen Kiprono, generated client request ID REQ-10015, with waybill number UCSL0000000017KE and a consignment note with ID CN-10015','2025-08-17 11:31:40','2025-08-17 11:31:40'),(51,17,NULL,NULL,'2025-08-17 14:52:45','Parcel received from Walk-in Client',NULL,NULL,NULL,'Neddy Sakura received 1 item with a total weight of 55kgs from Stephen Kiprono, generated client request ID REQ-10016, with waybill number UCSL0000000018KE and a consignment note with ID CN-10016','2025-08-17 11:52:45','2025-08-17 11:52:45'),(52,16,NULL,NULL,'2025-08-17 15:01:38','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Stephen Kiprono, request ID REQ-10015, with waybill number UCSL0000000017KE and a consignment note with ID CN-10015','2025-08-17 12:01:38','2025-08-17 12:01:38'),(53,17,NULL,NULL,'2025-08-17 15:01:39','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Stephen Kiprono, request ID REQ-10016, with waybill number UCSL0000000018KE and a consignment note with ID CN-10016','2025-08-17 12:01:39','2025-08-17 12:01:39'),(54,17,NULL,NULL,'2025-08-17 15:20:44','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-17 12:20:44','2025-08-17 12:20:44'),(55,16,NULL,NULL,'2025-08-17 15:21:07','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-17 12:21:07','2025-08-17 12:21:07'),(56,16,NULL,NULL,'2025-08-17 15:24:00','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Brian Kiprotich of phone number { 0785868559 } to deliver your parcel REQ-10015 Waybill No: UCSL0000000017KE.','2025-08-17 12:24:00','2025-08-17 12:24:00'),(57,17,NULL,NULL,'2025-08-17 15:41:19','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Brian Kiprotich of phone number { 0724911674 } to deliver your parcel REQ-10016 Waybill No: UCSL0000000018KE.','2025-08-17 12:41:19','2025-08-17 12:41:19'),(58,9,NULL,NULL,'2025-08-17 15:50:29','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Brian Kiprotich of phone number { 0724911674 } to deliver your parcel REQ-10008 Waybill No: UCSL0000000010KE.','2025-08-17 12:50:29','2025-08-17 12:50:29'),(59,18,2,1,'2025-08-17 16:07:12','Client Request Submitted for Collection',NULL,NULL,NULL,'Received client collection request, generated client request ID REQ-10017, allocated Jeff Kip KDG 123A for collection','2025-08-17 13:07:12','2025-08-17 13:07:12'),(60,18,NULL,NULL,'2025-08-17 16:11:14','Parcel Collected at Client Premises',NULL,NULL,NULL,'Rider arrived at client premises for collection; Collected 1 item with total weight of 50 kgs. Generated Consignment Note Number CN-10017','2025-08-17 13:11:14','2025-08-17 13:11:14'),(61,18,NULL,NULL,'2025-08-17 16:16:18','Parcel Verified and ready for dispatch',NULL,NULL,NULL,'Rider delivered the parcel to the office for verification; Parcel Verified; Waybill Number generated UCSL0000000020KE','2025-08-17 13:16:18','2025-08-17 13:16:18'),(62,18,NULL,NULL,'2025-08-17 16:18:28','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Kenlog Logistics, request ID REQ-10017, with waybill number UCSL0000000020KE and a consignment note with ID CN-10017','2025-08-17 13:18:28','2025-08-17 13:18:28'),(63,18,NULL,NULL,'2025-08-17 16:43:50','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-17 13:43:50','2025-08-17 13:43:50'),(64,18,NULL,NULL,'2025-08-17 16:51:08','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Caleb Wasuna of phone number { 0724911674 } to deliver your parcel REQ-10017 Waybill No: UCSL0000000020KE.','2025-08-17 13:51:08','2025-08-17 13:51:08'),(65,18,1,NULL,'2025-08-17 16:58:02','Parcel delivered to receiver/agent',NULL,NULL,NULL,'Delivered parcel for request ID REQ-10017 to designated receiver/agent.','2025-08-17 13:58:02','2025-08-17 13:58:02'),(66,12,2,NULL,'2025-08-17 20:59:48','Parcel Delivered and GRN Created',NULL,NULL,NULL,'Parcel with request ID REQ-10011 was delivered to JKL at BuruBuru, Nairobi by rider Jeff Kip. GRN No: ','2025-08-17 17:59:48','2025-08-17 17:59:48'),(67,19,2,1,'2025-08-17 21:12:34','Client Request Submitted for Collection',NULL,NULL,NULL,'Received client collection request, generated client request ID REQ-10018, allocated Jeff Kip KDG 123A for collection','2025-08-17 18:12:34','2025-08-17 18:12:34'),(68,19,NULL,NULL,'2025-08-17 21:14:42','Parcel Collected at Client Premises',NULL,NULL,NULL,'Rider arrived at client premises for collection; Collected 1 item with total weight of 15 kgs. Generated Consignment Note Number CN-10018','2025-08-17 18:14:42','2025-08-17 18:14:42'),(69,19,NULL,NULL,'2025-08-17 21:15:59','Parcel Verified and ready for dispatch',NULL,NULL,NULL,'Rider delivered the parcel to the office for verification; Parcel Verified; Waybill Number generated UCSL0000000022KE','2025-08-17 18:15:59','2025-08-17 18:15:59'),(70,19,NULL,NULL,'2025-08-17 21:18:55','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Kenlog Logistics, request ID REQ-10018, with waybill number UCSL0000000022KE and a consignment note with ID CN-10018','2025-08-17 18:18:55','2025-08-17 18:18:55'),(71,19,NULL,NULL,'2025-08-17 21:52:25','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-17 18:52:25','2025-08-17 18:52:25'),(72,19,NULL,NULL,'2025-08-17 21:55:30','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Caleb Wasuna of phone number { 0724911674 } to deliver your parcel REQ-10018 Waybill No: UCSL0000000022KE.','2025-08-17 18:55:30','2025-08-17 18:55:30'),(73,20,NULL,NULL,'2025-08-18 05:25:04','Parcel received from Walk-in Client',NULL,NULL,NULL,'Jeff Kip received 1 item with a total weight of 20kgs from Jumuka Supplies, generated client request ID REQ-10019, with waybill number UCSL0000000023KE and a consignment note with ID CN-10019','2025-08-18 02:25:04','2025-08-18 02:25:04'),(74,20,NULL,NULL,'2025-08-18 05:30:02','Parcel dispatched',NULL,NULL,NULL,'Jeff Letting dispatched the parcel  from Jumuka Supplies, request ID REQ-10019, with waybill number UCSL0000000023KE and a consignment note with ID CN-10019','2025-08-18 02:30:02','2025-08-18 02:30:02'),(75,20,NULL,NULL,'2025-08-18 05:31:07','Parcel Arrived, Verified and ready for Collection',NULL,NULL,NULL,'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection','2025-08-18 02:31:07','2025-08-18 02:31:07'),(76,20,NULL,NULL,'2025-08-18 05:56:25','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Geoffrey Letting of phone number { 0782911674 } to deliver your parcel REQ-10019 Waybill No: UCSL0000000023KE.','2025-08-18 02:56:25','2025-08-18 02:56:25'),(77,20,NULL,NULL,'2025-08-18 07:06:56','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Geoffrey Letting of phone number { 0782911674 } to deliver your parcel REQ-10019 Waybill No: UCSL0000000023KE.','2025-08-18 04:06:56','2025-08-18 04:06:56'),(78,20,NULL,NULL,'2025-08-18 07:09:52','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Geoffrey Letting of phone number { 0782911674 } to deliver your parcel REQ-10019 Waybill No: UCSL0000000023KE.','2025-08-18 04:09:52','2025-08-18 04:09:52'),(79,20,10,NULL,'2025-08-18 07:11:57','Parcel Delivered and GRN Created',NULL,NULL,NULL,'Parcel with request ID REQ-10019 was delivered to Jeff at Mombasa, Mombasa by rider Geoffrey Letting. GRN No: ','2025-08-18 04:11:57','2025-08-18 04:11:57'),(80,8,NULL,NULL,'2025-08-18 08:32:49','Delivery Rider Allocated',NULL,NULL,NULL,'We have allocated Jeff Kip of phone number { 0724911674 } to deliver your parcel REQ-10007 Waybill No: UCSL0000000009KE.','2025-08-18 05:32:49','2025-08-18 05:32:49'),(81,8,2,NULL,'2025-08-18 09:12:14','Parcel Delivered and GRN Created',NULL,NULL,NULL,'Parcel with request ID REQ-10007 was delivered to Fortune Maina at Eldoret, Eldoret by rider Jeff Kip. GRN No: ','2025-08-18 06:12:14','2025-08-18 06:12:14'),(82,21,NULL,NULL,'2025-08-18 10:27:34','Parcel received from Walk-in Client',NULL,NULL,NULL,'Jeff Kip received 1 item with a total weight of 20kgs from Jumuka Supplies, generated client request ID REQ-10020, with waybill number UCSL0000000024KE and a consignment note with ID CN-10020','2025-08-18 07:27:34','2025-08-18 07:27:34');
/*!40000 ALTER TABLE `tracking_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trackings`
--

DROP TABLE IF EXISTS `trackings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trackings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `deliveryStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pickup',
  `note` text COLLATE utf8mb4_unicode_ci,
  `office` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loadingSheetId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waybillNo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userId` int DEFAULT NULL,
  `shipment_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trackings`
--

LOCK TABLES `trackings` WRITE;
/*!40000 ALTER TABLE `trackings` DISABLE KEYS */;
/*!40000 ALTER TABLE `trackings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracks`
--

DROP TABLE IF EXISTS `tracks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tracks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clientId` bigint unsigned NOT NULL,
  `requestId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracks`
--

LOCK TABLES `tracks` WRITE;
/*!40000 ALTER TABLE `tracks` DISABLE KEYS */;
INSERT INTO `tracks` VALUES (1,8,'REQ-10000','Issued','2025-08-14 14:18:13','2025-08-15 08:45:28'),(2,8,'REQ-10001','Issued','2025-08-14 14:47:11','2025-08-14 14:52:09'),(3,5,'REQ-10002','Issued','2025-08-15 05:56:19','2025-08-15 08:42:11'),(4,7,'REQ-10003','Awaiting Dispatch','2025-08-15 06:31:22','2025-08-15 06:31:22'),(5,8,'REQ-10004','Delivery Rider Allocated','2025-08-15 06:35:58','2025-08-15 07:17:53'),(6,8,'REQ-10005','Awaiting Dispatch','2025-08-15 07:38:37','2025-08-15 07:38:37'),(7,8,'REQ-10006','Issued','2025-08-15 09:59:32','2025-08-15 10:07:47'),(8,8,'REQ-10007','Delivered','2025-08-15 11:59:01','2025-08-18 06:12:14'),(9,8,'REQ-10008','Delivery Rider Allocated','2025-08-15 13:26:44','2025-08-17 12:50:29'),(10,5,'REQ-10009','Delivered','2025-08-15 14:57:21','2025-08-15 15:49:02'),(11,5,'REQ-10010','Delivered','2025-08-15 15:13:41','2025-08-15 15:25:43'),(12,5,'REQ-10011','Delivered','2025-08-17 06:47:16','2025-08-17 17:59:48'),(13,11,'REQ-10012','Delivery Rider Allocated','2025-08-17 08:58:59','2025-08-17 09:12:56'),(14,12,'REQ-10013','Delivered','2025-08-17 09:39:20','2025-08-17 10:14:46'),(15,5,'REQ-10014','Delivered','2025-08-17 10:38:34','2025-08-17 10:45:44'),(16,14,'REQ-10015','Delivery Rider Allocated','2025-08-17 11:31:40','2025-08-17 12:24:00'),(17,14,'REQ-10016','Delivery Rider Allocated','2025-08-17 11:52:45','2025-08-17 12:41:19'),(18,6,'REQ-10017','Issued','2025-08-17 13:07:12','2025-08-17 13:58:02'),(19,6,'REQ-10018','Delivery Rider Allocated','2025-08-17 18:12:34','2025-08-17 18:55:30'),(20,8,'REQ-10019','Delivered','2025-08-18 02:25:04','2025-08-18 04:11:57'),(21,8,'REQ-10020','Awaiting Dispatch','2025-08-18 07:27:34','2025-08-18 07:27:34');
/*!40000 ALTER TABLE `tracks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transporter_trucks`
--

DROP TABLE IF EXISTS `transporter_trucks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transporter_trucks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transporter_id` bigint unsigned NOT NULL,
  `reg_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_id_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `truck_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transporter_trucks_transporter_id_foreign` (`transporter_id`),
  CONSTRAINT `transporter_trucks_transporter_id_foreign` FOREIGN KEY (`transporter_id`) REFERENCES `transporters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporter_trucks`
--

LOCK TABLES `transporter_trucks` WRITE;
/*!40000 ALTER TABLE `transporter_trucks` DISABLE KEYS */;
INSERT INTO `transporter_trucks` VALUES (1,1,'KBS 123A','Peter Mwangi','0711222333','12345678','10-Ton Lorry','booked','2025-08-14 14:02:49','2025-08-15 13:35:48'),(2,2,'KDA 456B','Janet Wanjiku','0799887766','87654321','Mini Truck','in_transit','2025-08-14 14:02:49','2025-08-14 14:02:49'),(3,1,'KCE 789C','James Otieno','0722333444','11223344','Box Truck','maintenance','2025-08-14 14:02:49','2025-08-14 14:02:49'),(4,2,'KDF 321D','Lucy Njeri','0733111222','44332211','Tipper Truck','booked','2025-08-14 14:02:49','2025-08-14 14:49:17'),(5,1,'KDG 654E','Brian Kiprotich','0700111222','99887766','Flatbed Truck','in_transit','2025-08-14 14:02:49','2025-08-14 14:02:49'),(6,2,'KDJ 987F','Grace Akinyi','0788333222','55667788','Container Truck','booked','2025-08-14 14:02:49','2025-08-15 06:01:37'),(7,1,'KDK 111G','Samuel Maina','0721222333','33445566','Refrigerated Truck','in_transit','2025-08-14 14:02:49','2025-08-14 14:02:49'),(8,2,'KDL 222H','Esther Mumo','0744222333','66778899','Pickup','booked','2025-08-14 14:02:49','2025-08-15 10:02:11'),(9,1,'KDM 333J','Anthony Kimani','0711777888','22334455','Canter','maintenance','2025-08-14 14:02:49','2025-08-14 14:02:49'),(10,2,'KDN 444K','Caroline Chebet','0766333444','77889900','Fuel Tanker','booked','2025-08-14 14:02:49','2025-08-15 13:04:35'),(11,3,'KCT 900F','Khamisi','0724911674','22152036','Truck','booked','2025-08-14 14:10:02','2025-08-14 14:24:45'),(12,3,'KDE 900H','Mwalimu','0782911674','36552201','Truck','booked','2025-08-17 11:33:45','2025-08-17 11:53:39'),(13,3,'KCX 008U','Salim','0747911672','66532015','Canter','booked','2025-08-17 11:34:36','2025-08-17 13:17:09'),(14,3,'KCT 005K','MARK MASAI','0725224510','25225545','Canter','booked','2025-08-17 11:35:26','2025-08-17 18:18:02'),(15,3,'KCQ 009Y','Victor','0782660411','22554463','Canter','booked','2025-08-17 11:36:26','2025-08-18 02:25:48');
/*!40000 ALTER TABLE `transporter_trucks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transporters`
--

DROP TABLE IF EXISTS `transporters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transporters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transporter_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cbv_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transporters_email_unique` (`email`),
  UNIQUE KEY `transporters_account_no_unique` (`account_no`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporters`
--

LOCK TABLES `transporters` WRITE;
/*!40000 ALTER TABLE `transporters` DISABLE KEYS */;
INSERT INTO `transporters` VALUES (1,'John Logistics Ltd.','0712345678','RC-0092345','Truck','CBV123456',NULL,'johnlogistics@example.com','ACC1001','2025-08-14 14:02:49','2025-08-14 14:02:49'),(2,'FastMove Transporters','0798765432','RC-0054321','Van',NULL,NULL,'fastmove@example.com','ACC1002','2025-08-14 14:02:49','2025-08-14 14:02:49'),(3,'Ufanisi Courier Services Ltd','0782911674','Active','self','6273GRT','signatures/adFeWRIBuk5S16IxmQNol5RVp3y1vszLE2oFZ2bm.png','info@ufanisicourier.co.ke','TRN-66222','2025-08-14 14:09:15','2025-08-14 14:09:15');
/*!40000 ALTER TABLE `transporters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_logs`
--

DROP TABLE IF EXISTS `user_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `table` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_logs`
--

LOCK TABLES `user_logs` WRITE;
/*!40000 ALTER TABLE `user_logs` DISABLE KEYS */;
INSERT INTO `user_logs` VALUES (1,'Jumuka Supplies','Logged in the tracking app','http://127.0.0.1:8000/client/login',8,'clients','2025-08-14 14:34:36','2025-08-14 14:34:36'),(2,'Jumuka Supplies','Tracked Parcel Request ID: REQ-10000 and results  found','http://127.0.0.1:8000/track/REQ-10000',8,'clients','2025-08-14 14:34:40','2025-08-14 14:34:40'),(3,'Jumuka Supplies','Tracked Parcel Request ID: REQ-10000 and generated pdf report','http://127.0.0.1:8000/track/REQ-10000/pdf',8,'clients','2025-08-14 14:34:50','2025-08-14 14:34:50'),(4,'Maisha Medcare','Logged in the tracking app','http://127.0.0.1:8000/client/login',5,'clients','2025-08-15 09:49:20','2025-08-15 09:49:20'),(5,'Maisha Medcare','Tracked Parcel Request ID: REQ-10002 and results  found','http://127.0.0.1:8000/track/REQ-10002',5,'clients','2025-08-15 09:49:24','2025-08-15 09:49:24'),(6,'Maisha Medcare','Tracked Parcel Request ID: REQ-10002 and generated pdf report','http://127.0.0.1:8000/track/REQ-10002/pdf',5,'clients','2025-08-15 09:49:50','2025-08-15 09:49:50'),(7,'Maisha Medcare','Tracked Parcel Request ID: REQ-10006 and results  found','http://127.0.0.1:8000/track/REQ-10006',5,'clients','2025-08-15 10:00:56','2025-08-15 10:00:56'),(8,'Maisha Medcare','Tracked Parcel Request ID: REQ-10006 and results  found','http://127.0.0.1:8000/track/REQ-10006',5,'clients','2025-08-15 10:06:02','2025-08-15 10:06:02'),(9,'Maisha Medcare','Tracked Parcel Request ID: REQ-10006 and results  found','http://127.0.0.1:8000/track/REQ-10006',5,'clients','2025-08-15 10:08:45','2025-08-15 10:08:45'),(10,'Maisha Medcare','Tracked Parcel Request ID: REQ-10006 and generated pdf report','http://127.0.0.1:8000/track/REQ-10006/pdf',5,'clients','2025-08-15 10:09:08','2025-08-15 10:09:08'),(11,'Maisha Medcare','Logged in the tracking app','http://127.0.0.1:8000/client/login',5,'clients','2025-08-15 13:17:16','2025-08-15 13:17:16'),(12,'Maisha Medcare','Tracked Parcel Request ID: REQ-10007 and results  found','http://127.0.0.1:8000/track/REQ-10007',5,'clients','2025-08-15 13:17:31','2025-08-15 13:17:31'),(13,'Maisha Medcare','Tracked Parcel Request ID: REQ-10007 and generated pdf report','http://127.0.0.1:8000/track/REQ-10007/pdf',5,'clients','2025-08-15 13:17:35','2025-08-15 13:17:35'),(14,'Maisha Medcare','Logged in the tracking app','http://127.0.0.1:8000/client/login',5,'clients','2025-08-15 15:11:32','2025-08-15 15:11:32'),(15,'Maisha Medcare','Tracked Parcel Request ID: REQ-10009 and results  found','http://127.0.0.1:8000/track/REQ-10009',5,'clients','2025-08-15 15:11:43','2025-08-15 15:11:43'),(16,'Maisha Medcare','Logged in the tracking app','http://127.0.0.1:8000/client/login',5,'clients','2025-08-15 15:20:01','2025-08-15 15:20:01'),(17,'Maisha Medcare','Tracked Parcel Request ID: REQ-10010 and results  found','http://127.0.0.1:8000/track/REQ-10010',5,'clients','2025-08-15 15:20:07','2025-08-15 15:20:07'),(18,'Maisha Medcare','Logged in the tracking app','http://127.0.0.1:8000/client/login',5,'clients','2025-08-15 15:48:24','2025-08-15 15:48:24'),(19,'Maisha Medcare','Tracked Parcel Request ID: REQ-10009 and results  found','http://127.0.0.1:8000/track/REQ-10009',5,'clients','2025-08-15 15:48:27','2025-08-15 15:48:27'),(20,'Maisha Medcare','Tracked Parcel Request ID: REQ-10009 and generated pdf report','http://127.0.0.1:8000/track/REQ-10009/pdf',5,'clients','2025-08-15 15:49:37','2025-08-15 15:49:37'),(21,'Shadrack Killu','Logged in the tracking app','http://127.0.0.1:8000/client/login',12,'clients','2025-08-17 10:24:07','2025-08-17 10:24:07'),(22,'Shadrack Killu','Tracked Parcel Request ID: REQ-10013 and results  found','http://127.0.0.1:8000/track/REQ-10013',12,'clients','2025-08-17 10:24:24','2025-08-17 10:24:24'),(23,'Shadrack Killu','Tracked Parcel Request ID: REQ-10013 and generated pdf report','http://127.0.0.1:8000/track/REQ-10013/pdf',12,'clients','2025-08-17 10:24:50','2025-08-17 10:24:50'),(24,'Shadrack Killu','Logged out the tracking app','http://127.0.0.1:8000/client/logout',12,'clients','2025-08-17 10:26:59','2025-08-17 10:26:59'),(25,'James Opiyo','Logged in the tracking app','http://127.0.0.1:8000/client/login',11,'clients','2025-08-17 10:27:39','2025-08-17 10:27:39'),(26,'James Opiyo','Tracked Parcel Request ID: REQ-10012 and results  found','http://127.0.0.1:8000/track/REQ-10012',11,'clients','2025-08-17 10:27:48','2025-08-17 10:27:48'),(27,'James Opiyo','Tracked Parcel Request ID: REQ-10012 and generated pdf report','http://127.0.0.1:8000/track/REQ-10012/pdf',11,'clients','2025-08-17 10:27:56','2025-08-17 10:27:56'),(28,'James Opiyo','Tracked Parcel Request ID: REQ-10014 and results  found','http://127.0.0.1:8000/track/REQ-10014',11,'clients','2025-08-17 11:17:13','2025-08-17 11:17:13'),(29,'James Opiyo','Tracked Parcel Request ID: REQ-10014 and generated pdf report','http://127.0.0.1:8000/track/REQ-10014/pdf',11,'clients','2025-08-17 11:17:19','2025-08-17 11:17:19'),(30,'Maisha Medcare','Logged in the tracking app','http://127.0.0.1:8000/client/login',5,'clients','2025-08-17 14:01:45','2025-08-17 14:01:45'),(31,'Maisha Medcare','Tracked Parcel Request ID: REQ-10017 and results  found','http://127.0.0.1:8000/track/REQ-10017',5,'clients','2025-08-17 14:01:49','2025-08-17 14:01:49'),(32,'Maisha Medcare','Tracked Parcel Request ID: REQ-10017 and generated pdf report','http://127.0.0.1:8000/track/REQ-10017/pdf',5,'clients','2025-08-17 14:02:00','2025-08-17 14:02:00');
/*!40000 ALTER TABLE `user_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empNo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idNo` int DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `verityCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `station` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` json DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_empno_unique` (`empNo`),
  UNIQUE KEY `users_idno_unique` (`idNo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Mwaele Emmanuel','mwaele@ufanisi.co.ke','0747911672','EMP001',21158229,'driver',NULL,'active','Ufanisi Courier','2',NULL,'2025-08-14 14:02:44','$2y$12$msFxcP6Nc51Ndaq6rJMON.M3ouWmsDOoT2Fj741IlW9RhWKED9g26',NULL,'2025-08-14 14:02:44','2025-08-14 14:06:58'),(2,'Jeff Kip','jeffkip354@gmail.com','0724911674','EMP002',35566464,'driver',NULL,'active','Ufanisi Courier','2',NULL,'2025-08-14 14:02:44','$2y$12$EbJWuPObDqkqM1MpGK9DXuy5dFHMaV9XUarfKS9Zagl.TXDOM79n6',NULL,'2025-08-14 14:02:44','2025-08-15 07:41:01'),(3,'James Mwangi','jmwangi@example.com','0788035137','EMP003',26126361,'manager',NULL,'active','Ufanisi Courier',NULL,NULL,'2025-08-14 14:02:44','$2y$12$fSDzPqx1n1yk4AYLcvMkI.klXCOdK3P1pKGSuAh6tOUIEPOi3SzRq',NULL,'2025-08-14 14:02:44','2025-08-14 14:02:44'),(4,'Neddy Sakura','neddy@ufanisi.co.ke','0726526020','EMP004',34291502,'driver',NULL,'active','Ufanisi Courier','2',NULL,'2025-08-14 14:02:45','$2y$12$ppa2c4GSmU7wRPEPWid2EuvfgsQ7vDn8GKG5Lc6ehgjKK88XHa6yC',NULL,'2025-08-14 14:02:45','2025-08-17 09:11:44'),(5,'Geff Letting','jeff.letting@ufanisi.co.ke','0724911674','EMP005',14692997,'admin',NULL,'active','Ufanisi Courier','2',NULL,'2025-08-14 14:02:45','$2y$12$n0xeTfUIRsB4eSoKyNnSHu5CIkDAolAMmdXFpz0M5nP47rVlJxouO',NULL,'2025-08-14 14:02:45','2025-08-14 14:05:56'),(6,'Alice Njeri','choroma@ufanisi.co.ke','0779292231','EMP006',22142115,'staff',NULL,'active','Ufanisi Courier','1',NULL,'2025-08-14 14:02:45','$2y$12$EY0PYuDIInR0pHtDuHJOeuaDczHAu8.OhlpbjG12q9mlR0qS1vb9u',NULL,'2025-08-14 14:02:45','2025-08-17 13:42:28'),(7,'Brian Kiprotich','bkiprotich@example.com','0724911674','EMP007',30456776,'driver',NULL,'active','Ufanisi Courier','1',NULL,'2025-08-14 14:02:45','$2y$12$oDOuMd2nFmaxqLTyHneXyeERICJCsNG.UuBFnsTZ4XZcqdnWqRmFy',NULL,'2025-08-14 14:02:45','2025-08-17 12:26:02'),(8,'Esther Naliaka','enaliaka@example.com','0710787482','EMP008',39979081,'manager',NULL,'active','Ufanisi Courier',NULL,NULL,'2025-08-14 14:02:45','$2y$12$9mrdW40R3Dzl6sSHZt.CaOVHD23nFEK6.M8zj2lIGoCRl6jJQlJr6',NULL,'2025-08-14 14:02:45','2025-08-14 14:02:45'),(9,'Daniel Mutua','dmutua@example.com','0772527354','EMP009',22079858,'superadmin',NULL,'active','Ufanisi Courier',NULL,NULL,'2025-08-14 14:02:46','$2y$12$PsD62tutzVbChPvvgTzMwupLoaXxVwwlJnr5gNYkUNcrV4F09PFMm',NULL,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(10,'Geoffrey Letting','geoffreyletting2@gmail.com','0782911674','EMP010',23480269,'driver',NULL,'active','Ufanisi Courier','1',NULL,'2025-08-14 14:02:46','$2y$12$LGsgJbJcla0egKhprY9R9OgUbk7ytNKh3wVbrwtecaIh8cGKyri4S',NULL,'2025-08-14 14:02:46','2025-08-18 02:23:04'),(11,'Salim Hassan','ict-support@ufanisi.co.ke','0782911674',NULL,NULL,'driver',NULL,'active',NULL,'2',NULL,NULL,'$2y$12$U93wZcWLCWvjmV6gfLYb1uhPHHAZoiNHnl45Y5JC3rxMA.Jv7/b7q',NULL,'2025-08-17 09:06:59','2025-08-17 09:06:59'),(13,'Caleb Wasuna','data@ufanisi.co.ke','0724911674',NULL,NULL,'driver',NULL,'active',NULL,'1',NULL,NULL,'$2y$12$Cq5wTYavaFHlPwhKU1/jT.gkJVhmpuAEpUt6xpTsAA9BzD4bIfNva',NULL,'2025-08-17 09:10:59','2025-08-17 12:48:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_allocations`
--

DROP TABLE IF EXISTS `vehicle_allocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_allocations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_allocations`
--

LOCK TABLES `vehicle_allocations` WRITE;
/*!40000 ALTER TABLE `vehicle_allocations` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicle_allocations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `regNo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tonnage` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `ownedBy` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_regno_unique` (`regNo`),
  KEY `vehicles_user_id_foreign` (`user_id`),
  KEY `vehicles_ownedby_foreign` (`ownedBy`),
  CONSTRAINT `vehicles_ownedby_foreign` FOREIGN KEY (`ownedBy`) REFERENCES `company_infos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vehicles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,'KDG 123A','Truck','White','5T','available','Assigned exclusively to one driver.',2,2,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(2,'KCM 456B','Van','Blue','2T','available','Assigned exclusively to one driver.',1,3,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(3,'KBX 789C','Pickup','Red','3T','available','Assigned exclusively to one driver.',7,3,'2025-08-14 14:02:46','2025-08-14 14:02:46'),(4,'KBC 992X','Pick up','White','1500','available','New',11,1,'2025-08-17 09:43:29','2025-08-17 09:43:29'),(5,'KCZ 990G','Pick up','White','1500','available','New',4,1,'2025-08-17 09:44:02','2025-08-17 09:44:02'),(6,'KAT 445P','Pick up','White','1500','available','New',13,1,'2025-08-17 09:44:28','2025-08-17 09:44:28');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `waybills`
--

DROP TABLE IF EXISTS `waybills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `waybills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `waybillNo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senderName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senderPhone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiverName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiverPhone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiverOTP` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `waybills`
--

LOCK TABLES `waybills` WRITE;
/*!40000 ALTER TABLE `waybills` DISABLE KEYS */;
/*!40000 ALTER TABLE `waybills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zones`
--

LOCK TABLES `zones` WRITE;
/*!40000 ALTER TABLE `zones` DISABLE KEYS */;
INSERT INTO `zones` VALUES (1,'Zone 1A','Zone 1A region',NULL,NULL),(2,'Zone 2A','Zone 2A region',NULL,NULL),(3,'Zone 3A','Zone 3A region',NULL,NULL),(4,'Zone 1B','Zone 1B region',NULL,NULL),(5,'Zone 2B','Zone 2B region',NULL,NULL),(6,'Zone 3B','Zone 3B region',NULL,NULL);
/*!40000 ALTER TABLE `zones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-18 11:34:08
