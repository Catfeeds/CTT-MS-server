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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `area` */

insert  into `area`(`id`,`province`,`city`,`district`) values (1,'四川','眉山','丹棱'),(2,'四川','眉山','东坡'),(3,'四川','眉山','洪雅'),(4,'四川','眉山','彭山'),(5,'四川','眉山','仁寿'),(6,'四川','眉山','青神');

/*Table structure for table `auth` */

DROP TABLE IF EXISTS `auth`;

CREATE TABLE `auth` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'userid',
  `stuff_in` tinyint(1) NOT NULL DEFAULT '0' COMMENT '材料入库',
  `stuff_review` tinyint(1) NOT NULL DEFAULT '0' COMMENT '材料管理审核',
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
  `manufacturer_manage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '生产商管理',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `id` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `auth` */

insert  into `auth`(`uid`,`stuff_in`,`stuff_review`,`stuff_out`,`stuff_leave`,`stuff_use`,`stuff_count`,`stuff_inventory`,`tool_in`,`tool_out`,`tool_back`,`tool_leave`,`tool_count`,`tool_infoconsummate`,`safty_in`,`safty_out`,`safty_back`,`safty_count`,`safty_infoconsummate`,`staff_manage`,`user_manage`,`area_manage`,`storehouse_manage`,`team_manage`,`category_manage`,`stuff_manage`,`manufacturer_manage`) values (1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),(2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),(4,1,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1),(7,1,1,1,1,0,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1),(8,1,1,1,1,0,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1);

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Data for the table `inventory` */

insert  into `inventory`(`id`,`stuff_in_record_id`,`stuff_id`,`manufacturer`,`type`,`storehouse`,`quantity`,`enabled`) values (1,1,1,'烽火','分光器-8口','丹棱库',35,1),(2,2,1,'中兴','ZXHN F663','丹棱库',400,1),(3,3,7,'其他','无','丹棱库',50,1),(4,4,3,'烽火','1*305米','丹棱库',30000,1),(5,5,2,'光谷','60MM','丹棱库',5000,1),(6,6,7,'其他','无','丹棱库',50,1),(7,7,3,'烽火','1*305米','丹棱库',9000,1),(8,8,11,'烽火','无','丹棱库',20000,1),(9,9,9,'其他','无','丹棱库',5000,1),(10,10,10,'品胜','P30标签打印纸（25-75）','丹棱库',30,1),(11,11,10,'品胜','P30标签打印纸（02F-200）','丹棱库',80,1),(12,12,16,'其他','无','丹棱库',10000,1),(13,13,17,'其他','无','丹棱库',150,1),(14,14,14,'其他','无','丹棱库',200,1);

/*Table structure for table `manufacturer` */

DROP TABLE IF EXISTS `manufacturer`;

CREATE TABLE `manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturer` varchar(20) NOT NULL COMMENT '厂商名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `manufacturer` */

insert  into `manufacturer`(`id`,`manufacturer`) values (1,'烽火'),(2,'光谷'),(3,'其他'),(4,'中兴'),(5,'品胜');

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `staff` */

insert  into `staff`(`id`,`name`,`sex`,`on_guard`,`idcard`,`area`,`team`,`phone`,`qq`,`sec_linkman`,`sec_phone`,`address`,`education`,`school`,`operator`,`employment_date`,`per_pic`,`idcard_front_pic`,`idcard_back_pic`,`remark`,`password`,`cookie_name`) values (1,'祝丹','男','是','513825199009152452','四川^眉山^丹棱','城区','15328754615','','','','','','','黄科鑫','2016-06-01','/CTT-MS-server\\public\\staff\\per_pic\\20171219\\2c10caafd6e90600d38b3592df3ca816.jpg','/CTT-MS-server\\public\\staff\\idcard_front_pic\\20171219\\a0855eae977a1bf9f0d77b7669400b46.jpg','/CTT-MS-server\\public\\staff\\idcard_back_pic\\20171219\\de658f8f5a62e510250db46518e809d7.jpg','','7396ab1e988e6b9cff98b3954e28d1d041df7265',NULL),(2,'王虎强','男','是','51382419910915271x','四川^眉山^丹棱','乡镇-仁双张','15282334487','','','','','','','黄科鑫','2016-06-01','/CTT-MS-server\\public\\staff\\per_pic\\20171219\\860bdae837c2834928e5df44ceceff50.jpg','/CTT-MS-server\\public\\staff\\idcard_front_pic\\20171219\\3f9ab9bde5cd9801183fa5ff55b78932.jpg','/CTT-MS-server\\public\\staff\\idcard_back_pic\\20171219\\13fef70d7c3caea068246fb052cb72da.jpg','','34813a4821dde24084462877597095490277cfb2',NULL),(3,'邓吉桥','男','是','513825198608244231','四川^眉山^丹棱','乡镇-仁双张','13990386661','','','','','','','黄科鑫','2016-06-01','/CTT-MS-server\\public\\staff\\per_pic\\20171219\\f2155028b873d7fdb82e4f1bc189f61f.jpg','/CTT-MS-server\\public\\staff\\idcard_front_pic\\20171219\\71fdbf42b52054c903c24917f69690d0.jpg','/CTT-MS-server\\public\\staff\\idcard_back_pic\\20171219\\33ca0094e837ba4c70e85a3c6c2c3928.jpg','','9f0f37878ef2848ea9ca923de646c80e7d9f670e',NULL),(4,'苏涛','男','是','111111111111111111','四川^眉山^丹棱','城区','13795544435','','','','','','','黄科鑫','2016-06-01','/CTT-MS-server\\public\\staff\\per_pic\\20171219\\56a577bfa461e7b60fe8ac297b960993.jpg','/CTT-MS-server\\public\\staff\\idcard_front_pic\\20171219\\1498f0154d6ebca3e71481293a86b846.jpg','/CTT-MS-server\\public\\staff\\idcard_back_pic\\20171219\\1ff9dc922d4a249a58844d2fe4ee94fb.jpg','','10470c3b4b1fed12c3baac014be15fac67c6e815','2ef470c9f5301764f208a65e0c333184');

/*Table structure for table `storehouse` */

DROP TABLE IF EXISTS `storehouse`;

CREATE TABLE `storehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '仓库名',
  `supervisor` varchar(50) DEFAULT NULL COMMENT '仓库负责人',
  `store_address` varchar(50) DEFAULT NULL COMMENT '仓库地址',
  `area` varchar(50) NOT NULL COMMENT '所属市、区县',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `storehouse` */

