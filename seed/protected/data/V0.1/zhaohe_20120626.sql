ALTER TABLE  `playerStatus` CHANGE  `guideLevel`  `guideLevel` SMALLINT( 4 ) NULL DEFAULT  '0';
ALTER TABLE  `playerStatus` ADD  `guideCompleted` SMALLINT( 4 ) NOT NULL DEFAULT  '0' AFTER  `guideLevel`;