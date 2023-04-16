CREATE TABLE `tbl_item_additional_category` (
  `id_item` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  PRIMARY KEY (`id_item`,`id_category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1