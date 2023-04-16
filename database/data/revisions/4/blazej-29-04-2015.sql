-- 2015-04-29

ALTER TABLE tbl_user ADD COLUMN google_id varchar(50) AFTER facebook_id;
ALTER TABLE tbl_user ADD COLUMN register_source tinyint(1) NOT NULL DEFAULT 1 AFTER show_email;

-- 2015-05-08

/*ALTER TABLE tbl_item ADD COLUMN color varchar(6);*/
ALTER TABLE tbl_package ADD COLUMN color varchar(6) after stats_color;

-- 2015-05-08

UPDATE tbl_package SET name='FREE' WHERE id=1;
UPDATE tbl_package SET name='SILVER' WHERE id=2;
UPDATE tbl_package SET name='GOLD' WHERE id=3;
UPDATE tbl_package SET name='PLATINUM' WHERE id=4;

INSERT INTO tbl_source_message (category , object_id, message) VALUES ('package.title', '1', 'FREE');
INSERT INTO tbl_source_message (category , object_id, message) VALUES ('package.content', '1', '{FREE}');

INSERT INTO tbl_source_message (category , object_id, message) VALUES ('package.title', '2', 'SILVER');
INSERT INTO tbl_source_message (category , object_id, message) VALUES ('package.content', '2', '{SILVER}');

INSERT INTO tbl_source_message (category , object_id, message) VALUES ('package.title', '3', 'GOLD');
INSERT INTO tbl_source_message (category , object_id, message) VALUES ('package.content', '3', '{GOLD}');

INSERT INTO tbl_source_message (category , object_id, message) VALUES ('package.title', '4', 'PLATINUM');
INSERT INTO tbl_source_message (category , object_id, message) VALUES ('package.content', '4', '{PLATINUM}');

-- 2015-05-14

ALTER TABLE tbl_user ADD COLUMN expire_days_msg integer NOT NULL DEFAULT 0 AFTER package_expire;

-- 2015-05-19

CREATE TABLE IF NOT EXISTS tbl_movie
(	
	id integer unsigned NOT NULL auto_increment,
	item_id integer NOT NULL,
	user_id integer NOT NULL,
	url varchar(100),	
	source tinyint(1) NOT NULL DEFAULT 1,
	title text,
	description text,			
	date timestamp DEFAULT now(),
	PRIMARY KEY (id)
) engine=InnoDB default charset utf8;

ALTER TABLE tbl_movie ADD FOREIGN KEY (item_id) REFERENCES tbl_item (id) ON DELETE CASCADE;
ALTER TABLE tbl_movie ADD FOREIGN KEY (user_id) REFERENCES tbl_user (id) ON DELETE CASCADE;

-- 2015-05-19

ALTER TABLE tbl_article ADD COLUMN date timestamp DEFAULT now();

-- 2015-05-21

ALTER TABLE tbl_item ADD COLUMN view_count integer NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `tbl_post_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_type` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- 2015-05-25

ALTER TABLE tbl_category ADD COLUMN order_index integer;

-- 2015-06-01

CREATE TABLE IF NOT EXISTS tbl_ad_box 
(	
	id integer unsigned NOT NULL auto_increment,
	alias varchar(16) NOT NULL,
	label varchar(20) NOT NULL,
	period integer NOT NULL, 
	price DECIMAL(6,2) DEFAULT 0,
	carousel tinyint(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (id)
) engine=InnoDB default charset utf8;


CREATE TABLE IF NOT EXISTS tbl_ad_order
(
	id integer unsigned NOT NULL auto_increment,
  	user_id integer,
  	box_id integer unsigned,
  	period integer NOT NULL,  	
  	price DECIMAL(6,2) DEFAULT 0,
  	date timestamp DEFAULT now(),
  	date_paid timestamp NULL,
  	paid smallint NOT NULL DEFAULT 0,  	
  	modified timestamp NULL,
  	status smallint NOT NULL DEFAULT 0,  	
  	t_id text,
  	t_status smallint NOT NULL default 0,
  	t_date timestamp NULL,  	
	PRIMARY KEY (id),	
	CONSTRAINT tbl_ad_order_ibfk_1 FOREIGN KEY (user_id) REFERENCES tbl_user (id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tbl_ad_order_ibfk_2 FOREIGN KEY (box_id) REFERENCES tbl_ad_box (id) ON DELETE SET NULL ON UPDATE CASCADE
) engine=InnoDB default charset utf8;

ALTER TABLE tbl_ad ADD date timestamp DEFAULT now();
ALTER TABLE tbl_ad ADD date_from timestamp NULL;
ALTER TABLE tbl_ad ADD date_to timestamp NULL;
ALTER TABLE tbl_ad ADD order_id integer unsigned;

-- ALTER TABLE tbl_ad ADD FOREIGN KEY (order_id) REFERENCES tbl_ad_order (id) ON DELETE SET NULL;
-- ALTER TABLE `tbl_ad` DROP FOREIGN KEY `tbl_ad_ibfk_1` ;

ALTER TABLE tbl_ad_box ADD description text AFTER label;
ALTER TABLE tbl_ad_box ADD name varchar(100) AFTER label;
ALTER TABLE tbl_ad_box ADD size varchar(10) AFTER name;

alter table tbl_ad change date_from date_from date;
alter table tbl_ad change date_to date_to date;

alter table tbl_ad ADD no_limit tinyint(1) NOT NULL DEFAULT 0;

-- 2015-06-10
