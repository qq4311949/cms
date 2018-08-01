/*
  jzadmin install sql

  Date: 2018-06-04 14:20:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for jz_admin
-- ----------------------------
DROP TABLE IF EXISTS `jz_admin`;
CREATE TABLE `jz_admin` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '账户',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(4) NOT NULL DEFAULT '' COMMENT '盐值',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `telephone` char(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登陆ip',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `token` varchar(255) NOT NULL DEFAULT '',
  `failure` tinyint(3) NOT NULL DEFAULT '0' COMMENT '失败次数',
  `login_at` int(10) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `create_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of jz_admin
-- ----------------------------
INSERT INTO `jz_admin` VALUES ('1', 'admin', '728db110eae61b898dd1577e77e7d474', '4118', '260591808@qq.com', '', '2130706433', '1', '51bb26f7-b300-4b9e-be27-b9a1b52a9e13', '0', '1528074035', '0', '1528074035', '');

-- ----------------------------
-- Table structure for jz_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `jz_admin_log`;
CREATE TABLE `jz_admin_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(40) NOT NULL DEFAULT '',
  `content` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `admin_id` mediumint(8) NOT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `ip` int(10) NOT NULL DEFAULT '0',
  `create_at` int(10) NOT NULL DEFAULT '0',
  `update_at` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jz_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `jz_auth_group`;
CREATE TABLE `jz_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(255) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_at` int(10) NOT NULL DEFAULT '0',
  `update_at` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jz_auth_group
-- ----------------------------
INSERT INTO `jz_auth_group` VALUES ('1', '超级管理员组', '1', '*', '该分组拥有所有权限', '1509681107', '1509681107');
INSERT INTO `jz_auth_group` VALUES ('3', '用户管理员', '1', '1,2,3,4,5,6,17', '洒点水啦', '1527230396', '1527503043');
INSERT INTO `jz_auth_group` VALUES ('6', '角色管理员', '1', '1,7,8,9,10,11', '', '1527503131', '1527503131');

-- ----------------------------
-- Table structure for jz_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `jz_auth_group_access`;
CREATE TABLE `jz_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jz_auth_group_access
-- ----------------------------
INSERT INTO `jz_auth_group_access` VALUES ('1', '1');
INSERT INTO `jz_auth_group_access` VALUES ('3', '3');
INSERT INTO `jz_auth_group_access` VALUES ('5', '3');

-- ----------------------------
-- Table structure for jz_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `jz_auth_rule`;
CREATE TABLE `jz_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `icon` varchar(50) NOT NULL DEFAULT '',
  `pid` mediumint(8) NOT NULL DEFAULT '0',
  `is_menu` tinyint(1) NOT NULL DEFAULT '0',
  `level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '层级',
  `sort` mediumint(8) NOT NULL DEFAULT '0' COMMENT '排序，从大到小',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_at` int(10) NOT NULL DEFAULT '0',
  `update_at` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jz_auth_rule
-- ----------------------------
INSERT INTO `jz_auth_rule` VALUES ('1', 'auth', '权限管理', 'am-icon-group', '0', '1', '1', '0', '1', '1', '', '', '1526796003', '1527494377');
INSERT INTO `jz_auth_rule` VALUES ('2', 'auth/user', '用户管理', 'am-icon-angle-right', '1', '1', '2', '0', '1', '1', '', '', '1526826942', '1527494404');
INSERT INTO `jz_auth_rule` VALUES ('3', 'auth/user/index', '查看', '', '2', '0', '0', '0', '1', '1', '', '', '1526874404', '1527233762');
INSERT INTO `jz_auth_rule` VALUES ('4', 'auth/user/add', '添加', '', '2', '0', '0', '0', '1', '1', '', '', '1527046278', '1527062473');
INSERT INTO `jz_auth_rule` VALUES ('5', 'auth/user/edit', '编辑', '', '2', '0', '0', '0', '1', '1', '', '', '1527046312', '1527046312');
INSERT INTO `jz_auth_rule` VALUES ('6', 'auth/user/del', '删除', '', '2', '0', '0', '0', '1', '1', '', '', '1527046336', '1527046336');
INSERT INTO `jz_auth_rule` VALUES ('7', 'auth/role', '角色管理', 'am-icon-angle-right', '1', '1', '0', '0', '1', '1', '', '', '1527056398', '1527056398');
INSERT INTO `jz_auth_rule` VALUES ('8', 'auth/role/index', '查看', '', '7', '0', '0', '0', '1', '1', '', '', '1527056549', '1527056549');
INSERT INTO `jz_auth_rule` VALUES ('9', 'auth/role/add', '添加', '', '7', '0', '0', '0', '1', '1', '', '', '1527056573', '1527057426');
INSERT INTO `jz_auth_rule` VALUES ('10', 'auth/role/edit', '编辑', '', '7', '0', '0', '0', '1', '1', '', '', '1527057898', '1527057909');
INSERT INTO `jz_auth_rule` VALUES ('11', 'auth/role/del', '删除', '', '7', '0', '0', '0', '1', '1', '', '', '1527057932', '1527057932');
INSERT INTO `jz_auth_rule` VALUES ('12', 'auth/rule', '规则管理', 'am-icon-angle-right', '1', '1', '0', '0', '1', '1', '', '', '1527057971', '1527057987');
INSERT INTO `jz_auth_rule` VALUES ('13', 'auth/rule/index', '查看', '', '12', '0', '0', '0', '1', '1', '', '', '1527058012', '1527058026');
INSERT INTO `jz_auth_rule` VALUES ('14', 'auth/rule/add', '添加', '', '12', '0', '0', '0', '1', '1', '', '', '1527056573', '1527057426');
INSERT INTO `jz_auth_rule` VALUES ('15', 'auth/rule/edit', '编辑', '', '12', '0', '0', '0', '1', '1', '', '', '1527057898', '1527057909');
INSERT INTO `jz_auth_rule` VALUES ('16', 'auth/rule/del', '删除', '', '12', '0', '0', '0', '1', '1', '', '', '1527057932', '1527057932');
INSERT INTO `jz_auth_rule` VALUES ('17', 'auth/user/muti', '批量操作', '', '2', '0', '0', '0', '1', '1', '', '', '1527125360', '1527478670');
INSERT INTO `jz_auth_rule` VALUES ('18', 'index', '首页', 'am-icon-home', '0', '1', '0', '999', '1', '1', '', '', '1527237012', '1527237137');
INSERT INTO `jz_auth_rule` VALUES ('19', 'general', '常规管理', 'am-icon-cogs', '0', '1', '1', '998', '1', '1', '', '', '1527665582', '1527665614');
INSERT INTO `jz_auth_rule` VALUES ('20', 'cms', 'CMS管理', 'am-icon-list', '0', '1', '1', '0', '1', '1', '', '', '1527758691', '1527758691');
INSERT INTO `jz_auth_rule` VALUES ('21', 'cms/archives', '内容管理', 'am-icon-angle-right', '20', '1', '2', '0', '1', '1', '', '', '1527758918', '1527758918');
INSERT INTO `jz_auth_rule` VALUES ('22', 'cms/channel', '栏目管理', 'am-icon-angle-right', '20', '1', '2', '0', '1', '1', '', '', '1527758961', '1527758961');
INSERT INTO `jz_auth_rule` VALUES ('23', 'cms/page', '单页管理', 'am-icon-angle-right', '20', '1', '2', '0', '1', '1', '', '', '1527758998', '1527758998');
INSERT INTO `jz_auth_rule` VALUES ('24', 'cms/channel/index', '查看', 'am-icon-angle-right', '22', '0', '3', '0', '1', '1', '', '', '1527819235', '1527819235');
INSERT INTO `jz_auth_rule` VALUES ('25', 'cms/channel/add', '添加', 'am-icon-angle-right', '22', '0', '3', '0', '1', '1', '', '', '1527819282', '1527819282');
INSERT INTO `jz_auth_rule` VALUES ('26', 'cms/channel/edit', '编辑', 'am-icon-angle-right', '22', '0', '3', '0', '1', '1', '', '', '1527819301', '1527819301');
INSERT INTO `jz_auth_rule` VALUES ('27', 'cms/channel/del', '删除', 'am-icon-angle-right', '22', '0', '3', '0', '1', '1', '', '', '1527819326', '1527819326');
INSERT INTO `jz_auth_rule` VALUES ('28', 'cms/lang', '语言管理', 'am-icon-angle-right', '20', '1', '2', '0', '1', '1', '', '', '1527835132', '1527835132');
INSERT INTO `jz_auth_rule` VALUES ('29', 'cms/lang/index', '查看', 'am-icon-angle-right', '28', '0', '3', '0', '1', '1', '', '', '1527835187', '1527835187');
INSERT INTO `jz_auth_rule` VALUES ('30', 'cms/lang/add', '添加', '', '28', '0', '3', '0', '1', '1', '', '', '1527835244', '1527835287');
INSERT INTO `jz_auth_rule` VALUES ('31', 'cms/lang/edit', '编辑', '', '28', '0', '3', '0', '1', '1', '', '', '1527835271', '1527835271');
INSERT INTO `jz_auth_rule` VALUES ('32', 'cms/lang/del', '删除', '', '28', '0', '3', '0', '1', '1', '', '', '1527835336', '1527835336');
INSERT INTO `jz_auth_rule` VALUES ('33', 'general/basic', '基础配置', 'am-icon-angle-right', '19', '1', '2', '0', '1', '1', '', '', '1527904581', '1527904581');
INSERT INTO `jz_auth_rule` VALUES ('34', 'general/basic/index', '查看', '', '33', '0', '3', '0', '1', '1', '', '', '1527904616', '1527904616');
INSERT INTO `jz_auth_rule` VALUES ('35', 'general/basic/add', '添加', '', '33', '0', '3', '0', '1', '1', '', '', '1527904635', '1527904635');
INSERT INTO `jz_auth_rule` VALUES ('36', 'general/basic/edit', '编辑', '', '33', '0', '3', '0', '1', '1', '', '', '1527904653', '1527904653');
INSERT INTO `jz_auth_rule` VALUES ('37', 'general/basic/del', '删除', '', '33', '0', '3', '0', '1', '1', '', '', '1527904872', '1527904872');

-- ----------------------------
-- Table structure for jz_channel
-- ----------------------------
DROP TABLE IF EXISTS `jz_channel`;
CREATE TABLE `jz_channel` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `lang_id` smallint(5) NOT NULL DEFAULT '0',
  `pid` mediumint(8) NOT NULL DEFAULT '0',
  `sort` mediumint(8) NOT NULL DEFAULT '0' COMMENT '排序，从大到小',
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'seo标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'seo关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `create_at` int(10) NOT NULL DEFAULT '0',
  `update_at` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jz_channel
-- ----------------------------
INSERT INTO `jz_channel` VALUES ('1', 'Home', '2', '0', '0', '', 'Shijiazhuang Jianliang Metal Products Co., Ltd. ', 'Hot-dip Galvanized Iron Wire, Electro Galvanized Iron Wire, Galfan Galvanization Fantastique', 'Hot-dip Galvanized Iron Wire, Electro Galvanized Iron Wire, Galfan Galvanization Fantastique', '1', '', '1527846121', '1527848435');
INSERT INTO `jz_channel` VALUES ('2', 'About Us', '2', '0', '0', '', 'About Us | Shijiazhuang Jianliang Metal Products Co., Ltd.', 'Hot-dip Galvanized Iron Wire, Electro Galvanized Iron Wire, Galfan Galvanization Fantastique', 'Hot-dip Galvanized Iron Wire, Electro Galvanized Iron Wire, Galfan Galvanization Fantastique', '1', '', '1527848521', '1527848521');

-- ----------------------------
-- Table structure for jz_lang
-- ----------------------------
DROP TABLE IF EXISTS `jz_lang`;
CREATE TABLE `jz_lang` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '英文别名',
  `sort` mediumint(8) NOT NULL DEFAULT '0' COMMENT '排序，从大到小',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `create_at` int(10) NOT NULL DEFAULT '0',
  `update_at` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jz_lang
-- ----------------------------
INSERT INTO `jz_lang` VALUES ('1', '中文', 'zh-cn', '0', '1', '', '0', '1527840295');
INSERT INTO `jz_lang` VALUES ('2', '英文', 'en', '0', '1', '', '1527841158', '1527841158');
