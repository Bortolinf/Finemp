-- MariaDB dump 10.17  Distrib 10.4.13-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: finemp
-- ------------------------------------------------------
-- Server version	10.4.13-MariaDB

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
-- Table structure for table `abilities`
--

DROP TABLE IF EXISTS `abilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abilities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `abilities_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abilities`
--

LOCK TABLES `abilities` WRITE;
/*!40000 ALTER TABLE `abilities` DISABLE KEYS */;
INSERT INTO `abilities` VALUES (1,'is-admin',NULL,'2020-11-14 21:38:26','2020-11-14 21:38:26'),(10,'Editar_Filiais',NULL,'2020-11-16 03:17:39','2020-11-16 03:17:39'),(11,'Editar_Contas',NULL,'2020-11-16 03:17:39','2020-11-16 03:17:39'),(12,'Lanctos_Diarios',NULL,'2020-11-16 03:17:39','2020-11-16 03:17:39'),(13,'DRE_Simplificado',NULL,'2020-11-16 03:17:39','2020-11-16 03:17:39'),(14,'Editar Contas',NULL,'2020-11-17 01:47:24','2020-11-17 01:47:24'),(15,'Editar_Lanctos',NULL,'2020-11-17 04:00:10','2020-11-17 04:00:10');
/*!40000 ALTER TABLE `abilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ability_role`
--

DROP TABLE IF EXISTS `ability_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ability_role` (
  `role_id` bigint(20) unsigned NOT NULL,
  `ability_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`,`ability_id`),
  KEY `ability_role_ability_id_foreign` (`ability_id`),
  CONSTRAINT `ability_role_ability_id_foreign` FOREIGN KEY (`ability_id`) REFERENCES `abilities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ability_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ability_role`
--

LOCK TABLES `ability_role` WRITE;
/*!40000 ALTER TABLE `ability_role` DISABLE KEYS */;
INSERT INTO `ability_role` VALUES (1,10,'2020-11-16 03:18:34','2020-11-16 03:18:34'),(1,11,'2020-11-16 03:18:34','2020-11-16 03:18:34'),(1,12,'2020-11-16 03:18:34','2020-11-16 03:18:34'),(1,13,'2020-11-16 03:18:34','2020-11-16 03:18:34');
/*!40000 ALTER TABLE `ability_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id_account` varchar(15) NOT NULL,
  `description` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `type` varchar(1) NOT NULL,
  `special_rule` varchar(10) DEFAULT NULL,
  `special_rule_cod` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_account`),
  KEY `accounts_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `accounts_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES ('1','RECEITAS','2020-11-16 02:45:07','2020-11-16 02:45:07',1,'S',NULL,NULL),('1.1','Vendas de Produtos','2020-11-16 02:46:54','2020-11-17 02:33:15',1,'A',NULL,NULL),('1.2','Venda de Serviços','2020-11-20 11:41:21','2020-11-20 11:41:21',1,'A',NULL,NULL),('2','DESPESAS','2020-11-17 01:47:45','2020-11-17 01:47:45',1,'S',NULL,NULL),('2.1','Despesas com Pessoal','2020-11-17 01:48:12','2020-11-17 02:35:08',1,'A',NULL,NULL),('2.2','Despesas Administrativas','2020-11-20 11:41:50','2020-11-20 11:41:50',1,'S',NULL,NULL),('2.2.1','Telefone','2020-11-20 11:42:10','2020-11-20 11:42:10',1,'A',NULL,NULL),('2.2.2','Internet','2020-11-20 11:42:25','2020-11-20 11:42:25',1,'A',NULL,NULL),('2.2.3','Condomínio','2020-11-20 11:42:53','2020-11-20 11:42:53',1,'A',NULL,NULL);
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenant_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_name_tenant_id_unique` (`name`,`tenant_id`),
  KEY `companies_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `companies_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'Filial 1','2020-11-14 23:36:11','2020-11-14 23:36:11',1),(2,'Filial 2','2020-11-14 23:37:29','2020-11-16 00:23:00',1),(3,'Filial 3','2020-11-14 23:37:35','2020-11-14 23:37:35',1);
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entries`
--

