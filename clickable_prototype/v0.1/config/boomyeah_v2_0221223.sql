-- MySQL dump 10.13  Distrib 8.0.31, for macos12 (x86_64)
--
-- Host: localhost    Database: boomyeah_v2
-- ------------------------------------------------------
-- Server version	8.0.32

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
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `workspace_id` int NOT NULL,
  `documentation_id` int NOT NULL,
  `access_level_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_collaborators_users1_idx` (`user_id`),
  KEY `fk_collaborators_workspaces1_idx` (`workspace_id`),
  KEY `fk_collaborators_documentations1_idx` (`documentation_id`),
  KEY `idx_collaborators_user_id` (`user_id`),
  CONSTRAINT `fk_collaborators_documentations1` FOREIGN KEY (`documentation_id`) REFERENCES `documentations` (`id`),
  CONSTRAINT `fk_collaborators_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_collaborators_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collaborators`
--

LOCK TABLES `collaborators` WRITE;
/*!40000 ALTER TABLE `collaborators` DISABLE KEYS */;
INSERT INTO `collaborators` VALUES (1,2,1,3,2,'2023-02-20 10:43:00','2023-02-20 10:43:00'),(2,3,1,4,2,'2023-02-20 10:43:48','2023-02-20 10:43:48'),(3,4,1,5,2,'2023-02-20 10:44:21','2023-02-20 10:44:21'),(4,5,1,4,2,'2023-02-20 10:44:26','2023-02-20 10:44:26'),(5,3,1,5,2,'2023-02-20 11:23:59','2023-02-20 11:23:59');
/*!40000 ALTER TABLE `collaborators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentations`
--

DROP TABLE IF EXISTS `documentations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documentations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `workspace_id` int NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` text,
  `sections_order` varchar(255) DEFAULT NULL,
  `is_archived` int DEFAULT NULL,
  `is_private` int DEFAULT NULL,
  `cache_collaborators_count` int DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_documentations_users1_idx` (`user_id`),
  KEY `fk_documentations_workspaces1_idx` (`workspace_id`),
  CONSTRAINT `fk_documentations_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_documentations_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentations`
--

LOCK TABLES `documentations` WRITE;
/*!40000 ALTER TABLE `documentations` DISABLE KEYS */;
INSERT INTO `documentations` VALUES (1,1,1,'Employee Handbook','This handbook replaces and supersedes all prior employee handbooks regarding employment or HR matters effective January 01, 2021. The policies and practices included in this handbook may be modified at any time. Your department has additional specific procedures for many of the general policies stated in the handbook. You are expected to learn your department\'s procedures and comply with them. You are also expected to conform to the professional standards of your occupation. Please direct any questions to your supervisor, department head, or to the Human Resources Management and Development Office.',NULL,0,1,0,'2023-02-20 10:22:29','2023-02-20 10:22:29'),(2,1,1,'Company Handout','We are excited to share with you some updates on our company\'s recent activities and achievements. As a leading tech company, we are constantly pushing ourselves to innovate and create new products that will make a positive impact on people\'s lives.\n\nOver the past quarter, we have been hard at work on several exciting projects. Our engineering team has made significant progress on a new software platform that will streamline our operations and improve our efficiency. This platform is set to launch next month, and we believe it will have a major impact on our ability to deliver high-quality products and services to our customers.\n\nIn addition, we recently launched a new product that has been met with an overwhelmingly positive response from both our customers and the wider tech community. This product represents the culmination of months of hard work and collaboration across multiple departments, and we are thrilled to see it gaining traction in the market.\n\nAs we move forward, we remain committed to our core values of innovation, excellence, and collaboration. We believe that by staying true to these values, we can continue to push the boundaries of what is possible and create products that make a real difference in the world.\n\nThank you for your hard work and dedication, and we look forward to continuing this journey with all of you.',NULL,0,0,0,'2023-02-20 10:34:13','2023-02-20 10:34:13'),(3,1,1,'Accounting','This documentation contains the guidelines for our accounting department.',NULL,0,1,1,'2023-02-20 10:34:20','2023-02-20 10:34:20'),(4,1,1,'V88 BE Code Guidelines','This documentation contains code guidelines for various programming languages/frameworks including MySQL. Please read these carefully and make sure to apply your learning in the projects you\'ll create.',NULL,0,1,2,'2023-02-20 10:36:50','2023-02-20 10:36:50'),(5,1,1,'V88 FE Code Guidelines','This documentation will contain code guidelines for CSS, LESS, JS, and other CSS/JS frameworks/libraries. Please read these carefully and make sure to apply your learning in the projects you\'ll create.',NULL,0,1,1,'2023-02-20 10:39:02','2023-02-20 10:39:02'),(6,1,1,'Sample Archived Documentation 1','This is just a sample documentation.',NULL,1,0,0,'2023-02-21 08:53:08','2023-02-21 08:53:08'),(7,1,1,'Sample Archived Documentation 2','This is just a sample documentation.',NULL,1,0,0,'2023-02-21 08:53:12','2023-02-21 08:53:12'),(8,1,1,'Sample Archived Documentation 3','This is just a sample documentation.',NULL,1,1,0,'2023-02-21 08:53:16','2023-02-21 08:53:16'),(9,1,1,'Sample Archived Documentation 4','This is just a sample documentation.',NULL,1,1,0,'2023-02-21 08:53:18','2023-02-21 08:53:18'),(10,1,1,'Sample Archived Documentation 5','This is just a sample documentation.',NULL,1,0,0,'2023-02-21 08:53:23','2023-02-21 08:53:23');
/*!40000 ALTER TABLE `documentations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `workspace_id` int NOT NULL,
  `user_level_id` int DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_workspaces1_idx` (`workspace_id`),
  CONSTRAINT `fk_users_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,9,'John','Doe','jdoe@village88.com','2023-02-20 10:18:04','2023-02-20 10:18:04'),(2,1,1,'Jane','Doe','jane.doe@village88.com','2023-02-20 10:40:08','2023-02-20 10:40:08'),(3,1,1,'Tony','Stark','tstark@village88.com','2023-02-20 10:41:00','2023-02-20 10:41:00'),(4,1,1,'Steve','Rogers','srogers@village88.com','2023-02-20 10:41:11','2023-02-20 10:41:11'),(5,1,1,'Stephen','Strange','sstrange@village88.com','2023-02-20 10:41:35','2023-02-20 10:41:35');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workspaces`
--

DROP TABLE IF EXISTS `workspaces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workspaces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `documentations_order` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_workspaces_users_idx` (`user_id`),
  CONSTRAINT `fk_workspaces_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workspaces`
--

LOCK TABLES `workspaces` WRITE;
/*!40000 ALTER TABLE `workspaces` DISABLE KEYS */;
INSERT INTO `workspaces` VALUES (1,1,'village88','2,1,5,4,3','2023-02-20 10:17:46','2023-02-20 10:17:46');
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

-- Dump completed on 2023-02-21 11:48:43
