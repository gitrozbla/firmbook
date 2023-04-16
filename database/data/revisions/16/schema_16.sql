-- ALTER TABLE tbl_user ADD language varchar(2);
CREATE TABLE IF NOT EXISTS tbl_spool
(
    id integer unsigned not null auto_increment,
    spool_cache_key varchar(100) not null,
    email varchar(100) not null,    
    date timestamp default now(),
    subject varchar(200) not null,    
    reply_to_email varchar(100),    
    reply_to_name varchar(100),    
    primary key (id)
) engine=InnoDB default charset utf8;
