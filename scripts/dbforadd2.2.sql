-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: jol
-- ------------------------------------------------------
-- Server version	5.7.44

use jol;

DROP TABLE IF EXISTS `all_problem_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `all_problem_tag` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpt_code` (
  `problem_id` int(11) NOT NULL,
  `code` text,
  `last_update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`problem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gpt_code`
--

LOCK TABLES `gpt_code` WRITE;
/*!40000 ALTER TABLE `gpt_code` DISABLE KEYS */;
INSERT INTO `gpt_code` VALUES (1000,'No gpt now!','2024-02-02 15:41:48');
/*!40000 ALTER TABLE `gpt_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privilege_distribution`
--
DROP TABLE IF EXISTS `privilege_distribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privilege_distribution` (
  `group_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `enter_admin_page` tinyint(4) DEFAULT NULL,
  `edit_default_problem` tinyint(4) DEFAULT NULL,
  `rejudge` tinyint(4) DEFAULT NULL,
  `edit_news` tinyint(4) DEFAULT NULL,
  `edit_contest` tinyint(4) DEFAULT NULL,
  `download_ranklist` tinyint(4) DEFAULT NULL,
  `generate_team` tinyint(4) DEFAULT NULL,
  `edit_user_profile` tinyint(4) DEFAULT NULL,
  `edit_privilege_group` tinyint(4) DEFAULT NULL,
  `edit_privilege_distribution` tinyint(4) DEFAULT NULL,
  `inner_function` tinyint(4) DEFAULT NULL,
  `see_hidden_default_problem` tinyint(4) DEFAULT NULL,
  `see_hidden_user_info` tinyint(4) DEFAULT NULL,
  `see_wa_info_out_of_contest` tinyint(4) DEFAULT NULL,
  `see_wa_info_in_contest` tinyint(4) DEFAULT NULL,
  `see_source_out_of_contest` tinyint(4) DEFAULT NULL,
  `see_source_in_contest` tinyint(4) DEFAULT NULL,
  `see_compare` tinyint(4) DEFAULT NULL,
  `upload_files` tinyint(4) DEFAULT NULL,
  `watch_solution_video` tinyint(4) DEFAULT NULL,
  `manage_gptcode` tinyint(4) DEFAULT NULL,
  `manage_tag` tinyint(4) DEFAULT NULL,
  `set_dailydetails` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;
--
-- Dumping data for table `privilege_distribution`
--

LOCK TABLES `privilege_distribution` WRITE;
/*!40000 ALTER TABLE `privilege_distribution` DISABLE KEYS */;
INSERT INTO `privilege_distribution` VALUES ('administrator',1,1,1,1,1,1,1,1,1,0,0,1,1,1,1,1,1,1,1,1,1,1,1),('exam_user',1,0,1,0,1,0,0,0,0,0,0,0,1,1,1,1,1,1,0,0,0,0,0),('hznu_viewer',1,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0),('root',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),('source_browser',1,0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,1,1,0,0,0,0,0),('teacher',1,1,1,0,1,1,0,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1),('teacher_assistant',1,1,1,0,1,0,0,0,0,0,0,1,1,1,1,1,1,0,1,0,0,0,0);
/*!40000 ALTER TABLE `privilege_distribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problem_tag`
--

DROP TABLE IF EXISTS `problem_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem_tag` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(10) unsigned zerofill NOT NULL,
  `tag` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problem_tag`
--

LOCK TABLES `problem_tag` WRITE;
/*!40000 ALTER TABLE `problem_tag` DISABLE KEYS */;
INSERT INTO `problem_tag` VALUES (1,0000001000,'tag1');
/*!40000 ALTER TABLE `problem_tag` ENABLE KEYS */;
UNLOCK TABLES;