insert  into `storehouse`(`id`,`name`,`supervisor`,`store_address`,`area`) values (1,'东坡库','龙科良','东坡服务中心','四川^眉山^东坡'),(2,'丹棱库','黄科鑫','眉山丹棱','四川^眉山^丹棱'),(3,'市公司库','苏涛','东坡区东坡湖广场','四川^眉山'),(4,'彭山库','莫奇','彭山经营部','四川^眉山^彭山'),(5,'洪雅库','秦谌磊','洪雅经营部','四川^眉山^洪雅');

/*Table structure for table `stuff` */

DROP TABLE IF EXISTS `stuff`;

CREATE TABLE `stuff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stuff_name` varchar(50) NOT NULL COMMENT '材料名称',
  `unit` varchar(20) NOT NULL COMMENT '材料单位',
  `category_name` varchar(50) NOT NULL COMMENT '材料大类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

/*Data for the table `stuff` */

insert  into `stuff`(`id`,`stuff_name`,`unit`,`category_name`) values (1,'光猫','台','移动材料'),(2,'热缩管','根','铁通材料'),(3,'网线','米','移动材料'),(5,'铝芯线','圈','铁通材料'),(8,'RJ45水晶头','颗','铁通材料'),(6,'分光器-8口','个','移动材料'),(7,'绝缘粘胶带','个','铁通材料'),(9,'热缩保护盒','根','铁通材料'),(10,'P30标签打印纸','盒','铁通材料'),(11,'普通皮纤','米','移动材料'),(12,'隐形皮纤','米','铁通材料'),(13,'摄像头','台','移动材料'),(14,'电话机','台','移动材料'),(15,'卡钉25#','颗','铁通材料'),(16,'塑料扎带','根','铁通材料'),(17,'业务开通确认单','本','铁通材料'),(18,'宽带服务贴','张','铁通材料'),(19,'PT-E100标签纸','盒','铁通材料'),(20,'垃圾袋','个','铁通材料');

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Data for the table `stuff_in_record` */

insert  into `stuff_in_record`(`id`,`stuff_id`,`manufacturer`,`type`,`quantity`,`storehouse`,`stuff_in_date`,`operator`,`remark`,`enabled`) values (1,1,'烽火','分光器-8口',35,'丹棱库','2017-12-19 15:28:08','黄科鑫','',1),(2,1,'中兴','ZXHN F663',400,'丹棱库','2017-12-19 15:37:27','黄科鑫','',1),(3,7,'其他','无',50,'丹棱库','2017-12-19 15:39:23','黄科鑫','',1),(4,3,'烽火','1*305米',30000,'丹棱库','2017-12-19 16:07:15','黄科鑫','',1),(5,2,'光谷','60MM',5000,'丹棱库','2017-12-19 16:17:15','黄科鑫','',1),(6,7,'其他','无',50,'丹棱库','2017-12-19 16:18:30','黄科鑫','',1),(7,3,'烽火','1*305米',9000,'丹棱库','2017-12-19 16:19:13','黄科鑫','',1),(8,11,'烽火','无',20000,'丹棱库','2017-12-19 16:20:08','黄科鑫','',1),(9,9,'其他','无',5000,'丹棱库','2017-12-19 16:20:56','黄科鑫','',1),(10,10,'品胜','P30标签打印纸（25-75）',30,'丹棱库','2017-12-19 16:41:15','黄科鑫','',1),(11,10,'品胜','P30标签打印纸（02F-200）',80,'丹棱库','2017-12-19 16:41:55','黄科鑫','',1),(12,16,'其他','无',10000,'丹棱库','2017-12-19 16:42:33','黄科鑫','',1),(13,17,'其他','无',150,'丹棱库','2017-12-19 16:43:16','黄科鑫','',1),(14,14,'其他','无',200,'丹棱库','2017-12-19 17:10:23','黄科鑫','',1);

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `stuff_leave_record` */

