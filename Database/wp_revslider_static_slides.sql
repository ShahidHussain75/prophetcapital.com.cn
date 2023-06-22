CREATE TABLE `wp_revslider_static_slides` (  `id` int(9) NOT NULL AUTO_INCREMENT,  `slider_id` int(9) NOT NULL,  `params` mediumtext NOT NULL,  `layers` mediumtext NOT NULL,  `settings` text NOT NULL,  UNIQUE KEY `id` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40000 ALTER TABLE `wp_revslider_static_slides` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_revslider_static_slides` ENABLE KEYS */;
