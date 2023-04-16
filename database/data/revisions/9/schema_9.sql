CREATE TABLE `tbl_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `filename` text NOT NULL,
  `anchor` text,
  `description` text,
  `date` timestamp NOT NULL DEFAULT now(),
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `date` (`date`),
  CONSTRAINT `tbl_attachment_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `tbl_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_attachment` ADD `orginal_name` text NOT NULL;