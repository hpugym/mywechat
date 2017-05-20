<?php
header("Access-Control-Allow-Origin: *");
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
require_once('conn.php');
$action = @$_GET["action"];
session_start();
//$param = "1234";
//var_dump($_POST);
//echo "<br/>";
//die();
if($action == "binding"){
    $param = $_SESSION['param'];
    if(isset($_POST) && !empty($_POST)){
        if(@$_POST['stu_validate'] == $param){
            $stu_opneid = trim(@$_POST['stu_openid']);
            $stu_num = trim(@$_POST['stu_num']);
            $stu_phone = trim(@$_POST['stu_phone']);
            $stu_image = str_replace("\/","/",@$_POST['stu_image']);

            $data = file_get_contents("./log.text");
            $fp = fopen("./log.text", "w");
            $data = date("Y-m-d H:i:s",time())."---openid:".$stu_opneid."---学号：".$stu_num."\n";
            fwrite($fp, $data);
            fclose($fp);
//            var_dump("学号".$stu_num."<br>");

            $stu = new Connection();
            $sql = "select `stu_openid` from `sign_students` where `stu_num` = ".$stu_num;
            $res = $stu->Query($sql);
//            var_dump($res);
//            echo "<br/>";
//            die();
            if($res['status'] == 1){
                if($res[0]['stu_openid'] == "" || empty($res[0]['stu_openid'])){//查询不到openid
                    $stu2 = new Connection();
                    $sql2 = "update `sign_students` set `stu_openid` = '".$stu_opneid."' , `stu_image` = '".$stu_image."' , `stu_phone` = '".$stu_phone."' where(`stu_num` = ".$stu_num.")";
                    $res2 = $stu2->Update($sql2);
//                    var_dump($res2);
                    if($res2 > 0){
                        echo "<script>alert(\"数据绑定成功,页面即将被关闭\");setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
//                    echo json_encode(array("code"=>"0000"),JSON_UNESCAPED_UNICODE);
//                     echo "6";
                    }else{
                        echo "<script>alert(\"数据帮绑定失败,页面会被重新加载，数据将被清空\");window.location.href='http://sign.goalschina.com/sign/getauthorize.php'</script>";
//                    echo json_encode(array("code"=>"0005"),JSON_UNESCAPED_UNICODE);
//                    echo "5";
                    }
                }else{//查询到openid
                    echo "<script> alert(\"该学号已经被绑定，页面即将关闭\"); setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
//                echo json_encode(array("code"=>"0002"),JSON_UNESCAPED_UNICODE);//已经绑定过
//                echo "2";
                }
            }else{
                echo "<script>alert(\"该学号不存在,页面会被重新加载，数据将被清空\");window.location.href='http://sign.goalschina.com/sign/getauthorize.php'</script>";
//            echo json_encode(array("code"=>"0003"),JSON_UNESCAPED_UNICODE);//数据请求出错
//            echo "3";
            }
        }else{
            echo "<script>alert(\"对不起，验证码错误，页面会被重新加载，数据将被清空\");window.location.href='http://sign.goalschina.com/sign/getauthorize.php'</script>";
//        echo json_encode(array("code"=>"0001"),JSON_UNESCAPED_UNICODE);//验证码错误
//        echo "1";
        }
    }else{
        echo "<script>alert(\"对不起，该操作不被允许\");setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
//    echo json_encode(array("code"=>"0004"),JSON_UNESCAPED_UNICODE);//请求方式错误
//        echo "4";
    }
}else if($action == "signing"){
    $stu_opneid = @$_GET['openid'];
    $qrcode_code = @$_GET['qrcode'];
    $teachs_code = @$_GET['teachs'];

    //先查询是否已经签到
    $signed = new Connection();
    $signed_sql = "SELECT `signs_status` FROM `sign_signs` where (`stu_openid` = '".$stu_opneid."' and `qrcode_code` = '".$qrcode_code."')";
    $res1 = $signed->Query($signed_sql);
//    var_dump($res1);
//    die();
    if($res1["status"] != 1){
        echo "<script>alert('签到信息核对失败:你未选修该课程!');setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
        die();
    }
    if($res1[0]['signs_status'] == '出勤'){
        echo "<script>alert('您已签到，不能再签'); setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
        die();
    }
    //更改签到信息
    $sign = new Connection();
    $sign_sql = "update `sign_signs` set `signs_status` = '出勤', `signs_time` = UNIX_TIMESTAMP(NOW()) where (`stu_openid` = '".$stu_opneid."' and `qrcode_code` = '".$qrcode_code."')";
    $res = $sign->Update($sign_sql);
//    var_dump($res);
//    die();
    if($res == 0){
        echo "<script>alert('签到失败');setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
        die();
    }else{
        echo "<script>alert('签到成功');setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
        die();
    }
}