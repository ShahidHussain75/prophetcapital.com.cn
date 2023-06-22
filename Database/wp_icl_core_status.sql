CREATE TABLE `wp_icl_core_status` (  `id` bigint(20) NOT NULL AUTO_INCREMENT,  `rid` bigint(20) NOT NULL,  `module` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,  `origin` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,  `target` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,  `status` smallint(6) NOT NULL,  PRIMARY KEY (`id`),  KEY `rid` (`rid`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_icl_core_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_icl_core_status` ENABLE KEYS */;
