/*
 Navicat MySQL Data Transfer

 Source Server         : mongopayDB
 Source Server Type    : MySQL
 Source Server Version : 50562
 Source Host           : localhost:3306
 Source Schema         : bbs_test

 Target Server Type    : MySQL
 Target Server Version : 50562
 File Encoding         : 65001

 Date: 20/05/2019 17:18:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for novel_content
-- ----------------------------
DROP TABLE IF EXISTS `novel_content`;
CREATE TABLE `novel_content`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `novel_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `list_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `chapter` int(11) NOT NULL DEFAULT 0,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `err_flag` tinyint(1) UNSIGNED NOT NULL,
  `add_time` datetime NOT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `list_id_index`(`list_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for novel_list
-- ----------------------------
DROP TABLE IF EXISTS `novel_list`;
CREATE TABLE `novel_list`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `novel_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `chapter_num` int(10) UNSIGNED NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `flag` tinyint(1) UNSIGNED NOT NULL,
  `err_flag` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `add_time` datetime NOT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for novel_main
-- ----------------------------
DROP TABLE IF EXISTS `novel_main`;
CREATE TABLE `novel_main`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `base_url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `list_url` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `novel_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '小说状态：1列表已抓取  3等待抓取列表',
  `insert_date` datetime NOT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  `delete_flag` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
