<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\YZSDK\KdtApiClient;

use App\Shops;
use App\Shopsku;
use App\Goods;

class ShopsController extends Controller
{
    private $user;
    public $paginate = 20;

    function __construct()
    {
        // 自定义权限中间件
        $this->middleware('check.permission:user');
    }
    //
    public function index(Request $request)
    {

        $search = $request->input('shop_name');
        if($search) {
            $shops = Shops::select('id', 'name', 'shoplv', 'province',
                                            'city', 'area',
                                            'address', 'status', 'created_at')
                                        ->where('name', 'LIKE', '%' . $search . '%')
                                        ->orderby('created_at', 'DESC')
                                        ->paginate($this->paginate);
        }else {

            $shops = Shops::select('id', 'name', 'shoplv', 'province',
                                            'city', 'area',
                                            'address', 'status', 'created_at')
                                        ->orderby('created_at', 'DESC')
                                        ->paginate($this->paginate);
        }
        if($shops){
        	foreach($shops as &$v)
        	{
        		$v->shoplv_name = $v->shoplv == 1?'总店':'分店';
        		$v->address_name = $v->province . $v->city . $v->area . $v->address;
        	}
        }
    	return view('admin.shop.list', ['shops'=>$shops, 'search'=>$search]);
    }
    // 获取店铺商品sku
    public function shopsku(Request $request, $shop_id)
    {

        $search = $request->input('shopsku_name');
        //$shop_id = $request->input('shop_id');
        $shop = Shops::where("id", $shop_id)->first();
        if($search) {

            $shopsku = Shopsku::select('id', 'name', 'is_listing', 'type', 'outer_buy_url',
                                            'num', 'price', 'created_at')
                                        ->where('name', 'LIKE', '%' . $search . '%')
                                        ->where('shop_id', $shop_id)
                                        ->where('shop_id', 1)
                                        ->orderby('created_at', 'DESC')
                                        ->paginate($this->paginate);
        }else {

            $shopsku = Shopsku::select('id', 'name', 'is_listing', 'type', 'outer_buy_url',
                                            'num', 'price', 'created_at')
                                        ->where('shop_id', $shop_id)
                                        ->where('is_listing', 1)
                                        ->orderby('created_at', 'DESC')
                                        ->paginate($this->paginate);
        }
        if($shopsku){
        	foreach($shopsku as &$v)
        	{
        		$v->shoplv_name = $v->shoplv == 1?'总店':'分店';
        		$v->address_name = $v->province . $v->city . $v->area . $v->address;
        	}
        }
    	return view('admin.shop.shopsku', ['shopsku'=>$shopsku, 'search'=>$search, 'shopid'=>$shop_id, 'shopname'=>$shop['name']]);
    }

