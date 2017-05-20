<?php
session_start();
$appid = "wxafa5f3c55b3a7617";
$secret = "bbde89d0d696cee2fb01e3054d1d7ee8";
$code = $_GET["code"];


$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$get_token_url);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);
$json_obj = json_decode($res,true);
//根据openid和access_token查询用户信息
$access_token = @$json_obj['access_token'];
$openid = @$json_obj['openid'];
$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);

$a = json_decode($res, true);
if(empty($a)){
    header("Location:http://sign.goalschina.com/ofo/");
    exit(0);
}
$headurl = @$a['headimgurl'];
$len = substr($headurl, 0, strlen($headurl)-1);
$newheadurl = $len."132";
$_SESSION['imgurl'] = @$newheadurl;
$_SESSION['nick'] = @$a['nickname'];
$_SESSION['openid'] = @$openid;
//$tmp = file_get_contents("log.text");
//$str = $tmp."\n查到session：".$_SESSION['openid'].date("Y-m-d H:i:s",$time);
//file_put_contents("log.text",$str);
header("location:http://sign.goalschina.com/ofo/my.php");
exit(0);
?>