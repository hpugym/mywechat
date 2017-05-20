<?php
$appid = "wxafa5f3c55b3a7617";
$secret = "bbde89d0d696cee2fb01e3054d1d7ee8";
$code = $_GET["code"];

$time = date("Y-m-d:H:i:s");
$str = "<br/>回调获得的数据code值：".$code."<br/>".$time."<br/>";
echo $str;

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
$access_token = $json_obj['access_token'];
$openid = $json_obj['openid'];
$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);

$time = date("Y-m-d:H:i:s");
$tmp = file_get_contents("log.text");
$str = $tmp."\n授权获得的信息:".$res.$time."事件：";
file_put_contents("log.text",$str);

$a = json_decode($res, true);
$headurl = $a['headimgurl'];
$len = substr($headurl, 0, strlen($headurl)-1);
$newheadurl = $len."46";
echo "<img src='".$newheadurl."'/>";

print_r($res);
?>