    //获取店铺列表ajax
    public function ajaxIndex()
    {
    	$responseData = Shops::where("status", 1)->get();
    	if($responseData)
    	{
    		foreach ($responseData as $key => $v) {
    			$v['address_shop'] = $v['province'] . $v['city'] . $v['area'] . $v['address'];
    		}
    	}
        $responseData = $this->shop->ajaxIndex();
        return response()->json($responseData);
    }
    // 获取网点所有商品配送方式设置
    public function yzpeisongget($shop_id)
    {
		$client = new KdtApiClient($this->appId, $this->appSecret);
		$shops_ps = Shops::where(array("status"=>1, "id"=>$shop_id))->first();
		//查看网点列表
		$method = 'kdt.multistore.offline.goods.settings.get';
		$params = array();
		// 更新所有商品状态：所有为未上架
		Shopsku::where(array("is_listing"=>1, "id"=>$shop_id))->update(array("is_listing"=>2));

		if($shops_ps)
		{
			// echo "<br> ---->>name: " . $shops_ps['name'] . "youzan_id: " .$shops_ps['youzan_id']. "<br>";
			$params['shop_id'] = $shops_ps['youzan_id'];
			//$params['shop_id'] = 20943074;
			$data = $client->get($method, $params);
			if($data)
			{
				foreach($data['response'] as $key=>$v)
				{
					// echo "<br> ---->>yz_num_iid: " . $v['goods_id'] . "  shop_id: " .$v['shop_id']. "<br>";
					// 如果商品不存在网点里设置为未上架
					$sku = Shopsku::where(array("youzan_id"=>$v['shop_id'], "yz_num_iid"=>$v['goods_id']))->first();
					
					if($sku)
					{
						Shopsku::where("id", $sku['id'])->update(array("is_listing"=>1));
					}
				}
			}
		}
    }
    // 获取sku列表
    public function yzshopskuget($shop_id)
    {
    	//更新商品列表
		$client = new KdtApiClient($this->appId, $this->appSecret);
		$shops = Shops::where("id", $shop_id)->first();
		$goods = Goods::select("id", "yznum_iid")->where("status", 1)->get();
		//查看网点列表
		$method = 'kdt.multistore.offline.goods.sku.get';
		$params = array();
		// 店铺列表
		$params['shop_id'] = $shop_id;
		// 有赞店铺id
		$youzan_id = $shops['youzan_id'];
		$n = 0;
		// 商品列表
		foreach ($goods as $kys => $val) 
		{
			$goods_id = $val['id'];
			$yznum_iid = $val['yznum_iid'];
			$params['num_iid'] = $val['yznum_iid'];
			$data = $client->get($method, $params);
			// 店铺商品sku
			$list = $data['response']['item'];
			if($list)
			{
				$shopsku = new Shopsku;
				$shopsku['shop_id'] = $shop_id; 
				$shopsku['youzan_id'] = $youzan_id;
				$shopsku['goods_id'] = $goods_id;
				$shopsku['yz_num_iid'] = $yznum_iid;
				$skus_id = 0;
				if(isset($list['skus'][0]['sku_id']))
				{
					$skus_id = $list['skus'][0]['sku_id'];
				}
				$shopsku['yz_sku_id'] = $skus_id;
				//$shopsku['type'] = $v['is_store'];

				$shopsku['name'] = $list['title'];
				//$shopsku['is_listing'] = $list['is_listing'];
				//$shopsku['outer_buy_url'] = $v['outer_buy_url'];
				$shopsku['num'] = $list['num'];
				
				$shopsku['price'] = $list['price'];
				$data = Shopsku::where(["youzan_id"=>$youzan_id, "yz_num_iid"=>$yznum_iid])->get();
				// echo "\r\n shop: " . $shops['name'] . "name: " . $shopsku['name'] . " num: " . $shopsku['num'] . "\r\n";
				if(count($data)>0)
				{
					$shopsku['id'] = $data[0]['id'];
					$shopsku->update();
				}
				else
				{
					$shopsku->save();
				}
			}
		}
		$this->yzpeisongget($shop_id);
		return response()->json(['result' => '1', 'succ' => '同步完成']);
	}
    //获取网点列表
    public function yzshopget()
    {
		$client = new KdtApiClient($this->appId, $this->appSecret);
		//查看网点列表
		$method = 'kdt.offlines.get';
		$data = $client->get($method, '');

		$list = $data['response']['list'];
		if($list)
		{
			foreach($list as $key=>$v)
			{	
				$shops = new Shops;
				$shops['shoplv'] = 0; 
				$shops['parentid'] = 0;
				$shops['youzan_id'] = $v['id'];
				$shops['yz_kdt_id'] = $v['kdt_id'];
				$shops['name'] = $v['name'];
				$shops['is_store'] = $v['is_store'];
				$shops['is_self_fetch'] = $v['is_self_fetch'];
				$shops['phone1'] = $v['phone1'];
				$shops['phone2'] = $v['phone2'];
				$shops['province'] = $v['province'];
				$shops['city'] = $v['city'];
				$shops['area'] = $v['area'];
				$shops['address'] = $v['address'];
				$shops['county_id'] = $v['county_id'];
				$shops['lng'] = $v['lng'];
				$shops['lat'] = $v['lat'];

				$data = Shops::where("youzan_id", $v['id'])->get();
				if(count($data)>0)
				{
					$shops['id'] = $data[0]['id'];
					$shops->update();
				}
				else
				{
					$shops->save();
				}
	            //$shops->firstOrCreate();
			}
			 return response()->json(['result' => '1', 'succ' => '同步完成']);
		}
    }

    // 获取网点所有商品配送方式设置
 //    public function yzpeisongget()
 //    {

