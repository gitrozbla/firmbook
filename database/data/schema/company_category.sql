CREATE TABLE `company_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category` bigint(20) DEFAULT NULL,
  `customer` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK1A006E80192A20BB` (`customer`),
  KEY `FK1A006E80D6EC9AFB` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1