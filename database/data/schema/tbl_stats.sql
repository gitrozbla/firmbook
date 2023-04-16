CREATE TABLE `tbl_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `users` int(11) NOT NULL,
  `package_owners` text NOT NULL,
  PRIMARY KEY (`id`,`date`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8