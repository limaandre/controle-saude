
DROP TABLE IF EXISTS `consults`;
DROP TABLE IF EXISTS `disease_medicine`;

DROP TABLE IF EXISTS `medicine`;
DROP TABLE IF EXISTS `exams`;


DROP TABLE IF EXISTS `doctor`;
DROP TABLE IF EXISTS `annotation`;
DROP TABLE IF EXISTS `disease`;

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `provider` varchar(100) NOT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `active` char(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `gender` char(1) NOT NULL,
  `birth_date` datetime NOT NULL,
  `image` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `iduser_UNIQUE` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor` (
  `iddoctor` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `medical_specialization` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `image` varchar(120) DEFAULT NULL,
  `file` varchar(130) DEFAULT NULL,
  `date` datetime NOT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`iddoctor`),
  UNIQUE KEY `iddoctor_UNIQUE` (`iddoctor`),
  KEY `fk_doctor_user_idx` (`iduser`),
  CONSTRAINT `fk_doctor_user` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor`
--

LOCK TABLES `doctor` WRITE;
/*!40000 ALTER TABLE `doctor` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctor` ENABLE KEYS */;
UNLOCK TABLES;


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `annotation` (
  `idannotation` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `title` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `iduser` int(11) NOT NULL,
  `image` varchar(120) DEFAULT NULL,
  `file` varchar(130) DEFAULT NULL,
  PRIMARY KEY (`idannotation`),
  UNIQUE KEY `idannotation_UNIQUE` (`idannotation`),
  KEY `fk_annotation_user1_idx` (`iduser`),
  CONSTRAINT `fk_annotation_user1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `annotation`
--

LOCK TABLES `annotation` WRITE;
/*!40000 ALTER TABLE `annotation` DISABLE KEYS */;
/*!40000 ALTER TABLE `annotation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_log`
--

DROP TABLE IF EXISTS `api_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_log` (
  `idapi_log` int(11) NOT NULL AUTO_INCREMENT,
  `habilitar` char(1) NOT NULL DEFAULT '0',
  `ip` varchar(100) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `app` char(1) NOT NULL DEFAULT '0',
  `login` varchar(100) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `uid` varchar(100) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `get` text,
  `post` text,
  `api_retorno` text,
  `versao` varchar(100) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  PRIMARY KEY (`idapi_log`)
) ENGINE=MyISAM AUTO_INCREMENT=23470 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_log`
--

LOCK TABLES `api_log` WRITE;
/*!40000 ALTER TABLE `api_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consults`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consults` (
  `idconsults` int(11) NOT NULL AUTO_INCREMENT,
  `date_consultation` datetime NOT NULL,
  `local` varchar(200) DEFAULT NULL,
  `note` text,
  `notify` char(1) NOT NULL DEFAULT '0',
  `image` varchar(120) DEFAULT NULL,
  `file` varchar(130) DEFAULT NULL,
  `iddoctor` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`idconsults`),
  UNIQUE KEY `idconsultation_UNIQUE` (`idconsults`),
  KEY `fk_consultation_doctor1_idx` (`iddoctor`),
  KEY `fk_consultation_user1_idx` (`iduser`),
  CONSTRAINT `fk_consultation_doctor1` FOREIGN KEY (`iddoctor`) REFERENCES `doctor` (`iddoctor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_consultation_user1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consults`
--

LOCK TABLES `consults` WRITE;
/*!40000 ALTER TABLE `consults` DISABLE KEYS */;
/*!40000 ALTER TABLE `consults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disease`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disease` (
  `iddisease` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `note` text,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`iddisease`),
  UNIQUE KEY `idillness_UNIQUE` (`iddisease`),
  KEY `fk_illness_user1_idx` (`iduser`),
  CONSTRAINT `fk_illness_user1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disease`
--

LOCK TABLES `disease` WRITE;
/*!40000 ALTER TABLE `disease` DISABLE KEYS */;
/*!40000 ALTER TABLE `disease` ENABLE KEYS */;
UNLOCK TABLES;




--
-- Table structure for table `exams`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exams` (
  `idexams` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `date_exam` datetime NOT NULL,
  `name` varchar(100) NOT NULL,
  `local` varchar(200) DEFAULT NULL,
  `note` text,
  `image` varchar(120) DEFAULT NULL,
  `file` varchar(130) DEFAULT NULL,
  `iddoctor` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `notify` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idexams`),
  UNIQUE KEY `idexams_UNIQUE` (`idexams`),
  KEY `fk_exams_doctor1_idx` (`iddoctor`),
  KEY `fk_exams_user1_idx` (`iduser`),
  CONSTRAINT `fk_exams_doctor1` FOREIGN KEY (`iddoctor`) REFERENCES `doctor` (`iddoctor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_exams_user1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams`
--

LOCK TABLES `exams` WRITE;
/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
/*!40000 ALTER TABLE `exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicine`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicine` (
  `idmedicine` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `concentration` varchar(100) DEFAULT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `medication_schedules` text,
  `date_initial` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `prescription` varchar(100) DEFAULT NULL,
  `note` text,
  `notify` char(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `image` varchar(120) DEFAULT NULL,
  `file` varchar(130) DEFAULT NULL,
  `iduser` int(11) NOT NULL,
  `iddoctor` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmedicine`),
  UNIQUE KEY `idmedicine_UNIQUE` (`idmedicine`),
  KEY `fk_medicine_user1_idx` (`iduser`),
  KEY `fk_medicine_doctor1_idx` (`iddoctor`),
  CONSTRAINT `fk_medicine_doctor1` FOREIGN KEY (`iddoctor`) REFERENCES `doctor` (`iddoctor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_medicine_user1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `garbage`
--

CREATE TABLE `garbage` (
  `idgarbage` int(11) NOT NULL,
  `habilitar` char(1) NOT NULL DEFAULT '0',
  `data` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `tabela` varchar(100) DEFAULT NULL,
  `usuario_nome` varchar(100) DEFAULT NULL,
  `id` varchar(100) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `dados` text,
  `sql_insert` text,
  `hash` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Dumping data for table `medicine`
--

LOCK TABLES `medicine` WRITE;
/*!40000 ALTER TABLE `medicine` DISABLE KEYS */;
/*!40000 ALTER TABLE `medicine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disease_medicine`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disease_medicine` (
  `idmedicine` int(11) NOT NULL,
  `iddisease` int(11) NOT NULL,
  PRIMARY KEY (`idmedicine`,`iddisease`),
  KEY `fk_medicine_has_disease_disease1_idx` (`iddisease`),
  KEY `fk_medicine_has_disease_medicine1_idx` (`idmedicine`),
  CONSTRAINT `fk_medicine_has_disease_disease1` FOREIGN KEY (`iddisease`) REFERENCES `disease` (`iddisease`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_medicine_has_disease_medicine1` FOREIGN KEY (`idmedicine`) REFERENCES `medicine` (`idmedicine`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disease_medicine`
--

LOCK TABLES `disease_medicine` WRITE;
/*!40000 ALTER TABLE `disease_medicine` DISABLE KEYS */;
/*!40000 ALTER TABLE `disease_medicine` ENABLE KEYS */;
UNLOCK TABLES;

