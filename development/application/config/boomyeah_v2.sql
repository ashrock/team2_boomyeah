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
  `collaboarator_level_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_collaborators_users1_idx` (`user_id`),
  KEY `fk_collaborators_workspaces1_idx` (`workspace_id`),
  KEY `fk_collaborators_documentations1_idx` (`documentation_id`),
  CONSTRAINT `fk_collaborators_documentations1` FOREIGN KEY (`documentation_id`) REFERENCES `documentations` (`id`),
  CONSTRAINT `fk_collaborators_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_collaborators_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collaborators`
--

LOCK TABLES `collaborators` WRITE;
/*!40000 ALTER TABLE `collaborators` DISABLE KEYS */;
INSERT INTO `collaborators` VALUES (1,5,1,264,1,'2023-02-28 10:36:21','2023-02-28 10:36:21'),(2,5,1,266,1,'2023-02-28 10:36:23','2023-02-28 10:36:23');
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
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentations`
--

LOCK TABLES `documentations` WRITE;
/*!40000 ALTER TABLE `documentations` DISABLE KEYS */;
INSERT INTO `documentations` VALUES (258,1,1,'sample 3',NULL,NULL,1,1,0,NULL,'2023-02-24 11:51:12','2023-02-24 11:51:12'),(260,1,1,'sample 1',NULL,NULL,1,1,0,NULL,'2023-02-24 12:00:43','2023-02-24 12:00:43'),(262,1,1,'sample 4',NULL,NULL,1,1,0,NULL,'2023-02-24 12:00:48','2023-02-24 12:00:48'),(263,1,1,'Sample 1',NULL,NULL,0,0,0,NULL,'2023-02-24 14:31:09','2023-02-24 14:31:09'),(264,1,1,'Sample 2',NULL,NULL,0,1,0,NULL,'2023-02-24 14:31:11','2023-02-24 14:31:11'),(265,1,1,'Sample 3',NULL,NULL,0,1,0,NULL,'2023-02-24 14:31:13','2023-02-24 14:31:13'),(266,1,1,'Sample 4',NULL,NULL,0,1,0,NULL,'2023-02-24 14:31:15','2023-02-24 14:31:15'),(267,1,1,'Sample 5',NULL,NULL,0,0,0,NULL,'2023-02-24 14:31:17','2023-02-24 14:31:17');
/*!40000 ALTER TABLE `documentations` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,9,'John','Doe','jdoe@village88.com','2023-02-20 10:18:04','2023-02-20 10:18:04'),(2,1,1,'Jane','Doe','jane.doe@village88.com','2023-02-20 10:40:08','2023-02-20 10:40:08'),(3,1,1,'Tony','Stark','tstark@village88.com','2023-02-20 10:41:00','2023-02-20 10:41:00'),(4,1,1,'Steve','Rogers','srogers@village88.com','2023-02-20 10:41:11','2023-02-20 10:41:11'),(5,1,1,'Stephen','Strange','emailnijovic@gmail.com','2023-02-20 10:41:35','2023-02-20 10:41:35'),(19,1,9,'Jovic','Abengona','jabengona@village88.com','2023-02-27 15:39:08','2023-02-27 15:39:08');
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
INSERT INTO `workspaces` VALUES (1,1,'village88','267,266,263,265,264','2023-02-20 10:17:46','2023-02-20 10:17:46');
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

-- Dump completed on 2023-02-28 14:43:35
