CREATE TABLE `tbl_package_service_mn` (
  `package_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `threshold` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`package_id`,`service_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `tbl_package_service_mn_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `tbl_package` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_package_service_mn_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `tbl_package_service` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8