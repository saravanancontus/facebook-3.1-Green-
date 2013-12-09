<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('product_fb_new')};
CREATE TABLE {$this->getTable('product_fb_new')} (
  `facebookapp_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL default '0',  
  PRIMARY KEY (`facebookapp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('product_fb_time')};
CREATE TABLE {$this->getTable('product_fb_time')} (
  `facebookapp_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`facebookapp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();
