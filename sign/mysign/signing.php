<?php
    //设置一下时区
    date_default_timezone_set('Asia/Shanghai');
    require_once ("./conn.php");
    session_start();
    $id = @$_GET['id'];
    $openid = @$_GET['openid'];

    $qrcode = new Connection();
    $qrcode_sql = "SELECT * FROM `sign_qrcode` WHERE `teachs_code` = ".$id." ORDER BY `qrcode_time` DESC LIMIT 1";
    $res = $qrcode->Query($qrcode_sql);
    if($res['status'] != 1){
        echo "<script>alert('二维码信息拉取失败');</script>";
        die();
    }
//    var_dump($res);
//    echo "场景ID:".$id."and openid:".$openid."<br/>";
//    die();


    //核对签到时间
    $end_time = $res[0]['qrcode_end_time'];

    if($end_time < time()){
        echo "<script>alert('对不起，签到时间已过，页面将关闭。');setTimeout(function() {WeixinJSBridge.call('closeWindow');},50)</script>";
        die();
    }

    //核对地理位置

    //拉取微信端的经纬度
    $local = new  Connection();
    $local_sql = "select * from `sign_stulocal` where (`openid` ='".$openid."')";
    $result = $local->Query($local_sql);
    if($result['status'] != 1){
        echo "<script>alert('经纬度拉取失败');</script>";
        die();
    }
//    var_dump($result);
//    echo "<br/>";



    $stu_lon = $result[0]['lon'];
    $stu_lat = $result[0]['lat'];
    $lon = $res[0]['qrcode_lon'];
    $lat = $res[0]['qrcode_lat'];

//    echo "old1:".$lon.",".$lat."<br/>";
//    echo "oldstu:".$stu_lon.",".$stu_lat."<br/>";

    $radLat1 = deg2rad($lat); //deg2rad()函数将角度转换为弧度
    $radLat2 = deg2rad($stu_lat);
    $radLng1 = deg2rad($lon);
    $radLng2 = deg2rad($stu_lon);
    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
