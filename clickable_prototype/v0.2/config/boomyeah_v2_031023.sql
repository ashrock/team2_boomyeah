-- MySQL dump 10.13  Distrib 8.0.31, for macos12 (x86_64)
--
-- Host: localhost    Database: boomyeah_v2
-- ------------------------------------------------------
-- Server version	5.7.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `collaborators`
--

DROP TABLE IF EXISTS `collaborators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `collaborators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `workspace_id` int(11) NOT NULL,
  `documentation_id` int(11) NOT NULL,
  `collaborator_level_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_collaborators_users1_idx` (`user_id`),
  KEY `fk_collaborators_workspaces1_idx` (`workspace_id`),
  KEY `fk_collaborators_documentations1_idx` (`documentation_id`),
  CONSTRAINT `fk_collaborators_documentations1` FOREIGN KEY (`documentation_id`) REFERENCES `documentations` (`id`),
  CONSTRAINT `fk_collaborators_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_collaborators_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collaborators`
--

LOCK TABLES `collaborators` WRITE;
/*!40000 ALTER TABLE `collaborators` DISABLE KEYS */;
INSERT INTO `collaborators` VALUES (13,5,1,376,1,'2023-03-06 09:59:26','2023-03-06 09:59:26'),(14,5,1,377,1,'2023-03-06 09:59:34','2023-03-06 09:59:34'),(15,1,1,376,1,'2023-03-07 11:04:19','2023-03-07 11:04:19');
/*!40000 ALTER TABLE `collaborators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentations`
--

DROP TABLE IF EXISTS `documentations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documentations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `workspace_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` text,
  `section_ids_order` varchar(255) DEFAULT NULL,
  `is_archived` int(11) DEFAULT NULL,
  `is_private` int(11) DEFAULT NULL,
  `cache_collaborators_count` int(11) DEFAULT NULL,
  `updated_by_user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_documentations_users1_idx` (`user_id`),
  KEY `fk_documentations_workspaces1_idx` (`workspace_id`),
  KEY `idx_docs_workspace_is_archived` (`workspace_id`,`is_archived`),
  CONSTRAINT `fk_documentations_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_documentations_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=423 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentations`
--

LOCK TABLES `documentations` WRITE;
/*!40000 ALTER TABLE `documentations` DISABLE KEYS */;
INSERT INTO `documentations` VALUES (375,19,1,'Slicing Pie 2.0',NULL,NULL,0,0,0,19,'2023-03-03 15:00:22','2023-03-03 15:00:22'),(376,19,1,'BE Code Guidelines',NULL,NULL,0,1,0,19,'2023-03-03 15:14:28','2023-03-03 15:14:28'),(377,19,1,'BE Code Guidelines (DRAFT)',NULL,NULL,1,1,0,NULL,'2023-03-03 15:14:40','2023-03-03 15:14:40'),(378,19,1,'FE Code Guidelines',NULL,NULL,0,1,0,NULL,'2023-03-06 09:56:48','2023-03-06 09:56:48'),(380,19,1,'Philosopher\'s Stone',NULL,NULL,0,0,0,NULL,'2023-03-06 10:08:59','2023-03-06 10:08:59'),(381,19,1,'Chamber of Secrets',NULL,NULL,0,1,0,NULL,'2023-03-06 10:09:02','2023-03-06 10:09:02'),(382,19,1,'Prisoner of Azkaban',NULL,NULL,0,0,0,NULL,'2023-03-06 10:09:05','2023-03-06 10:09:05'),(383,19,1,'Goblet of Fire',NULL,NULL,0,0,0,NULL,'2023-03-06 10:09:08','2023-03-06 10:09:08'),(384,19,1,'Order of the Phoenix',NULL,NULL,0,0,0,NULL,'2023-03-06 10:09:11','2023-03-06 10:09:11'),(385,19,1,'Half-blood Prince',NULL,NULL,0,1,0,NULL,'2023-03-06 10:10:25','2023-03-06 10:10:25'),(386,19,1,'Deathly Hollows',NULL,NULL,0,1,0,NULL,'2023-03-06 10:11:09','2023-03-06 10:11:09'),(387,19,1,'Fantastic Beasts and Where to Find Them',NULL,NULL,0,0,0,NULL,'2023-03-06 10:24:09','2023-03-06 10:24:09'),(393,19,1,'Sample Documentation with Description','Lorem ipsum dolor sit amet','2,1,3',0,0,0,19,'2023-03-06 10:30:53','2023-03-06 10:30:53'),(395,19,1,'Fantastic Beasts: The Crimes of Grindelwald',NULL,NULL,0,0,0,NULL,'2023-03-06 10:36:24','2023-03-06 10:36:24'),(396,19,1,'Fantastic Beast: The Secrets of Dumbledore',NULL,NULL,0,0,0,NULL,'2023-03-06 10:36:34','2023-03-06 10:36:34'),(397,19,1,'The Hobbit: An Unexpected Journey',NULL,NULL,0,0,0,NULL,'2023-03-06 10:37:42','2023-03-06 10:37:42'),(398,19,1,'The Hobbit: The Desolation of Smaug',NULL,NULL,0,0,0,NULL,'2023-03-06 10:37:50','2023-03-06 10:37:50'),(399,19,1,'The Hobbit: The Battle of Five Armies',NULL,NULL,0,0,0,NULL,'2023-03-06 10:38:41','2023-03-06 10:38:41'),(400,19,1,'The Lord of The Rings: The Fellowship of The ',NULL,NULL,0,1,0,NULL,'2023-03-06 10:38:53','2023-03-06 10:38:53'),(401,19,1,'The Lord of The Rings: The Two Towers',NULL,NULL,0,1,0,NULL,'2023-03-06 10:39:11','2023-03-06 10:39:11'),(402,19,1,'The Lord of The Rings: The Return of The King',NULL,NULL,0,0,0,19,'2023-03-06 10:40:00','2023-03-06 10:40:00'),(403,19,1,'National Treasures 3',NULL,NULL,1,0,0,NULL,'2023-03-06 10:49:50','2023-03-06 10:49:50'),(409,1,1,'The Wall Assignment (BE)',NULL,NULL,0,1,0,19,'2023-03-06 15:58:21','2023-03-06 15:58:21'),(411,19,1,'The Wall Assignment (FE)',NULL,NULL,0,1,0,19,'2023-03-06 16:01:32','2023-03-06 16:01:32'),(420,19,1,'Private Doc with Sections','Lorem ipsum dolor sit amet','5,4,8,7,6,9,10',0,1,0,19,'2023-03-07 17:15:08','2023-03-10 08:52:15'),(421,19,1,'New','','11',0,1,0,19,'2023-03-09 14:10:21','2023-03-09 14:20:02'),(422,19,1,'New 2','','12,13,14',0,1,0,19,'2023-03-09 14:11:23','2023-03-09 14:21:55');
/*!40000 ALTER TABLE `documentations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tab_ids_order` varchar(2000) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_modules_section_id_idx` (`section_id`),
  CONSTRAINT `fk_modules_section_id` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,1,19,'2,1','2023-03-07 17:15:08','2023-03-07 17:15:08'),(2,1,19,'4,3,5','2023-03-07 17:15:08','2023-03-07 17:15:08');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documentation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` text,
  `updated_by_user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sections_documentations1_idx` (`documentation_id`),
  KEY `fk_sections_user_id1_idx` (`user_id`),
  CONSTRAINT `fk_sections_documentation_id1` FOREIGN KEY (`documentation_id`) REFERENCES `documentations` (`id`),
  CONSTRAINT `fk_sections_user_id1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,393,19,'Sample 1','Sample description',NULL,'2023-03-06 15:40:05','2023-03-06 15:40:05'),(2,393,19,'Sample 2','Sample description',NULL,'2023-03-06 15:40:12','2023-03-06 15:40:12'),(3,393,19,'Sample 5','Sample description',NULL,'2023-03-06 15:40:17','2023-03-06 15:40:17'),(4,420,19,'Sample 1','Sample description',NULL,'2023-03-07 17:15:36','2023-03-07 17:15:36'),(5,420,19,'Sample 2','Sample description',NULL,'2023-03-07 17:15:39','2023-03-07 17:15:39'),(6,420,19,'Sample 3','Sample description',19,'2023-03-07 17:15:41','2023-03-09 09:43:59'),(7,420,19,'Sample 4',NULL,NULL,'2023-03-08 11:32:04','2023-03-08 11:32:04'),(8,420,19,'Sample 5',NULL,19,'2023-03-09 09:43:47','2023-03-09 09:44:03'),(9,420,19,'Sample 7',NULL,19,'2023-03-09 09:44:18','2023-03-09 09:50:35'),(10,420,19,'Sample 6',NULL,19,'2023-03-09 09:48:00','2023-03-09 09:50:44'),(11,421,19,'Section 1',NULL,NULL,'2023-03-09 14:10:36','2023-03-09 14:10:36'),(12,422,19,'Section 1',NULL,NULL,'2023-03-09 14:20:32','2023-03-09 14:20:32'),(13,422,19,'Section 2',NULL,NULL,'2023-03-09 14:21:06','2023-03-09 14:21:06'),(14,422,19,'Section 3',NULL,NULL,'2023-03-09 14:21:55','2023-03-09 14:21:55');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tabs`
--

DROP TABLE IF EXISTS `tabs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` text,
  `is_comments_allowed` tinyint(4) DEFAULT NULL,
  `updated_by_user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tabs_module_id_idx` (`module_id`),
  CONSTRAINT `fk_tabs_module_id` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tabs`
--

LOCK TABLES `tabs` WRITE;
/*!40000 ALTER TABLE `tabs` DISABLE KEYS */;
INSERT INTO `tabs` VALUES (1,1,19,'Tab 1 Module 1','Sample',0,19,'2023-03-07 17:15:08','2023-03-07 17:15:08'),(2,1,19,'Tab 2 Module 1','Sample',1,19,'2023-03-07 17:15:08','2023-03-07 17:15:08'),(3,2,19,'Tab 1 Module 2','Sample',1,19,'2023-03-07 17:15:08','2023-03-07 17:15:08'),(4,2,19,'Tab 2 Module 2','Sample',0,19,'2023-03-07 17:15:08','2023-03-07 17:15:08'),(5,2,19,'Tab 3 Module 2','Sample',0,19,'2023-03-07 17:15:08','2023-03-07 17:15:08');
/*!40000 ALTER TABLE `tabs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workspace_id` int(11) NOT NULL,
  `user_level_id` int(11) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_workspaces1_idx` (`workspace_id`),
  KEY `idx_users_email` (`email`),
  CONSTRAINT `fk_users_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,9,'John','Doe','jdoe@village88.com','2023-02-20 10:18:04','2023-02-20 10:18:04'),(2,1,1,'Jane','Doe','jane.doe@village88.com','2023-02-20 10:40:08','2023-02-20 10:40:08'),(3,1,1,'Tony','Stark','tstark@village88.com','2023-02-20 10:41:00','2023-02-20 10:41:00'),(4,1,1,'Steve','Rogers','srogers@village88.com','2023-02-20 10:41:11','2023-02-20 10:41:11'),(5,1,1,'Stephen','Strange','emailnijovic@gmail.com','2023-02-20 10:41:35','2023-02-20 10:41:35'),(19,1,9,'Jovic','Abengona','jabengona@village88.com','2023-02-27 15:39:08','2023-02-27 15:39:08'),(48,1,1,NULL,NULL,'sample1@gmail.com','2023-03-09 11:25:08','2023-03-09 11:25:08'),(49,1,1,NULL,NULL,'sample2@gmail.com','2023-03-09 13:38:55','2023-03-09 13:38:55'),(50,1,1,NULL,NULL,'sample@gmail.com','2023-03-09 17:12:51','2023-03-09 17:12:51'),(51,1,1,NULL,NULL,'sample3@gmail.com','2023-03-09 17:22:08','2023-03-09 17:22:08');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workspaces`
--

DROP TABLE IF EXISTS `workspaces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workspaces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `documentation_ids_order` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_workspaces_users_idx` (`user_id`),
  CONSTRAINT `fk_workspaces_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workspaces`
--

LOCK TABLES `workspaces` WRITE;
/*!40000 ALTER TABLE `workspaces` DISABLE KEYS */;
INSERT INTO `workspaces` VALUES (1,1,'village88','376,378,393,375,380,381,382,383,384,385,386,387,395,396,397,398,399,400,401,402,409,411,420,421,422','2023-02-20 10:17:46','2023-02-20 10:17:46');
/*!40000 ALTER TABLE `workspaces` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-03-10  8:54:25