DROP TABLE IF EXISTS `entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `date` date DEFAULT current_timestamp(),
  `company_id` bigint(20) unsigned NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `es` varchar(1) NOT NULL,
  `value` double unsigned NOT NULL,
  `info` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entries_tenant_id_foreign` (`tenant_id`),
  KEY `entries_company_id_foreign` (`company_id`),
  KEY `entries_account_id_foreign` (`account_id`),
  CONSTRAINT `entries_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id_account`),
  CONSTRAINT `entries_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `entries_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entries`
--

LOCK TABLES `entries` WRITE;
/*!40000 ALTER TABLE `entries` DISABLE KEYS */;
INSERT INTO `entries` VALUES (1,1,'2020-11-17',1,'1.1','E',1250,'VENDAS','2020-11-17 22:06:12','2020-11-17 22:06:12'),(2,1,'2020-11-15',1,'1.1','E',1300,'VENDAS','2020-11-17 22:06:12','2020-11-17 22:06:12'),(3,1,'2020-11-15',1,'2.1','S',899,'despesas','2020-11-17 22:06:12','2020-11-17 22:06:12'),(4,1,'2020-11-25',1,'1.1','E',123,'oioioi','2020-11-20 02:37:51','2020-11-20 02:37:51'),(5,1,'2020-11-25',1,'1.1','E',555,'lololo','2020-11-20 02:39:58','2020-11-20 02:39:58'),(6,1,'2020-11-25',1,'1.1','E',111,'lolololo','2020-11-20 03:16:47','2020-11-20 03:16:47'),(7,1,'2020-11-25',1,'1.1','E',59,NULL,'2020-11-20 03:33:22','2020-11-20 03:33:22'),(8,1,'2020-11-25',3,'2.1','E',643,'tem que ir agora','2020-11-20 03:57:01','2020-11-20 03:57:01'),(9,1,'2020-11-25',1,'2.1','S',4746,'salario do gerentao','2020-11-20 04:01:15','2020-11-20 04:01:15'),(10,1,'2020-11-25',1,'2.1','S',4746,'salario do gerentao','2020-11-20 04:01:15','2020-11-20 04:01:15'),(11,1,'2020-11-25',2,'2.1','S',1250,'salario caixa loja 2','2020-11-20 04:02:47','2020-11-20 04:02:47'),(12,1,'2020-11-25',1,'2.1','E',8,'vale','2020-11-20 04:06:15','2020-11-20 04:06:15'),(13,1,'2020-11-25',1,'1.1','E',17,'bolsonaro','2020-11-20 04:12:02','2020-11-20 04:12:02'),(14,1,'2020-11-25',1,'1.1','E',123,'kkkkkkkk','2020-11-20 04:12:33','2020-11-20 04:12:33'),(15,1,'2020-11-25',1,'1.1','E',43,'agora sim','2020-11-20 04:13:33','2020-11-20 04:13:33'),(16,1,'2020-11-25',1,'1.1','E',78,'agora sim 7','2020-11-20 04:13:45','2020-11-20 04:13:45'),(17,1,'2020-11-25',2,'2.1','S',2511,'um dia especial','2020-11-20 04:16:11','2020-11-20 04:16:11'),(18,1,'2020-11-25',1,'1.1','E',48,'focus','2020-11-20 04:17:48','2020-11-20 04:17:48'),(19,1,'2020-11-25',1,'2.2.1','S',19.55,'recarga cel','2020-11-20 11:51:57','2020-11-20 11:51:57'),(20,1,'2020-11-25',3,'2.2.1','E',10,'dez pila','2020-11-20 12:02:29','2020-11-20 12:02:29'),(21,1,'2020-11-25',1,'2.2.2','S',11,'onze pila de internet','2020-11-20 12:06:32','2020-11-20 12:06:32'),(22,1,'2020-11-25',2,'2.2.1','S',3,'tres pila de internet','2020-11-20 12:07:42','2020-11-20 12:07:42'),(23,1,'2020-11-25',1,'1.1','E',444,'eita','2020-11-20 12:19:49','2020-11-20 12:19:49'),(24,1,'2020-11-25',1,'2.2.3','E',777,'sete sete tudo aqui','2020-11-20 12:29:56','2020-11-20 12:29:56'),(25,1,'2020-11-25',1,'2.2.3','E',123,'one two three','2020-11-20 12:30:43','2020-11-20 12:30:43'),(26,1,'2020-11-25',1,'1.1','E',123,'kmmklj','2020-11-20 12:36:11','2020-11-20 12:36:11'),(27,1,'2020-11-25',1,'1.1','E',5555,'lhj','2020-11-20 12:36:34','2020-11-20 12:36:34'),(28,1,'2020-11-25',1,'1.1','E',5555,'fcghghdf','2020-11-20 12:41:34','2020-11-20 12:41:34'),(29,1,'2020-11-25',1,'1.1','E',5555,'hf','2020-11-20 12:44:35','2020-11-20 12:44:35'),(30,1,'2020-11-25',1,'1.1','E',111,'gfgfd','2020-11-20 12:46:13','2020-11-20 12:46:13'),(31,1,'2020-11-25',1,'1.1','E',4544,'v','2020-11-20 12:48:27','2020-11-20 12:48:27'),(32,1,'2020-11-25',1,'1.1','E',599,'599','2020-11-20 12:48:49','2020-11-20 12:48:49'),(33,1,'2020-11-25',1,'1.1','E',4544,'qwe','2020-11-20 12:51:42','2020-11-20 12:51:42'),(34,1,'2020-11-25',1,'1.1','E',111,'hg','2020-11-20 12:57:56','2020-11-20 12:57:56'),(35,1,'2020-11-25',1,'1.1','E',5555,'asd','2020-11-20 13:02:22','2020-11-20 13:02:22'),(36,1,'2020-11-25',1,'1.1','E',4544,'u','2020-11-20 13:04:37','2020-11-20 13:04:37'),(37,1,'2020-11-25',1,'1.1','E',8779,'tudo isso','2020-11-20 13:07:39','2020-11-20 13:07:39'),(38,1,'2020-11-25',1,'1.1','E',5555,'44444mnjh','2020-11-20 13:10:19','2020-11-20 13:10:19'),(39,1,'2020-11-25',1,'1.1','E',123,'fhgfhf','2020-11-20 13:13:13','2020-11-20 13:13:13'),(40,1,'2020-11-25',1,'1.1','E',111,'gf','2020-11-20 13:14:38','2020-11-20 13:14:38'),(41,1,'2020-11-25',1,'1.1','E',123,'fff','2020-11-20 13:17:23','2020-11-20 13:17:23'),(42,1,'2020-11-25',1,'1.1','E',123,'d','2020-11-20 13:20:07','2020-11-20 13:20:07'),(43,1,'2020-11-25',1,'1.1','E',5,'nk','2020-11-20 13:38:47','2020-11-20 13:38:47'),(44,1,'2020-11-25',1,'1.1','E',1,'hhh','2020-11-20 13:40:18','2020-11-20 13:40:18'),(45,1,'2020-11-25',1,'1.1','E',5555,'fd','2020-11-20 13:45:13','2020-11-20 13:45:13'),(46,1,'2020-11-25',1,'1.1','E',123,'mkkllklç','2020-11-20 13:49:49','2020-11-20 13:49:49'),(47,1,'2020-11-25',1,'1.1','E',1,'1','2020-11-20 13:55:59','2020-11-20 13:55:59');
/*!40000 ALTER TABLE `entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_100000_create_password_resets_table',1),(2,'2019_08_19_000000_create_failed_jobs_table',1),(3,'2020_10_12_013310_first_tables',1),(4,'2020_10_24_190550_create_table_roles',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_user` (
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (2,1,NULL,NULL);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenant_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_tenant_id_unique` (`name`,`tenant_id`),
  KEY `roles_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `roles_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Usuários',NULL,'2020-11-16 02:54:38','2020-11-16 02:54:38',1);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `prefix` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_email_unique` (`email`),
  UNIQUE KEY `tenants_prefix_unique` (`prefix`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (1,'Teste','teste@teste.com','teste');
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `admin` smallint(6) NOT NULL,
  `id_group` bigint(20) unsigned DEFAULT NULL,
  `tenant_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Bortolin','bortolin@yahoo.com',NULL,'$2y$10$pxiKOm1kf/YiJVvRN1W.iOCG/F0r7/iZOuuolt/3xMvu/QcFJjV1O',NULL,1,NULL,1),(2,'Jocemar','jocemar@grenal.com.br',NULL,'$2y$10$PyvjUn4DGoCaefQDdzNJjuBSYlwVpvBDmsTFddZFG5Wi.UV.pRyXO',NULL,0,NULL,1);
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

-- Dump completed on 2020-11-21 17:20:19
