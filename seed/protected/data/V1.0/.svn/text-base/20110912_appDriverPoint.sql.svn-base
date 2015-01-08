CREATE TABLE `appDriverPoint` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `achieve_id` VARCHAR(255) DEFAULT NULL,
  `identifier` INT UNSIGNED DEFAULT NULL,
  `point` INT DEFAULT NULL,
  `payment` INT DEFAULT NULL,
  `campaign_id` INT UNSIGNED DEFAULT NULL,
  `campaign_name` TEXT DEFAULT NULL,
  `advertisement_id` INT UNSIGNED DEFAULT NULL,
  `advertisement_name` TEXT DEFAULT NULL,
  `accepted_time` VARCHAR(255) DEFAULT NULL,
  `url` TEXT NOT NULL,
  `status` TINYINT DEFAULT NULL,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `U_achieveId` (`achieve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

