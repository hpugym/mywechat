<?php
require_once ("./conn.php");
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
$openid = @$_GET['openid'];
if($openid == "" || empty($openid)){
    echo "<script>alert('openid丢失');</script>";
    die();
}
//新建连接
$sumList = new Connection();
//拉取学生信息
$stu_sql = "select `stu_name`,`stu_image`,`stu_num` from `sign_students` where `stu_openid` = '".$openid."'";
$res = $sumList->Query($stu_sql);
if($res['status'] != 1){
    echo "<script>alert('个人信息拉取失败');</script>";
    die();
}
$stu_name = $res[0]['stu_name'];
$stu_image = $res[0]['stu_image'];
$stu_num = $res[0]['stu_num'];

//拉取全部签到记录
$signAll = "select count(*) from `sign_signs` where `stu_openid`='".$openid."'";
$res1 = $sumList->Query($signAll);
if($res1['status'] != 1){
    echo "<script>alert('全部签到列表获取失败');</script>";
    die();
}
$signAllNum = $res1[0]['count(*)'];//全部签到

$signGet = "select count(*) from `sign_signs` where `stu_openid`='".$openid."' and `signs_status` ='出勤'";
$res2 = $sumList->Query($signGet);
if($res2['status'] != 1){
    echo "<script>alert('签到列表获取失败');</script>";
    die();
}
$signGetNum = $res2[0]['count(*)'];

$signLeave = "select count(*) from `sign_signs` where `stu_openid`='".$openid."' and `signs_status` ='请假'";
$res3 = $sumList->Query($signLeave);
if($res3['status'] != 1){
    echo "<script>alert('签到列表获取失败');</script>";
    die();
}
$signLeaveNum = $res3[0]['count(*)'];

$signLost = "select count(*) from `sign_signs` where `stu_openid`='".$openid."' and `signs_status` ='缺勤'";
$res4 = $sumList->Query($signLost);
if($res4['status'] != 1){
    echo "<script>alert('签到列表获取失败');</script>";
    die();
}
$signLostNum = $res4[0]['count(*)'];


if($signAllNum == 0){
    $percent = 0;
}else{
    $percent = sprintf("%.2f",($signAllNum - $signLostNum)/$signAllNum*100);
}
if($percent >= 90){
    $status = "优&nbsp;秀";
}else if($percent >=80 && $percent < 90){
    $status ="良&nbsp;好";
}else if($percent >=60 && $percent < 80){
    $status ="及&nbsp;格";
}else{
    $status = "不合格";
}

