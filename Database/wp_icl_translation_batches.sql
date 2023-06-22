CREATE TABLE `wp_icl_translation_batches` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `batch_name` text COLLATE utf8mb4_unicode_ci NOT NULL,  `tp_id` int(11) DEFAULT NULL,  `ts_url` text COLLATE utf8mb4_unicode_ci,  `last_update` datetime DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_icl_translation_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_icl_translation_batches` ENABLE KEYS */;