//    echo $s."<br/>";




    //转换微信传来的经纬度为百度地图的经纬度
    $JSON = file_get_contents("http://api.map.baidu.com/geoconv/v1/?coords=".$stu_lon.",".$stu_lat."&from=1&to=5&ak=LgOKlXU4ZjIMQDlCuj2UD063HiKMxKlu");
    $data = json_decode($JSON,true);
    //var_dump($data);
    //var_dump($data['result']);
    if($data["status"] == 0){
        $new_lon = $data['result'][0]['x'];
        $new_lat = $data['result'][0]['y'];

    }else{
        echo "<script>alert('经纬度转换失败');</script>";
        die();
    }
    $JSON2 = file_get_contents("http://api.map.baidu.com/geoconv/v1/?coords=".$lon.",".$lat."&from=1&to=5&ak=LgOKlXU4ZjIMQDlCuj2UD063HiKMxKlu");
    $data2 = json_decode($JSON2,true);
    if($data["status"] == 0) {
        $new_lon2 = $data2['result'][0]['x'];
        $new_lat2 = $data2['result'][0]['y'];

//        echo "new:".$new_lon2.",".$new_lat2."<br/>";
//        echo "new2:".$new_lon.",".$new_lat."<br/>";
        //开始计算距离
        $radLat1 = deg2rad($new_lat); //deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($new_lat2);
        $radLng1 = deg2rad($new_lon);
        $radLng2 = deg2rad($new_lon2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
//        echo $s;
//        die();
        if($s > 500){
            echo "<script>alert('对不起，您和教室的距离太大，小微怀疑您不在上课，不允许签到，页面将关闭。');setTimeout(function() {WeixinJSBridge.call('closeWindow');},50)</script>";
            die();
        }

    }else{
        echo "<script>alert('经纬度转换失败');</script>";
        die();
    }

    //先查询课程信息
    $teachs_code = $res[0]['teachs_code'];//课程标示id
    $qrcode_code = $res[0]['qrcode_code'];//签到标识码
    $add = $res[0]['qrcode_add'];//今日签到地址
    $course = new Connection();
    $course_sql = "select `sign_courses`.`course_num`, `course_name`, `course_type`, `teachs_add`, `teachs_time`, `teachs_grade`, `teachs_stu`, `teachs_avai`, `teachs_status`, `teach_id` from `sign_courses`, `sign_teachs` where(`sign_courses`.`course_num` = `sign_teachs`.`course_num` and `teachs_code` = '".$teachs_code."')";
    $res2 = $course->Query($course_sql);
    if($res2['status'] != 1){
        echo "<script>alert(\"课程信息拉取失败\");</script>";
        die();
    }
//    var_dump($res2);
//    die();
    $course_num = $res2[0]['course_num'];
    $course_name = $res2[0]['course_name'];
    $course_type = $res2[0]['course_type'];
    $teachs_time = $res2[0]['teachs_time'];
    $teachs_grade = $res2[0]['teachs_grade'];
    $teachs_avai = $res2[0]['teachs_avai'];
    $teachs_stu = $res2[0]['teachs_stu'];
    $teachs_status = $res2[0]['teachs_status'];
    $teachs_add = $res2[0]['teachs_add'];

    //拉取教师的信息
    $teach_id = $res2[0]['teach_id'];
    $teach = new  Connection();
    $teach_sql = "select *  from `sign_teachers` where `teach_id` ='".$teach_id."'";
    $res3 = $teach->Query($teach_sql);
    if($res3['status'] != 1){
        echo "<script>alert('教师信息拉取失败');</script>";
        die();
    }
//    var_dump($res3);
//    die();
    $teacher_name = $res3[0]['teach_name'];
    $teacher_level = $res3[0]['teach_level'];
    $teacher_phone = $res3[0]['teach_phone'];
    $teach_img = $res3[0]['teach_image'];

    if($teach_img == null || $teach_img == ""){
        $teach_img = "http://sign.rural-style.cn/public/upload/uploadfiles/default.jpg";
    }else{
        $teach_img = "http://sign.rural-style.cn/public/".$teach_img;
    }
//echo $teach_img;
//die();
    //拉取学生信息
    $stu = new Connection();
    $stu_sql = "select `stu_num`,`stu_name`, `stu_image` from `sign_students` where `stu_openid` = '".$openid."'";
    $res4 = $stu->Query($stu_sql);
    if($res4['status'] != 1){
        echo "<script>alert('个人信息拉取失败');</script>";
        die();
    }
//    var_dump($res4);
//    die();
    $stu_name = $res4[0]['stu_name'];
    $stu_num = $res4[0]['stu_num'];
    $stu_image = $res4[0]['stu_image'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>签到课程信息</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="">
    <script type="text/javascript">
        // 高度适配说明：通过设置一个方便计算的数值，来设置根元素的font-size,所有元素通过rem为单位达到适配需求；
        //具体使用：
        //通过视觉稿宽度除以一个数获得一个方便计算的值100；
        //所有元素的高度可用{(视觉稿量的的高度)/100}rem获得适配的高度；
        !function(){function a(){document.documentElement.style.fontSize=document.documentElement.clientWidth/6.4+"px"}var b=null;window.addEventListener("resize",function(){clearTimeout(b),b=setTimeout(a,300)},!1),a()}(window);
    </script>
    <style type="text/css">
        *{
            margin: 0;
            padding: 0;
            font-family: "微软雅黑";
        }
        html{
            width: 100%;
            height: 100%;
            opacity: 0.84;
        }
        body{
            width: 100%;
            height: 100%;
            background: #373737;
        }
        /*table:此元素会作为块级表格来显示（类似 <table>），表格前后带有换行符。*/
        .table{
            width: 100%;
            height: 40px;
            display: table;
            line-height: 40px;
        }

        #center{
            width: 100%;
            display: table;
            text-align:center;
            background-color:#d5e4eb;
        }
        #centerChild{
            display: table-cell;
            vertical-align: middle;
        }
        #center img{
            display: inline-block;
            width: 1.4rem;
            height: 1.4rem;
            border-radius: 50%;
            margin-top: 3%;
        }
        #name{
            font-size: 0.3rem;
            color:darkslategrey;
            margin-top: -10px;
            color: #014d64;
        }
        #centerChildDetail{
            width: 80%;
            margin-left: 10%;
            background-color: #fff;
            color:#014d64;
            font-size: 0.3rem;
            border-radius: 0.15rem;
            text-align: left;
            margin-top: .3rem;
            padding: .1rem 0;
        }
        #centerChildDetail p{
            padding-left: 0.2rem;
            height: .7rem;
            line-height: .7rem;
        }
        #submit{
            display: block;
            color: #014d64;
            background-color: #fff;
            font-size: 0.35rem;
            border: none;
            width: 80%;
            margin-left: 10%;
            border-radius: 0.15rem;
            border: 1px solid #b3ccd5;
            height: 0.6rem;
            margin-top: .35rem;
            margin-bottom: .2rem;
            line-height: .6rem;
        }
        #footer{
            display: table-cell;
            width: 100%;
            height: 100%;
            text-align:center;
            vertical-align:middle;
            color: #fff;
            font-size:.22rem;
            display: block;
        }
        /*蒙层*/
        .overlay {
            position: absolute;
            top: 0px;
            left: 0px;
            bottom: 0;
            width: 100%;
            z-index: 10001;
            display:none;
            /*所有浏览器都支持opacity属性:设置元素的不透明度，取值越大，越不透明;
            IE8 以及更早的版本支持替代的 filter 属性。例如：filter:Alpha(opacity=50)*/
            /*filter：滤镜 取值：alpha(opacity=60) 改变图片的透明度 相当于 opacity*/
            filter:alpha(opacity=50);
            background-color: #777;
            opacity: 0.5;
            -moz-opacity: 0.5;
        }
        .loading-tip {
            z-index: 10002;
            position: fixed;
            display:none;
            text-align: center;
        }
        .loading-tip img {
            width:100px;
            height:100px;
        }
    </style>
