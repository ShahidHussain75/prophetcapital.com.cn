CREATE TABLE `wp_icl_node` (  `nid` bigint(20) NOT NULL,  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,  `links_fixed` tinyint(4) NOT NULL DEFAULT '0',  PRIMARY KEY (`nid`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_icl_node` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_icl_node` ENABLE KEYS */;
