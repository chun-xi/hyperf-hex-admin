/*
 Navicat MySQL Data Transfer

 Source Server         : hex
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : 10.10.72.66:3306
 Source Schema         : hex

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 06/01/2020 18:04:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for hex_system_dict
-- ----------------------------
DROP TABLE IF EXISTS `hex_system_dict`;
CREATE TABLE `hex_system_dict`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字典名称',
  `code` varchar(42) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字典编号',
  `remark` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hex_system_dict
-- ----------------------------
INSERT INTO `hex_system_dict` VALUES (1, '菜单管理_权限类型', 'system_menu_type', '权限类型');
INSERT INTO `hex_system_dict` VALUES (5, '用户管理_用户状态', 'system_user_status', '用户状态');

-- ----------------------------
-- Table structure for hex_system_dict_list
-- ----------------------------
DROP TABLE IF EXISTS `hex_system_dict_list`;
CREATE TABLE `hex_system_dict_list`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '数据名称',
  `dict_id` int(11) UNSIGNED NOT NULL COMMENT '字典ID',
  `val` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '数据值',
  `status` tinyint(4) NOT NULL COMMENT '状态:0=停用,1=启用',
  `rank` smallint(6) UNSIGNED NOT NULL COMMENT '排序',
  `create_date` datetime(0) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `dict_id`(`dict_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `rank`(`rank`) USING BTREE,
  CONSTRAINT `hex_system_dict_list_ibfk_1` FOREIGN KEY (`dict_id`) REFERENCES `hex_system_dict` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hex_system_dict_list
-- ----------------------------
INSERT INTO `hex_system_dict_list` VALUES (1, '菜单', 1, '0', 1, 0, '2020-01-03 20:59:47');
INSERT INTO `hex_system_dict_list` VALUES (2, '按钮/权限', 1, '1', 1, 1, '2020-01-05 15:02:28');
INSERT INTO `hex_system_dict_list` VALUES (5, '正常', 5, '1', 1, 0, '2020-01-06 16:33:49');
INSERT INTO `hex_system_dict_list` VALUES (6, '封禁', 5, '0', 1, 0, '2020-01-06 16:33:57');

-- ----------------------------
-- Table structure for hex_system_role
-- ----------------------------
DROP TABLE IF EXISTS `hex_system_role`;
CREATE TABLE `hex_system_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `status` tinyint(4) UNSIGNED NOT NULL COMMENT '角色状态:0=停用,1=启用',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hex_system_role
-- ----------------------------
INSERT INTO `hex_system_role` VALUES (1, '超级管理员', 1);
INSERT INTO `hex_system_role` VALUES (4, '测试橘色1', 1);
INSERT INTO `hex_system_role` VALUES (5, '测试橘色2', 1);

-- ----------------------------
-- Table structure for hex_system_role_router
-- ----------------------------
DROP TABLE IF EXISTS `hex_system_role_router`;
CREATE TABLE `hex_system_role_router`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `router_id` int(11) UNSIGNED NOT NULL COMMENT '权限id',
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `router_id`(`router_id`) USING BTREE,
  INDEX `role_id`(`role_id`) USING BTREE,
  CONSTRAINT `hex_system_role_router_ibfk_1` FOREIGN KEY (`router_id`) REFERENCES `hex_system_router` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `hex_system_role_router_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `hex_system_role` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 339 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hex_system_role_router
-- ----------------------------
INSERT INTO `hex_system_role_router` VALUES (309, 5, 1);
INSERT INTO `hex_system_role_router` VALUES (310, 32, 1);
INSERT INTO `hex_system_role_router` VALUES (311, 34, 1);
INSERT INTO `hex_system_role_router` VALUES (312, 33, 1);
INSERT INTO `hex_system_role_router` VALUES (313, 4, 1);
INSERT INTO `hex_system_role_router` VALUES (314, 3, 1);
INSERT INTO `hex_system_role_router` VALUES (315, 30, 1);
INSERT INTO `hex_system_role_router` VALUES (316, 31, 1);
INSERT INTO `hex_system_role_router` VALUES (317, 17, 1);
INSERT INTO `hex_system_role_router` VALUES (318, 20, 1);
INSERT INTO `hex_system_role_router` VALUES (319, 19, 1);
INSERT INTO `hex_system_role_router` VALUES (320, 18, 1);
INSERT INTO `hex_system_role_router` VALUES (321, 9, 1);
INSERT INTO `hex_system_role_router` VALUES (322, 26, 1);
INSERT INTO `hex_system_role_router` VALUES (323, 25, 1);
INSERT INTO `hex_system_role_router` VALUES (324, 24, 1);
INSERT INTO `hex_system_role_router` VALUES (325, 23, 1);
INSERT INTO `hex_system_role_router` VALUES (326, 22, 1);
INSERT INTO `hex_system_role_router` VALUES (327, 21, 1);
INSERT INTO `hex_system_role_router` VALUES (328, 10, 1);
INSERT INTO `hex_system_role_router` VALUES (329, 7, 1);
INSERT INTO `hex_system_role_router` VALUES (330, 16, 1);
INSERT INTO `hex_system_role_router` VALUES (331, 11, 1);
INSERT INTO `hex_system_role_router` VALUES (332, 8, 1);
INSERT INTO `hex_system_role_router` VALUES (333, 6, 1);
INSERT INTO `hex_system_role_router` VALUES (334, 29, 1);
INSERT INTO `hex_system_role_router` VALUES (335, 28, 1);
INSERT INTO `hex_system_role_router` VALUES (336, 27, 1);
INSERT INTO `hex_system_role_router` VALUES (337, 1, 1);
INSERT INTO `hex_system_role_router` VALUES (338, 2, 1);

-- ----------------------------
-- Table structure for hex_system_router
-- ----------------------------
DROP TABLE IF EXISTS `hex_system_router`;
CREATE TABLE `hex_system_router`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '权限路径',
  `pid` int(11) UNSIGNED NOT NULL COMMENT '上级权限',
  `name` varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '权限名称',
  `status` tinyint(4) NOT NULL COMMENT '状态:0=停用,1=启用',
  `face` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图标',
  `type` tinyint(4) NOT NULL COMMENT '类型:0=菜单,1=路由权限/按钮',
  `rank` smallint(6) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hex_system_router
-- ----------------------------
INSERT INTO `hex_system_router` VALUES (1, 'dashboard', 0, '仪表盘', 1, 'layui-icon-home', 0, 0);
INSERT INTO `hex_system_router` VALUES (2, 'console', 1, '控制台', 1, 'layui-icon-water', 0, 0);
INSERT INTO `hex_system_router` VALUES (3, '/system/user/getMeInfo', 32, '获取自己的信息', 1, 'layui-icon-water', 1, 0);
INSERT INTO `hex_system_router` VALUES (4, '/system/user/getMenu', 32, '获取菜单列表', 1, 'layui-icon-water', 1, 0);
INSERT INTO `hex_system_router` VALUES (5, 'system', 0, '系统管理', 1, 'layui-icon-set', 0, 5);
INSERT INTO `hex_system_router` VALUES (6, 'user', 5, '用户管理', 1, NULL, 0, 0);
INSERT INTO `hex_system_router` VALUES (7, 'menu', 5, '菜单管理', 1, NULL, 0, 1);
INSERT INTO `hex_system_router` VALUES (8, '/system/menu/getMenus', 7, '获取全部菜单', 1, NULL, 1, 0);
INSERT INTO `hex_system_router` VALUES (9, 'dict', 5, '数据字典', 1, NULL, 0, 2);
INSERT INTO `hex_system_router` VALUES (10, '/system/dict/getDict', 9, '获取字典数据', 1, NULL, 1, 0);
INSERT INTO `hex_system_router` VALUES (11, '/system/menu/saveMenu', 7, '保存菜单', 1, NULL, 1, 0);
INSERT INTO `hex_system_router` VALUES (16, '/system/menu/delMenu', 7, '删除菜单', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (17, 'role', 5, '角色管理', 1, '', 0, 3);
INSERT INTO `hex_system_router` VALUES (18, '/system/role/getRoles', 17, '获取权限列表', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (19, '/system/role/saveRole', 17, '保存角色', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (20, '/system/role/delRole', 17, '删除角色', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (21, '/system/dict/getDicts', 9, '获取全部字典数据', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (22, '/system/dict/saveDict', 9, '保存数据字典', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (23, '/system/dict/delDict', 9, '删除字典', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (24, '/system/dict/list/getDictLists', 9, '获取数据列表', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (25, '/system/dict/list/saveDictValue', 9, '保存字典数据值', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (26, '/system/dict/list/delDictValue', 9, '删除字典数据', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (27, '/system/user/getUsers', 6, '获取用户列表', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (28, '/system/user/saveUser', 6, '保存用户', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (29, '/system/user/delUser', 6, '删除用户', 1, '', 1, 0);
INSERT INTO `hex_system_router` VALUES (30, 'other', 5, '其他功能', 0, NULL, 0, 0);
INSERT INTO `hex_system_router` VALUES (31, '/system/other/upload', 30, '文件上传', 1, NULL, 1, 0);
INSERT INTO `hex_system_router` VALUES (32, 'personal', 5, '个人中心', 0, NULL, 0, 0);
INSERT INTO `hex_system_router` VALUES (33, '/system/user/saveMeInfo', 32, '修改资料', 1, NULL, 1, 0);
INSERT INTO `hex_system_router` VALUES (34, '/system/user/logout', 32, '安全注销', 1, NULL, 1, 0);

-- ----------------------------
-- Table structure for hex_system_user
-- ----------------------------
DROP TABLE IF EXISTS `hex_system_user`;
CREATE TABLE `hex_system_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pass` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `face` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `phone` varchar(13) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `nickname` varchar(24) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '呢称',
  `salt` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `login_date` datetime(0) NULL DEFAULT NULL,
  `create_date` datetime(0) NOT NULL,
  `login_ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '状态:0=停用,1=启用',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user`(`user`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hex_system_user
-- ----------------------------
INSERT INTO `hex_system_user` VALUES (1, 'admin', 'ea0b83a8daeca2bcaaaae913342cdad9', '/resource/20200105/64f22b996f5c3ae.jpg', '13800138000', '没有梦想的小鱼', '57b386b112780d120f16a87b51584ef', '2020-01-05 21:11:32', '2020-01-05 21:11:26', '10.10.72.26', 1);
INSERT INTO `hex_system_user` VALUES (4, 'root', 'cce280985a4dccd4a8378d484df1cbee', NULL, NULL, NULL, 'dd4e15eefa384d9fcaf2db361d2b1fb', '2020-01-05 17:28:59', '2020-01-05 17:45:02', '10.10.72.26', 1);

-- ----------------------------
-- Table structure for hex_system_user_role
-- ----------------------------
DROP TABLE IF EXISTS `hex_system_user_role`;
CREATE TABLE `hex_system_user_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `role_id` int(11) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  INDEX `role_id`(`role_id`) USING BTREE,
  CONSTRAINT `hex_system_user_role_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `hex_system_role` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `hex_system_user_role_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `hex_system_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hex_system_user_role
-- ----------------------------
INSERT INTO `hex_system_user_role` VALUES (22, 4, 1);
INSERT INTO `hex_system_user_role` VALUES (27, 1, 1);

SET FOREIGN_KEY_CHECKS = 1;
