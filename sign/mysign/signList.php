<?php
require_once ("./conn.php");
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
$openid = @$_GET['openid'];
if($openid == "" || empty($openid)){
    echo "<script>alert('openid丢失');</script>";
    die();
}
//新建连接对象

$sign = new Connection();

//获取学生信息
//$stu = new Connection();
$stu_sql = "select `stu_name`,`stu_image` from `sign_students` where `stu_openid` = '".$openid."'";
$res1 = $sign->Query($stu_sql);
if($res1['status'] != 1){
    echo "<script>alert('个人信息拉取失败');</script>";
    die();
}
$stu_name = $res1[0]['stu_name'];
$stu_image = $res1[0]['stu_image'];

$sign_sql = "SELECT
                `sign_signs`.`signs_time` as `signs_time`,
                `sign_signs`.`signs_status` as `signs_status`,
                `sign_qrcode`.`qrcode_add` as `qrcode_add`,
                `sign_courses`.course_name as `course_name`,
                `sign_teachers`.`teach_name` as `teach_name`,
                `sign_teachs`.`teachs_status` as `teachs_status`
             FROM
                `sign_signs`,
                `sign_qrcode`,
                `sign_teachs`,
                `sign_courses`,
                `sign_teachers`
             WHERE
                `sign_signs`.`stu_openid` = '".$openid."' AND
                `sign_signs`.`qrcode_code` = `sign_qrcode`.`qrcode_code` AND
                `sign_signs`.`teachs_code` = `sign_teachs`.`teachs_code` AND
                `sign_teachs`.`course_num` = `sign_courses`.`course_num` AND
                `sign_qrcode`.`teach_id` = `sign_teachers`.`teach_id`
            ORDER BY 
                `sign_signs`.`signs_time` DESC";
$res = $sign->Query($sign_sql);


