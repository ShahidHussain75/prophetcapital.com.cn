CREATE TABLE `wp_icl_reminders` (  `id` bigint(20) NOT NULL,  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,  `can_delete` tinyint(4) NOT NULL,  `show` tinyint(4) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_icl_reminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_icl_reminders` ENABLE KEYS */;