/*Table structure for table `stuff_out_record` */

DROP TABLE IF EXISTS `stuff_out_record`;

CREATE TABLE `stuff_out_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL COMMENT '发放材料批次',
  `storehouse` varchar(20) NOT NULL COMMENT '发放仓库',
  `out_quantity` int(11) NOT NULL COMMENT '发放数量',
  `odd_quantity` int(11) NOT NULL DEFAULT '0' COMMENT '相同材料的剩余',
  `staff` varchar(20) NOT NULL COMMENT '申请装维',
  `operator1` varchar(20) DEFAULT NULL COMMENT '管理员姓名',
  `operator2` varchar(20) DEFAULT NULL COMMENT '材料员姓名',
  `apply_date` date NOT NULL COMMENT '申请日期',
  `out_date` date DEFAULT NULL COMMENT '同意发放日期',
  `is_out` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未审核，1管理员通过，2管理员驳回，3材料员通过，4材料员驳回，5装维确认接收',
  `remark` text COMMENT '退回原因等',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

/*Data for the table `stuff_out_record` */

insert  into `stuff_out_record`(`id`,`inventory_id`,`storehouse`,`out_quantity`,`odd_quantity`,`staff`,`operator1`,`operator2`,`apply_date`,`out_date`,`is_out`,`remark`) values (1,1,'丹棱库',2,0,'苏涛','黄科鑫',NULL,'2017-12-19',NULL,1,NULL),(2,4,'丹棱库',300,18,'苏涛','黄科鑫',NULL,'2017-12-19',NULL,1,NULL),(3,8,'丹棱库',1000,160,'苏涛','黄科鑫',NULL,'2017-12-19',NULL,1,NULL),(4,14,'丹棱库',5,0,'苏涛',NULL,NULL,'2017-12-19',NULL,2,'装一个领一个'),(5,5,'丹棱库',50,12,'苏涛','黄科鑫',NULL,'2017-12-19',NULL,1,NULL),(6,3,'丹棱库',20,0,'苏涛',NULL,NULL,'2017-12-19',NULL,0,NULL),(7,10,'丹棱库',2,0,'苏涛',NULL,NULL,'2017-12-19',NULL,0,NULL),(8,11,'丹棱库',3,0,'苏涛',NULL,NULL,'2017-12-19',NULL,0,NULL),(9,12,'丹棱库',200,0,'苏涛',NULL,NULL,'2017-12-19',NULL,0,NULL);

/*Table structure for table `team` */

DROP TABLE IF EXISTS `team`;

CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '班组名称',
  `area` varchar(50) NOT NULL COMMENT '所属地区',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `team` */

insert  into `team`(`id`,`name`,`area`) values (3,'乡镇','四川^眉山^丹棱'),(2,'城区','四川^眉山^丹棱'),(4,'城区-石桥','四川^眉山^丹棱'),(5,'乡镇-仁双张','四川^眉山^丹棱'),(6,'乡镇-杨石顺','四川^眉山^丹棱'),(7,'城区-机动','四川^眉山^丹棱'),(8,'线路班','四川^眉山^丹棱');

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`area`,`storehouse`,`name`,`sex`,`phone`,`qq`,`email`,`address`,`idcard`,`cookie_username`,`last_login_time`,`last_logout_time`) values (1,'001','10470c3b4b1fed12c3baac014be15fac67c6e815','四川^眉山','市公司库','苏涛','男','13795544435',NULL,NULL,NULL,'00001','29033d834a1c9a87387e870cd697cb87','2017-12-19 18:04:53','2017-12-19 18:04:53'),(2,'002','10470c3b4b1fed12c3baac014be15fac67c6e815','四川^眉山^东坡','东坡库','龙科良','男',NULL,NULL,NULL,NULL,'00001',NULL,'2017-12-27 16:54:08','2017-12-27 16:55:45'),(4,'003','10470c3b4b1fed12c3baac014be15fac67c6e815','四川^眉山^洪雅','','秦谌磊','男','1133213123','','','','123123123123',NULL,'2017-12-15 13:24:04','2017-12-15 13:25:04'),(7,'004','10470c3b4b1fed12c3baac014be15fac67c6e815','四川^眉山^彭山','彭山库','莫奇','男','13890348066','','','','13890348066',NULL,NULL,NULL),(8,'005','10470c3b4b1fed12c3baac014be15fac67c6e815','四川^眉山^丹棱','丹棱库','黄科鑫','男','15883300091','','','','15883300091','9c6f308a757a33718f71a6a4611730e7','2017-12-27 16:55:51','2017-12-27 16:55:51');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
