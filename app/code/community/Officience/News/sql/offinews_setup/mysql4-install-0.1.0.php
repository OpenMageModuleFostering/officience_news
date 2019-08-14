<?php

$installer = $this;
$installer->startSetup();
try {
    $installer->run("
DROP TABLE IF EXISTS {$this->getTable('officience_news')};
CREATE TABLE {$this->getTable('officience_news')} (
    `news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL DEFAULT '',
    `identifier` varchar(255) NOT NULL DEFAULT '',
    `description` text NOT NULL,
    `full_content` text NOT NULL,
    `thumbnail` varchar(255) NOT NULL DEFAULT '',
    `news_status` smallint(6) NOT NULL DEFAULT '0',
    `created_time` datetime DEFAULT NULL,
    `update_time` datetime DEFAULT NULL,
    `publicate_from_date` datetime DEFAULT NULL,
    `publicate_to_date` datetime DEFAULT NULL,
    `publicate_from_time` varchar(255)  DEFAULT '0',
    `publicate_to_time` varchar(255)  DEFAULT '0',
    `author` varchar(255) NOT NULL DEFAULT '',
    `meta_keywords` text NOT NULL,
    `meta_description` text NOT NULL,
    `comments_enabled` tinyint(11) NOT NULL,
    `tags` text NOT NULL,
    `sort_order` int(11) default '0',
    PRIMARY KEY ( `news_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS {$this->getTable('officience_comment')};
CREATE TABLE {$this->getTable('officience_comment')} (
    `comment_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
    `news_id` smallint( 11 ) NOT NULL default '0',
    `comment` text NOT NULL ,
    `comment_status` smallint( 6 ) NOT NULL default '0',
    `created_time` datetime default NULL ,
    `user` varchar( 255 ) NOT NULL default '',
    `email` varchar( 255 ) NOT NULL default '',
    PRIMARY KEY ( `comment_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS {$this->getTable('officience_category')};
CREATE TABLE {$this->getTable('officience_category')} (
    `category_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
    `title` varchar( 255 ) NOT NULL default '',
    `description` varchar(255) default '',
    `display_setting` int(11) default '0' ,
    `category_status` int(11) default '0',
    `thumbnail` varchar(255) default '',
    `identifier` varchar( 255 ) NOT NULL default '',
    `parent_id` int(11) default '0',
    `path` varchar(255) default '',
    `level` varchar(255) default '',
    `sort_order` int(11) default '0',
    `meta_keywords` text NOT NULL ,
    `meta_description` text NOT NULL ,
PRIMARY KEY ( `category_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS {$this->getTable('officience_news_store')};
CREATE TABLE {$this->getTable('officience_news_store')} (
    `news_id` int(11) unsigned,
    `store_id` int(11) unsigned,
    `title` varchar(255) NOT NULL DEFAULT '',
    `identifier` varchar(255) NOT NULL DEFAULT '',
    `description` text NOT NULL,
    `full_content` text NOT NULL,
    `news_status` smallint(6) NOT NULL DEFAULT '0',
    `publicate_from_date` datetime DEFAULT NULL,
    `publicate_to_date` datetime DEFAULT NULL,
    `publicate_from_time` varchar(255)   DEFAULT '0',
    `publicate_to_time` varchar(255)  DEFAULT '0',
    `author` varchar(255) NOT NULL DEFAULT '',
    `meta_keywords` text NOT NULL,
    `meta_description` text NOT NULL,
    `comments_enabled` tinyint(11) NOT NULL,
    `tags` text NOT NULL,
    `sort_order` int(11) default '0',
    `default_value` varchar(500) default '',
    PRIMARY KEY ( `news_id`,`store_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS {$this->getTable('officience_category_store')};
CREATE TABLE {$this->getTable('officience_category_store')} (
    `category_id` int(11),
    `store_id` int(11) unsigned,
    `category_status` int(11) default '0',
    `path` varchar(255) default '',
    `display_setting`int(11) default '0' ,
    `title` varchar( 255 ) NOT NULL default '',
    `description` varchar(255) default '',
    `identifier` varchar( 255 ) NOT NULL default '',
    `sort_order` int(11) default '0',
    `meta_keywords` text NOT NULL ,
    `meta_description` text NOT NULL ,
    `default_value` varchar(500) default '',
   
    
    PRIMARY KEY ( `category_id`,`store_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS {$this->getTable('officience_news_category')};
CREATE TABLE {$this->getTable('officience_news_category')} (
    `category_id` int(11)unsigned ,
    `news_id` int(11)unsigned,
     PRIMARY KEY ( `category_id`,`news_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
");
} catch (Exception $e) {
    
}

$installer->endSetup();
?>
