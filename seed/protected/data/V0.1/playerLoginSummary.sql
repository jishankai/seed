CREATE TABLE `playerLoginSummary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dayTime` int(10) unsigned NOT NULL,
  `activePlayerNum` int(10) unsigned NOT NULL DEFAULT '0',
  `createTime` INT UNSIGNED,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;