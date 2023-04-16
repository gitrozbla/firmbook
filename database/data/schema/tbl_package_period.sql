CREATE TABLE `tbl_package_period` (
  `package_id` int(10) unsigned NOT NULL,
  `period` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  `price` decimal(6,2) DEFAULT '0.00',
  PRIMARY KEY (`package_id`,`period`),
  CONSTRAINT `tbl_package_period_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `tbl_package` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8