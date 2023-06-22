CREATE TABLE `wp_upme_post_features` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `post_id` int(11) DEFAULT NULL,  `user_id` int(11) NOT NULL,  `read_status` tinyint(1) DEFAULT NULL,  `recommend_status` tinyint(1) DEFAULT NULL,  `favorite_status` tinyint(1) DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40000 ALTER TABLE `wp_upme_post_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_upme_post_features` ENABLE KEYS */;
