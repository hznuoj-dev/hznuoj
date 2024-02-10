-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: jol
-- ------------------------------------------------------
-- Server version	5.7.44

use jol;

DROP TABLE IF EXISTS `all_problem_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `all_problem_tag` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `all_problem_tag`
--

LOCK TABLES `all_problem_tag` WRITE;
/*!40000 ALTER TABLE `all_problem_tag` DISABLE KEYS */;
INSERT INTO `all_problem_tag` VALUES (1,'tag1'),(2,'tag2'),(3,'tag3');
/*!40000 ALTER TABLE `all_problem_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dailydetails`
--

DROP TABLE IF EXISTS `dailydetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dailydetails` (
  `start_time` date NOT NULL,
  `end_time` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dailydetails`
--

LOCK TABLES `dailydetails` WRITE;
/*!40000 ALTER TABLE `dailydetails` DISABLE KEYS */;
INSERT INTO `dailydetails` VALUES ('2023-09-01','2024-02-01');
/*!40000 ALTER TABLE `dailydetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gpt_code`
--

DROP TABLE IF EXISTS `gpt_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gpt_code` (
  `problem_id` int(11) NOT NULL,
  `code` text,
  `last_update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`problem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gpt_code`
--

LOCK TABLES `gpt_code` WRITE;
/*!40000 ALTER TABLE `gpt_code` DISABLE KEYS */;
INSERT INTO `gpt_code` VALUES (1000,'No gpt now!','2024-02-02 15:41:48');
/*!40000 ALTER TABLE `gpt_code` ENABLE KEYS */;
UNLOCK TABLES;

-- privilege_distribution添加新的列
ALTER TABLE `privilege_distribution`
ADD COLUMN `manage_gptcode` tinyint(4) DEFAULT NULL,
ADD COLUMN `manage_tag` tinyint(4) DEFAULT NULL,
ADD COLUMN `set_dailydetails` tinyint(4) DEFAULT NULL;

-- 更新数据
LOCK TABLES `privilege_distribution` WRITE;
/*!40000 ALTER TABLE `privilege_distribution` DISABLE KEYS */;

UPDATE `privilege_distribution`
SET `manage_gptcode` = 1,
    `manage_tag` = 1,
    `set_dailydetails` = 1
WHERE `group_name` = 'administrator';

UPDATE `privilege_distribution`
SET `manage_gptcode` = 0,
    `manage_tag` = 0,
    `set_dailydetails` = 0
WHERE `group_name` = 'exam_user';

UPDATE `privilege_distribution`
SET `manage_gptcode` = 0,
    `manage_tag` = 0,
    `set_dailydetails` = 0
WHERE `group_name` = 'hznu_viewer';

UPDATE `privilege_distribution`
SET `manage_gptcode` = 1,
    `manage_tag` = 1,
    `set_dailydetails` = 1
WHERE `group_name` = 'root';

UPDATE `privilege_distribution`
SET `manage_gptcode` = 0,
    `manage_tag` = 0,
    `set_dailydetails` = 0
WHERE `group_name` = 'source_browser';

UPDATE `privilege_distribution`
SET `manage_gptcode` = 1,
    `manage_tag` = 1,
    `set_dailydetails` = 1
WHERE `group_name` = 'teacher';

UPDATE `privilege_distribution`
SET `manage_gptcode` = 0,
    `manage_tag` = 0,
    `set_dailydetails` = 0
WHERE `group_name` = 'teacher_assistant';

/*!40000 ALTER TABLE `privilege_distribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problem_tag`
--

DROP TABLE IF EXISTS `problem_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `problem_tag` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(10) unsigned zerofill NOT NULL,
  `tag` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problem_tag`
--

LOCK TABLES `problem_tag` WRITE;
/*!40000 ALTER TABLE `problem_tag` DISABLE KEYS */;
INSERT INTO `problem_tag` VALUES (1,0000001000,'tag1');
/*!40000 ALTER TABLE `problem_tag` ENABLE KEYS */;
UNLOCK TABLES;
