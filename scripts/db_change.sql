DROP TABLE IF EXISTS `dailydetails`;

--
-- Table structure for table `more_settings`
--

DROP TABLE IF EXISTS `more_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `more_settings` (
  `start_time` date NOT NULL,
  `end_time` date NOT NULL,
  `ai_model` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `more_settings`
--

LOCK TABLES `more_settings` WRITE;
INSERT INTO `more_settings` VALUES ('2023-09-01','2024-02-01', 0);
UNLOCK TABLES;


ALTER TABLE `privilege_distribution`
CHANGE COLUMN `set_dailydetails` `set_more_settings` tinyint(4) DEFAULT NULL;
