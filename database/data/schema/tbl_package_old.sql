CREATE TABLE `tbl_package_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `css_name` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `badge_css` text COLLATE utf8_polish_ci NOT NULL,
  `item_css` text COLLATE utf8_polish_ci NOT NULL,
  `stats_color` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci