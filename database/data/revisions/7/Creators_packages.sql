alter table tbl_package ADD creators tinyint(1) NOT NULL DEFAULT 0 AFTER name;
alter table tbl_package_service ADD creators tinyint(1) NOT NULL DEFAULT 0 AFTER name;
alter table tbl_package_purchase ADD creators tinyint(1) NOT NULL DEFAULT 0 AFTER package_id;

alter table tbl_user ADD creators_package_id integer unsigned AFTER expire_days_msg;
alter table tbl_user ADD creators_package_expire date NULL AFTER creators_package_id;
alter table tbl_user ADD creators_expire_days_msg integer NOT NULL DEFAULT 0 AFTER creators_package_expire;

UPDATE tbl_user SET creators_package_id = 5;

alter table tbl_settings ADD creators_last_cron_run date NOT NULL AFTER last_cron_run;

ALTER TABLE tbl_user ADD COLUMN remote_source tinyint(1) AFTER register_source;

ALTER TABLE tbl_ad_box ADD height varchar(4) AFTER size;

alter table tbl_article ADD creators tinyint(1) NOT NULL DEFAULT 0;