-- MySQL dump 10.13  Distrib 8.0.40, for Linux (x86_64)
--
-- Host: localhost    Database: custo_extras
-- ------------------------------------------------------
-- Server version	8.0.40

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
-- Table structure for table `cargo_types`
--

DROP TABLE IF EXISTS `cargo_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cargo_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargo_types`
--

LOCK TABLES `cargo_types` WRITE;
/*!40000 ALTER TABLE `cargo_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `cargo_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contract_manager` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj` (`cnpj`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cost_types`
--

DROP TABLE IF EXISTS `cost_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cost_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cost_types`
--

LOCK TABLES `cost_types` WRITE;
/*!40000 ALTER TABLE `cost_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `cost_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `costs`
--

DROP TABLE IF EXISTS `costs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `costs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tracking_number` varchar(20) DEFAULT NULL,
  `date` date NOT NULL,
  `client_id` int NOT NULL,
  `cargo_type_id` int NOT NULL,
  `cost_type_id` int NOT NULL,
  `operational_status_id` int NOT NULL,
  `romaneio` varchar(50) DEFAULT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `nfe_number` varchar(50) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `total_value` decimal(10,2) DEFAULT NULL,
  `approved_value` decimal(10,2) DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `occurrence_number` varchar(50) DEFAULT NULL,
  `status_id` int NOT NULL,
  `document_type_id` int DEFAULT NULL,
  `cte_number` varchar(50) DEFAULT NULL,
  `cte_emission_date` date DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `observation` text,
  `driver_id` int DEFAULT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tracking_number` (`tracking_number`),
  KEY `client_id` (`client_id`),
  KEY `cargo_type_id` (`cargo_type_id`),
  KEY `cost_type_id` (`cost_type_id`),
  KEY `operational_status_id` (`operational_status_id`),
  KEY `status_id` (`status_id`),
  KEY `document_type_id` (`document_type_id`),
  KEY `driver_id` (`driver_id`),
  KEY `fornecedor_id` (`fornecedor_id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `costs_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `costs_ibfk_10` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `costs_ibfk_2` FOREIGN KEY (`cargo_type_id`) REFERENCES `cargo_types` (`id`),
  CONSTRAINT `costs_ibfk_3` FOREIGN KEY (`cost_type_id`) REFERENCES `cost_types` (`id`),
  CONSTRAINT `costs_ibfk_4` FOREIGN KEY (`operational_status_id`) REFERENCES `operational_status` (`id`),
  CONSTRAINT `costs_ibfk_5` FOREIGN KEY (`status_id`) REFERENCES `status_types` (`id`),
  CONSTRAINT `costs_ibfk_6` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`),
  CONSTRAINT `costs_ibfk_7` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  CONSTRAINT `costs_ibfk_8` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `costs_ibfk_9` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `costs`
--

LOCK TABLES `costs` WRITE;
/*!40000 ALTER TABLE `costs` DISABLE KEYS */;
/*!40000 ALTER TABLE `costs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custos`
--

DROP TABLE IF EXISTS `custos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transportadora_id` int DEFAULT NULL,
  `embarcador_id` int DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo_custo` varchar(100) DEFAULT NULL,
  `status` enum('pendente','aprovado','rejeitado') DEFAULT 'pendente',
  `observacoes` text,
  PRIMARY KEY (`id`),
  KEY `transportadora_id` (`transportadora_id`),
  KEY `embarcador_id` (`embarcador_id`),
  CONSTRAINT `custos_ibfk_1` FOREIGN KEY (`transportadora_id`) REFERENCES `transportadoras` (`id`),
  CONSTRAINT `custos_ibfk_2` FOREIGN KEY (`embarcador_id`) REFERENCES `embarcadores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custos`
--

LOCK TABLES `custos` WRITE;
/*!40000 ALTER TABLE `custos` DISABLE KEYS */;
/*!40000 ALTER TABLE `custos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_types`
--

DROP TABLE IF EXISTS `document_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_types`
--

LOCK TABLES `document_types` WRITE;
/*!40000 ALTER TABLE `document_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `document_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drivers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `document` varchar(20) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document` (`document`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivers`
--

LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
/*!40000 ALTER TABLE `drivers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `embarcadores`
--

DROP TABLE IF EXISTS `embarcadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `embarcadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `razao_social` varchar(255) NOT NULL,
  `cnpj` varchar(20) NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `embarcadores`
--

LOCK TABLES `embarcadores` WRITE;
/*!40000 ALTER TABLE `embarcadores` DISABLE KEYS */;
/*!40000 ALTER TABLE `embarcadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow_up_history`
--

DROP TABLE IF EXISTS `follow_up_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follow_up_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cost_id` int NOT NULL,
  `type_id` int NOT NULL,
  `description` text,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cost_id` (`cost_id`),
  KEY `type_id` (`type_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `follow_up_history_ibfk_1` FOREIGN KEY (`cost_id`) REFERENCES `costs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `follow_up_history_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `follow_up_types` (`id`),
  CONSTRAINT `follow_up_history_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow_up_history`
--

LOCK TABLES `follow_up_history` WRITE;
/*!40000 ALTER TABLE `follow_up_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `follow_up_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow_up_types`
--

DROP TABLE IF EXISTS `follow_up_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follow_up_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `color` varchar(7) DEFAULT '#000000',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow_up_types`
--

LOCK TABLES `follow_up_types` WRITE;
/*!40000 ALTER TABLE `follow_up_types` DISABLE KEYS */;
INSERT INTO `follow_up_types` VALUES (1,'ObservaÃ§Ã£o','ObservaÃ§Ã£o geral','#808080','2024-12-07 23:45:22','2024-12-07 23:45:22'),(2,'Problema','Problema identificado','#FF0000','2024-12-07 23:45:22','2024-12-07 23:45:22'),(3,'AtualizaÃ§Ã£o','AtualizaÃ§Ã£o de status','#0000FF','2024-12-07 23:45:22','2024-12-07 23:45:22'),(4,'ResoluÃ§Ã£o','Problema resolvido','#008000','2024-12-07 23:45:22','2024-12-07 23:45:22');
/*!40000 ALTER TABLE `follow_up_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fornecedores`
--

DROP TABLE IF EXISTS `fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `endereco` text,
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj` (`cnpj`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fornecedores`
--

LOCK TABLES `fornecedores` WRITE;
/*!40000 ALTER TABLE `fornecedores` DISABLE KEYS */;
/*!40000 ALTER TABLE `fornecedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operational_status`
--

DROP TABLE IF EXISTS `operational_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operational_status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operational_status`
--

LOCK TABLES `operational_status` WRITE;
/*!40000 ALTER TABLE `operational_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `operational_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'view_dashboard','Visualizar dashboard',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(2,'manage_users','Gerenciar usuÃ¡rios',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(3,'manage_roles','Gerenciar perfis e permissÃµes',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(4,'manage_costs','Gerenciar custos',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(5,'manage_types','Gerenciar tipos',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(6,'manage_clients','Gerenciar clientes',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(7,'view_reports','Visualizar relatÃ³rios',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(8,'export_data','Exportar dados',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(9,'manage_drivers','Gerenciar motoristas',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(10,'manage_fornecedores','Gerenciar fornecedores',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(11,'manage_followup','Gerenciar follow-up',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(12,'view_analytics','Visualizar anÃ¡lises',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(18,'manage_shippers','Gerenciar transportadoras',1,'2024-12-08 00:17:23','2024-12-08 00:17:23');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1,'2024-12-07 23:45:22'),(1,2,'2024-12-07 23:45:22'),(1,3,'2024-12-07 23:45:22'),(1,4,'2024-12-07 23:45:22'),(1,5,'2024-12-07 23:45:22'),(1,6,'2024-12-07 23:45:22'),(1,7,'2024-12-07 23:45:22'),(1,8,'2024-12-07 23:45:22'),(1,9,'2024-12-07 23:45:22'),(1,10,'2024-12-07 23:45:22'),(1,11,'2024-12-07 23:45:22'),(1,12,'2024-12-07 23:45:22'),(1,18,'2024-12-08 00:17:23'),(2,1,'2024-12-07 23:45:22'),(2,4,'2024-12-07 23:45:22'),(2,5,'2024-12-07 23:45:22'),(2,6,'2024-12-07 23:45:22'),(2,7,'2024-12-07 23:45:22'),(2,8,'2024-12-07 23:45:22'),(2,9,'2024-12-07 23:45:22'),(2,10,'2024-12-07 23:45:22'),(2,11,'2024-12-07 23:45:22'),(2,12,'2024-12-07 23:45:22'),(3,1,'2024-12-07 23:45:22'),(3,4,'2024-12-07 23:45:22'),(3,7,'2024-12-07 23:45:22'),(3,11,'2024-12-07 23:45:22');
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Administrador do sistema',1,'2024-12-07 23:45:22','2024-12-07 23:49:57'),(2,'manager','Gerente com acesso a relatÃ³rios e gestÃ£o',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(3,'operator','Operador com acesso bÃ¡sico ao sistema',1,'2024-12-07 23:45:22','2024-12-07 23:45:22'),(6,'user','UsuÃ¡rio padrÃ£o',1,'2024-12-08 00:17:23','2024-12-08 00:17:23');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_types`
--

DROP TABLE IF EXISTS `status_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `color` varchar(7) DEFAULT '#000000',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_types`
--

LOCK TABLES `status_types` WRITE;
/*!40000 ALTER TABLE `status_types` DISABLE KEYS */;
INSERT INTO `status_types` VALUES (1,'Pendente','Aguardando anÃ¡lise','#FFA500','2024-12-07 23:45:22','2024-12-07 23:45:22'),(2,'Em AnÃ¡lise','Em processo de anÃ¡lise','#0000FF','2024-12-07 23:45:22','2024-12-07 23:45:22'),(3,'Aprovado','Custo aprovado','#008000','2024-12-07 23:45:22','2024-12-07 23:45:22'),(4,'Rejeitado','Custo rejeitado','#FF0000','2024-12-07 23:45:22','2024-12-07 23:45:22');
/*!40000 ALTER TABLE `status_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES (1,'login_background','/assets/images/login-bg.jpg','2024-12-07 23:45:22','2024-12-07 23:45:22'),(2,'login_logo','/assets/images/logo.png','2024-12-07 23:45:22','2024-12-07 23:45:22');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transportadoras`
--

DROP TABLE IF EXISTS `transportadoras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transportadoras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(20) NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transportadoras`
--

LOCK TABLES `transportadoras` WRITE;
/*!40000 ALTER TABLE `transportadoras` DISABLE KEYS */;
/*!40000 ALTER TABLE `transportadoras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES (6,1,'2024-12-08 03:06:14');
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Senhas devem ser hash bcrypt',
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` int DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `department` enum('Transportes','Custos','Financeiro') NOT NULL DEFAULT 'Custos',
  `password_reset_token` varchar(100) DEFAULT NULL,
  `password_reset_expires_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `fk_users_created_by` (`created_by`),
  KEY `fk_users_updated_by` (`updated_by`),
  KEY `idx_password_reset_token` (`password_reset_token`),
  CONSTRAINT `fk_users_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_users_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$gzR2533caB3fhIa6RvvAg.1xLb0PAFtfidM3ENztT9wioRAQv.9wq','Administrador','admin@example.com',1,1,NULL,'2024-12-07 23:45:22','2024-12-08 00:26:33','Custos',NULL,NULL,NULL,NULL,NULL),(2,'admin_novo','$2y$10$YOUHIYRM70qcgg3lPCfuUuHj0eZ6OptZOrubrY/pUklnGGQDv6drO','Administrador Novo','admin_novo@example.com',1,1,'2024-12-08 06:28:25','2024-12-08 01:30:17','2024-12-08 06:28:25','Custos','9e2a56539b8a56baa4661f514d143504198bbe0f7c4c387dd0139cecf53e3f43','2024-12-08 02:59:40',NULL,NULL,NULL),(6,'admin_sistema','$2y$10$9w3ok3RpWoAKwp8zp.bQxudtGHJGYo6Z3XqF4cGdQ1fNQFgzNqyXa','Administrador','admin@admin.com',NULL,1,NULL,'2024-12-08 03:06:14','2024-12-08 03:11:40','Custos',NULL,NULL,NULL,NULL,NULL);
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

-- Dump completed on 2024-12-08  6:52:45
