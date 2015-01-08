/*
Navicat MySQL Data Transfer

Source Server         : Seed
Source Server Version : 50513
Source Host           : 192.168.1.93:3306
Source Database       : seed2

Target Server Type    : MYSQL
Target Server Version : 50513
File Encoding         : 65001

Date: 2012-07-26 13:51:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `famitsuCode`
-- ----------------------------
DROP TABLE IF EXISTS `famitsuCode`;
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

-- ----------------------------
-- Records of famitsuCode
-- ----------------------------
