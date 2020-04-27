DROP TABLE IF EXISTS `wcl_lti`;
CREATE TABLE `wcl_lti` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `intro` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `introformat` smallint(4) DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `typeid` bigint(10) DEFAULT NULL,
  `toolurl` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `securetoolurl` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `instructorchoicesendname` tinyint(1) DEFAULT NULL,
  `instructorchoicesendemailaddr` tinyint(1) DEFAULT NULL,
  `instructorchoiceallowroster` tinyint(1) DEFAULT NULL,
  `instructorchoiceallowsetting` tinyint(1) DEFAULT NULL,
  `instructorcustomparameters` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `instructorchoiceacceptgrades` tinyint(1) DEFAULT NULL,
  `grade` bigint(10) NOT NULL DEFAULT '100',
  `launchcontainer` tinyint(2) NOT NULL DEFAULT '1',
  `resourcekey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `debuglaunch` tinyint(1) NOT NULL DEFAULT '0',
  `showtitlelaunch` tinyint(1) NOT NULL DEFAULT '0',
  `showdescriptionlaunch` tinyint(1) NOT NULL DEFAULT '0',
  `servicesalt` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `icon` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `secureicon` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  KEY `wcl_lti_cou_ix` (`course`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=COMPRESSED COMMENT='This table contains Basic LTI activities instances';

--
-- Dumping data for table `wcl_lti`
--
INSERT INTO `wcl_lti` VALUES (1,3,'J Ext Activty','',1,1561725694,1561725694,1,'',NULL,1,1,NULL,NULL,'',1,100,1,NULL,NULL,0,1,0,'5d160afe953326.17162879',NULL,NULL),(2,4,'LTI Activity','',1,1561980805,1561980805,2,'',NULL,1,1,NULL,NULL,'',1,100,2,NULL,NULL,0,1,0,'5d19ef85e49037.19143779',NULL,NULL),(3,0,'tl-naaaaaaame - LTI','',1,1564827084,1564827084,17,'','',1,1,0,0,'',1,100,2,'','',0,1,0,'5d19ef85e49037.19143779','',''),(4,0,'Test Tool Provider - LTI','',1,1564835892,1564835892,18,'','',1,1,0,0,'',1,100,2,'','',0,1,0,'5d19ef85e49037.19143779','',''),(5,0,'google Tool - LTI','',1,1564839103,1564839103,19,'','',1,1,0,0,'',1,100,2,'','',0,1,0,'5d19ef85e49037.19143779','',''),(6,0,'SOme tool - LTI','',1,1564910001,1564910001,20,'','',1,1,0,0,'',1,100,2,'','',0,1,0,'5d19ef85e49037.19143779','','');

--
-- Table structure for table `wcl_lti_types`
--

DROP TABLE IF EXISTS `wcl_lti_types`;
CREATE TABLE `wcl_lti_types` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'basiclti Activity',
  `baseurl` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `tooldomain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `state` tinyint(2) NOT NULL DEFAULT '2',
  `course` bigint(10) NOT NULL,
  `coursevisible` tinyint(1) NOT NULL DEFAULT '0',
  `ltiversion` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `clientid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `toolproxyid` bigint(10) DEFAULT NULL,
  `enabledcapability` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `parameter` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `icon` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `secureicon` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `createdby` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`)  
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=COMPRESSED COMMENT='Basic LTI pre-configured activities';

--
-- Dumping data for table `wcl_lti_types`
--
INSERT INTO `wcl_lti_types` VALUES (1,'J-Ext','https://lti-ri.imsglobal.org/lti/tools/237/launches','lti-ri.imsglobal.org',1,3,1,'1.3.0','NgR7GOufUZbkVLl',NULL,NULL,NULL,'','',2,1561725286,1561725286,'test ext tool desc'),(2,'LocalLTI-1-3','http://localhost:9001/example/launch.php','localhost:9001',1,1,1,'1.3.0','L1UUnG3MoRHw4It',NULL,NULL,NULL,'','',2,1561980568,1563363087,'LTI test tool');

--
-- Table structure for table `wcl_lti_types_config`
--

DROP TABLE IF EXISTS `wcl_lti_types_config`;
CREATE TABLE `wcl_lti_types_config` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `typeid` bigint(10) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wcl_ltitypeconf_typ_ix` (`typeid`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=COMPRESSED COMMENT='Basic LTI types configuration';

--
-- Dumping data for table `wcl_lti_types_config`
--
INSERT INTO `wcl_lti_types_config` VALUES (1,1,'publickey','-----BEGIN PUBLIC KEY----- MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA8osiSa75nmqmakwNNocL A2N2huWM9At/tjSZOFX1r4+PDclSzxhMw+ZcgHH+E/05Ec6Vcfd75i8Z+Bxu4ctb Yk2FNIvRMN5UgWqxZ5Pf70n8UFxjGqdwhUA7/n5KOFoUd9F6wLKa6Oh3OzE6v9+O 3y6qL40XhZxNrJjCqxSEkLkOK3xJ0J2npuZ59kipDEDZkRTWz3al09wQ0nvAgCc9 6DGH+jCgy0msA0OZQ9SmDE9CCMbDT86ogLugPFCvo5g5zqBBX9Ak3czsuLS6Ni9W co8ZSxoaCIsPXK0RJpt6Jvbjclqb4imsobifxy5LsAV0l/weNWmU2DpzJsLgeK6V VwIDAQAB -----END PUBLIC KEY-----'),(2,1,'initiatelogin','https://lti-ri.imsglobal.org/lti/tools/237/login_initiations'),(3,1,'redirectionuris',''),(4,1,'customparameters',''),(5,1,'coursevisible','1'),(6,1,'launchcontainer','3'),(7,1,'contentitem','1'),(8,1,'ltiservice_gradesynchronization','0'),(9,1,'ltiservice_memberships','0'),(10,1,'ltiservice_toolsettings','0'),(11,1,'sendname','2'),(12,1,'sendemailaddr','2'),(13,1,'acceptgrades','2'),(14,1,'forcessl','0'),(15,1,'servicesalt','5d160966820d98.97312594'),(16,2,'publickey',''),(17,2,'initiatelogin','http://localhost:9001/example/login.php'),(18,2,'redirectionuris',''),(19,2,'customparameters','custom_context_memberships_url=http://www.google.com'),(20,2,'coursevisible','1'),(21,2,'launchcontainer','3'),(22,2,'contentitem','1'),(23,2,'ltiservice_gradesynchronization','0'),(24,2,'ltiservice_memberships','1'),(25,2,'ltiservice_toolsettings','1'),(26,2,'sendname','1'),(27,2,'sendemailaddr','1'),(28,2,'acceptgrades','1'),(29,2,'organizationid',''),(30,2,'organizationurl',''),(31,2,'forcessl','0'),(32,2,'servicesalt','5d19ee9808cfc2.16905989');