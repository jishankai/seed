CREATE TABLE `achievement` (
  `achievementId` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `category` TINYINT NOT NULL,
  `class` TINYINT NOT NULL,
  `event` VARCHAR(255) NOT NULL,
  `checkClass` VARCHAR(255) NOT NULL,
  `expectedParams` VARCHAR(255),
  `paramsCount` INT UNSIGNED NOT NULL DEFAULT '0',
  `rewardItem` VARCHAR(255),
  `rewardCup` VARCHAR(255),
  `rewardExp` INT UNSIGNED,
  `rewardGold` INT UNSIGNED,
  `rewardUserMoney` INT UNSIGNED,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`achievementId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `achievementRecord` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `playerId` INT UNSIGNED NOT NULL,
  `achievementId` INT UNSIGNED NOT NULL,
  `status` TINYINT NOT NULL,
  `process` TEXT,
  `processCount` INT UNSIGNED NOT NULL DEFAULT '0',
  `statusTime` INT UNSIGNED,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `catalog` (
  `playerId` INT UNSIGNED NOT NULL,
  `content` MEDIUMTEXT,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `friend` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `playerId` INT UNSIGNED NOT NULL DEFAULT '0',
  `friendId` INT UNSIGNED NOT NULL DEFAULT '0',
  `status` TINYINT NOT NULL,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `statusChangeTime` TIMESTAMP NOT NULL,
  `fosterSeed` INT UNSIGNED,
  `powerChangeTime` INT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `U_playerId_friendId` (`playerId`,`friendId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `friendInfo` (
  `playerId` INT UNSIGNED NOT NULL DEFAULT '0',
  `randomTime` INT UNSIGNED NOT NULL,
  `randomList` VARCHAR(511),
  `isAddFriend` TINYINT NOT NULL DEFAULT '1',
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `garden` (
  `gardenId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `playerId` INT UNSIGNED NOT NULL,
  `backGround` INT UNSIGNED NOT NULL,
  `seedList` VARCHAR(255) NOT NULL,
  `fosterList` VARCHAR(255) NOT NULL,
  `seedCount` INT UNSIGNED NOT NULL DEFAULT '0',
  `decoExtraGrow` INT UNSIGNED NOT NULL,
  `decorationInfo` TEXT,
  `favouriteFlag` TINYINT NOT NULL,
  `gardenSign` INT UNSIGNED NOT NULL,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`gardenId`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `item` (
  `playerId` INT UNSIGNED NOT NULL,
  `decoItem` TEXT,
  `resItem` TEXT,
  `useItem` TEXT,
  `chestItem`  TEXT,
  `cupState` TEXT,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `itemActionLog` (
  `logId` INT NOT NULL AUTO_INCREMENT,
  `itemId` INT,
  `num` INT,
  `actionType` enum('add','use'),
  `desc` VARCHAR(200),
  `playerId` INT,
  `createTime` INT NOT NULL DEFAULT '0',
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `loginRewardLog` (
  `logId` INT NOT NULL AUTO_INCREMENT,
  `rewardId` INT,
  `num` INT,
  `actionType` enum('systemGive','playerGet','playerUse'),
  `desc` VARCHAR(200),
  `playerId` INT,
  `createTime` INT NOT NULL DEFAULT '0',
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `mail` (
  `mailId` INT NOT NULL AUTO_INCREMENT,
  `playerId` INT NOT NULL DEFAULT '0',
  `isGet` TINYINT NOT NULL DEFAULT '0',
  `getDays` INT NOT NULL DEFAULT '0',
  `keepDays` INT NOT NULL DEFAULT '0',
  `informType` TINYINT NOT NULL DEFAULT '0',
  `fromId` INT NOT NULL DEFAULT '0',
  `sentThings` TEXT,
  `mailTitle` VARCHAR(50) DEFAULT NULL,
  `content` VARCHAR(255) NOT NULL DEFAULT '',
  `isRead` TINYINT NOT NULL DEFAULT '0',
  `createTime` INT NOT NULL DEFAULT '0',
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action` TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (`mailId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `mailHistory` (
  `mailId` INT NOT NULL DEFAULT '0',
  `playerId` INT DEFAULT '0',
  `isGet` TINYINT DEFAULT '0',
  `getDays` INT DEFAULT '0',
  `keepDays` INT DEFAULT '0',
  `informType` TINYINT DEFAULT '0',
  `fromId` INT DEFAULT '0',
  `sentThings` TEXT,
  `mailTitle` VARCHAR(50) DEFAULT NULL,
  `content` VARCHAR(255),
  `isRead` TINYINT DEFAULT '0',
  `createTime` INT NOT NULL DEFAULT '0',
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action` TINYINT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `mailNotice` (
  `noticeId` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `notice` TEXT NOT NULL DEFAULT '',
  `createTime` INT NOT NULL DEFAULT '0',
  `startTime` INT NOT NULL DEFAULT '0',
  `endTime` INT NOT NULL DEFAULT '0',
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`noticeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `mailQueue` (
  `queueId` INT NOT NULL AUTO_INCREMENT,
  `playerId` INT NOT NULL DEFAULT '0',
  `getDays` INT NOT NULL DEFAULT '0',
  `keepDays` INT NOT NULL DEFAULT '0',
  `informType` TINYINT NOT NULL DEFAULT '0',
  `fromId` INT NOT NULL DEFAULT '0',
  `sentThings` TEXT,
  `mailTitle` VARCHAR(50) DEFAULT NULL,
  `content` VARCHAR(255) DEFAULT NULL,
  `createTime` INT NOT NULL DEFAULT '0',
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isMoveOut` TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (`queueId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `mission` (
  `missionId` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `endCondition` VARCHAR(255) NOT NULL,
  `count` INT UNSIGNED,
  `event` VARCHAR(255) NOT NULL,
  `checkClass` VARCHAR(255) NOT NULL,
  `expectedParams` VARCHAR(255),
  `preLevel` INT UNSIGNED NOT NULL,
  `preMissionId` VARCHAR(255),
  `endImage` VARCHAR(255),
  `endCount` INT UNSIGNED,
  `endPre` VARCHAR(255),
  `endNext` VARCHAR(255),
  `rewardSeed` VARCHAR(255),
  `rewardItem` VARCHAR(255),
  `rewardGold` INT UNSIGNED,
  `rewardUserMoney` INT UNSIGNED,
  `rewardExp` INT UNSIGNED,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`missionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `missionHistory` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `playerId` INT UNSIGNED NOT NULL,
  `missionId` INT UNSIGNED NOT NULL,
  `status` TINYINT NOT NULL,
  `process` TEXT,
  `processCount` INT UNSIGNED NOT NULL DEFAULT '0',
  `statusTime` INT UNSIGNED,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `missionRecord` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `playerId` INT UNSIGNED NOT NULL,
  `missionId` INT UNSIGNED NOT NULL,
  `status` tinyINT,
  `process` TEXT,
  `processCount` INT UNSIGNED NOT NULL DEFAULT '0',
  `statusTime` INT UNSIGNED,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `package` (
  `playerId` INT UNSIGNED NOT NULL,
  `content` TEXT,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `player` (
  `playerId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` INT UNSIGNED NOT NULL UNIQUE,
  `playerName` VARCHAR(255) NOT NULL UNIQUE,
  `exp` INT UNSIGNED NOT NULL,
  `level` INT UNSIGNED NOT NULL,
  `gold` INT unsigned NOT NULL DEFAULT '0',
  `gardenNum` INT UNSIGNED NOT NULL,
  `defaultGarden` INT UNSIGNED NOT NULL,
  `favouriteGarden` INT UNSIGNED NOT NULL,
  `favouriteSeed` INT UNSIGNED,
  `inviteId` VARCHAR(20) NOT NULL,
  `inviterId` INT,
  `visitedCount` INT UNSIGNED NOT NULL DEFAULT '0',
  `messageData` TEXT,
  `settingData` VARCHAR(1024),
  `sessionId` VARCHAR(32) COLLATE utf8_bin DEFAULT NULL,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playerId`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `playerLogin` (
  `playerId` INT UNSIGNED NOT NULL,
  `lastLoginTime` INT UNSIGNED NOT NULL,
  `loginDays` INT UNSIGNED NOT NULL,
  `rewardInfo` TEXT NOT NULL,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `playerPoint` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `playerId` INT UNSIGNED NOT NULL,
  `type` INT UNSIGNED NOT NULL,
  `max` INT UNSIGNED NOT NULL,
  `value` INT UNSIGNED NOT NULL,
  `refreshTime` INT UNSIGNED NOT NULL,
  `changeValue` INT UNSIGNED NOT NULL DEFAULT '0',
  `changeInterval` INT UNSIGNED NOT NULL,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `pushQueue` (
  `queueId` INT NOT NULL AUTO_INCREMENT,
  `playerId` INT NOT NULL,
  `content` VARCHAR(255) NOT NULL,
  `state` TINYINT DEFAULT '0',
  `sendTime` INT DEFAULT '0',
  `createTime` INT NOT NULL,
  `updateTime` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`queueId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `playerStatus` (
  `playerId` int NOT NULL,
  `mapId` tinyint DEFAULT '1',
  `guideLevel` SMALLINT DEFAULT '0',
  `guideCompleted` SMALLINT DEFAULT '0',
  `visualFoster` int DEFAULT NULL,
  `breedCDTime` VARCHAR(255) DEFAULT NULL,
  `chargeCDTime` INT UNSIGNED NOT NULL,
  `noticeStartTime` int(11) DEFAULT NULL,
  `newMailNum` text COLLATE utf8_bin,
  `snsContent` text COLLATE utf8_bin,
  `createTime` int NOT NULL,
  `updateTime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `seed` (
  `seedId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `playerId` INT UNSIGNED NOT NULL,
  `gardenId` INT UNSIGNED,
  `favouriteFlag` TINYINT NOT NULL,
  `bodyId` INT UNSIGNED NOT NULL,
  `faceId` INT UNSIGNED NOT NULL,
  `budId` INT UNSIGNED NOT NULL,
  `dressId` INT UNSIGNED,
  `attributes` VARCHAR(255) NOT NULL,
  `hideAttribute` TINYINT,
  `growValue` float unsigned NOT NULL,
  `maxGrowValue` INT UNSIGNED NOT NULL,
  `lastGrowTime` INT UNSIGNED NOT NULL DEFAULT '0',
  `growPeriod` TINYINT NOT NULL DEFAULT '0',
  `feedCount` INT UNSIGNED NOT NULL,
  `breedCDTime` INT UNSIGNED NOT NULL,
  `isFoster` INT UNSIGNED,
  `isSell` TINYINT NOT NULL DEFAULT '0',
  `getFrom` TINYINT,
  `sellTime` INT UNSIGNED,
  `state` TINYINT( 1 ) NOT NULL DEFAULT '0',
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`seedId`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `seedActionLog` (
  `logId` INT NOT NULL AUTO_INCREMENT,
  `seedId` INT,
  `actionType` enum('equip','sell','feed','get'),
  `actionDesc` VARCHAR(200),
  `playerId` INT,
  `createTime` INT,
  `updateTime` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `seedUser` (
  `userId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lastLoginWorldId` INT UNSIGNED,
  `lastLoginTime` INT UNSIGNED,
  `sessionId` VARCHAR(255),
  `createTime` INT UNSIGNED,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `user` (
  `userId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `deviceId` VARCHAR(255) UNIQUE,
  `token` VARCHAR(255),
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `userFacebook` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` INT NOT NULL DEFAULT '0',
  `systemId` VARCHAR(255),
  `systemName` VARCHAR(255),
  `createTime` INT UNSIGNED,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `accessToken` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `userMoney` (
  `userId` INT UNSIGNED NOT NULL,
  `purchaseGold` INT unsigned NOT NULL DEFAULT '0',
  `systemGold` INT unsigned NOT NULL DEFAULT '0',
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `userMoneyLog` (
  `logId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` INT UNSIGNED NOT NULL,
  `type` TINYINT NOT NULL,
  `linkId` INT UNSIGNED,
  `gold` INT NOT NULL DEFAULT '0',
  `currentGold` INT NOT NULL DEFAULT '0',
  `afterGold` INT NOT NULL DEFAULT '0',
  `comment` TEXT NOT NULL,
  `createTime` INT UNSIGNED NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `userTwitter` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` INT NOT NULL DEFAULT '0',
  `systemId` VARCHAR(255),
  `systemName` VARCHAR(255),
  `createTime` INT UNSIGNED,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usertoken` text COLLATE utf8_bin,
  `userSecret` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `playerLoginSummary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dayTime` int(10) unsigned NOT NULL,
  `activePlayerNum` int(10) unsigned NOT NULL DEFAULT '0',
  `createTime` INT UNSIGNED,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `playerLoginSummary_M` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `monthTime` int(10) unsigned NOT NULL,
  `activePlayerNum` int(10) unsigned NOT NULL DEFAULT '0',
  `createTime` INT UNSIGNED,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `adminUser` (
  `adminId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `token` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `endTime` int(10) DEFAULT NULL,
  `deleteFlag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`adminId`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `ItunesPaymentTransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned DEFAULT NULL,
  `sns_id` varchar(64) COLLATE utf8_bin NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `transaction_status` int(10) unsigned NOT NULL,
  `product_id` varchar(45) COLLATE utf8_bin NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `purchaseGold` int(10) unsigned NOT NULL,
  `systemGold` int(10) unsigned NOT NULL,
  `record_time` int(10) unsigned NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `sns_id` (`sns_id`),
  KEY `Index_4` (`record_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `famitsuCode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(16) COLLATE utf8_bin NOT NULL,
  `userId` int(10) unsigned DEFAULT NULL,
  `createTime` int(10) unsigned NOT NULL,
  `updateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleteFlag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

