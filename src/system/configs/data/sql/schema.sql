CREATE TABLE `address` (`id` INT UNSIGNED AUTO_INCREMENT, `type` ENUM('billing', 'shipping') NOT NULL, `company` VARCHAR(40), `firstname` VARCHAR(32), `lastname` VARCHAR(64), `address` VARCHAR(100), `address2` VARCHAR(100), `city` VARCHAR(40), `state` CHAR(2), `zip` CHAR(5), `phone` VARCHAR(25), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE `admin` (`id` INT UNSIGNED AUTO_INCREMENT, `username` VARCHAR(15) NOT NULL UNIQUE, `password` CHAR(128) NOT NULL, `email` VARCHAR(127) NOT NULL UNIQUE, `firstname` VARCHAR(32) NOT NULL, `lastname` VARCHAR(64) NOT NULL, `active` TINYINT(1) DEFAULT '0' NOT NULL, `signup_date` DATETIME NOT NULL, `last_login` DATETIME, `token` CHAR(32), `password_key` CHAR(5), UNIQUE INDEX `login_idx` (`username`, `password`, `active`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE `category` (`id` INT UNSIGNED AUTO_INCREMENT, `name` VARCHAR(100) NOT NULL, `active` TINYINT(1) DEFAULT '0' NOT NULL, `lft` INT, `rgt` INT, `level` SMALLINT, `slug` VARCHAR(255), UNIQUE INDEX `category_sluggable_idx` (`slug`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE `user` (`id` INT UNSIGNED AUTO_INCREMENT, `password` CHAR(128) NOT NULL, `email` VARCHAR(127) NOT NULL UNIQUE, `firstname` VARCHAR(32) NOT NULL, `lastname` VARCHAR(64) NOT NULL, `active` TINYINT(1) DEFAULT '0' NOT NULL, `billing_id` INT UNSIGNED, `shipping_id` INT UNSIGNED, `signup_date` DATETIME NOT NULL, `last_login` DATETIME, `token` CHAR(32), `password_key` CHAR(5), UNIQUE INDEX `login_idx` (`email`, `password`, `active`), INDEX `billing_id_idx` (`billing_id`), INDEX `shipping_id_idx` (`shipping_id`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
ALTER TABLE `user` ADD CONSTRAINT `user_shipping_id_address_id` FOREIGN KEY (`shipping_id`) REFERENCES `address`(`id`);
ALTER TABLE `user` ADD CONSTRAINT `user_billing_id_address_id` FOREIGN KEY (`billing_id`) REFERENCES `address`(`id`);