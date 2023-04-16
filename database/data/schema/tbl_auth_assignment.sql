CREATE TABLE `tbl_auth_assignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` int(11) NOT NULL,
  `bizrule` text CHARACTER SET utf8,
  `data` text CHARACTER SET utf8,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `userid` (`userid`),
  CONSTRAINT `tbl_auth_assignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `tbl_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_auth_assignment_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1