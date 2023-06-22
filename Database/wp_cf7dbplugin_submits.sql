CREATE TABLE `wp_cf7dbplugin_submits` (  `submit_time` decimal(16,4) NOT NULL,  `form_name` varchar(127) CHARACTER SET utf8 DEFAULT NULL,  `field_name` varchar(127) CHARACTER SET utf8 DEFAULT NULL,  `field_value` longtext CHARACTER SET utf8,  `field_order` int(11) DEFAULT NULL,  `file` longblob,  KEY `submit_time_idx` (`submit_time`),  KEY `form_name_idx` (`form_name`),  KEY `field_name_idx` (`field_name`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40000 ALTER TABLE `wp_cf7dbplugin_submits` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_cf7dbplugin_submits` ENABLE KEYS */;
