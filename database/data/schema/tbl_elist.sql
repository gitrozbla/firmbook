CREATE TABLE `tbl_elist` (
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `item_type` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`,`item_id`,`type`,`item_type`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8