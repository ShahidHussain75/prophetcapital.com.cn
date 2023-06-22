CREATE TABLE `wp_revslider_navigations` (  `id` int(9) NOT NULL AUTO_INCREMENT,  `name` varchar(191) NOT NULL,  `handle` varchar(191) NOT NULL,  `css` mediumtext NOT NULL,  `markup` mediumtext NOT NULL,  `settings` mediumtext,  UNIQUE KEY `id` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40000 ALTER TABLE `wp_revslider_navigations` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_revslider_navigations` ENABLE KEYS */;
