/*
SQLyog Ultimate v12.5.0 (64 bit)
MySQL - 5.7.20 : Database - wenx
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `wx_aliyun_sms` */

DROP TABLE IF EXISTS `wx_aliyun_sms`;

CREATE TABLE `wx_aliyun_sms` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `accessKeyId` varchar(30) DEFAULT NULL COMMENT '阿里短信accessKeyId',
  `accessKeySecret` varchar(50) DEFAULT NULL COMMENT '阿里短信accessKeySecret',
  `SignName` varchar(20) DEFAULT NULL COMMENT '阿里短信签名',
  `TemplateCode` varchar(30) DEFAULT NULL COMMENT '阿里短信模板Code',
  `TemplateParam` varchar(30) DEFAULT NULL COMMENT '模板参数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `wx_aliyun_sms` */

insert  into `wx_aliyun_sms`(`id`,`accessKeyId`,`accessKeySecret`,`SignName`,`TemplateCode`,`TemplateParam`) values 
(1,'LTAIcBEu6T83LuMM','zJPwNcOyDqOUPwd2V5PfMtSVTvPEzk','WENX','SMS_134940077','password');

/*Table structure for table `wx_article` */

DROP TABLE IF EXISTS `wx_article`;

CREATE TABLE `wx_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `user_id` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `title` varchar(120) NOT NULL DEFAULT '' COMMENT '标题',
  `picture` varchar(225) NOT NULL DEFAULT '' COMMENT '标题图片',
  `keywords` varchar(120) NOT NULL DEFAULT '' COMMENT '关键字',
  `summary` varchar(255) NOT NULL COMMENT '简介',
  `content` mediumtext NOT NULL COMMENT '内容',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `onclick` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击',
  `create_time` int(10) unsigned NOT NULL COMMENT '发布时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `isgood` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '推荐（1，推荐）',
  `istop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '置顶',
  PRIMARY KEY (`id`),
  KEY `status` (`id`,`status`),
  KEY `catid` (`id`,`cate_id`,`status`),
  KEY `listorder` (`id`,`cate_id`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `wx_article` */

insert  into `wx_article`(`id`,`cate_id`,`user_id`,`username`,`title`,`picture`,`keywords`,`summary`,`content`,`status`,`onclick`,`create_time`,`update_time`,`isgood`,`istop`) values 
(11,4,0,'','测试数据','/uploads/images/20180519\\408c6bd35bc64a18d5651a8b6c625ebd.jpg','测试数据','测试数据','&lt;p&gt;测试数据测试数据测试数据测试数据测试数据测试数据测试数据测试数据测试数据测试数据测试数据&lt;/p&gt;',1,0,1526692926,1526692927,0,0),
(12,4,0,'','测试','','测试','','&lt;p&gt;测试&lt;/p&gt;',1,0,1526636014,1526636014,1,1),
(13,4,0,'','最新文章','','最新文章','','&lt;p&gt;最新文章最新文章&lt;/p&gt;',1,0,1526636114,1526636114,1,0),
(14,4,0,'','测试文章','/uploads/images/20180519\\55db55bc0840e9b1fef733e9dbcad0f6.jpg','测试文章','测试文章测试文章','&lt;p&gt;测试文章测试文章测试文章测试文章测试文章测试文章测试文章测试文章测试文章测试文章测试文章测试文章测试文章测试文章测试文章&lt;/p&gt;',1,0,1526952311,1526693116,0,0),
(15,4,0,'','测试','','测试','测试','',1,0,2018,1526693330,0,0),
(16,4,0,'','成都市','','成都市','成都市','&lt;p&gt;成都市&lt;/p&gt;',1,0,2018,1526693452,0,0),
(17,4,0,'','测试','','测试','测试','&lt;p&gt;测试&lt;/p&gt;',0,0,1526693976,0,0,0),
(20,4,0,'','测试','','测试','测试','&lt;p&gt;测试&lt;/p&gt;',1,0,1526780459,1526718703,1,0),
(21,4,0,'','测试','','测试','测试','&lt;p&gt;测试&lt;/p&gt;',1,0,1527609600,1526718756,0,0),
(26,4,1,'admin','测试','','测试','测试','&lt;p&gt;测试&lt;/p&gt;',1,0,1526695216,1526718871,1,1);

/*Table structure for table `wx_category` */

DROP TABLE IF EXISTS `wx_category`;

CREATE TABLE `wx_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '栏目名',
  `bname` varchar(50) NOT NULL COMMENT '别名',
  `navdir` varchar(30) NOT NULL COMMENT '栏目目录',
  `pid` smallint(5) NOT NULL COMMENT '父栏目id',
  `mid` tinyint(3) unsigned NOT NULL COMMENT '模型id',
  `images` varchar(255) NOT NULL COMMENT '栏目图片',
  `title` varchar(100) NOT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL COMMENT 'SEO关键字',
  `description` varchar(255) NOT NULL COMMENT 'SEO描述',
  `is_show` tinyint(1) unsigned NOT NULL COMMENT '是否显示首页（1，显示；0，不显示）',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `islast` tinyint(1) unsigned NOT NULL COMMENT '是否终极栏目（1，是；0，不是）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `wx_category` */

insert  into `wx_category`(`id`,`name`,`bname`,`navdir`,`pid`,`mid`,`images`,`title`,`keywords`,`description`,`is_show`,`sort`,`islast`) values 
(1,'文章','文章','',0,1,'','','文章','文章',1,1,0),
(2,'留言','留言','',0,1,'','','留言','留言',1,2,1),
(3,'微语','微语','',0,1,'','','微语','微语',1,3,1),
(4,'文章分类1','文章分类2','',1,1,'/uploads/images/20180517\\6781f693a15a084d8432dfb30a14d029.png','','文章分类3','文章分类1',1,2,1),
(6,'er','erere','',0,1,'','','erer','ere',1,4,0),
(7,'ere','rerer','',6,1,'','','erer','ere',0,5,1),
(8,'34','3434','',0,1,'','','343','4343',1,7,1);

/*Table structure for table `wx_mail` */

DROP TABLE IF EXISTS `wx_mail`;

CREATE TABLE `wx_mail` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `smtphost` varchar(30) DEFAULT NULL COMMENT 'SMTP服务器',
  `smtpport` varchar(20) DEFAULT NULL COMMENT 'SMTP端口',
  `fromemail` varchar(50) DEFAULT NULL COMMENT '发信人地址',
  `emailname` varchar(20) DEFAULT NULL COMMENT '发信人呢称',
  `emailusername` varchar(30) DEFAULT NULL COMMENT '邮箱登录用户名',
  `emailpassword` varchar(30) DEFAULT NULL COMMENT '邮箱登录密码',
  `emailtitle` varchar(50) DEFAULT NULL COMMENT '邮件标题',
  `emailcontent` text COMMENT '邮件内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `wx_mail` */

insert  into `wx_mail`(`id`,`smtphost`,`smtpport`,`fromemail`,`emailname`,`emailusername`,`emailpassword`,`emailtitle`,`emailcontent`) values 
(1,'smtp.163.com','465','m15917697915@163.com','文先生','m15917697915@163.com','wenxian19940424','您好！这是我的邮件标题。','您好！这是我的邮件内容。更多内容请关注我们...。');

/*Table structure for table `wx_member` */

DROP TABLE IF EXISTS `wx_member`;

CREATE TABLE `wx_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `pwdkey` char(8) NOT NULL COMMENT '密码随机字符串',
  `group_id` int(5) DEFAULT NULL COMMENT '会员组,表wx_member_group',
  `nickname` varchar(20) DEFAULT NULL COMMENT '昵称',
  `header_pic` varchar(255) DEFAULT NULL COMMENT '头像',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `create_time` int(10) DEFAULT NULL COMMENT '注册时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `last_login_time` varchar(10) DEFAULT NULL COMMENT '最后登陆时间',
  `last_login_ip` varchar(50) DEFAULT NULL COMMENT '最后登录IP',
  `login_num` int(11) DEFAULT NULL COMMENT '登录次数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态（1:正常,0:禁止,2:审核中）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `wx_member` */

insert  into `wx_member`(`id`,`username`,`password`,`pwdkey`,`group_id`,`nickname`,`header_pic`,`email`,`phone`,`create_time`,`update_time`,`last_login_time`,`last_login_ip`,`login_num`,`status`) values 
(1,'123456','123456','123456',1,'第一个',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1);

/*Table structure for table `wx_member_group` */

DROP TABLE IF EXISTS `wx_member_group`;

CREATE TABLE `wx_member_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `group_name` varchar(20) NOT NULL COMMENT '会员组ID',
  `level` int(2) NOT NULL DEFAULT '0' COMMENT '等级',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `wx_member_group` */

insert  into `wx_member_group`(`id`,`group_name`,`level`,`create_time`,`update_time`) values 
(1,'普通会员',0,NULL,NULL);

/*Table structure for table `wx_model` */

DROP TABLE IF EXISTS `wx_model`;

CREATE TABLE `wx_model` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) DEFAULT NULL COMMENT '模型名称',
  `href` varchar(50) DEFAULT NULL COMMENT '控制器/方法',
  `dataname` varchar(30) DEFAULT NULL COMMENT '数据表名（去前缀）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `wx_model` */

insert  into `wx_model`(`id`,`title`,`href`,`dataname`) values 
(1,'文章模型','article/index','article'),
(2,'新闻模型','news/index','news');

/*Table structure for table `wx_power` */

DROP TABLE IF EXISTS `wx_power`;

CREATE TABLE `wx_power` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '权限名称',
  `icon` varchar(20) DEFAULT NULL COMMENT '菜单图标',
  `href` varchar(50) DEFAULT NULL COMMENT '控制器/方法',
  `pid` smallint(5) unsigned DEFAULT NULL COMMENT '父ID',
  `sort` smallint(5) unsigned DEFAULT NULL COMMENT '排序',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '权限状态（1，正常；0，禁用）',
  `menustatus` tinyint(1) unsigned DEFAULT NULL COMMENT '菜单状态（1，显示；0，不显示）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `wx_power` */

insert  into `wx_power`(`id`,`name`,`icon`,`href`,`pid`,`sort`,`create_time`,`status`,`menustatus`) values 
(1,'系统设置','','System/index',0,50,NULL,0,1),
(2,'系统参数设置','','System/index',1,50,NULL,1,1),
(3,'邮件设置','','Mail/index',1,50,NULL,1,1),
(4,'短信设置','','Sms/index',1,50,NULL,1,1),
(5,'用户管理','','User/index',0,50,NULL,0,1),
(6,'用户列表','','User/index',5,50,NULL,1,1),
(7,'添加用户','','User/add',6,50,NULL,1,0),
(8,'编辑用户','','User/edit',6,50,NULL,1,0),
(9,'修改状态','','User/status',6,50,NULL,1,0),
(10,'删除用户','','User/del',6,50,NULL,1,0),
(11,'权限列表','','Power/index',5,50,NULL,1,1),
(12,'添加权限','','Power/add',11,50,NULL,1,0),
(13,'编辑权限','','Power/edit',11,50,NULL,1,0),
(14,'权限状态','','Power/status',11,50,NULL,1,0),
(15,'菜单状态','','Power/menustatus',11,50,NULL,1,0),
(16,'删除权限','','Power/del',11,50,NULL,1,0),
(17,'角色列表','','Role/index',5,50,NULL,1,1),
(18,'添加角色','','Role/add',17,50,NULL,1,0),
(19,'编辑角色','','Role/edit',17,50,NULL,1,0),
(20,'角色状态','','Role/status',17,50,NULL,1,0),
(21,'删除角色','','Role/del',17,50,NULL,1,0);

/*Table structure for table `wx_role` */

DROP TABLE IF EXISTS `wx_role`;

CREATE TABLE `wx_role` (
  `role_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '名称',
  `power` text COMMENT '权限',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态（1，正常；0，禁用）',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `wx_role` */

insert  into `wx_role`(`role_id`,`name`,`power`,`status`) values 
(1,'超级管理员',NULL,1),
(2,'管理员',NULL,1),
(3,'测试','',0);

/*Table structure for table `wx_system` */

DROP TABLE IF EXISTS `wx_system`;

CREATE TABLE `wx_system` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '网站名称',
  `keywords` varchar(100) DEFAULT NULL COMMENT '网站关键字',
  `describe` varchar(255) DEFAULT NULL COMMENT '网站描述',
  `logo` varchar(255) DEFAULT NULL COMMENT '网站logo',
  `siteurl` varchar(30) DEFAULT NULL COMMENT '网站网址',
  `email` varchar(50) DEFAULT NULL COMMENT '管理员邮箱',
  `imgtype` varchar(100) DEFAULT NULL COMMENT '上传图片扩展名',
  `imgsize` int(10) unsigned DEFAULT NULL COMMENT '上传图片大小',
  `filetype` varchar(100) DEFAULT NULL COMMENT '上传文件扩展名',
  `filesize` int(10) unsigned DEFAULT NULL COMMENT '上传文件大小',
  `openmark` tinyint(1) unsigned DEFAULT NULL COMMENT '开启水印（1，开启；0，关闭）',
  `markpos` tinyint(1) unsigned DEFAULT NULL COMMENT '水印位置',
  `markpath` varchar(255) DEFAULT NULL COMMENT '水印图片路径',
  `markquality` tinyint(3) unsigned DEFAULT NULL COMMENT '图像质量',
  `markpct` tinyint(3) unsigned DEFAULT NULL COMMENT '水印透明度',
  `marktext` varchar(30) DEFAULT NULL COMMENT '文字内容',
  `markfont` varchar(255) DEFAULT NULL COMMENT '文字字体',
  `markfontcolor` varchar(20) DEFAULT NULL COMMENT '文字颜色',
  `markfontsize` tinyint(3) DEFAULT NULL COMMENT '文字大小',
  `markfontoffset` smallint(5) DEFAULT NULL COMMENT '文字相对当前位置的偏移量',
  `markfontangle` tinyint(3) DEFAULT NULL COMMENT '文字倾斜角度',
  `banip` text COMMENT '禁止ip访问',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `wx_system` */

insert  into `wx_system`(`id`,`name`,`keywords`,`describe`,`logo`,`siteurl`,`email`,`imgtype`,`imgsize`,`filetype`,`filesize`,`openmark`,`markpos`,`markpath`,`markquality`,`markpct`,`marktext`,`markfont`,`markfontcolor`,`markfontsize`,`markfontoffset`,`markfontangle`,`banip`) values 
(1,'WX后台管理1','WX后台管理1','WX后台管理1','/uploads/images/20180519\\d77d1e6b8be437a51ebc6a46b44c20f1.jpg',NULL,'1262596703@qq.com','png,jpg',2048,'zip,rar,ttf',5120,0,9,'/uploads/images/20180519\\78606ee5f450d8c3dac6da5096861d46.jpg',80,100,'文','/uploads/file/20180514\\5d23a9e19a1f5307e7c563be5bd0f1fa.ttf','#000000',24,-20,-15,'127.0.0.1\n127.0.0.1');

/*Table structure for table `wx_user` */

DROP TABLE IF EXISTS `wx_user`;

CREATE TABLE `wx_user` (
  `user_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL COMMENT '用户名',
  `truename` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `password` varchar(50) DEFAULT NULL COMMENT '密码',
  `pwdkey` char(8) DEFAULT NULL COMMENT '密码随机字符串',
  `role_id` smallint(5) unsigned DEFAULT NULL COMMENT '角色ID',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `last_login_time` int(10) unsigned DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(50) DEFAULT NULL COMMENT '最后登录ip',
  `login_num` smallint(8) unsigned DEFAULT NULL COMMENT '登录次数',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态（1，正常；0，禁止）',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `wx_user` */

insert  into `wx_user`(`user_id`,`username`,`truename`,`password`,`pwdkey`,`role_id`,`create_time`,`update_time`,`last_login_time`,`last_login_ip`,`login_num`,`status`) values 
(1,'admin','admin','562ff6f5ed17798b7b9eef46c6fe7b0e','nB0}F;zm',1,159888888,1526434822,1526695206,'127.0.0.1',7,1),
(2,'wenwen','wenwen','123456','123456',1,159888888,159888888,159888888,'127.0.0.1',1,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
