ALTER TABLE `mail`
ADD COLUMN `mailTitle`  VARCHAR(50) NULL AFTER `sentThings`;

ALTER TABLE `mailHistory`
ADD COLUMN `mailTitle`  VARCHAR(50) NULL AFTER `sentThings`;

ALTER TABLE `mailQueue`
ADD COLUMN `mailTitle`  VARCHAR(50) NULL AFTER `sentThings`;

ALTER TABLE `mailNotice`
ADD COLUMN `startTime`  INT NOT NULL DEFAULT '0' AFTER `createTime`;

ALTER TABLE `mailNotice`
ADD COLUMN `endTime`  INT NOT NULL DEFAULT '0' AFTER `startTime`;