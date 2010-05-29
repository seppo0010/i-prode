-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: prode
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `prode2_encuesta`
--

DROP TABLE IF EXISTS `prode2_encuesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode2_encuesta` (
  `id_encuesta` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `pregunta` varchar(255) DEFAULT NULL,
  `opcion1` varchar(255) DEFAULT NULL,
  `opcion2` varchar(255) DEFAULT NULL,
  `opcion3` varchar(255) DEFAULT NULL,
  `opcion4` varchar(255) DEFAULT NULL,
  `limite` datetime DEFAULT NULL,
  PRIMARY KEY (`id_encuesta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode2_encuesta`
--

LOCK TABLES `prode2_encuesta` WRITE;
/*!40000 ALTER TABLE `prode2_encuesta` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode2_encuesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prode2_noticias`
--

DROP TABLE IF EXISTS `prode2_noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode2_noticias` (
  `id_noticia` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `noticia` text,
  PRIMARY KEY (`id_noticia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode2_noticias`
--

LOCK TABLES `prode2_noticias` WRITE;
/*!40000 ALTER TABLE `prode2_noticias` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode2_noticias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prode2_pronostico`
--

DROP TABLE IF EXISTS `prode2_pronostico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode2_pronostico` (
  `id_pronostico` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `partido` int(3) unsigned DEFAULT NULL,
  `usuario` int(3) unsigned DEFAULT NULL,
  `resultado` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_pronostico`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode2_pronostico`
--

LOCK TABLES `prode2_pronostico` WRITE;
/*!40000 ALTER TABLE `prode2_pronostico` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode2_pronostico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prode2_voto`
--

DROP TABLE IF EXISTS `prode2_voto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode2_voto` (
  `id_voto` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `encuesta` int(3) unsigned DEFAULT NULL,
  `opcion` tinyint(1) DEFAULT NULL,
  `usuario` int(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_voto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode2_voto`
--

LOCK TABLES `prode2_voto` WRITE;
/*!40000 ALTER TABLE `prode2_voto` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode2_voto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prode_admin`
--

DROP TABLE IF EXISTS `prode_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode_admin` (
  `id_admin` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `admin` varchar(255) DEFAULT NULL,
  `clave` char(32) DEFAULT NULL,
  `usuario` int(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode_admin`
--

LOCK TABLES `prode_admin` WRITE;
/*!40000 ALTER TABLE `prode_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prode_correo`
--

DROP TABLE IF EXISTS `prode_correo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode_correo` (
  `id_correo` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `emisor` int(3) unsigned NOT NULL,
  `receptor` int(3) unsigned NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `mensaje` text,
  `fecha` datetime DEFAULT NULL,
  `leido` char(1) DEFAULT 'N',
  PRIMARY KEY (`id_correo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode_correo`
--

LOCK TABLES `prode_correo` WRITE;
/*!40000 ALTER TABLE `prode_correo` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode_correo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prode_equipo`
--

DROP TABLE IF EXISTS `prode_equipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode_equipo` (
  `id_equipo` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `corto` varchar(255) DEFAULT NULL,
  `largo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_equipo`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode_equipo`
--

LOCK TABLES `prode_equipo` WRITE;
/*!40000 ALTER TABLE `prode_equipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode_equipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prode_mensaje`
--

DROP TABLE IF EXISTS `prode_mensaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode_mensaje` (
  `id_mensaje` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` int(1) unsigned DEFAULT NULL,
  `autor` int(3) unsigned DEFAULT NULL,
  `mensaje` text,
  `dia` datetime DEFAULT NULL,
  PRIMARY KEY (`id_mensaje`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode_mensaje`
--

LOCK TABLES `prode_mensaje` WRITE;
/*!40000 ALTER TABLE `prode_mensaje` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode_mensaje` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prode_partido`
--

DROP TABLE IF EXISTS `prode_partido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode_partido` (
  `id_partido` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` int(2) DEFAULT NULL,
  `partido` int(1) DEFAULT NULL,
  `local` int(2) NOT NULL,
  `visitante` int(2) NOT NULL,
  `resultado` char(1) DEFAULT NULL,
  `dia` date DEFAULT NULL,
  `temp` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_partido`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode_partido`
--

LOCK TABLES `prode_partido` WRITE;
/*!40000 ALTER TABLE `prode_partido` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode_partido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prode_usuario`
--

DROP TABLE IF EXISTS `prode_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prode_usuario` (
  `id_usuario` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `clave` char(32) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `recibirmail` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prode_usuario`
--

LOCK TABLES `prode_usuario` WRITE;
/*!40000 ALTER TABLE `prode_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `prode_usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-05-29 14:57:03
