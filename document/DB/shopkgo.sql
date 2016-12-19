#生成数据库
CREATE DATABASE shopkgo DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

#使用数据库
use shopkgo;

-- #用户列表
-- create table users(
-- 	id bigint not null auto_increment primary key,
-- 	username varchar(50) not null COMMENT "用户昵称",
-- 	account varchar(50) not null COMMENT "账号", 
-- 	password varchar(100) not null COMMENT "用户密码",
-- 	type tinyint(2) DEFAULT 1 COMMENT "用户类型：1.普通账号,2:管理员账号",
-- 	level tinyint(2) DEFAULT 1 COMMENT "用户级别： 1.普通账号,2.合作vip账号，3.vvip账号",
-- 	cdkynumb int(10) DEFAULT 0 COMMENT "可使用cdky数量",
-- 	cdkynumbed int(10) DEFAULT 0 COMMENT "已使用cdky数量",
-- 	cdkytnumb int(10) DEFAULT 0 COMMENT "可使用体验数量",
-- 	cdkytnumbed int(10) DEFAULT 0 COMMENT "已使用体验数量",
-- 	status tinyint(2) DEFAULT 1 COMMENT "状态：0.未使用 1.正常， 2.禁用",
-- 	umoney decimal(10,2) DEFAULT '0.00' COMMENT "账号余额",
-- 	totalmoney decimal(10,2) DEFAULT '0.00' COMMENT "充值总金额",
-- 	ctime datetime not null COMMENT "创建时间"
-- )engine=innodb DEFAULT charset=utf8;

#网点管理
create table shops(
	id bigint not null auto_increment primary key,
	shoplv  tinyint(1) not null  COMMENT "店铺级别 0:总店，1:分店",
	parentid bigint not null  COMMENT "总店id 0:总店自己",
	youzan_id bigint not null  COMMENT "有赞店铺id",
	yz_kdt_id bigint not null  COMMENT "有赞店铺 kdt_id",
	name varchar(50) not null COMMENT "店铺名称",
	is_store tinyint(1) not null DEFAULT 1 COMMENT "是否是门店",
	is_self_fetch tinyint(1) not null DEFAULT 1 COMMENT "是否是自提点",
	phone1 int(13) not null COMMENT "电话1",
	phone2 int(13) not null COMMENT "电话2",
	province varchar(50) not null COMMENT "省",
	city varchar(50) not null COMMENT "市",
	area varchar(50) not null COMMENT "地区",
	address varchar(300) not null COMMENT "店铺地址",
	county_id int(11) not null COMMENT "县id",
	lng varchar(50) not null COMMENT "经纬度",
	lat varchar(50) not null COMMENT "经纬度",
	status tinyint(1) not null DEFAULT 1 COMMENT "1:正常， 2:关闭",
	created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT "创建时间",
  	updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT "更新时间" ON UPDATE CURRENT_TIMESTAMP,
  	UNIQUE KEY `yzid` (`youzan_id`,`yz_kdt_id`)
)engine=innodb DEFAULT charset=utf8;
-- 商品列表
create table goods(
	id bigint not null auto_increment primary key,
	yznum_iid bigint not null  COMMENT "有赞商品id",
	type tinyint(1)  not null DEFAULT 1 COMMENT "商品类型。0：普通商品； 10：分销商品",
	name varchar(50) not null COMMENT "商品名称",
	num int(13) not null COMMENT "商品数量",
	price decimal(10, 2) DEFAULT '0.00' not null COMMENT "商品价格",
	is_listing tinyint(1) not null DEFAULT 1 COMMENT "1 为已上架，2 为已下架",
	outer_buy_url varchar(100) not null COMMENT "购买地址",
	status tinyint(1) not null DEFAULT 1 COMMENT "1:正常， 2:关闭",
	created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT "创建时间",
  	updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT "更新时间" ON UPDATE CURRENT_TIMESTAMP,
  	UNIQUE KEY `yznum_iid` (`yznum_iid`)
)engine=innodb DEFAULT charset=utf8;

#网点sku管理
create table shopsku(
	id bigint not null auto_increment primary key,
	shop_id bigint not null  COMMENT "店铺 id",
	youzan_id bigint not null  COMMENT "有赞店铺id",
	goods_id bigint not null  COMMENT "商品 id",
	yz_num_iid  bigint not null COMMENT "有赞商品id",
	yz_sku_id bigint not null COMMENT "有赞商品skuid",
	type tinyint(1)  not null DEFAULT 1 COMMENT "商品类型。1：普通商品； 2：分销商品",
	name varchar(50) COMMENT "商品名称",
	is_listing tinyint(1) not null DEFAULT 2 COMMENT "1 为已上架，2 为已下架",
	outer_buy_url varchar(100) COMMENT "购买地址",
	settings tinyint(1)  not null DEFAULT 1 COMMENT "配送方式：1:快递, 2:同城送, 3自提",
	num int(13) not null DEFAULT 0 COMMENT "商品数量",
	price decimal(10, 2) DEFAULT '0.00' not null COMMENT "商品价格",
	created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT "创建时间",
  	updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT "更新时间" ON UPDATE CURRENT_TIMESTAMP,
  	UNIQUE KEY `yz_sku_id` (`youzan_id`, `yz_num_iid`, `yz_sku_id`)
)engine=innodb DEFAULT charset=utf8;


create table orders(
	id bigint not null auto_increment primary key,
	name varchar(50) not null COMMENT "店铺名称",
	consign_time datetime  COMMENT "卖家发货时间",
	buyer_area
	num
	adjust_fee
	relation_type
	type
	yzbuyer_id
	yztid
	feedback
	outer_user_id
	price
	total_fee
	payment
	weixin_user_id
	sub_trades
	delivery_time_display
	buyer_message
	created
	pay_time
	shop_id
	out_trade_no
	points_price
	tuan_no
	orders
	promotion_details
	refund_state
	status
	post_fee
	pic_thumb_path
	receiver_city
	shipping_type
	refunded_fee
	yznum_iid
	title
	discount_fee
	hotel_info
	receiver_state
	update_time
	coupon_details
	receiver_zip
	receiver_name
	pay_type
	profit
	fans_info
	fetch_detail
	buyer_type
	receiver_district
	pic_path
	receiver_mobile
	sign_time
	seller_flag
	buyer_nick
	handled
	receiver_address
	trade_memo
	relations
	outer_tid
)engine=innodb DEFAULT charset=utf8;

#商品管理
create table goods(
	id bigint not null auto_increment primary key,

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
