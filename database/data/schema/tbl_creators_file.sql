CREATE TABLE `tbl_creators_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `filename` text NOT NULL,
  `generated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `generated` (`generated`),
  CONSTRAINT `tbl_creators_file_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `tbl_creators_website` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1