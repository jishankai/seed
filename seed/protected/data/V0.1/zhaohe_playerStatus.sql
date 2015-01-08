CREATE TABLE `playerStatus` (
  `playerId` int NOT NULL,
  `mapId` tinyint DEFAULT '1',
  `guideLevel` tinyint DEFAULT '0',
  `createTime` int NOT NULL,
  `updateTime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;