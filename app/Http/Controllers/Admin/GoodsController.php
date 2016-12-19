<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\YZSDK\KdtApiClient;

use App\Goods;


class GoodsController extends Controller
{
    public $paginate = 20;

    function __construct()
    {
        // 自定义权限中间件
        $this->middleware('check.permission:user');
    }
   
    public function index(Request $request)
    {

        $search = $request->input('goods_name');
        if($search) {

            $goods = Goods::select('id', 'name', 'yznum_iid', 'type',
                                            'num', 'price',
                                            'outer_buy_url', 'status', 'created_at')
                                        ->where('name', 'LIKE', '%' . $search . '%')
                                        ->orderby('created_at', 'DESC')
                                        ->paginate($this->paginate);
        }else {

            $goods = Goods::select('id', 'name', 'yznum_iid', 'type',
                                            'num', 'price',
                                            'outer_buy_url', 'status', 'created_at')
                                        ->orderby('created_at', 'DESC')
                                        ->paginate($this->paginate);
        }
        if($goods){
        	foreach($goods as &$v)
        	{
                $v->type_name = $v->shoplv == 1?'普通商品':'分销商品';
        	}
        }
    	return view('admin.goods.list', ['goods'=>$goods, 'search'=>$search]);
    }

    public function yzgoodsget()
    {
		$client = new KdtApiClient($this->appId, $this->appSecret);
		//查看商品列表
		$method = 'kdt.items.onsale.get';
		$data = $client->get($method, '');
        // dd($data);
		$list = $data['response']['items'];
		if($list)
		{
			foreach($list as $key=>$v)
			{	
				$goods = new Goods;
				$goods['yznum_iid'] = $v['num_iid']; 
				$goods['type'] = $v['item_type'];
				$goods['name'] = $v['title'];
				$goods['num'] = $v['num'];
                // echo 'is_listing: ' . $v['is_listing'] . '<br>';
                $goods['is_listing'] = $v['is_listing']==true?1:2;
				$goods['price'] = $v['price'];
				$goods['outer_buy_url'] = $v['outer_buy_url'];
				$goods['status'] = 1;

				$data = Goods::where("yznum_iid", $v['num_iid'])->get();
				if(count($data)>0)
				{
					$goods['id'] = $data[0]['id'];
					$goods->update();
				}
				else
				{
					$goods->save();
				}
			}
			 return response()->json(['result' => '1', 'succ' => '同步完成']);
		}
    }
}