	// 	$client = new KdtApiClient($this->appId, $this->appSecret);
	// 	$shops_ps = Shops::select("id", "youzan_id", "name")->where("status", 1)->get();
	// 	//查看网点列表
	// 	$method = 'kdt.multistore.offline.goods.settings.get';
	// 	$params = array();
	// 	// 更新所有商品状态：所有为未上架
	// 	Shopsku::where("is_listing", 1)->update(array("is_listing"=>2));
		
	// 	if($shops_ps)
	// 	{
	// 		foreach($shops_ps as $key=>$v)
	// 		{
	// 			// echo "<br> ----name: " . $v['name'] . "youzan_id: " .$v['youzan_id']. "<br>";
	// 			$params['shop_id'] = $v['youzan_id'];
	// 			//$params['shop_id'] = 20943074;
	// 			$data = $client->get($method, $params);
	// 			if($data)
	// 			{
	// 				foreach($data['response'] as $key=>$v)
	// 				{
	// 					// 如果商品不存在网点里设置为未上架
	// 					$shopsku = new Shopsku;
	// 					$sku = Shopsku::where(array("youzan_id"=>$v['shop_id'], "yz_num_iid"=>$v['goods_id']))->first();
	// 					if($sku)
	// 					{
	// 						$sku['is_listing'] = 1;
	// 						$sku->update();
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
 //    }
 //    // 获取sku列表
 //    public function yzshopskuget()
 //    {
 //    	//更新商品列表
	// 	$client = new KdtApiClient($this->appId, $this->appSecret);
	// 	$shops = Shops::select("id", "youzan_id", "name")->where("status", 1)->get();
	// 	$goods = Goods::select("id", "yznum_iid")->where("status", 1)->get();
	// 	//查看网点列表
	// 	$method = 'kdt.multistore.offline.goods.sku.get';
	// 	$params = array();
	// 	// 店铺列表
	// 	foreach($shops as $key=>$v)
	// 	{
	// 		$params['shop_id'] = $v['youzan_id'];
	// 		// echo "<br> ----name : ". $v['name'] . "<br>";
	// 		$shop_id = $v['id'];
	// 		$youzan_id = $v['youzan_id'];
	// 		$n = 0;
	// 		// 商品列表
	// 		foreach ($goods as $kys => $val) 
	// 		{
	// 			// echo "<br> yznum_iid : ". $val['yznum_iid'] . "<br>";
	// 			$goods_id = $val['id'];
	// 			$yznum_iid = $val['yznum_iid'];
	// 			$params['num_iid'] = $val['yznum_iid'];
	// 			$data = $client->get($method, $params);
	// 			// 店铺商品sku
	// 			$list = $data['response']['item'];
	// 			if($list)
	// 			{
	// 				$shopsku = new Shopsku;
	// 				$shopsku['shop_id'] = $shop_id; 
	// 				$shopsku['youzan_id'] = $youzan_id;
	// 				$shopsku['goods_id'] = $goods_id;
	// 				$shopsku['yz_num_iid'] = $yznum_iid;
	// 				$skus_id = 0;
	// 				if(isset($list['skus'][0]['sku_id']))
	// 				{
	// 					$skus_id = $list['skus'][0]['sku_id'];
	// 				}
	// 				$shopsku['yz_sku_id'] = $skus_id;
	// 				//$shopsku['type'] = $v['is_store'];

	// 				$shopsku['name'] = $list['title'];
	// 				//$shopsku['is_listing'] = $list['is_listing'];
	// 				//$shopsku['outer_buy_url'] = $v['outer_buy_url'];
	// 				$shopsku['num'] = $list['num'];
					
	// 				$shopsku['price'] = $list['price'];
	// 				$data = Shopsku::where(["youzan_id"=>$params['shop_id'], "yz_num_iid"=>$goods_id])->get();
	// 				if(count($data)>0)
	// 				{
	// 					$shopsku['id'] = $data[0]['id'];
	// 					$shopsku->update();
	// 				}
	// 				else
	// 				{
	// 					$shopsku->save();
	// 				}
	// 			}
	// 		}
	// 		$this->yzpeisongget();
	// 	}
	// 	return response()->json(['result' => '1', 'succ' => '同步完成']);
	// }
}
