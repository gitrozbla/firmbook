CREATE TABLE `tbl_yii_session` (
  `id` char(32) CHARACTER SET utf8 NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` longblob,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1