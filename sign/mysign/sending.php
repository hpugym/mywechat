<?php
header("Access-Control-Allow-Origin: *");
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
//载入ucpass类
require_once('Ucpaas.class.php');

//开启session
session_start();
//初始化必填
$options['accountsid']='e7f96cfad9ac69b5ca19a99dabf847bd';
$options['token']='37a403b7638df7c3d9fbfa221a508eb0';


//初始化 $options必填
$ucpass = new Ucpaas($options);

//开发者账号信息查询默认为json或xml
//echo $ucpass->getDevinfo('json');

//短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
$appId = "7804c10a4f3d465c81e6ad4de401b1ad";
//$to = "15138479371";
$templateId = "40346";
//$param="1234";

$to = trim(@$_POST['to']);
$param = rand(1000,9999);
$_SESSION['param'] = $param;

echo $ucpass->templateSMS($appId,$to,$templateId,$param);