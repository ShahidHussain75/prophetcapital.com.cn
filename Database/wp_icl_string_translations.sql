CREATE TABLE `wp_icl_string_translations` (  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,  `string_id` bigint(20) unsigned NOT NULL,  `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,  `status` tinyint(4) NOT NULL,  `value` text COLLATE utf8mb4_unicode_ci,  `translator_id` bigint(20) unsigned DEFAULT NULL,  `translation_service` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `batch_id` int(11) NOT NULL DEFAULT '0',  `translation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  PRIMARY KEY (`id`),  UNIQUE KEY `string_language` (`string_id`,`language`)) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_icl_string_translations` DISABLE KEYS */;
INSERT INTO `wp_icl_string_translations` VALUES('1', '5687', 'zh-hans', '10', '活跃', NULL, '', '0', '2016-08-15 20:08:36');
INSERT INTO `wp_icl_string_translations` VALUES('2', '4580', 'zh-hans', '10', '保存更改', NULL, '', '0', '2016-08-15 20:08:36');
INSERT INTO `wp_icl_string_translations` VALUES('3', '5150', 'zh-hans', '10', '状态', NULL, '', '0', '2016-08-15 20:08:36');
INSERT INTO `wp_icl_string_translations` VALUES('4', '6580', 'zh-hans', '10', '标题：', NULL, '', '0', '2016-08-15 20:08:36');
/*!40000 ALTER TABLE `wp_icl_string_translations` ENABLE KEYS */;
