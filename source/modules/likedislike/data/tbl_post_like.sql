CREATE TABLE IF NOT EXISTS `tbl_post_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_type` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;