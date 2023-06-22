CREATE TABLE `wp_cimy_uef_data` (  `ID` bigint(20) NOT NULL AUTO_INCREMENT,  `USER_ID` bigint(20) NOT NULL,  `FIELD_ID` bigint(20) NOT NULL,  `VALUE` text COLLATE utf8mb4_unicode_ci NOT NULL,  PRIMARY KEY (`ID`),  KEY `USER_ID` (`USER_ID`),  KEY `FIELD_ID` (`FIELD_ID`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_cimy_uef_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_cimy_uef_data` ENABLE KEYS */;
