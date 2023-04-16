CREATE TABLE `tbl_alert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `context_id` int(11) NOT NULL,
  `context_type` tinyint(1) NOT NULL DEFAULT '1',
  `item_id` int(11) DEFAULT NULL,
  `item_type` tinyint(1) DEFAULT '1',
  `event` varchar(50) DEFAULT NULL,
  `message` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8