/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.7.14 : Database - ctt-ms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ctt-ms` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `ctt-ms`;

/*Table structure for table `area` */

DROP TABLE IF EXISTS `area`;

CREATE TABLE `area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province` varchar(20) NOT NULL COMMENT '省',
  `city` varchar(20) NOT NULL COMMENT '市',
  `district` varchar(20) NOT NULL COMMENT '区县',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

/*Data for the table `area` */

insert  into `area`(`id`,`province`,`city`,`district`) values (1,'四川','眉山','丹棱');

/*Table structure for table `auth` */

DROP TABLE IF EXISTS `auth`;

CREATE TABLE `auth` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'userid',
  `stuff_in` tinyint(1) NOT NULL DEFAULT '0' COMMENT '材料入库',
  `stuff_out` tinyint(1) DEFAULT '0' COMMENT '材料出库',
  `stuff_leave` tinyint(1) NOT NULL DEFAULT '0' COMMENT '材料调库',
  `stuff_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '材料消耗',
  `stuff_count` tinyint(1) NOT NULL DEFAULT '0' COMMENT '材料统计',
  `stuff_inventory` tinyint(1) NOT NULL DEFAULT '0' COMMENT '材料盘存',
  `tool_in` tinyint(1) NOT NULL DEFAULT '0' COMMENT '工具入库',
  `tool_out` tinyint(1) NOT NULL DEFAULT '0' COMMENT '工具出库',
  `tool_back` tinyint(1) NOT NULL DEFAULT '0' COMMENT '工具退库',
  `tool_leave` tinyint(1) NOT NULL DEFAULT '0' COMMENT '工具调库',
  `tool_count` tinyint(1) NOT NULL DEFAULT '0' COMMENT '工具统计',
  `tool_infoconsummate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '工具信息完善',
  `safty_in` tinyint(1) DEFAULT '0' COMMENT '安全品入库',
  `safty_out` tinyint(1) NOT NULL DEFAULT '0' COMMENT '安全品出库',
  `safty_back` tinyint(1) NOT NULL DEFAULT '0' COMMENT '安全品退库',
  `safty_count` tinyint(1) NOT NULL DEFAULT '0' COMMENT '安全品统计',
  `safty_infoconsummate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '安全品信息完善',
  `staff_manage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '装维人员管理',
  `user_manage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理员管理',
  `area_manage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '片区管理',
  `storehouse_manage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '仓库管理',
  `team_manage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '班组管理',
  `category_manage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '材料大类管理',
  `stuff_manage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '材料名称管理',
  `manufacturer_manage` varchar(20) NOT NULL DEFAULT '0' COMMENT '生产商管理',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `id` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `auth` */

insert  into `auth`(`uid`,`stuff_in`,`stuff_out`,`stuff_leave`,`stuff_use`,`stuff_count`,`stuff_inventory`,`tool_in`,`tool_out`,`tool_back`,`tool_leave`,`tool_count`,`tool_infoconsummate`,`safty_in`,`safty_out`,`safty_back`,`safty_count`,`safty_infoconsummate`,`staff_manage`,`user_manage`,`area_manage`,`storehouse_manage`,`team_manage`,`category_manage`,`stuff_manage`,`manufacturer_manage`) values (1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,'1'),(2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,'1'),(4,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'0');

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL COMMENT '材料大类名称',
  `stuff_source` varchar(50) NOT NULL COMMENT '材料来源',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `category` */

insert  into `category`(`id`,`category_name`,`stuff_source`) values (1,'移动材料','中国移动'),(2,'铁通材料','眉山铁通');

/*Table structure for table `inventory` */

DROP TABLE IF EXISTS `inventory`;

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stuff_in_record_id` int(11) NOT NULL COMMENT '对应的入库记录id',
  `stuff_id` int(11) NOT NULL COMMENT '对应物资名称id',
  `manufacturer` varchar(20) NOT NULL COMMENT '生产商名',
  `type` varchar(20) NOT NULL COMMENT '型号',
  `storehouse` varchar(20) NOT NULL COMMENT '仓库名称',
  `quantity` int(11) NOT NULL COMMENT '当前数量',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可用，默认1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `inventory` */

insert  into `inventory`(`id`,`stuff_in_record_id`,`stuff_id`,`manufacturer`,`type`,`storehouse`,`quantity`,`enabled`) values (1,1,2,'咪咕','爱是大海阿萨德','丹棱一库',70,1),(2,2,2,'咪咕','型号','丹棱一库',72,1);

/*Table structure for table `manufacturer` */

DROP TABLE IF EXISTS `manufacturer`;

CREATE TABLE `manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturer` varchar(20) NOT NULL COMMENT '厂商名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `manufacturer` */

insert  into `manufacturer`(`id`,`manufacturer`) values (1,'烽火'),(2,'咪咕');

/*Table structure for table `staff` */

DROP TABLE IF EXISTS `staff`;

CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '装维姓名',
  `sex` varchar(5) NOT NULL COMMENT '性别',
  `on_guard` varchar(5) NOT NULL COMMENT '是否在岗',
  `idcard` varchar(20) NOT NULL COMMENT '身份证号',
  `area` varchar(50) NOT NULL COMMENT '所在片区',
  `team` varchar(20) DEFAULT NULL COMMENT '所属班组',
  `phone` varchar(20) NOT NULL COMMENT '联系电话',
  `qq` varchar(20) DEFAULT NULL COMMENT 'qq号码',
  `sec_linkman` varchar(20) DEFAULT NULL COMMENT '第二联系人',
  `sec_phone` varchar(20) DEFAULT NULL COMMENT '第二联系人电话',
  `address` varchar(50) DEFAULT NULL COMMENT '家庭住址',
  `education` varchar(20) DEFAULT NULL COMMENT '学历',
  `school` varchar(50) DEFAULT NULL COMMENT '毕业学校',
  `operator` varchar(50) NOT NULL COMMENT '经办人',
  `employment_date` date NOT NULL COMMENT '入职时间',
  `per_pic` varchar(200) NOT NULL COMMENT '个人照片路径',
  `idcard_front_pic` varchar(200) NOT NULL COMMENT '身份证正面照路径',
  `idcard_back_pic` varchar(200) NOT NULL COMMENT '身份证背面照路径',
  `remark` text COMMENT '注释',
  `password` varchar(200) NOT NULL COMMENT '装维密码，默认值是对应的手机号',
  `cookie_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `staff` */

insert  into `staff`(`id`,`name`,`sex`,`on_guard`,`idcard`,`area`,`team`,`phone`,`qq`,`sec_linkman`,`sec_phone`,`address`,`education`,`school`,`operator`,`employment_date`,`per_pic`,`idcard_front_pic`,`idcard_back_pic`,`remark`,`password`,`cookie_name`) values (1,'张三','男','是','123123','四川^眉山^丹棱','','123123','','','','','','','超管2','2017-10-23','/CTT-MS-server\\public\\staff\\per_pic\\20171023\\8648f347dd357eb91c8c320d07e03332.jpg','/CTT-MS-server\\public\\staff\\idcard_front_pic\\20171023\\78daff7d7e8f9ac74c0fa97c5207906b.jpg','/CTT-MS-server\\public\\staff\\idcard_back_pic\\20171023\\6551b5baa846207a829a6eeb7cde55f2.jpg','备注','040bd08a4290267535cd247b8ba2eca129d9fe9f','91944e30f171db954deef0d3b218a176'),(2,'李四','男','是','123123','四川^眉山','眉山班','12345678','','','','','','','徐志雷','2017-10-23','/CTT-MS-server\\public\\staff\\per_pic\\20171023\\cec518d5d0dcc11039c22a0e59b1280e.jpg','/CTT-MS-server\\public\\staff\\idcard_front_pic\\20171023\\33ae3bc3cccc1e86ac23a64ed38986ed.jpg','/CTT-MS-server\\public\\staff\\idcard_back_pic\\20171023\\8680d5fb32aad5101385601998aa721a.jpg','','1f82ea75c5cc526729e2d581aeb3aeccfef4407e',NULL);

/*Table structure for table `storehouse` */

DROP TABLE IF EXISTS `storehouse`;

CREATE TABLE `storehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '仓库名',
  `supervisor` varchar(50) DEFAULT NULL COMMENT '仓库负责人',
  `store_address` varchar(50) DEFAULT NULL COMMENT '仓库地址',
  `area` varchar(50) NOT NULL COMMENT '所属市、区县',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `storehouse` */

insert  into `storehouse`(`id`,`name`,`supervisor`,`store_address`,`area`) values (1,'丹棱一库','徐志雷','丹棱','四川^眉山^丹棱'),(3,'眉山库','超管1','123123','四川^眉山');

/*Table structure for table `stuff` */

DROP TABLE IF EXISTS `stuff`;

CREATE TABLE `stuff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stuff_name` varchar(50) NOT NULL COMMENT '材料名称',
  `unit` varchar(20) NOT NULL COMMENT '材料单位',
  `category_name` varchar(50) NOT NULL COMMENT '材料大类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `stuff` */

insert  into `stuff`(`id`,`stuff_name`,`unit`,`category_name`) values (1,'光猫','个','移动材料'),(2,'网线','根','移动材料'),(3,'热缩管','根','铁通材料'),(4,'光猫','个','铁通材料');

/*Table structure for table `stuff_in_record` */

DROP TABLE IF EXISTS `stuff_in_record`;

CREATE TABLE `stuff_in_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stuff_id` int(11) NOT NULL COMMENT '对应stuff表的id',
  `manufacturer` varchar(20) NOT NULL COMMENT '生产商名称',
  `type` varchar(20) NOT NULL COMMENT '材料型号',
  `quantity` int(11) NOT NULL COMMENT '入库数量',
  `storehouse` varchar(20) NOT NULL COMMENT '存入仓库',
  `stuff_in_date` datetime NOT NULL COMMENT '入库时间',
  `operator` varchar(20) NOT NULL COMMENT '经办人姓名',
  `remark` text COMMENT '备注',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可用，默认为1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `stuff_in_record` */

