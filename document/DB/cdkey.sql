#生成数据库
CREATE DATABASE shopkgo DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

#使用数据库
use ecdkey;

#用户列表
create table users(
	id bigint not null auto_increment primary key,
	username varchar(50) not null COMMENT "用户昵称",
	account varchar(50) not null COMMENT "账号", 
	password varchar(100) not null COMMENT "用户密码",
	type tinyint(2) DEFAULT 1 COMMENT "用户类型：1.普通账号,2:管理员账号",
	level tinyint(2) DEFAULT 1 COMMENT "用户级别： 1.普通账号,2.合作vip账号，3.vvip账号",
	cdkynumb int(10) DEFAULT 0 COMMENT "可使用cdky数量",
	cdkynumbed int(10) DEFAULT 0 COMMENT "已使用cdky数量",
	cdkytnumb int(10) DEFAULT 0 COMMENT "可使用体验数量",
	cdkytnumbed int(10) DEFAULT 0 COMMENT "已使用体验数量",
	status tinyint(2) DEFAULT 1 COMMENT "状态：0.未使用 1.正常， 2.禁用",
	umoney decimal(10,2) DEFAULT '0.00' COMMENT "账号余额",
	totalmoney decimal(10,2) DEFAULT '0.00' COMMENT "充值总金额",
	ctime datetime not null COMMENT "创建时间"
)engine=innodb DEFAULT charset=utf8;

#菜单列表
create table menus(
	id bigint not null auto_increment primary key,
	mname varchar(100) not null COMMENT "菜单名称",
	mcode varchar(20)  not null COMMENT "菜单code",
	mpath varchar(100) not null  COMMENT "菜单路径",
	ctime datetime not null  COMMENT "创建时间" 
)engine=innodb DEFAULT charset=utf8;


#充值记录表
drop table paymoney;
create table paymoneyrecode(
	id bigint not null auto_increment primary key,
	order_number varchar(100) not null COMMENT "订单号",
	user_id varchar(100) not null COMMENT "用户id",
	opera_id varchar(20)  not null COMMENT "操作人id",
	act_type tinyint(1) not null COMMENT "事件类型： 1:充值，2:消费",
	umoney varchar(100) not null  COMMENT "充值金额",
	pay_type tinyint(1) not null COMMENT "充值方式：1。线下， 2.微信，3.支付宝",
	trad_order_number varchar(100) COMMENT "第三方订单号",
	pay_time datetime COMMENT "支付完成时间",
	oldmoney varchar(100) not null COMMENT "历史金额",
	newmoney varchar(100) not null COMMENT "当前金额",
	remark varchar(3000) not null COMMENT "备注",
	ctime datetime not null  COMMENT "创建时间" 
)engine=innodb DEFAULT charset=utf8;

#用户留言
/**
create table messages(
	id bigint not null auto_increment primary key,
	user_id bigint not null COMMENT "用户id",
	subject varchar(100) not null COMMENT "标题",
	context varchar(300) not null COMMENT "内容",
)engine=innodb DEFAULT charset=utf8;
*/

# cdkey 列表
create table cdkeys(
	id bigint not null auto_increment primary key,
	user_id bigint not null COMMENT"用户id",
	user_name varchar(100) not null COMMENT"用户账号",
	data varchar(300) not null COMMENT "cdkey",
	company varchar(300) not null COMMENT "公司名称",
	mac varchar(300) not null COMMENT "绑定mac地址",
	seatnum varchar(300) not null COMMENT "座位数",
	acttime datetime not null COMMENT "有效时间",
	type tinyint(2) not null COMMENT "cdkey 类型: 1.体验， 2.授权位置, 3:授权时间",
	status tinyint(2) DEFAULT 1 COMMENT "状态: 1.未使用 2.正常",
	ctime datetime not null COMMENT "创建时间"
)engine=innodb DEFAULT charset=utf8;

alter table cdkeys add `user_name` varchar(50) not null COMMENT "用户账号" after `user_id`;


#system 系统设置
create table systems(
	id bigint not null auto_increment primary key,
	ukey varchar(50) not null COMMENT"键",
	uval varchar(3000) not null COMMENT "值",
	ctime datetime not null COMMENT "创建时间"
)engine=innodb DEFAULT charset=utf8;

#软件版本包管理
create table version(
	id bigint not null auto_increment primary key,
	version varchar(20) not null  COMMENT"版本名称",
	type tinyint(2) not null COMMENT '类型(1:服务器版本 , 2:必装软件 , 3:App )',
	edesc text not null  COMMENT "更新功能介绍",
	durl varchar(200) not null  COMMENT "下载地址",
	ubook_url varchar(200) not null  COMMENT "说明书下载地址",
	ctime datetime not null COMMENT "生成时间"
)engine=innodb DEFAULT charset=utf8;
