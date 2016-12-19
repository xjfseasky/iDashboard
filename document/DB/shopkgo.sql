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
	buyer_area  varchar(50) not null COMMENT "买家下单的地区",
	num varchar(50) not null COMMENT "商品购买数量",
	adjust_fee varchar(50) not null COMMENT "json:订单改价, 总改价金额, 邮费改价",
	relation_type varchar(50) not null COMMENT "分销/采购单",
	type varchar(50) not null COMMENT "交易类型",
	yzbuyer_id varchar(50) not null COMMENT "有赞买家ID",
	yztid varchar(50) not null COMMENT "有赞交易编号",
	feedback varchar(50) not null COMMENT "交易维权状态",
	outer_user_id varchar(50) not null COMMENT "三方APP用户id",
	price varchar(50) not null COMMENT "商品价格",
	total_fee varchar(50) not null COMMENT "商品总价",
	payment varchar(50) not null COMMENT "实付金额",
	weixin_user_id varchar(50) not null COMMENT "微信粉丝ID",
	sub_trades varchar(50) not null COMMENT "交易中包含的子交易",
	delivery_time_display varchar(50) not null COMMENT "同城送订单送达时间",
	buyer_message varchar(50) not null COMMENT "买家购买附言",
	created varchar(50) not null COMMENT "交易创建时间",
	pay_time varchar(50) not null COMMENT "买家付款时间",
	shop_id varchar(50) not null COMMENT "多门店订单的门店id 非多门店订单则默认为0",
	out_trade_no varchar(50) not null COMMENT "代付订单外部交易号列表,非代付订单类型返回空",
	points_price varchar(50) not null COMMENT "积分兑换订单，数值代表消耗的积分 非积分兑换订单默认为0",
	tuan_no varchar(50) not null COMMENT "拼团订单对应的团编号",
	orders varchar(50) not null COMMENT "交易明细列表",
	promotion_details varchar(50) not null COMMENT "在交易中使用到优惠活动详情，包括：满减满送",
	refund_state varchar(50) not null COMMENT "退款状态NO_REFUND（无退款）PARTIAL_REFUNDING（部分退款中）PARTIAL_REFUNDED（已部分退款）PARTIAL_REFUND_FAILED（部分退款失败）FULL_REFUNDING（全额退款中）FULL_REFUNDED（已全额退款）FULL_REFUND_FAILED（全额退款失败",
	status varchar(50) not null COMMENT "交易状态",
	post_fee varchar(50) not null COMMENT "运费",
	pic_thumb_path varchar(50) not null COMMENT "商品主图片缩略图地址",
	receiver_city varchar(50) not null COMMENT "收货人的所在城市",
	shipping_type varchar(50) not null COMMENT "创建交易时的物流方式",
	refunded_fee varchar(50) not null COMMENT "交易完成后退款的金额",
	yznum_iid varchar(50) not null COMMENT "有赞商品数字编号",
	title varchar(50) not null COMMENT "店铺名称",
	discount_fee varchar(50) not null COMMENT "店铺名称",
	hotel_info varchar(50) not null COMMENT "店铺名称",
	receiver_state varchar(50) not null COMMENT "店铺名称",
	update_time varchar(50) not null COMMENT "店铺名称",
	coupon_details varchar(50) not null COMMENT "店铺名称",
	receiver_zip varchar(50) not null COMMENT "店铺名称",
	receiver_name varchar(50) not null COMMENT "店铺名称",
	pay_type varchar(50) not null COMMENT "店铺名称",
	profit varchar(50) not null COMMENT "店铺名称",
	fans_info varchar(50) not null COMMENT "店铺名称",
	fetch_detail varchar(50) not null COMMENT "店铺名称",
	buyer_type varchar(50) not null COMMENT "店铺名称",
	receiver_district varchar(50) not null COMMENT "店铺名称",
	pic_path varchar(50) not null COMMENT "店铺名称",
	receiver_mobile varchar(50) not null COMMENT "店铺名称",
	sign_time varchar(50) not null COMMENT "店铺名称",
	seller_flag varchar(50) not null COMMENT "店铺名称",
	buyer_nick varchar(50) not null COMMENT "店铺名称",
	handled varchar(50) not null COMMENT "店铺名称",
	receiver_address varchar(50) not null COMMENT "店铺名称",
	trade_memo varchar(50) not null COMMENT "店铺名称",
	relations varchar(50) not null COMMENT "店铺名称",
	outer_tid varchar(50) not null COMMENT "店铺名称",
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
