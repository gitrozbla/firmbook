CREATE TABLE `tbl_follow` (
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_type` tinyint(1) NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`item_id`,`item_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8