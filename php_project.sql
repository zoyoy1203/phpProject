/*
Navicat MySQL Data Transfer

Source Server         : phplink
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : php_project

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2020-01-09 17:25:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论id',
  `new_id` int(11) NOT NULL COMMENT '评论的动态id',
  `user_id` int(11) NOT NULL COMMENT '评论用户id',
  `content` varchar(255) NOT NULL COMMENT '评论内容',
  `createtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '评论时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of comment
-- ----------------------------
INSERT INTO `comment` VALUES ('53', '24', '8', '走过路过不要错过！', '2020-01-01 16:12:47');
INSERT INTO `comment` VALUES ('54', '24', '9', '看起来不错！', '2020-01-01 16:16:47');
INSERT INTO `comment` VALUES ('55', '23', '9', '味道好极了', '2020-01-01 16:16:54');
INSERT INTO `comment` VALUES ('56', '27', '11', '2020年来了哦', '2020-01-01 16:24:11');
INSERT INTO `comment` VALUES ('57', '26', '11', '是滴', '2020-01-01 16:24:18');
INSERT INTO `comment` VALUES ('58', '24', '11', '呵呵', '2020-01-01 16:24:24');
INSERT INTO `comment` VALUES ('59', '27', '7', '新年快乐！', '2020-01-01 16:25:06');
INSERT INTO `comment` VALUES ('60', '25', '7', '想去。。。只能想想', '2020-01-01 16:25:44');

-- ----------------------------
-- Table structure for friend_connection
-- ----------------------------
DROP TABLE IF EXISTS `friend_connection`;
CREATE TABLE `friend_connection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of friend_connection
-- ----------------------------
INSERT INTO `friend_connection` VALUES ('10', '8', '10');
INSERT INTO `friend_connection` VALUES ('11', '8', '11');
INSERT INTO `friend_connection` VALUES ('12', '9', '8');
INSERT INTO `friend_connection` VALUES ('13', '9', '11');
INSERT INTO `friend_connection` VALUES ('14', '7', '9');
INSERT INTO `friend_connection` VALUES ('15', '7', '11');

-- ----------------------------
-- Table structure for like_news
-- ----------------------------
DROP TABLE IF EXISTS `like_news`;
CREATE TABLE `like_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of like_news
-- ----------------------------
INSERT INTO `like_news` VALUES ('145', '24', '8');
INSERT INTO `like_news` VALUES ('148', '25', '9');
INSERT INTO `like_news` VALUES ('149', '24', '9');
INSERT INTO `like_news` VALUES ('150', '27', '11');
INSERT INTO `like_news` VALUES ('151', '26', '11');
INSERT INTO `like_news` VALUES ('152', '25', '11');
INSERT INTO `like_news` VALUES ('153', '24', '11');
INSERT INTO `like_news` VALUES ('154', '27', '7');

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '动态id',
  `user_id` int(11) NOT NULL COMMENT '发布动态的用户id',
  `content` varchar(255) DEFAULT NULL COMMENT '动态文本',
  `img` varchar(1000) DEFAULT NULL COMMENT '动态图片',
  `createtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of news
-- ----------------------------
INSERT INTO `news` VALUES ('22', '8', '朋友圈第一条动态', 'upload/newsImgs/1577865490_96657food1.jpg,upload/newsImgs/1577865490_28482food2.jpg,upload/newsImgs/1577865490_51060food3.jpg', '2020-01-01 15:58:10');
INSERT INTO `news` VALUES ('23', '8', '朋友圈第二条动态', 'upload/newsImgs/1577865516_33175food3.jpg,upload/newsImgs/1577865516_14103food4.jpg', '2020-01-01 15:58:36');
INSERT INTO `news` VALUES ('24', '8', '小吃街逛一逛~~', 'upload/newsImgs/1577865535_24688food5.jpg,upload/newsImgs/1577865535_11349food6.jpg', '2020-01-01 15:58:55');
INSERT INTO `news` VALUES ('25', '9', '夜宵，去不去！！', 'upload/newsImgs/1577866594_36807food7.jpg', '2020-01-01 16:16:34');
INSERT INTO `news` VALUES ('26', '9', '啊，天要黑了', 'upload/newsImgs/1577866686_24791mongodb4.jpg,upload/newsImgs/1577866686_35321mongodb5.jpg', '2020-01-01 16:18:06');
INSERT INTO `news` VALUES ('27', '11', '过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了过年了', 'upload/newsImgs/1577866970_24878food_bgs.png', '2020-01-01 16:22:50');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `nickname` varchar(255) NOT NULL COMMENT '用户昵称',
  `avatar` varchar(255) NOT NULL DEFAULT 'public/img/favicon.png' COMMENT '用户头像',
  `motto` varchar(255) NOT NULL DEFAULT '该用户有点懒哦!' COMMENT '座右铭',
  `createtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('7', 'zyy', '123456', 'zyy', 'upload/avatars/1577864056_92501.gif', '快些php作业！！！', '2020-01-01 15:12:34');
INSERT INTO `user` VALUES ('8', 'yy', '123456', 'yy', 'upload/avatars/1577865416_57749.png', '该用户有点懒哦!', '2020-01-01 15:52:53');
INSERT INTO `user` VALUES ('9', 'yoy', '123456', 'yoy', 'upload/avatars/1577866544_98220.jpg', '爱吃兔兔', '2020-01-01 15:54:09');
INSERT INTO `user` VALUES ('10', '小白', '123456', '小白', 'public/img/favicon.png', '该用户有点懒哦!', '2020-01-01 15:55:11');
INSERT INTO `user` VALUES ('11', 'zoyoy', '123456', 'zoyoy', 'upload/avatars/1577866788_69128.png', '要过年了啊', '2020-01-01 15:56:14');
