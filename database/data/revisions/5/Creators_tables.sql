/* CreatorsWebsite */
CREATE TABLE IF NOT EXISTS `tbl_creators_website` (
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
  `footer_text` text CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* CreatorsPage */
CREATE TABLE IF NOT EXISTS `tbl_creators_page` (
  `id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `type` varchar(8) NOT NULL DEFAULT 'custom',
  `items_per_page` int(11) NOT NULL DEFAULT '20',
  `title` text CHARACTER SET utf8 NOT NULL,
  `alias` varchar(64) NOT NULL,
  `content` text CHARACTER SET utf8
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

/* CreatorsFile */
CREATE TABLE IF NOT EXISTS `tbl_creators_file` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `filename` text NOT NULL,
  `generated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;



/* CreatorsWebsite */
ALTER TABLE `tbl_creators_website`
  ADD PRIMARY KEY (`company_id`), ADD KEY `home_page_id` (`home_page_id`);
ALTER TABLE `tbl_creators_website`
ADD CONSTRAINT `tbl_creators_website_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `tbl_creators_website_ibfk_2` FOREIGN KEY (`home_page_id`) REFERENCES `tbl_creators_page` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

/* CreatorsPage */
ALTER TABLE `tbl_creators_page`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website_id_2` (`website_id`,`alias`), ADD KEY `website_id` (`website_id`), ADD KEY `position` (`position`);

ALTER TABLE `tbl_creators_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;

ALTER TABLE `tbl_creators_page`
ADD CONSTRAINT `tbl_creators_page_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `tbl_creators_website` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/* CreatorsFile */
ALTER TABLE `tbl_creators_file`
  ADD PRIMARY KEY (`id`), ADD KEY `company_id` (`company_id`), ADD KEY `generated` (`generated`);

ALTER TABLE `tbl_creators_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;

ALTER TABLE `tbl_creators_file`
ADD CONSTRAINT `tbl_creators_file_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `tbl_creators_website` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;