</head>
<body>
<div id="center">
    <div id="centerChild">
        <img src="<?php echo $teach_img ?>" alt="">
        <p id="name"><?php echo $teacher_name ?></p>
        <div id="centerChildDetail">
            <input name="stu_openid" type="hidden" value="<?php echo $openid; ?>">
            <input name="qrcode_code" type="hidden" value="<?php echo $qrcode_code; ?>">
            <input name="teachs_code" type="hidden" value="<?php echo $teachs_code; ?>">
            <p>课程号: <span><?php echo $course_num; ?></span></p>
            <p>课程名：<span><?php echo $course_name; ?></span></p>
            <p>课程类型：<span><?php echo $course_type; ?></span></p>
            <p>课程学时：<span><?php echo $teachs_time; ?></span></p>
            <p>课程学分：<span><?php echo $teachs_grade; ?></span></p>
            <p>授课学生：<span><?php echo $teachs_stu; ?></span></p>
            <p>课程容量：<span><?php echo $teachs_avai; ?></span></p>
            <p>课程状态：<span><?php echo $teachs_status == 0 ? "在教":"结课"; ?></span></p>
            <p>教师姓名: <span><?php echo $teacher_name; ?></span></p>
            <p>教师电话: <span><?php echo $teacher_phone ?></span></p>
            <p>上课地点: <span><?php echo $add ?></span></p>
            <p>学生姓名: <span><?php echo $stu_name ?></span></p>
            <p>学生学号: <span><?php echo $stu_num?></span></p>
        </div>
        <button id="submit">一键签到</button>
    </div>
</div>
<!-- 尾部 -->
<div class="table">
    <p id="footer">版权所有:&nbsp;&nbsp;&copy河南理工大学&nbsp;&nbsp;郭月盟</p>
</div>
<!-- 蒙层 -->
<div id="overlay" class="overlay"></div>
<!-- Loading提示 DIV -->
<div id="loadingTip" class="loading-tip">
    <img src="../images/loading.gif" />
    <p style="font-size: .27rem;color: #fff;line-height: .02rem;">加载中...</p>
</div>
<script type="text/javascript" src="../js/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
    // 取得浏览器可视区高度
    function getWindowInnerHeight(){
        var winHeight=window.innerHeight || (document.documentElement && document.documentElement.clientHeight) || (document.body && document.body.clientHeight);
        return winHeight;
    }
    // 取得浏览器可视区宽度
    function getWindowInnerWidth(){
        var winWidth=window.innerWidth || (document.documentElement && document.documentElement.clientWidth) || (document.body && document.body.clientWidth);
        return winWidth;
    }
    // 显示遮罩层
    function showOverlay(){
        // 遮罩层宽度与高度分别对应(pc端)当前文档/(手机端)设备屏幕的宽度与高度
        $(".overlay").css({"height":$(document).height(),"width":$(document).width()});
        $(".overlay").show();
    }
    // 显示loading提示
    function showLoading(){
        // 先显示遮罩层
        $('.overlay').css("opacity",.6);
        showOverlay();
        console.log((getWindowInnerWidth()-$("#loadingTip").width())/2);
        // loading窗口水平垂直居中
        $("#loadingTip").css("top",(getWindowInnerHeight()-$("#loadingTip").height())/2+'px');
        $("#loadingTip").css("left",(getWindowInnerWidth()-$("#loadingTip").width())/2+'px');
        $("#loadingTip").show();
        $(document).scroll(function(){
            return false;
        })
    }
    $(function(){
        // document.documentElement.clientHeight:获取设备的高度
        var deviceH=document.documentElement.clientHeight-$(".table").height()*2+"px";
        // 设置中间部分的高度
        $("#center").attr("style","height:"+deviceH);
        $("#submit").click(function () {
            var stu_openid = $("input[name='stu_openid']").val();
            var qrcode_code = $("input[name='qrcode_code']").val();
            var teachs_code = $("input[name='teachs_code']").val();

            showLoading();
            setTimeout(function () {
                window.location.href="./dealing.php?action=signing&openid="+stu_openid+"&qrcode="+qrcode_code+"&teachs="+teachs_code;
            },1000)
        });
    })

</script>
</body>
</html>


