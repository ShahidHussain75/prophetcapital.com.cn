CREATE TABLE `wp_icl_string_status` (  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,  `rid` bigint(20) NOT NULL,  `string_translation_id` bigint(20) NOT NULL,  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,  PRIMARY KEY (`id`),  KEY `string_translation_id` (`string_translation_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_icl_string_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_icl_string_status` ENABLE KEYS */;
