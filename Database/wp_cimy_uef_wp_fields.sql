CREATE TABLE `wp_cimy_uef_wp_fields` (  `ID` bigint(20) NOT NULL AUTO_INCREMENT,  `F_ORDER` bigint(20) NOT NULL,  `NAME` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,  `LABEL` text COLLATE utf8mb4_unicode_ci,  `DESCRIPTION` text COLLATE utf8mb4_unicode_ci,  `TYPE` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,  `RULES` text COLLATE utf8mb4_unicode_ci,  `VALUE` text COLLATE utf8mb4_unicode_ci,  PRIMARY KEY (`ID`),  KEY `F_ORDER` (`F_ORDER`),  KEY `NAME` (`NAME`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_cimy_uef_wp_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_cimy_uef_wp_fields` ENABLE KEYS */;