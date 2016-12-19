@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/dataTables/datatables.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-8">
    <h2>{!!trans('admin/shopsku.title')!!} -- ({{$shopname}})</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('shop/list')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.shopsku.list')!!}</strong>
        </li>
    </ol>
  </div>
  @permission(config('admin.permissions.shop.create'))
  <div class="col-lg-4">
    <div class="title-action"><span style="display:none;" id="get_loading"><img width="20" height="20" src="/admin/img/loading156.gif" />正在同步ing...</span>
      <a href="javascript:;" id="yzupdate" data="{{url('admin/shop/yzshopskuget')}}" class="btn btn-info">{!!trans('admin/shopsku.action.yzupdate')!!}</a>
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
                                <input type="text" name="shopsku_name" value="{{$search}}" class="form-control" placeholder="店铺名称" />
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
                                    <th style="text-align: center;">商品数量</th>
                                    <th style="text-align: center;">商品单价</th>
                                    <th style="text-align: center;">是否上架</th>
                                    <th style="text-align: center;">创建时间</th>
                                </tr>
                                </thead>
                                <tbody id="tbody">
                                @foreach ($shopsku as  $k=>$v)
                                    <tr class="{{$k%2 ? 'odd':'even'}} gradeX"   mrmId="{{ $v->id}}">
                                        <td style=" vertical-align: middle;">{{ $k+1}}</td>
                                        <td align="left" style=" vertical-align: middle;" class="name">{{ $v->name}}</td>
                                        <td style=" vertical-align: middle;" class="shoplv">{{ $v->type==1?'普通商品':'分销商品'}}</td>
                                        <td style=" vertical-align: middle;" class="address">{{ $v->num }}</td>
                                        <td style=" vertical-align: middle;" class="address">{{ $v->price }}</td>
                                        <td style=" vertical-align: middle;" class="address">{{ $v->is_listing==1?'已上架':'已下架' }}</td>
                                        <td style=" vertical-align: middle;" class="created_at">{{ $v->created_at}}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <div style="text-align: center;">
                            {!! $shopsku->appends(['shop_name'=>$search])->links() !!}
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
        url:"{{url('admin/shop/yzshopskuget/'.$shopid)}}",
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