/*
 Navicat MySQL Data Transfer

 Source Server         : 167.179.101.150-hex
 Source Server Type    : MySQL
 Source Server Version : 80017
 Source Host           : 167.179.101.150:3306
 Source Schema         : hex

 Target Server Type    : MySQL
 Target Server Version : 80017
 File Encoding         : 65001

 Date: 08/01/2020 20:49:21
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for hex_system_config
-- ----------------------------
DROP TABLE IF EXISTS `hex_system_config`;
CREATE TABLE `hex_system_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(42) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '键',
  `name` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配置名称',
  `options` json NOT NULL COMMENT '配置选项',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
