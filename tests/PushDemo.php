<?php
/**
 * 有赞推送服务消息接收示例
 * Auther: Moyu
 * Date: 2016/11/15
 * Time: 21:10
 */

$AppID = "";//商家后台AppID,如果是三方服务商,这里是client_id
$AppSecret = "";//商家后台AppSecret,如果是三方服务商,这里是client_secret


$json = file_get_contents('php://input'); //接收推送数据
$data = json_decode($json, true);
$msg = $data['msg'];

/**
 * 收到推送后返回成功消息
 */
if ($msg){
    $result = array("code"=>"0","msg"=>"success") ;
    var_dump($result);
}

/**
 * 判断消息是否合法
 */
$signString = $AppID."".$msg."".$AppSecret;
$sign = md5($signString);
if($sign != $data['sign']){
    exit();
}

/**
 * msg是经过unicode（UTF-8）编码的消息对象,所以要进行解码
 */
$msg = json_decode(urldecode($msg),true);


if($data['type'] == "TRADE"){
    //处理交易信息
}

if($data['type'] == "ITEM"){
    //处理商品信息
}