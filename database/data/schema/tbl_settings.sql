CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_from_level` int(11) NOT NULL,
  `last_cron_run` date NOT NULL,
  `image_sizes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8