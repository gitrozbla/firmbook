CREATE TABLE `tbl_creators_website` (
  `company_id` int(11) NOT NULL,
  `meta_title` text CHARACTER SET utf8,
  `meta_description` text CHARACTER SET utf8,
  `meta_keywords` text CHARACTER SET utf8,
  `layout` varchar(10) DEFAULT NULL,
  `theme` varchar(10) DEFAULT NULL,
  `favicon` varchar(32) DEFAULT NULL,
  `name` text CHARACTER SET utf8,
  `name_color` varchar(7) DEFAULT NULL,
  `logo` varchar(32) DEFAULT NULL,
  `slogan` text CHARACTER SET utf8,
  `slogan_color` varchar(7) DEFAULT NULL,
  `header_text_align` varchar(7) NOT NULL DEFAULT 'left',
  `header_bg` varchar(32) DEFAULT NULL,
  `extended_header_bg` tinyint(1) NOT NULL DEFAULT '1',
  `header_bg_brightness` decimal(3,2) NOT NULL DEFAULT '0.62',
  `header_height` int(11) DEFAULT '150',
  `home_page_id` int(11) DEFAULT NULL,
  `items_on_page` int(11) NOT NULL DEFAULT '16',
  `footer_text` text CHARACTER SET utf8,
  PRIMARY KEY (`company_id`),
  KEY `home_page_id` (`home_page_id`),
  CONSTRAINT `tbl_creators_website_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_creators_website_ibfk_2` FOREIGN KEY (`home_page_id`) REFERENCES `tbl_creators_page` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1