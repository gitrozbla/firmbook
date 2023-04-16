CREATE TABLE `old_tbl_type` (
  `id` int(11) NOT NULL,
  `type` varchar(7) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci