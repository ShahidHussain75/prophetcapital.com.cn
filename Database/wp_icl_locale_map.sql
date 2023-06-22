CREATE TABLE `wp_icl_locale_map` (  `code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,  `locale` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,  UNIQUE KEY `code` (`code`,`locale`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_icl_locale_map` DISABLE KEYS */;
INSERT INTO `wp_icl_locale_map` VALUES('en', 'en_US');
INSERT INTO `wp_icl_locale_map` VALUES('zh-hans', 'zh_CN');
/*!40000 ALTER TABLE `wp_icl_locale_map` ENABLE KEYS */;
