<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Service\Api\RoleService;
class RoleController extends Controller
{
    private $role;

    public function __construct(RoleService $role)
    {
        $this->role = $role;
    }

    /**
     * 角色列表数据
     * @author 晚黎
     * @date   2016-11-22T11:21:33+0800
     * @return [type]                   [description]
     */
    public function index()
    {
        $responseData = $this->role->ajaxIndex();
        return response()->json($responseData);
    }

    /**
     * 角色视图获取所有权限
     * @author 晚黎
     * @date   2016-11-22T15:33:35+0800
     * @return [type]                   [description]
     */
    public function create()
    {
        $responseData = $this->role->getAllPermissionList();
        return response()->json($responseData);
    }

    /**
     * 添加角色
     * @author 晚黎
     * @date   2016-11-22T16:52:27+0800
     * @param  RoleRequest              $request [description]
     * @return [type]                            [description]
     */
    public function store(RoleRequest $request)
    {
        $responseData = $this->role->storeRole($request->all());
        return response()->json($responseData);
    }

    /**
     * 查看角色信息
     * @author 晚黎
     * @date   2016-11-23T15:05:27+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function show($id)
    {
        $responseData = $this->role->findRoleById($id,true);
        return response()->json($responseData);
    }

    /**
     * @author 晚黎
     * @date   2016-11-22
     * @param  修改角色数据
     * @return [type]
     */
    public function edit($id)
    {
        $responseData = $this->role->findRoleById($id);
        return response()->json($responseData);
    }

    /**
     * 修改角色
     * @author 晚黎
     * @date   2016-11-23T14:09:13+0800
     * @param  Request                  $request [description]
     * @param  [type]                   $id      [description]
     * @return [type]                            [description]
     */
    public function update(Request $request, $id)
    {
        $responseData = $this->role->updateRole($request->all(),$id);
        return response()->json($responseData);
    }

    /**
     * 删除角色
     * @author 晚黎
     * @date   2016-11-23T14:18:40+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function destroy($id)
    {
        $responseData = $this->role->destroyRole($id);
        return response()->json($responseData);
    }
}