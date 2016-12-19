<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
	 return Redirect::to('/admin/dash');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware' => ['auth']],function ($router)
{
	$router->get('/dash','DashboardController@index')->name('system.index');
	$router->get('/i18n', 'DashboardController@dataTableI18n');
	// 权限
	require(__DIR__ . '/admin/permission.php');
	// 角色
	require(__DIR__ . '/admin/role.php');
	// 用户
	require(__DIR__ . '/admin/user.php');
	// 菜单
	require(__DIR__ . '/admin/menu.php');
	//网点管理
	$router->any('/shop/list', 'ShopsController@index')->name('shop.list');
	$router->any('/shop/ajaxIndex', 'ShopsController@ajaxIndex')->name('shop.lists');
	$router->any('/shop/yzshopget', 'ShopsController@yzshopget')->name('shop.yzshopget');
	$router->any('/shop/shopsku/{shop_id}', 'ShopsController@shopsku')->name('shop.shopsku');
	// 商品sku
	$router->any('/shop/yzshopskuget/{shop_id}', 'ShopsController@yzshopskuget')->name('shop.yzshopskuget');
	// 商品配送方式
	$router->any('/shop/yzpeisongget/{shop_id}', 'ShopsController@yzpeisongget')->name('shop.yzpeisongget');


	// 商品sku -all 
	$router->any('/shop/yzshopskugetall', 'ShopsController@yzshopskugetall')->name('shop.yzshopskugetall');
	// 商品配送方式 -all
	$router->any('/shop/yzpeisonggetall', 'ShopsController@yzpeisonggetall')->name('shop.yzpeisonggetall');


	//商品管理
	$router->any('/goods/list', 'GoodsController@index')->name('goods.list');
	$router->any('/goods/ajaxIndex', 'goods@ajaxIndex')->name('goods.lists');
	// 商品同步
	$router->any('/goods/yzgoodsget', 'GoodsController@yzgoodsget')->name('goods.yzgoodsget');
	//商品sku管理

	// 订单列表
	$router->any('/order/list', 'OrderController@index')->name('order.list');
	// 订单列表
	// $router->any('/order/list', 'OrderController@index')->name('order.list');


});

// 后台系统日志
Route::group(['prefix' => 'admin/log','middleware' => ['auth','check.permission:log']],function ($router)
{
	$router->get('/','\Arcanedev\LogViewer\Http\Controllers\LogViewerController@index')->name('log.dash');
	$router->get('list','\Arcanedev\LogViewer\Http\Controllers\LogViewerController@listLogs')->name('log.index');
	$router->post('delete','\Arcanedev\LogViewer\Http\Controllers\LogViewerController@delete')->name('log.destroy');
	$router->get('/{date}','\Arcanedev\LogViewer\Http\Controllers\LogViewerController@show')->name('log.show');
	$router->get('/{date}/download','\Arcanedev\LogViewer\Http\Controllers\LogViewerController@download')->name('log.download');
	$router->get('/{date}/{level}','\Arcanedev\LogViewer\Http\Controllers\LogViewerController@showByLevel')->name('log.filter');

});