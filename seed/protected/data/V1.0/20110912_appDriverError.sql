CREATE TABLE `appDriverError` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(255) DEFAULT NULL,
  `url` TEXT DEFAULT NULL,
  `count` INT UNSIGNED DEFAULT 1,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

