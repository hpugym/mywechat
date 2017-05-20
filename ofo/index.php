<?php
/**
 * Created by PhpStorm.
 * User: 不倾国倾城_只倾你
 * Date: 2017/3/2
 * Time: 9:53
 */
//require 'conn.php';
//header("Content-type:text/html;charset=utf-8");
//$conn = new Connection();
//$sql = "insert into `shares` (`share_phone`,`share_pass`,`share_name`,`join_date`) values('18336800665','guo1022','郭月盟',now())";
//$res = $conn->Insert($sql);
//var_dump($res);

$APPID='wxafa5f3c55b3a7617';
$REDIRECT_URI='http://sign.goalschina.com/ofo/callback.php';
//$scope='snsapi_base';
$scope='snsapi_userinfo';//需要授权
$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
header("Location:".$url);
?>