ALTER TABLE `missionRecord`
MODIFY COLUMN `process`  text CHARACTER SET utf8 COLLATE utf8_bin NULL AFTER `status`;