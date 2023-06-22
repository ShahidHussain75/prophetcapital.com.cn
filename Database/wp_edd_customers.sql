CREATE TABLE `wp_edd_customers` (  `id` bigint(20) NOT NULL AUTO_INCREMENT,  `user_id` bigint(20) NOT NULL,  `email` varchar(50) NOT NULL,  `name` mediumtext NOT NULL,  `purchase_value` mediumtext NOT NULL,  `purchase_count` bigint(20) NOT NULL,  `payment_ids` longtext NOT NULL,  `notes` longtext NOT NULL,  `date_created` datetime NOT NULL,  PRIMARY KEY (`id`),  UNIQUE KEY `email` (`email`),  KEY `user` (`user_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `wp_edd_customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_customers` ENABLE KEYS */;
