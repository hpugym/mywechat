<?php
    //设置一下时区
    date_default_timezone_set('Asia/Shanghai');
    $code = $_GET['code'];
    $appId  = "wxafa5f3c55b3a7617";
    $appSecret = "bbde89d0d696cee2fb01e3054d1d7ee8";
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appId.'&secret='.$appSecret.'&code='.$code.'&grant_type=authorization_code';
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($res,true);
    $openid = $json['openid'];
    $return_url = "http://sign.goalschina.com/sign/mysign/signList.php?openid=".$openid;
    header("Location:".$return_url);
?>