<?php
namespace App\Repositories\Eloquent;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Contracts\ShopsRepository;
use App\Service\Admin\ShopService;
use App\Shops;
/**
 * Class UserRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class ShopsRepositoryEloquent extends BaseRepository implements ShopsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Shops::class;
    }

    /**
     * 查询角色并分页
     * @author 晚黎
     * @date   2016-11-03T12:56:28+0800
     * @param  [type]                   $start  [起始数目]
     * @param  [type]                   $length [读取条数]
     * @param  [type]                   $search [搜索数组数据]
     * @param  [type]                   $order  [排序数组数据]
     * @return [type]                           [查询结果集，包含查询的数量及查询的结果对象]
     */
    public function getShopsList($start,$length,$search,$order)
    {
        $shops = $this->model;
        if ($search['value']) {
            if($search['regex'] == 'true'){
                $shops = $shops->where('name', 'like', "%{$search['value']}%");
            }else{
                $shops = $shops->where('name', $search['value']);
            }
        }

        $count = $shops->count();
        
        $shops = $shops->orderBy($order['name'], $order['dir']);

        $shops_arr = $shops->offset($start)->limit($length)->get();

        return compact('count','shops_arr');
    }

    // public function createRole($formData)
    // {
    //     $role = $this->model;
    //     if ($role->fill($formData)->save()) {
    //         // 更新角色权限关系
    //         if (isset($formData['permission'])) {
    //             $role->permissions()->sync($formData['permission']);
    //         }
    //         return true;
    //     }
    //     return false;
    // }
    
}
