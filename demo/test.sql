/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.65
Source Server Version : 50538
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50538
File Encoding         : 65001

Date: 2014-11-19 12:22:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tang_tag
-- ----------------------------
DROP TABLE IF EXISTS `tang_tag`;
CREATE TABLE `tang_tag` (
  `tagId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`tagId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tang_tag
-- ----------------------------
INSERT INTO `tang_tag` VALUES ('1', '宅男');
INSERT INTO `tang_tag` VALUES ('2', '萝莉控');
INSERT INTO `tang_tag` VALUES ('3', '晚报');
INSERT INTO `tang_tag` VALUES ('4', '技术宅');

-- ----------------------------
-- Table structure for tang_user
-- ----------------------------
DROP TABLE IF EXISTS `tang_user`;
CREATE TABLE `tang_user` (
  `userId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userName` char(10) NOT NULL,
  `password` char(32) NOT NULL,
  `realName` varchar(10) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tang_user
-- ----------------------------
INSERT INTO `tang_user` VALUES ('1', 'wujibing', 'a968fb6d801a00d1497e5048907b6c6c', '吴吉兵');
INSERT INTO `tang_user` VALUES ('3', 'wujibing2', 'dcff5c1d31f0530eef72a1142eaf6474', 'wujibing2');

-- ----------------------------
-- Table structure for tang_user_info
-- ----------------------------
DROP TABLE IF EXISTS `tang_user_info`;
CREATE TABLE `tang_user_info` (
  `userId` int(10) unsigned NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `qq` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tang_user_info
-- ----------------------------
INSERT INTO `tang_user_info` VALUES ('1', '283109896@qq.com', '283109896@qq.com');
INSERT INTO `tang_user_info` VALUES ('2', '283109896@qq.com', '283109896@qq.com');

-- ----------------------------
-- Table structure for tang_user_sign
-- ----------------------------
DROP TABLE IF EXISTS `tang_user_sign`;
CREATE TABLE `tang_user_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tang_user_sign
-- ----------------------------

-- ----------------------------
-- Table structure for tang_user_tag
-- ----------------------------
DROP TABLE IF EXISTS `tang_user_tag`;
CREATE TABLE `tang_user_tag` (
  `userId` int(10) unsigned NOT NULL,
  `tagId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`userId`,`tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tang_user_tag
-- ----------------------------
INSERT INTO `tang_user_tag` VALUES ('1', '1');
INSERT INTO `tang_user_tag` VALUES ('1', '2');
INSERT INTO `tang_user_tag` VALUES ('1', '3');
INSERT INTO `tang_user_tag` VALUES ('2', '1');
INSERT INTO `tang_user_tag` VALUES ('2', '4');
