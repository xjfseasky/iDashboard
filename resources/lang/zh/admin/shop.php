<?php
return [
	'title' 	=> '网点管理',
	'desc' 		=> '店铺列表',
	'create' 	=> '添加店铺',
	'edit' 		=> '修改店铺',
	'info' 		=> '网点信息',
	'permission'=> '权限',
	'role'		=> '角色',
	'module'	=> '模块',
	'model' 	=> [
		'id' 			=> 'ID',
		'name' 			=> '店铺名',
		'username' 		=> '店铺类型',
		'address' 		=> '店铺地址',
		'email' 		=> '邮箱',
        'created_at' 	=> '创建时间',
        'updated_at' 	=> '修改时间',
	],
	'action' => [
		'create' => '<i class="fa fa-user"></i> 添加网点',
		'yzupdate' => '<i class="fa fa-spinner"></i> 同步有赞网点',

	],
	'other_permission'	=> '<strong>注意！</strong> 当某个角色的用户需要额外权限时添加。',
	'role_info'			=> '查看角色权限',
];