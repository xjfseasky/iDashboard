<?php
return [
	'title' 	=> '网点管理',
	'desc' 		=> '商品列表',
	'create' 	=> '添加商品',
	'edit' 		=> '修改商品',
	'info' 		=> '商品信息',
	'permission'=> '权限',
	'role'		=> '角色',
	'module'	=> '模块',
	'model' 	=> [
		'id' 			=> 'ID',
		'name' 			=> '商品名',
		'username' 		=> '商品类型',
		'address' 		=> '商品地址',
		'email' 		=> '邮箱',
        'created_at' 	=> '创建时间',
        'updated_at' 	=> '修改时间',
	],
	'action' => [
		'create' => '<i class="fa fa-user"></i> 添加商品',
		'yzupdate' => '<i class="fa fa-spinner"></i> 同步有赞商品',

	],
	'other_permission'	=> '<strong>注意！</strong> 当某个角色的用户需要额外权限时添加。',
	'role_info'			=> '查看角色权限',
];