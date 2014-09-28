
		
	CREATE TABLE IF NOT EXISTS `#__wbty_shop_products` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`ordering` INT(11)  NOT NULL ,
		`state` TINYINT(1)  NOT NULL DEFAULT '1',
		`checked_out` INT(11)  NOT NULL ,
		`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
		`created_by` INT(11)  NOT NULL ,
		`created_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
		`modified_by` INT(11)  NOT NULL ,
		`modified_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
		`image` VARCHAR(255) NOT NULL,
		`name` VARCHAR(255) NOT NULL,
		`description` LONGBLOB NOT NULL,
		`category` INT(11) NOT NULL,
		`pricing_set` INT(11) NOT NULL,
		`slide_type` VARCHAR(255) NOT NULL,
		`menu_link` VARCHAR(255) NOT NULL,
		`caption` TEXT NOT NULL,
		`base_id` INT(11)  NOT NULL ,
	PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;
				