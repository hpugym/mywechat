<?php
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
$APPID='wxafa5f3c55b3a7617';
$REDIRECT_URI='http://sign.goalschina.com/sign/mysign';
$scope='snsapi_base';//不需要点击授权
//$scope='snsapi_userinfo';//需要授权
$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state=23#wechat_redirect';
header("Location:".$url);
?>