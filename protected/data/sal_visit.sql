/*
Navicat MySQL Data Transfer

Source Server         : ldb
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : salesdev

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-01-13 17:59:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sal_visit`
-- ----------------------------
DROP TABLE IF EXISTS `sal_visit`;
CREATE TABLE `sal_visit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `visit_dt` datetime NOT NULL,
  `visit_type` int(10) unsigned NOT NULL,
  `visit_obj` varchar(100) NOT NULL,
  `quotation` varchar(11) NOT NULL DEFAULT '否',
  `service_type` varchar(100) NOT NULL,
  `cust_type` int(10) unsigned NOT NULL,
  `cust_name` varchar(255) NOT NULL,
  `cust_alt_name` varchar(255) DEFAULT NULL,
  `cust_person` varchar(255) DEFAULT NULL,
  `cust_person_role` varchar(255) DEFAULT NULL,
  `cust_tel` varchar(50) DEFAULT NULL,
  `district` int(10) unsigned NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `remarks` varchar(5000) DEFAULT NULL,
  `shift` char(1) DEFAULT NULL COMMENT 'Y为转移的',
  `status` char(1) NOT NULL DEFAULT 'N',
  `status_dt` datetime DEFAULT NULL,
  `city` char(5) NOT NULL,
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_visit_01` (`city`,`username`),
  KEY `idx_visit_02` (`username`,`visit_dt`,`city`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sal_visit
-- ----------------------------
INSERT INTO `sal_visit` VALUES ('3', 'test1', '2020-01-06 00:00:00', '1', '[\"1\",\"10\"]', '是', '', '4', '陳記', '', '陳生', '老闆', '212122121', '37', 'XXXX', 'TTTXXXXX', 'Y', 'Y', null, 'SZ', 'testuser', 'testuser', '2018-04-20 16:43:05', '2021-01-13 15:45:10');
INSERT INTO `sal_visit` VALUES ('4', 'test2', '2020-01-06 00:00:00', '2', '[\"3\",\"10\"]', '是', '', '17', '我们这一家', '', '张先森', '总经理', '123456', '221', '翠景路', '', 'Y', 'Y', null, 'SZ', 'VivienneChen', 'VivienneChen', '2018-04-20 17:01:03', '2021-01-13 15:45:10');
INSERT INTO `sal_visit` VALUES ('5', 'test2', '2020-01-06 00:00:00', '1', '[\"1\",\"10\"]', '是', '', '19', '上井料理', '', '小瓜', '', '1313131313', '5', '大悦城', '测试', 'Z', 'Y', null, 'SZ', 'kittyzhou', 'kittyzhou', '2018-04-20 17:41:15', '2021-01-13 15:45:10');
INSERT INTO `sal_visit` VALUES ('6', 'test2', '2020-01-06 00:00:00', '1', '[\"10\"]', '否', '', '2', '泰兴药业', '', '姚生', '', '1242233345', '215', '', '', null, 'Y', null, 'SZ', 'JoeY', 'JoeY', '2018-04-21 11:54:58', '2020-01-09 15:25:44');
INSERT INTO `sal_visit` VALUES ('7', 'VivienneChen', '2018-04-24 00:00:00', '1', '[\"2\"]', '否', '', '1', '表妹火锅', '', '你老板', '经理', '', '160', '', '', null, 'Y', null, 'NN', 'VivienneChen', 'VivienneChen', '2018-04-24 14:06:11', '2018-04-24 14:06:11');
INSERT INTO `sal_visit` VALUES ('8', 'kittyzhou', '2018-04-25 00:00:00', '1', '[\"2\",\"4\"]', '否', '', '36', '啦啦', '', '1', '', '1236547821', '17', '', '测试', null, 'Y', null, 'CN', 'kittyzhou', 'kittyzhou', '2018-04-25 15:47:27', '2018-04-25 15:47:27');
INSERT INTO `sal_visit` VALUES ('10', 'testuser', '2018-05-21 00:00:00', '1', '[\"1\",\"10\"]', '是', '', '25', 'Chan Tai Man Company', '', 'Chan Tai Man', '', '1263634634', '54', '', '', null, 'Y', null, 'SH', 'testuser', 'testuser', '2018-05-21 18:44:36', '2021-01-13 15:45:10');
INSERT INTO `sal_visit` VALUES ('11', 'testuser', '2018-05-23 00:00:00', '1', '[\"1\",\"10\"]', '是', '', '25', 'Chan Tai Man Company', '', 'Chan Tai Man', '', '1263634634', '54', '', '', null, 'Y', null, 'SH', 'testuser', 'testuser', '2018-05-23 12:28:43', '2021-01-13 15:45:10');
INSERT INTO `sal_visit` VALUES ('12', 'VivienneChen', '2018-05-25 00:00:00', '1', '[\"1\"]', '否', '', '23', '一小段', '', '', '', '', '9', '', '', null, 'Y', null, 'CD', 'VivienneChen', 'VivienneChen', '2018-05-25 10:07:08', '2018-05-25 10:07:08');
INSERT INTO `sal_visit` VALUES ('13', 'testmgr', '2018-05-25 00:00:00', '1', '[\"1\",\"10\"]', '否', '', '25', 'Chan Tai Man', '', '', '', '', '71', '', '', null, 'Y', null, 'HD', 'testmgr', 'testmgr', '2018-05-25 16:45:16', '2018-05-25 16:45:16');
INSERT INTO `sal_visit` VALUES ('14', 'test', '2018-04-25 00:00:00', '2', '[\"8\",\"10\"]', '否', '', '5', '一点点', '', '王祥生', '', '1223232313', '153', '', '', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-05-25 17:58:24', '2020-11-11 09:12:44');
INSERT INTO `sal_visit` VALUES ('15', 'test', '2018-03-25 00:00:00', '4', '[\"10\"]', '否', '', '7', '一小段', '', '', '', '', '216', '', '', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-05-25 17:59:11', '2020-11-11 09:13:07');
INSERT INTO `sal_visit` VALUES ('16', 'JoeY', '2018-05-25 00:00:00', '2', '[\"10\"]', '否', '', '5', '一点点', '', '王祥生', '', '1223232313', '153', '', '测试', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-05-25 18:01:34', '2018-05-25 18:01:34');
INSERT INTO `sal_visit` VALUES ('17', 'JoeY', '2018-05-25 00:00:00', '3', '[\"10\"]', '否', '', '4', '翠华', '', '张祥生', '经理', '12321321323213', '23', '', '', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-05-25 23:29:03', '2018-05-25 23:29:03');
INSERT INTO `sal_visit` VALUES ('18', 'JoeY', '2018-05-26 00:00:00', '3', '[\"10\"]', '否', '', '15', '太兴餐厅', '', '陈胜', '总监', '132131232132132', '193', '', '', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-05-26 22:37:52', '2018-05-26 22:37:52');
INSERT INTO `sal_visit` VALUES ('19', 'JoeY', '2018-05-28 00:00:00', '3', '[\"10\"]', '否', '', '22', '同珍酱油', '', '黄生', '经理', '134232323', '216', '', '', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-05-28 14:28:38', '2018-05-28 14:28:38');
INSERT INTO `sal_visit` VALUES ('20', 'JoeY', '2018-05-28 00:00:00', '1', '[\"11\"]', '否', '', '41', '瑞罗空气', '', '罗生', '副总', '123232323', '224', '', '测试', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-05-28 17:39:44', '2018-05-28 17:39:44');
INSERT INTO `sal_visit` VALUES ('21', 'VivienneChen', '2018-05-28 00:00:00', '2', '[\"10\"]', '否', '', '4', '再来一次', '', '郝经理', '', '123456', '168', '', '', null, 'Y', null, 'FZ', 'VivienneChen', 'VivienneChen', '2018-05-28 17:43:10', '2018-05-28 17:43:10');
INSERT INTO `sal_visit` VALUES ('22', 'kittyzhou', '2018-05-28 00:00:00', '2', '[\"10\",\"11\"]', '否', '', '19', '上井料理', '', '小瓜', '', '1313131313', '19', '大悦城', '', null, 'Y', null, 'BJ', 'kittyzhou', 'kittyzhou', '2018-05-28 18:34:14', '2018-05-28 18:46:38');
INSERT INTO `sal_visit` VALUES ('23', 'Chris', '2018-05-28 00:00:00', '2', '[\"4\"]', '否', '', '16', '58同城', '', '春雨里', '副经理', '1021392323', '98', '', '', null, 'Y', null, 'CQ', 'Chris', 'Chris', '2018-05-28 22:55:46', '2018-05-28 22:55:46');
INSERT INTO `sal_visit` VALUES ('24', 'Chris', '2018-05-28 00:00:00', '3', '[\"10\"]', '否', '', '17', '太兴', '', '童童总', '副经理', '123213213213', '110', '', '', null, 'Y', null, 'CQ', 'Chris', 'Chris', '2018-05-28 22:58:17', '2018-05-28 22:58:17');
INSERT INTO `sal_visit` VALUES ('25', 'HZMGR1', '2018-05-28 00:00:00', '2', '[\"10\"]', '否', '', '4', '台山硬料', '', '写生', '经理', '1232323232', '72', '', '', null, 'Y', null, 'HZ', 'HZMGR1', 'HZMGR1', '2018-05-28 23:40:29', '2018-05-28 23:40:29');
INSERT INTO `sal_visit` VALUES ('26', 'kittyzhou', '2018-05-29 00:00:00', '2', '[\"10\"]', '否', '', '1', '火锅', '', '', '', '', '31', '', '', null, 'Y', null, 'BJ', 'kittyzhou', 'kittyzhou', '2018-05-29 09:54:30', '2018-05-29 09:54:30');
INSERT INTO `sal_visit` VALUES ('27', 'kittyzhou', '2018-05-29 00:00:00', '3', '[\"10\"]', '否', '', '6', '喵喵喵', '', '', '', '', '35', '', '', null, 'Y', null, 'BJ', 'kittyzhou', 'kittyzhou', '2018-05-29 10:12:02', '2018-05-29 10:12:02');
INSERT INTO `sal_visit` VALUES ('28', 'VivienneChen', '2018-05-29 00:00:00', '2', '[\"10\"]', '否', '', '24', '我又來了', '', '', '', '', '172', '', '', null, 'Y', null, 'FZ', 'VivienneChen', 'VivienneChen', '2018-05-29 10:16:16', '2018-05-29 10:16:16');
INSERT INTO `sal_visit` VALUES ('29', 'VivienneChen', '2018-06-08 00:00:00', '2', '[\"10\"]', '否', '', '6', '我又来了', '', '', '', '', '172', '', '', null, 'Y', null, 'FZ', 'VivienneChen', 'VivienneChen', '2018-06-08 17:02:23', '2018-06-08 17:02:23');
INSERT INTO `sal_visit` VALUES ('30', 'VivienneChen', '2018-06-08 00:00:00', '2', '[\"3\"]', '否', '', '1', '真好吃', '', '', '', '', '172', '', '', null, 'Y', null, 'FZ', 'VivienneChen', 'VivienneChen', '2018-06-08 17:11:35', '2018-06-08 17:11:35');
INSERT INTO `sal_visit` VALUES ('31', 'kittyzhou', '2018-06-08 00:00:00', '1', '[\"10\"]', '否', '', '1', '犇犇', '', '', '', '', '23', '', '', null, 'Y', null, 'BJ', 'kittyzhou', 'kittyzhou', '2018-06-08 17:21:26', '2018-06-08 17:21:26');
INSERT INTO `sal_visit` VALUES ('32', 'VivienneChen', '2018-06-08 00:00:00', '2', '[\"10\"]', '否', '', '1', '真好吃', '', '', '', '', '172', '', '', null, 'Y', null, 'FZ', 'VivienneChen', 'VivienneChen', '2018-06-08 17:22:46', '2018-06-08 17:22:46');
INSERT INTO `sal_visit` VALUES ('33', 'JoeY', '2018-06-10 00:00:00', '1', '[\"2\"]', '否', '', '15', '太兴餐厅', '', '陈胜', '总监', '132131232132132', '193', '', '', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-06-10 13:09:22', '2018-06-10 13:09:22');
INSERT INTO `sal_visit` VALUES ('34', 'JoeY', '2018-06-10 00:00:00', '1', '[\"10\"]', '否', '', '15', '翠华茶餐厅', '', '康生', '', '143434434343', '121', '', '', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-06-10 13:13:21', '2018-06-10 13:13:21');
INSERT INTO `sal_visit` VALUES ('35', 'JoeY', '2018-06-10 00:00:00', '2', '[\"10\"]', '否', '', '15', '翠华茶餐厅（上下九路分店）', '', '前生', '总监', '1343432323', '23', '', '', null, 'Y', null, 'CN', 'JoeY', 'JoeY', '2018-06-10 13:15:24', '2018-06-10 13:15:24');
INSERT INTO `sal_visit` VALUES ('36', 'HZMGR1', '2018-06-11 00:00:00', '2', '[\"11\"]', '否', '', '5', '大大公司', '', '东升', '经理', '143343242343', '69', '', '', null, 'Y', null, 'HZ', 'HZMGR1', 'HZMGR1', '2018-06-11 21:20:01', '2018-06-11 21:20:01');
INSERT INTO `sal_visit` VALUES ('37', 'VivienneChen', '2018-06-12 00:00:00', '2', '[\"11\"]', '否', '', '15', '我来了', '', '', '', '', '172', '', '', null, 'Y', null, 'FZ', 'VivienneChen', 'VivienneChen', '2018-06-12 14:39:53', '2018-06-12 14:39:53');
INSERT INTO `sal_visit` VALUES ('38', 'VivienneChen', '2018-06-12 00:00:00', '4', '[\"10\"]', '否', '', '1', '真好吃', '', '', '', '', '172', '', '', null, 'Y', null, 'FZ', 'VivienneChen', 'VivienneChen', '2018-06-12 14:41:25', '2018-06-12 14:41:25');
INSERT INTO `sal_visit` VALUES ('39', 'VivienneChen', '2018-06-12 00:00:00', '2', '[\"10\"]', '否', '', '17', '我们这一家', '', '张先森', '总经理', '123456', '172', '翠景路', '', null, 'Y', null, 'FZ', 'VivienneChen', 'VivienneChen', '2018-06-12 14:44:54', '2018-06-12 14:44:54');
INSERT INTO `sal_visit` VALUES ('40', 'VivienneChen', '2018-06-12 00:00:00', '2', '[\"11\"]', '否', '', '24', '我又來了', '', '', '', '', '172', '', '', null, 'Y', null, 'FZ', 'VivienneChen', 'VivienneChen', '2018-06-12 14:46:34', '2018-06-12 14:46:34');
INSERT INTO `sal_visit` VALUES ('41', 'kittyzhou', '2018-06-13 00:00:00', '1', '[\"2\"]', '否', '', '5', '汪汪汪', '', '', '', '', '93', '', '', null, 'Y', null, 'CN', 'kittyzhou', 'kittyzhou', '2018-06-13 14:30:30', '2018-06-13 14:30:30');
INSERT INTO `sal_visit` VALUES ('42', 'kittyzhou', '2018-06-13 00:00:00', '1', '[\"10\"]', '否', '', '4', '喵喵喵喵', '', '', '', '', '19', '', '', null, 'Y', null, 'CN', 'kittyzhou', 'kittyzhou', '2018-06-13 14:32:21', '2018-06-13 14:32:21');
INSERT INTO `sal_visit` VALUES ('50', 'test', '2021-01-05 00:00:00', '1', '[\"1\",\"2\",\"3\"]', '否', '[\"1\",\"2\",\"3\"]', '17', '111', null, '11', '', '', '85', '', '', null, 'Y', null, 'SH', 'test', 'test', '2021-01-05 10:39:10', '2021-01-05 10:41:43');
INSERT INTO `sal_visit` VALUES ('51', 'test', '2021-01-11 00:00:00', '1', '[\"2\"]', '否', '[\"2\"]', '17', '111', null, '11', '', '', '85', '', '', null, 'Y', null, 'SH', 'test', 'test', '2021-01-11 17:50:42', '2021-01-11 17:50:42');
INSERT INTO `sal_visit` VALUES ('52', 'test', '2021-01-11 00:00:00', '2', '[\"2\"]', '否', '[\"2\"]', '7', '11', null, '', '', '', '44', '', '', null, 'Y', null, 'SH', 'test', 'test', '2021-01-11 17:51:24', '2021-01-11 17:51:24');
INSERT INTO `sal_visit` VALUES ('53', 'test', '2021-01-11 00:00:00', '2', '[\"1\"]', '否', '[\"1\"]', '17', '111', null, '11', '', '', '85', '', '', null, 'Y', null, 'SH', 'test', 'test', '2021-01-11 17:55:08', '2021-01-11 17:55:08');
INSERT INTO `sal_visit` VALUES ('54', 'test', '2021-01-12 00:00:00', '1', '[\"1\",\"2\"]', '否', '[\"1\",\"2\"]', '17', '111', null, '11', '', '', '85', '', '', null, 'Y', null, 'SH', 'test', 'test', '2021-01-12 16:12:15', '2021-01-13 15:41:24');
INSERT INTO `sal_visit` VALUES ('55', 'test', '2021-01-12 00:00:00', '1', '[\"1\",\"9\"]', '否', '[\"1\",\"9\"]', '17', '111', null, '11', '', '', '85', '', '', null, 'Y', null, 'SH', 'test', 'test', '2021-01-12 16:13:52', '2021-01-12 16:13:52');
INSERT INTO `sal_visit` VALUES ('56', 'test', '2021-01-12 00:00:00', '2', '[\"1\",\"4\",\"5\"]', '否', '[\"1\"]', '17', '111', null, '11', '', '', '85', '', '', null, 'Y', null, 'SH', 'test', 'test', '2021-01-12 16:15:11', '2021-01-12 16:15:11');
INSERT INTO `sal_visit` VALUES ('57', 'test', '2021-01-12 00:00:00', '1', '[\"1\",\"3\",\"5\",\"6\"]', '否', '[\"1\"]', '15', '2', null, '11', '', '', '79', '', '', null, 'Y', null, 'SH', 'test', 'test', '2021-01-12 16:17:13', '2021-01-12 16:17:13');
