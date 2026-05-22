CREATE DATABASE  IF NOT EXISTS `psychology_center` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `psychology_center`;
-- MySQL dump 10.13  Distrib 8.0.46, for macos15 (arm64)
--
-- Host: localhost    Database: psychology_center
-- ------------------------------------------------------
-- Server version	8.0.46

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
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `appointment_id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `psychologist_id` int NOT NULL,
  `service_id` int DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time DEFAULT NULL,
  `consultation_type` enum('offline','online') NOT NULL,
  `appointment_datetime` datetime DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `booking_code` varchar(64) DEFAULT NULL,
  `cancel_reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`appointment_id`),
  KEY `client_id` (`client_id`),
  KEY `psychologist_id` (`psychologist_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`psychologist_id`) REFERENCES `psychologists` (`psychologist_id`),
  CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (4,17,1,1,'2026-05-24','11:00:00','offline',NULL,'confirmed','0d60f31c0aa7948ea25eb2374641b79fe78c194d34daacbad45b951e498aa154',NULL),(5,23,3,2,'2026-05-23','16:00:00','online',NULL,'pending','da33d852f3afb0e4b36dd79dc700a71b9ec284f536a0960f4ce34c26ab0a1631',NULL);
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `client_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `registration_date` date NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (17,'Лисичкин Даниил','709234252422','danilabakaa@icloud.com','2026-05-22'),(18,'Иван Петров','+7 900 111-22-33','ivan.petrov@example.com','2026-05-01'),(19,'Мария Соколова','+7 900 222-33-44','maria.sokolova@example.com','2026-05-02'),(20,'Алексей Иванов','+7 900 333-44-55','alexey.ivanov@example.com','2026-05-03'),(21,'Екатерина Смирнова','+7 900 444-55-66','ekaterina.smirnova@example.com','2026-05-04'),(22,'Дмитрий Кузнецов','+7 900 555-66-77','dmitry.kuznetsov@example.com','2026-05-05'),(23,'Анна Орлова','+7 900 666-77-88','anna.orlova@example.com','2026-05-06'),(24,'Никита Волков','+7 900 777-88-99','nikita.volkov@example.com','2026-05-07'),(25,'Ольга Морозова','+7 900 888-99-00','olga.morozova@example.com','2026-05-08'),(26,'Сергей Павлов','+7 901 111-22-33','sergey.pavlov@example.com','2026-05-09'),(27,'Виктория Белова','+7 901 222-33-44','viktoria.belova@example.com','2026-05-10'),(28,'Артём Фёдоров','+7 901 333-44-55','artem.fedorov@example.com','2026-05-11'),(29,'Полина Новикова','+7 901 444-55-66','polina.novikova@example.com','2026-05-12'),(30,'Максим Егоров','+7 901 555-66-77','maxim.egorov@example.com','2026-05-13'),(31,'Елена Громова','+7 901 666-77-88','elena.gromova@example.com','2026-05-14'),(32,'Кирилл Тихонов','+7 901 777-88-99','kirill.tikhonov@example.com','2026-05-15');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `methods`
--

DROP TABLE IF EXISTS `methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `methods` (
  `method_id` int NOT NULL AUTO_INCREMENT,
  `method_name` varchar(100) NOT NULL,
  PRIMARY KEY (`method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `methods`
--

LOCK TABLES `methods` WRITE;
/*!40000 ALTER TABLE `methods` DISABLE KEYS */;
INSERT INTO `methods` VALUES (1,'Когнитивно-поведенческая терапия'),(2,'Гештальт-терапия'),(3,'Арт-терапия'),(4,'Психоанализ'),(5,'Семейная терапия'),(6,'Телесно-ориентированная терапия'),(7,'НЛП'),(8,'Экзистенциальная терапия'),(9,'Рационально-эмоциональная терапия'),(10,'Игровая терапия'),(11,'Песочная терапия'),(12,'Медитативные практики'),(13,'Терапия принятия и ответственности'),(14,'Схема-терапия'),(15,'Кризисное консультирование');
/*!40000 ALTER TABLE `methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `psychologist_methods`
--

DROP TABLE IF EXISTS `psychologist_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `psychologist_methods` (
  `psychologist_id` int NOT NULL,
  `method_id` int NOT NULL,
  PRIMARY KEY (`psychologist_id`,`method_id`),
  KEY `method_id` (`method_id`),
  CONSTRAINT `psychologist_methods_ibfk_1` FOREIGN KEY (`psychologist_id`) REFERENCES `psychologists` (`psychologist_id`),
  CONSTRAINT `psychologist_methods_ibfk_2` FOREIGN KEY (`method_id`) REFERENCES `methods` (`method_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `psychologist_methods`
--

LOCK TABLES `psychologist_methods` WRITE;
/*!40000 ALTER TABLE `psychologist_methods` DISABLE KEYS */;
/*!40000 ALTER TABLE `psychologist_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `psychologist_notes`
--

DROP TABLE IF EXISTS `psychologist_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `psychologist_notes` (
  `note_id` int NOT NULL AUTO_INCREMENT,
  `appointment_id` int NOT NULL,
  `psychologist_id` int NOT NULL,
  `note_text` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`note_id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `psychologist_id` (`psychologist_id`),
  CONSTRAINT `psychologist_notes_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`),
  CONSTRAINT `psychologist_notes_ibfk_2` FOREIGN KEY (`psychologist_id`) REFERENCES `psychologists` (`psychologist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `psychologist_notes`
--

LOCK TABLES `psychologist_notes` WRITE;
/*!40000 ALTER TABLE `psychologist_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `psychologist_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `psychologist_services`
--

DROP TABLE IF EXISTS `psychologist_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `psychologist_services` (
  `psychologist_id` int NOT NULL,
  `service_id` int NOT NULL,
  PRIMARY KEY (`psychologist_id`,`service_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `psychologist_services_ibfk_1` FOREIGN KEY (`psychologist_id`) REFERENCES `psychologists` (`psychologist_id`),
  CONSTRAINT `psychologist_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `psychologist_services`
--

LOCK TABLES `psychologist_services` WRITE;
/*!40000 ALTER TABLE `psychologist_services` DISABLE KEYS */;
INSERT INTO `psychologist_services` VALUES (1,1),(2,1),(3,1),(1,2),(3,2),(2,3),(1,4),(4,4),(3,5),(4,5);
/*!40000 ALTER TABLE `psychologist_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `psychologists`
--

DROP TABLE IF EXISTS `psychologists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `psychologists` (
  `psychologist_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`psychologist_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `psychologists`
--

LOCK TABLES `psychologists` WRITE;
/*!40000 ALTER TABLE `psychologists` DISABLE KEYS */;
INSERT INTO `psychologists` VALUES (1,'Анна Сергеевна Орлова','Когнитивно-поведенческая терапия'),(2,'Михаил Андреевич Соколов','Семейная психология'),(3,'Екатерина Игоревна Смирнова','Гештальт-терапия'),(4,'Дмитрий Павлович Волков','Кризисное консультирование'),(5,'Ольга Викторовна Морозова','Детская психология'),(6,'Наталья Алексеевна Кузнецова','Психоанализ'),(7,'Илья Романович Фёдоров','Арт-терапия'),(8,'Мария Денисовна Белова','Терапия тревожных состояний'),(9,'Алексей Николаевич Егоров','Экзистенциальная терапия'),(10,'Вера Константиновна Лебедева','Работа с самооценкой'),(11,'Павел Олегович Захаров','Телесно-ориентированная терапия'),(12,'Софья Максимовна Новикова','Подростковая психология'),(13,'Роман Евгеньевич Киселёв','Психологическое консультирование'),(14,'Елена Артёмовна Громова','Семейная терапия'),(15,'Кирилл Валерьевич Тихонов','Кризисная психология');
/*!40000 ALTER TABLE `psychologists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `anonymous_name` varchar(100) DEFAULT NULL,
  `request_text` text,
  `request_date` date NOT NULL,
  PRIMARY KEY (`request_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `service_id` int NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) NOT NULL,
  `duration_minutes` int NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Первичная консультация',60,2500.00),(2,'Повторная консультация',45,2000.00),(3,'Семейная консультация',90,4000.00),(4,'Онлайн-консультация',60,2200.00),(5,'Кризисная консультация',60,3000.00);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-22 14:05:47