insert  into `stuff_in_record`(`id`,`stuff_id`,`manufacturer`,`type`,`quantity`,`storehouse`,`stuff_in_date`,`operator`,`remark`,`enabled`) values (1,2,'咪咕','爱是大海阿萨德',100,'丹棱一库','2017-11-15 18:42:31','超管1','',1),(2,2,'咪咕','型号',200,'丹棱一库','2017-11-15 18:56:54','超管1','',1);

/*Table structure for table `stuff_leave_record` */

DROP TABLE IF EXISTS `stuff_leave_record`;

CREATE TABLE `stuff_leave_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL COMMENT '调拨的材料批次',
  `send_storehouse` varchar(20) NOT NULL COMMENT '调离仓库名',
  `receive_storehouse` varchar(20) NOT NULL COMMENT '接收仓库名',
  `leave_quantity` int(11) NOT NULL COMMENT '调拨数量',
  `send_operator` varchar(20) NOT NULL COMMENT '调拨经办人姓名',
  `receive_operator` varchar(20) DEFAULT NULL COMMENT '接收经办人姓名',
  `is_received` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已经确认接收',
  `send_date` date NOT NULL COMMENT '调拨日期',
  `receive_date` date DEFAULT NULL COMMENT '确认接受日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `stuff_leave_record` */

insert  into `stuff_leave_record`(`id`,`inventory_id`,`send_storehouse`,`receive_storehouse`,`leave_quantity`,`send_operator`,`receive_operator`,`is_received`,`send_date`,`receive_date`) values (1,1,'丹棱一库','丹棱一库',50,'超管1',NULL,0,'2017-11-15',NULL),(3,2,'丹棱一库','丹棱一库',100,'超管2',NULL,0,'2017-11-07',NULL),(4,2,'丹棱一库','丹棱一库',8,'超管1',NULL,0,'2017-11-15',NULL);

/*Table structure for table `stuff_out_record` */

DROP TABLE IF EXISTS `stuff_out_record`;

CREATE TABLE `stuff_out_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL COMMENT '发放材料批次',
  `storehouse` int(11) NOT NULL COMMENT '发放仓库',
  `out_quantity` int(11) NOT NULL COMMENT '发放数量',
  `staff` varchar(20) NOT NULL COMMENT '申请装维',
  `operator1` varchar(20) DEFAULT NULL COMMENT '管理员姓名',
  `operator2` varchar(20) DEFAULT NULL COMMENT '材料员姓名',
  `apply_date` date NOT NULL COMMENT '申请日期',
  `out_date` date DEFAULT NULL COMMENT '同意发放日期',
  `is_out` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未审核，1管理员通过，2管理员驳回，3材料员通过，4材料员驳回，5装维确认接收',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `stuff_out_record` */

/*Table structure for table `team` */

DROP TABLE IF EXISTS `team`;

CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '班组名称',
  `area` varchar(50) NOT NULL COMMENT '所属地区',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `team` */

insert  into `team`(`id`,`name`,`area`) values (1,'丹棱1班','四川^眉山^丹棱'),(2,'眉山班','四川^眉山');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '员工编号',
  `password` varchar(200) NOT NULL,
  `area` varchar(20) NOT NULL COMMENT '所属地区',
  `storehouse` varchar(50) DEFAULT NULL COMMENT '所属仓库',
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `sex` varchar(5) NOT NULL COMMENT '性别',
  `phone` varchar(20) DEFAULT NULL COMMENT '电话号码',
  `qq` varchar(20) DEFAULT NULL COMMENT 'qq号码',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `address` varchar(50) DEFAULT NULL COMMENT '地址',
  `idcard` varchar(20) NOT NULL COMMENT '身份证',
  `cookie_username` varchar(200) DEFAULT NULL COMMENT '经过加密后cookie中的username',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_logout_time` datetime DEFAULT NULL COMMENT '最后注销时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`area`,`storehouse`,`name`,`sex`,`phone`,`qq`,`email`,`address`,`idcard`,`cookie_username`,`last_login_time`,`last_logout_time`) values (1,'001','10470c3b4b1fed12c3baac014be15fac67c6e815','四川^眉山^丹棱','丹棱一库','超管1','男',NULL,NULL,NULL,NULL,'00001','20ffab402b6cde533d7fcca35804961a','2017-11-16 15:16:42','2017-11-16 15:16:42'),(2,'002','10470c3b4b1fed12c3baac014be15fac67c6e815','四川^眉山^丹棱','丹棱一库','超管2','男',NULL,NULL,NULL,NULL,'00001','f24b4de5f21402ef4792822168606918','2017-11-15 20:27:07','2017-11-15 20:27:07'),(4,'003','67a74306b06d0c01624fe0d0249a570f4d093747','四川^眉山','眉山库','徐志雷','男','1133213123','','','','123123123123',NULL,'2017-11-03 21:33:49','2017-11-03 21:34:56');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
