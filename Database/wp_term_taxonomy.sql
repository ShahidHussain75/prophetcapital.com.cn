CREATE TABLE `wp_term_taxonomy` (  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',  `count` bigint(20) NOT NULL DEFAULT '0',  PRIMARY KEY (`term_taxonomy_id`),  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),  KEY `taxonomy` (`taxonomy`)) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_term_taxonomy` DISABLE KEYS */;
INSERT INTO `wp_term_taxonomy` VALUES('1', '1', 'category', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('5', '5', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('6', '6', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('7', '7', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('8', '8', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('9', '9', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('10', '10', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('11', '11', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('12', '12', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('13', '13', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('14', '14', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('15', '15', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('16', '16', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('17', '17', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('18', '18', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('19', '19', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('20', '20', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('21', '21', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('22', '22', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('23', '23', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('24', '24', 'post_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('25', '25', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('26', '26', 'portfolio_cats', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('27', '27', 'download_category', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('28', '28', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('29', '29', 'edd_log_type', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('30', '30', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('31', '31', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('32', '32', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('33', '33', 'edd_log_type', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('34', '34', 'portfolio_cats', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('35', '35', 'portfolio_cats', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('36', '36', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('37', '37', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('38', '38', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('39', '39', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('40', '40', 'download_category', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('41', '41', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('42', '42', 'edd_log_type', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('43', '43', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('44', '44', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('45', '45', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('46', '46', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('47', '47', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('48', '48', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('49', '49', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('50', '50', 'download_tag', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('51', '51', 'nav_menu', '', '0', '3');
INSERT INTO `wp_term_taxonomy` VALUES('55', '55', 'category', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('56', '56', 'category', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('61', '61', 'category', '', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('62', '62', 'nav_menu', '', '0', '2');
INSERT INTO `wp_term_taxonomy` VALUES('68', '68', 'category', 'Blog', '0', '0');
INSERT INTO `wp_term_taxonomy` VALUES('69', '69', 'ats-employer-taxonomy', '', '0', '4');
INSERT INTO `wp_term_taxonomy` VALUES('70', '70', 'nav_menu', '', '0', '4');
INSERT INTO `wp_term_taxonomy` VALUES('71', '71', 'nav_menu', '', '0', '3');
INSERT INTO `wp_term_taxonomy` VALUES('72', '72', 'nav_menu', '', '0', '2');
INSERT INTO `wp_term_taxonomy` VALUES('73', '73', 'nav_menu', '', '0', '4');
INSERT INTO `wp_term_taxonomy` VALUES('74', '74', 'category', 'Level1', '0', '18');
INSERT INTO `wp_term_taxonomy` VALUES('75', '75', 'category', 'Level2', '0', '18');
/*!40000 ALTER TABLE `wp_term_taxonomy` ENABLE KEYS */;