if($res['status'] != 1){
    echo "<script>alert('签到信息不存在');setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
    die();
}
//var_dump($res);
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
        .signTime{
            font-size: .3rem;
            font-weight: 700;
            line-height: .8rem;
            color: #014d64;
            text-align: left;
        }
        .Attendance{
            position: absolute;
            color: #fff;
            font-size: .32rem;
            line-height: .5rem;
            background-color: #d5e4eb;
            top: .15rem;
            right: 2%;
            width: .9rem
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
            margin-left: -1.8em;
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

        .teacherImg{
            display: block;
            width: .8rem;
            height: auto;
            padding-left: .1rem;
            padding-bottom: .1rem;
        }
        .attendanceP{
            position: absolute;
            font-size: .25rem;
            color: #014d64;
            right: 2%;
            bottom: .3rem;
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
            /*rgba(0,0,0,0):黑色 完全透明 /rgba(255,255,255,0):白色 完全透明  == 无色透明*/
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
    <p id="title">我的签到列表</p>
</div>
<!-- 中间 -->
<div id="center">
    <img src="<?php echo $stu_image ?>" alt="" id="headImg">
    <p id="name"><?php echo $stu_name; ?></p>
    <?php for($i = 0; $i < count($res)-1; $i++){?>

        <div class="centerChild">
            <p class="signTime"><?php echo date("Y年m月d日",$res[$i]['signs_time']); ?></p>
            <p class="Attendance" style="<?php
                if($res[$i]['signs_status'] == '出勤'){
                    echo 'color:rgba(0,0,251,0.82);';
                }else if($res[$i]['signs_status'] == '请假'){
                    echo 'color:gray';
                }else if($res[$i]['signs_status'] == '缺勤'){
                    echo "color:#f90217";
                } ?>">
                <?php echo $res[$i]['signs_status'];?>
            </p>
            <div class="courseDetail">
                <p><img src="../images/courseName.png" alt=""><span class="courseName"><?php if(strlen($res[$i]['course_name'])>=42){echo substr($res[$i]['course_name'],0,40);}else{echo $res[$i]['course_name'];}; ?></span></p>
                <p><img src="../images/courseTeacher.png" alt=""><span class="courseTeacher"><?php echo $res[$i]['teach_name']; ?></span></p>
                <p><img src="../images/courseClassroom.png" alt=""><span class="courseClassroom"><?php echo $res[$i]['qrcode_add']; ?></span></p>
                <p><img src="../images/signDetailTime.png" alt=""><span class="signDetailTime"><?php echo date('H:i:s',$res[$i]['signs_time']); ?></span></p>
            </div>
            <img src="<?php
                if($res[$i]['teachs_status'] == 0){
                    echo "../images/teacherImg.png";
                }else{
                  echo "../images/end.png";
                } ?>" alt="" class="teacherImg">
            <p class="attendanceP"><img style="width:1.5rem;" src="../images/sign.jpg"></p>
        </div>

    <?php } ?>

<!---->
<!--    <div class="centerChild">-->
<!--        <p class="signTime">2017年4月4日</p>-->
<!--        <p class="Attendance" style="color: red;">缺勤</p>-->
<!--        <div class="courseDetail">-->
<!--            <p><img src="../images/courseName.png" alt=""><span class="courseName">数据库原理与应用</span></p>-->
<!--            <p><img src="../images/courseTeacher.png" alt=""><span class="courseTeacher">赵文峰</span></p>-->
<!--            <p><img src="../images/courseClassroom.png" alt=""><span class="courseClassroom">jsj101</span></p>-->
<!--            <p><img src="../images/signDetailTime.png" alt=""><span class="signDetailTime">17:56:28</span></p>-->
<!--        </div>-->
<!--        <img src="../images/teacherImg.png" alt="" class="teacherImg">-->
<!--        <p class="attendanceP">当前出勤率为: <span>90%</span></p>-->
<!--    </div>-->
<!--    <div class="centerChild">-->
<!--        <p class="signTime">2017年4月3日</p>-->
<!--        <p class="Attendance" style="color: #000;">请假</p>-->
<!--        <div class="courseDetail">-->
<!--            <p><img src="../images/courseName.png" alt=""><span class="courseName">数据库原理与应用</span></p>-->
<!--            <p><img src="../images/courseTeacher.png" alt=""><span class="courseTeacher">赵文峰</span></p>-->
<!--            <p><img src="../images/courseClassroom.png" alt=""><span class="courseClassroom">jsj101</span></p>-->
<!--            <p><img src="../images/signDetailTime.png" alt=""><span class="signDetailTime">17:56:28</span></p>-->
<!--        </div>-->
<!--        <img src="../images/teacherImg.png" alt="" class="teacherImg">-->
<!--        <p class="attendanceP">当前出勤率为: <span>90%</span></p>-->
<!--    </div>-->
<!--    <div class="centerChild">-->
<!--        <p class="signTime">2017年4月2日</p>-->
<!--        <p class="Attendance" style="color: red;">缺勤</p>-->
<!--        <div class="courseDetail">-->
<!--            <p><img src="../images/courseName.png" alt=""><span class="courseName">数据库原理与应用</span></p>-->
<!--            <p><img src="../images/courseTeacher.png" alt=""><span class="courseTeacher">赵文峰</span></p>-->
<!--            <p><img src="../images/courseClassroom.png" alt=""><span class="courseClassroom">jsj101</span></p>-->
<!--            <p><img src="../images/signDetailTime.png" alt=""><span class="signDetailTime">17:56:28</span></p>-->
<!--        </div>-->
<!--        <img src="../images/end.png" alt="" class="teacherImg endImg">-->
<!--        <p class="attendanceP">当前出勤率为: <span>90%</span></p>-->
<!--    </div>-->
    <!-- 在center底部增加一个div ，起撑起底部作用 -->
    <div id="brace"></div>
</div>
<!-- 尾部 -->
<ul>
    <li><a href="javascript:void(0)"><img src="../images/11.png" alt=""><span class="bottomSpan">签到</span></a></li>
    <li><a href="courseList.php?openid=<?php echo $openid; ?>"><img src="../images/2.png" alt=""><span class="bottomSpan whiteColor">课程</span></a></li>
    <li><a href="sumList.php?openid=<?php echo $openid; ?>"><img src="../images/3.png" alt=""><span class="bottomSpan whiteColor">出勤</span></a></li>
</ul>
</body>
</html>
