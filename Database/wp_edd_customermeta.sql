CREATE TABLE `wp_edd_customermeta` (  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,  `customer_id` bigint(20) NOT NULL,  `meta_key` varchar(255) DEFAULT NULL,  `meta_value` longtext,  PRIMARY KEY (`meta_id`),  KEY `customer_id` (`customer_id`),  KEY `meta_key` (`meta_key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `wp_edd_customermeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_customermeta` ENABLE KEYS */;