//echo $signAllNum."<br/>";
//echo $signGetNum."<br/>";
//echo $signLeaveNum."<br/>";
//echo $signLostNum."<br/>";
//echo sprintf("%.2f",$percent);
//die();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
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
            height: 50px;
            display: table;
            line-height: 50px;
        }
        /*table-cell:元素会作为一个表格单元格显示（类似 <td> 和 <th>）*/
        #title{
            font-size: 0.37rem;
            color: #fff;
            text-align:center;
            display: table-cell;
            vertical-align: middle;
            width: 100%;
            height: 100%;
        }
        #center{
            width: 100%;
            text-align:center;
            background-color:#d5e4eb;
        }
        #headImg{
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
            margin-bottom: .5rem;
        }
        .centerChild{
            position: relative;
            width: 78%;
            margin-left: 10%;
            padding: 1%;
            margin-bottom: .3rem;
            background-color: #fff;

        }
        .courseNumberImg{
            width: .75rem;
            display: block;
            margin-bottom: .2rem;
        }
        .courseNumber{
            position: absolute;
            top: .05rem;
            left: .95rem;
            font-size: .3rem;
            line-height: .8rem;
            color: #014d64;
            text-align: left;
        }
        .Attendance{
            position: absolute;
            color: #000;
            font-size: .3rem;
            line-height: .5rem;
            background-color: #d5e4eb;
            top: .15rem;
            right: 2%;
            width: 1.3rem;
            font-weight: 700;
        }
        .courseDetail{
            color: #014d64;
            font-size:.27rem;
            padding-left: .8rem;
            margin-top: .1rem;
        }
        .courseDetail p{
            height: .6rem;
            line-height: .6rem;
            text-align: left;
        }
        .courseDetail img{
            width: .35rem;
            height: auto;
            position: relative;
            top: .07rem;
        }
        .courseDetail p span{
            display: inline-block;
            margin-left: .4rem;
        }

        .personImg{
            display: block;
            width: .5rem;
            height: auto;
            padding-left: .1rem;
            padding-bottom: .1rem;
            margin-top: .15rem;
        }
        .attendanceP{
            position: absolute;
            font-size: .3rem;
            color: #014d64;
            right: 2%;
            bottom: .2rem;
            font-weight: 700;
        }
        .endImg{
            width:.55rem;
        }
        #brace{
            width: 100%;
            height:70px;
        }
        ul{
            list-style: none;
            width: 100%;
            height: 53px;
            position: fixed;
            bottom: 0;
            background-color:#373737;
        }
        ul li{
            display: table;
            width: 33.1%;
            float: left;
            height: 53px;
        }
        ul li a{
            display: table-cell;
            /*子元素：{vertical-align:middle} 此属性设置子元素相对于父元素的垂直对齐方式。*/
            vertical-align: middle;
        }
        ul li img{
            display: block;
            width: .5rem;
            height: auto;
            margin: 0 auto;
        }
        a,a:hover,a:active,a:visited,a:link,a:focus{
            -webkit-tap-highlight-color:rgba(0,0,0,0);
            -webkit-tap-highlight-color: transparent;
            /*outline:none;*/
            background: none;
            text-decoration: none;
        }
        .bottomSpan{
            display: block;
            font-size: .2rem;
            color: #d5e4eb;
            text-align: center;
            margin-top: .05rem;
        }
        .whiteColor{
            color: #fff;
        }
    </style>
</head>
<body>
<!-- 头部 -->
<div class="table">
    <p id="title">我的出勤状况</p>
</div>
<!-- 中间 -->
<div id="center">
    <img src="<?php echo $stu_image ?>" alt="" id="headImg">
    <p id="name"><?php echo $stu_name; ?></p>
    <div class="centerChild">
        <img src="../images/courseNumberImg.png" alt="" class="courseNumberImg">
        <p class="courseNumber"><?php echo $stu_num; ?></p>
        <p class="Attendance" style="color:#f90217;"><?php echo $status; ?></p>
        <div class="courseDetail">
            <p><img src="../images/33.png" alt=""><span class="courseName"><?php echo $signAllNum; ?>条签到信息</span></p>
            <p><img src="../images/attendanceS.png" alt=""><span class="courseTeacher"><?php echo $signGetNum; ?>条出勤信息</span></p>
            <p><img src="../images/leave.png" alt=""><span class="allTime"><?php echo $signLeaveNum; ?>条请假记录</span></p>
            <p><img src="../images/absence.png" alt=""><span class="signDetailTime"><?php echo $signLostNum ?>条缺勤记录</span></p>
        </div>
        <img src="../images/person.png" alt="" class="personImg">
        <p class="attendanceP">我的当前出勤率为: <span><?php echo $percent; ?>%</span></p>
    </div>
</div>
<!-- 尾部 -->
<ul>
    <li><a href="signList.php?openid=<?php echo $openid; ?>"><img src="../images/1.png" alt=""><span class="bottomSpan whiteColor">签到</span></a></li>
    <li><a href="courseList.php?openid=<?php echo $openid; ?>"><img src="../images/2.png" alt=""><span class="bottomSpan whiteColor">课程</span></a></li>
    <li><a href="javascript:void(0);"><img src="../images/33.png" alt=""><span class="bottomSpan ">出勤</span></a></li>
</ul>
<script type="text/javascript" src="../js/jquery-1.8.3-min.js"></script>
<script type="text/javascript">

    $(function(){
        // document.documentElement.clientHeight:获取设备的高度
        var deviceH=document.documentElement.clientHeight-$(".table").height()*2+"px";
        // 设置中间部分的高度
        $("#center").attr("style","height:"+deviceH);
    })
</script>
</body>
</html>
