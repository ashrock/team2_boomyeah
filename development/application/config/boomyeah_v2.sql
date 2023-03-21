-- MySQL dump 10.13  Distrib 8.0.31, for macos12 (x86_64)
--
-- Host: 127.0.0.1    Database: boomyeah_v2
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
  `collaborator_level_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_collaborators_users1_idx` (`user_id`),
  KEY `fk_collaborators_workspaces1_idx` (`workspace_id`),
  KEY `fk_collaborators_documentations1_idx` (`documentation_id`),
  CONSTRAINT `fk_collaborators_documentations1` FOREIGN KEY (`documentation_id`) REFERENCES `documentations` (`id`),
  CONSTRAINT `fk_collaborators_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_collaborators_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collaborators`
--

LOCK TABLES `collaborators` WRITE;
/*!40000 ALTER TABLE `collaborators` DISABLE KEYS */;
INSERT INTO `collaborators` VALUES (74,3,1,458,1,'2023-03-14 10:31:13','2023-03-14 10:31:13'),(78,4,1,458,1,'2023-03-14 10:42:28','2023-03-14 10:42:28'),(79,5,1,458,1,'2023-03-15 10:07:36','2023-03-15 10:07:36'),(80,56,1,458,1,'2023-03-16 08:32:34','2023-03-16 08:32:34'),(81,5,1,536,1,'2023-03-20 17:33:07','2023-03-20 17:33:07'),(82,4,1,536,1,'2023-03-20 17:33:17','2023-03-20 17:33:17'),(83,56,1,536,1,'2023-03-20 17:33:41','2023-03-20 17:33:41'),(84,3,1,536,1,'2023-03-20 17:35:57','2023-03-20 17:35:57'),(85,57,1,458,1,'2023-03-21 08:34:15','2023-03-21 08:34:15');
/*!40000 ALTER TABLE `collaborators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `message` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comments_user_id1_idx` (`user_id`),
  KEY `fk_comments_post_id1_idx` (`post_id`),
  CONSTRAINT `fk_comments_post_id1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `fk_comments_user_id1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
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
  `section_ids_order` varchar(255) DEFAULT NULL,
  `is_archived` int DEFAULT NULL,
  `is_private` int DEFAULT NULL,
  `cache_collaborators_count` int DEFAULT '0',
  `updated_by_user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_documentations_users1_idx` (`user_id`),
  KEY `fk_documentations_workspaces1_idx` (`workspace_id`),
  KEY `idx_docs_workspace_is_archived` (`workspace_id`,`is_archived`),
  CONSTRAINT `fk_documentations_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_documentations_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=538 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentations`
--

LOCK TABLES `documentations` WRITE;
/*!40000 ALTER TABLE `documentations` DISABLE KEYS */;
INSERT INTO `documentations` VALUES (458,19,1,'Lorem Ipsum Dolor','This is the first documentation','67,68,69',0,1,5,19,'2023-03-14 10:30:11','2023-03-21 08:34:15'),(536,19,1,'New Documentation',NULL,NULL,0,1,4,19,'2023-03-20 17:32:51','2023-03-20 17:35:57'),(537,19,1,'Upload Files','For uploading',NULL,0,1,0,19,'2023-03-21 10:12:06','2023-03-21 10:12:12');
/*!40000 ALTER TABLE `documentations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `tab_ids_order` varchar(2000) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_modules_section_id_idx` (`section_id`),
  CONSTRAINT `fk_modules_section_id` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (5,67,19,'8,6','2023-03-14 10:53:15','2023-03-14 10:53:15'),(6,67,19,'9,10','2023-03-14 10:53:23','2023-03-16 13:47:42'),(7,68,19,'12,13,14,15,16','2023-03-17 14:22:23','2023-03-17 14:22:23'),(8,68,19,'17,18,19,20,21','2023-03-17 14:24:01','2023-03-17 14:24:01'),(9,69,19,'22,23,24,25,26','2023-03-17 14:26:11','2023-03-17 14:26:11'),(10,69,19,'27,28,29,30,31','2023-03-17 14:32:59','2023-03-17 14:32:59'),(132,264,19,'441','2023-03-21 10:12:26','2023-03-21 10:12:26');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tab_id` int NOT NULL,
  `message` text,
  `cache_comments_count` int DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_posts_user_id1_idx` (`user_id`),
  KEY `fk_posts_tab_id1_idx` (`tab_id`),
  CONSTRAINT `fk_posts_tab_id1` FOREIGN KEY (`tab_id`) REFERENCES `tabs` (`id`),
  CONSTRAINT `fk_posts_user_id1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (3,19,8,'I really appreciate the flexibility of cloud computing deployment models. Whether it\'s public, private, or hybrid, I can choose the one that best fits my organization\'s needs and budget.',NULL,'2023-01-10 16:18:14','2023-01-10 16:18:14'),(4,3,8,'Deploying our applications on the public cloud has allowed us to scale our business faster and more efficiently than ever before. The resources are virtually limitless, and we only pay for what we use.',NULL,'2023-02-10 16:18:14','2023-02-10 16:18:14'),(5,4,8,'We decided to go with a private cloud deployment model because we handle sensitive data that needs to be kept secure. The added control and customization options were worth the investment for us.',NULL,'2023-03-14 16:18:14','2023-03-14 16:22:14'),(6,5,8,'Hybrid cloud deployment has been a game-changer for us. We can keep some of our applications on-premise for added security, while still taking advantage of the scalability and cost-effectiveness of the public cloud for others.',NULL,'2023-03-16 11:44:00','2023-03-16 11:44:00'),(7,56,8,'The ability to quickly provision resources and spin up new instances on the cloud has drastically reduced our deployment times. We\'re able to get our products to market faster than ever before.',NULL,'2023-03-16 13:10:12','2023-03-16 13:10:12'),(26,5,9,'Sample',NULL,'2023-03-16 15:38:30','2023-03-16 15:38:30'),(31,19,8,'I am the admin',0,'2023-03-20 16:41:24','2023-03-20 16:41:24');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `documentation_id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` text,
  `updated_by_user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sections_documentations1_idx` (`documentation_id`),
  KEY `fk_sections_user_id1_idx` (`user_id`),
  CONSTRAINT `fk_sections_documentation_id1` FOREIGN KEY (`documentation_id`) REFERENCES `documentations` (`id`),
  CONSTRAINT `fk_sections_user_id1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=265 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (67,458,19,'Pros and Cons of Cloud Computing','Cloud computing has become a popular technology trend in recent years, enabling users to access computing resources such as storage, applications, and services through the internet. Cloud computing offers several advantages, such as increased flexibility, scalability, and cost savings. However, it also has some disadvantages, including security concerns, potential downtime, and dependence on internet connectivity. In this topic, we will explore the benefits and drawbacks of cloud computing and how businesses can make informed decisions about whether to adopt this technology for their IT needs. hehehe',19,'2023-03-14 10:30:29','2023-03-15 17:05:57'),(68,458,19,'BE Code Guidelines','BE Stuff',19,'2023-03-14 10:30:31','2023-03-17 14:22:22'),(69,458,19,'FE Code Guidelines','Basta sa FE ito\r\n',19,'2023-03-14 10:30:33','2023-03-17 14:22:15'),(264,537,19,'For uploading','For uploading',19,'2023-03-21 10:12:19','2023-03-21 10:12:24');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tabs`
--

DROP TABLE IF EXISTS `tabs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tabs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` text,
  `updated_by_user_id` int DEFAULT NULL,
  `cache_posts_count` int DEFAULT '0',
  `is_comments_allowed` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tabs_module_id_idx` (`module_id`),
  CONSTRAINT `fk_tabs_module_id` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=442 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tabs`
--

LOCK TABLES `tabs` WRITE;
/*!40000 ALTER TABLE `tabs` DISABLE KEYS */;
INSERT INTO `tabs` VALUES (6,5,19,'Cloud Storage','<p>Cloud storage is a service that allows users to store and access data over the internet. It offers several advantages such as accessibility, flexibility, and cost savings. However, it also has some concerns, such as data security and privacy, and dependence on internet connectivity.</p>',19,0,0,'2023-03-14 10:56:19','2023-03-15 15:09:31'),(8,5,19,'Cloud Computing Deployment Model','<p>Cloud computing can be deployed in various models such as <b>Public Cloud</b>, <b>Private Cloud</b>, <b>Hybrid Cloud</b>, and <b>Multi-Cloud</b>. Each deployment model has its unique features, advantages, and disadvantages. For example, a public cloud offers cost savings and scalability but may have security concerns, while a private cloud provides better security but may be more expensive to maintain.</p>',19,6,1,'2023-03-14 10:59:44','2023-03-16 09:51:03'),(9,6,19,'Cloud Cost Optimization','<p>Cloud cost optimization involves optimizing the cost of cloud computing resources to achieve cost savings without sacrificing performance or quality. It requires continuous monitoring and optimization of cloud usage, resources, and infrastructure to ensure that the organization is not overspending on cloud resources.</p>',19,1,0,'2023-03-14 11:03:27','2023-03-17 13:30:40'),(10,6,19,'Cloud Performance','<p>Cloud performance refers to the speed, responsiveness, and reliability of cloud services. It is essential to ensure that cloud services meet the organization\'s requirements for performance and availability. Performance monitoring and optimization can help identify and resolve issues that may affect cloud performance.</p>',19,0,0,'2023-03-14 11:03:34','2023-03-15 15:10:25'),(12,7,19,'Consistent Code Style','<p>Use a consistent code style throughout the project to make the code more readable and maintainable.</p>',19,0,1,'2023-03-17 14:22:23','2023-03-17 14:23:11'),(13,7,19,'Error Handling','<p>Always handle errors and exceptions properly. Document error codes and messages for debugging purposes.</p>',19,0,0,'2023-03-17 14:23:11','2023-03-17 14:23:22'),(14,7,19,'Input Validation','<p>Validate user input and ensure that it is sanitized to prevent security vulnerabilities like SQL injection attacks.</p>',19,0,0,'2023-03-17 14:23:26','2023-03-17 14:23:33'),(15,7,19,'Scalability','<p>Write code that is scalable and can handle increased traffic as the application grows.</p>',19,0,1,'2023-03-17 14:23:37','2023-03-17 14:23:45'),(16,7,19,'Database Management','<p>Use database connection pooling, connection timeouts, and prepared statements to optimize database performance.</p>',19,0,1,'2023-03-17 14:23:46','2023-03-17 14:24:00'),(17,8,19,'API Design','<p>Design APIs that are consistent, easy to understand, and follow RESTful principles.</p>',19,0,0,'2023-03-17 14:24:01','2023-03-17 14:24:13'),(18,8,19,'Documentation','<p>Document the code using inline comments, readme files, and API documentation tools.</p>',19,0,0,'2023-03-17 14:24:13','2023-03-17 14:24:22'),(19,8,19,'Security','<p>Implement security measures like encryption, token-based authentication, and access control to prevent unauthorized access.</p>',19,0,0,'2023-03-17 14:24:21','2023-03-17 14:24:29'),(20,8,19,'Testing','<p>Write automated tests for every function and API endpoint to ensure code quality and prevent regressions.</p>',19,0,0,'2023-03-17 14:24:29','2023-03-17 14:24:37'),(21,8,19,'Performance Optimization','<p>Optimize code performance by reducing database queries, optimizing algorithms, and using caching techniques.</p>',19,0,0,'2023-03-17 14:24:37','2023-03-17 14:24:47'),(22,9,19,'Consistent Code Style','<p>Use a consistent code style throughout the project to make the code more readable and maintainable.</p>',19,0,0,'2023-03-17 14:26:11','2023-03-17 14:27:10'),(23,9,19,'Responsive Design','<p>Design web pages that are responsive to different screen sizes and devices, and follow the latest web design trends.</p>',19,0,0,'2023-03-17 14:30:52','2023-03-17 14:30:59'),(24,9,19,'Accessibility','<p>Ensure that the website is accessible to all users, including those with disabilities. Use semantic HTML, alt tags for images, and ARIA attributes where appropriate.</p>',19,0,0,'2023-03-17 14:30:59','2023-03-17 14:32:29'),(25,9,19,'Performance Optimization','<p>Optimize the website\'s performance by minimizing page load times, using caching techniques, and minimizing the size of images and other media.</p>',19,0,0,'2023-03-17 14:32:37','2023-03-17 14:32:44'),(26,9,19,'Untitled TabCross-Browser Compatibility','<p>Ensure that the website works well on different browsers and platforms, including desktop and mobile devices.</p>',19,0,1,'2023-03-17 14:32:49','2023-03-17 14:32:58'),(27,10,19,'JavaScript Best Practices','<p>Follow best practices for JavaScript development, such as using proper variable naming conventions, using constants where appropriate, and avoiding global variables.</p>',19,0,0,'2023-03-17 14:32:59','2023-03-17 14:33:32'),(28,10,19,'Framework and Library Use','<p>If using a framework or library, follow the best practices and guidelines for that framework or library.</p>',19,0,1,'2023-03-17 14:33:32','2023-03-17 14:34:44'),(29,10,19,'Version Control','<p>Use version control tools like Git to manage the codebase, and follow best practices like committing often, writing descriptive commit messages, and using branches for feature development.</p>',19,0,0,'2023-03-17 14:33:54','2023-03-17 14:34:02'),(30,10,19,'Code Quality','<p>Write high-quality code that is easy to read and maintain, and follow coding standards and guidelines.</p>',19,0,0,'2023-03-17 14:34:02','2023-03-17 14:34:13'),(31,10,19,'Testing','<p>Write automated tests for the frontend code to ensure code quality and prevent regressions.</p>',19,0,1,'2023-03-17 14:34:30','2023-03-17 14:34:40'),(441,132,19,'For Uploading','<p>For Uploading</p>',19,0,1,'2023-03-21 10:12:26','2023-03-21 10:12:40');
/*!40000 ALTER TABLE `tabs` ENABLE KEYS */;
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
  `profile_picture` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_workspaces1_idx` (`workspace_id`),
  KEY `idx_users_email` (`email`),
  CONSTRAINT `fk_users_workspaces1` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,9,'John','Doe','jdoe@village88.com',NULL,'2023-02-20 10:18:04','2023-02-20 10:18:04'),(2,1,1,'Jane','Doe','jane.doe@village88.com',NULL,'2023-02-20 10:40:08','2023-02-20 10:40:08'),(3,1,1,'Tony','Stark','tstark@village88.com',NULL,'2023-02-20 10:41:00','2023-02-20 10:41:00'),(4,1,1,'Steve','Rogers','srogers@village88.com',NULL,'2023-02-20 10:41:11','2023-02-20 10:41:11'),(5,1,1,'Jovic','Abengona','emailnijovic@gmail.com','https://lh3.googleusercontent.com/a/AGNmyxZSuej16DZ520q5bwbfjg8GMf42xjDJ02OKM7Pp=s96-c','2023-02-20 10:41:35','2023-03-14 10:03:47'),(19,1,9,'Jovic','Abengona','jabengona@village88.com','https://lh3.googleusercontent.com/a/AGNmyxaonh6TCeLf4Vu1RIze_baqfG6aIg_jsrdqoi7M=s96-c','2023-02-27 15:39:08','2023-03-14 10:01:49'),(48,1,1,NULL,NULL,'sample1@gmail.com',NULL,'2023-03-09 11:25:08','2023-03-09 11:25:08'),(49,1,1,NULL,NULL,'sample2@gmail.com',NULL,'2023-03-09 13:38:55','2023-03-09 13:38:55'),(50,1,1,NULL,NULL,'sample@gmail.com',NULL,'2023-03-09 17:12:51','2023-03-09 17:12:51'),(51,1,1,NULL,NULL,'sample3@gmail.com',NULL,'2023-03-09 17:22:08','2023-03-09 17:22:08'),(52,1,1,NULL,NULL,'sample100@gmail.com',NULL,'2023-03-10 10:43:07','2023-03-10 10:43:07'),(53,1,1,NULL,NULL,'sample101@gmail.com',NULL,'2023-03-10 10:43:07','2023-03-10 10:43:07'),(54,1,9,'Jovic','Abengona','jabengona@village88.comOLD',NULL,'2023-03-13 09:29:33','2023-03-13 09:30:56'),(55,1,1,'Jovic','Abengona','emailnijovic@gmail.comOLD',NULL,'2023-03-13 09:32:44','2023-03-13 09:40:48'),(56,1,1,'encourage','mint','enc0uragem1ntgames@gmail.com','https://lh3.googleusercontent.com/a/AGNmyxbL_lQqVH4tfzpLxTqOfTD93tXb7JT44v8kWFzt=s96-c','2023-03-16 08:32:34','2023-03-16 08:32:39'),(57,1,1,NULL,NULL,'sample102@gmail.com',NULL,'2023-03-21 08:34:15','2023-03-21 08:34:15');
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
  `documentation_ids_order` varchar(255) DEFAULT NULL,
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
INSERT INTO `workspaces` VALUES (1,1,'village88','458,536,537','2023-02-20 10:17:46','2023-02-20 10:17:46');
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

-- Dump completed on 2023-03-21 10:14:03
