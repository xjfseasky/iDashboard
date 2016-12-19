@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/dataTables/datatables.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-8">
    <h2>{!!trans('admin/goods.title')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('goods/list')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.goods.list')!!}</strong>
        </li>
    </ol>
  </div>
  @permission(config('admin.permissions.goods.create'))
  <div class="col-lg-4">
    <div class="title-action"><span style="display:none;" id="get_loading"><img width="20" height="20" src="/admin/img/loading156.gif" />正在同步ing...</span>
      <a href="javascript:;" id="yzupdate" data="{{url('admin/goods/yzgoodsget')}}" class="btn btn-info">{!!trans('admin/goods.action.yzupdate')!!}</a>
    </div>
    <!--
    <div class="title-action">
      <a href="{{url('admin/shop/create')}}" class="btn btn-info">{!!trans('admin/shop.action.create')!!}</a>
    </div>
    -->
  </div>
  @endpermission
</div>
<div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <h2 style="color:gray;margin:10px;"></h2>
                    <!-- /.panel-heading -->
                    <div class="row">
                        <div class="col-xs-5"  style="padding-top:8px;">
                           
                        </div>
                        <div class="col-xs-7">
                            <form class="form-inline" action="" method="post" role="form">
                                {{ csrf_field() }}
                                <input type="hidden" name="page" value="1" />
                                <input type="text" name="goods_name" value="{{$search}}" class="form-control" placeholder="店铺名称" />
                                <button type="submit" class="btn btn-default" style='margin-left:10px;margin-right:20px;' id="search">搜索</button>
                            </form>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="text-align: center;">
                                <thead>
                                <tr>
                                    <th style="text-align: center;">编号</th>
                                    <th style="text-align: center;">商品名称</th>
                                    <th style="text-align: center;">商品类型</th>
                                    <th style="text-align: center;">库存量</th>
                                    <th style="text-align: center;">创建时间</th>
                                    <th style="text-align: center;">状态</th>
                                    <!--<th style="text-align: center;">操作</th>-->
                                </tr>
                                </thead>
                                <tbody id="tbody">
                                @foreach ($goods as  $k=>$v)
                                    <tr class="{{$k%2 ? 'odd':'even'}} gradeX"   mrmId="{{ $v->id}}">
                                        <td style=" vertical-align: middle;">{{ $v->id}}</td>
                                        <td align="left" style=" vertical-align: middle;" class="name">{{ $v->name}}</td>
                                        <td style=" vertical-align: middle;" class="type">{{ $v->type_name}}</td>
                                        <td style="vertical-align: middle;" class="num">{{ $v->num }}</td>
                                        <td style=" vertical-align: middle;" class="created_at">{{ $v->created_at}}</td>
                                        <td style=" vertical-align: middle;" class="status">{{ $v->status==1 ? '正常':'禁用'  }}</td>
                                        <!--<td>
                                            <form class="form-inline" role="form">
                                                <button type="button" class="btn btn-primary doEdit">编辑</button>
                                                <button type="button" class="btn btn-primary doSelectGoods">查看商品</button>
                                                <button type="button" class="btn btn-danger  toDestroy" mname="{{ $v->name }}">删除</button>
                                            </form>
                                        </td>-->
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <div style="text-align: center;">
                            {!! $goods->appends(['goods_name'=>$search])->links() !!}
                        </div>

                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
</div>
@endsection
@section('js')
<script src="{{asset('vendors/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('vendors/layer/layer.js')}}"></script>
<script>
$("#yzupdate").click(function(){

    $("#get_loading").show();
    $.ajax({
        type:'get',
        url:"{{url('admin/goods/yzgoodsget')}}",
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        success:function(msg) {
          console.log(msg);
            if(msg['result']=='1') {
                alert(msg['succ']);
                window.location.reload();
            }else{
                alert(msg['err']);
            }

            $("#get_loading").hide();
        },
        error:function(xhr , t , e) {
            $(that).attr('disabled',false);
            alert('操作失败，请稍后再试');
        }
    })
})
</script>
@endsection