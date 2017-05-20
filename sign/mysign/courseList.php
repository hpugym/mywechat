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
$courseSign = new Connection();

//拉取学生信息
$stu_sql = "select `stu_name`,`stu_image` from `sign_students` where `stu_openid` = '".$openid."'";
$res = $courseSign->Query($stu_sql);
if($res['status'] != 1){
    echo "<script>alert('个人信息拉取失败');</script>";
    die();
}
$stu_name = $res[0]['stu_name'];
$stu_image = $res[0]['stu_image'];

//拉取课程信息

$course_sql = "SELECT 
                     `sign_teachs`.`course_num` as `course_num`,
                     `sign_courses`.`course_name` as `course_name`,
                     `sign_teachers`.`teach_name` as `teach_name`,
                     `sign_teachs`.`teachs_time` as `teachs_time`,
                     `sign_teachs`.`teachs_grade` as `teachs_grade`,
                     `sign_teachers`.`teach_phone` as `teach_phone`,
                     `sign_signs`.`teachs_code` as `teachs_code`,
                     `sign_teachs`.`teachs_status` as `teachs_status`
               FROM `sign_signs`, `sign_teachs`,`sign_teachers`,`sign_courses`
               WHERE
                    `sign_signs`.`teachs_code` = `sign_teachs`.`teachs_code` AND
                    `sign_teachs`.`teach_id` = `sign_teachers`.`teach_id` AND
                    `sign_teachs`.`course_num` = `sign_courses`.`course_num` AND
                    `sign_signs`.`stu_openid` = '".$openid."'
              GROUP BY `sign_signs`.`teachs_code`
              ORDER BY
                    `sign_teachs`.`course_num` ASC";
$res1 = $courseSign->Query($course_sql);
if($res1['status'] != 1){
    echo "<script>alert('课程信息列表获取失败');</script>";
    die();
}
//var_dump($res1);
//die();
//拉取课程出勤率
    //遍历teachs_code
    for($i = 0; $i < count($res1)-1; $i++){
        $teachs_code_arr[$i] = $res1[$i]['teachs_code'];
    }
    //逐个查询出勤信息
    for($j = 0; $j < count($teachs_code_arr); $j++){
        //拉取整个签到数
        $sql = "select count(*) from `sign_signs` where(`stu_openid`='".$openid."' and `teachs_code` = $teachs_code_arr[$j])";
        $num = $courseSign->Query($sql);
        if($num['status'] != 1){
            echo "<script>alert('出勤信息获取失败');setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
            die();
        }

        $all = $num[0]['count(*)'];
        //拉取缺勤数
        $sql2 = "select count(*) from `sign_signs` where(`stu_openid`='".$openid."' and `teachs_code` = $teachs_code_arr[$j] and `signs_status` = '缺勤')";
        $num2 = $courseSign->Query($sql2);
        if($num2['status'] != 1){
            echo "<script>alert('出勤信息获取失败');setTimeout(function() {WeixinJSBridge.call('closeWindow');},500)</script>";
            die();
        }

        $lost = $num2[0]['count(*)'];
        $myrate = sprintf("%.2f",($all - $lost)/$all * 100);
        $rateInfo[$teachs_code_arr[$j]]['rate'] = $myrate;
    }
//    var_dump($rateInfo);
//    die();

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
            margin-top: .1rem;
        }
        .attendanceP{
            position: absolute;
            font-size: .3rem;
            color: #014d64;
            right: 2%;
            bottom: .3rem;
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
        /*-webkit-tap-highlight-color:rgba(255,255,255,0)/rgba(0,0,0,0):同时屏蔽ios和android下点击元素时出现的阴影。 设置为transparent:只在ios上有效*/
        /*a:focus：a标签获取焦点时*/
        a,a:hover,a:active,a:visited,a:link,a:focus{
            /*ios android*/
            -webkit-tap-highlight-color:rgba(255,255,255,0);
            /*ios*/
            -webkit-tap-highlight-color: transparent;
            /*outline：位于元素border外边的轮廓*/
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
    <p id="title">我的课程列表</p>
</div>
<!-- 中间 -->
<div id="center">
    <img src="<?php echo $stu_image ?>" alt="" id="headImg">
    <p id="name"><?php echo $stu_name; ?></p>

    <?php for($s = 0; $s < count($res1)-1; $s ++){ ?>
        <div class="centerChild">
            <img src="../images/courseNumberImg.png" alt="" class="courseNumberImg">
            <p class="courseNumber"><?php echo $res1[$s]['course_num']; ?></p>
            <p class="Attendance">
                <?php
                    if($rateInfo[$res1[$s]['teachs_code']]['rate'] >= 90){
                        echo "<span style='color:rgba(0,0,251,0.82);'>优&nbsp;秀</span>";
                    }else if($rateInfo[$res1[$s]['teachs_code']]['rate']>=80){
                        echo "<span style='color:black;'>良&nbsp;好</span>";
                    }else if($rateInfo[$res1[$s]['teachs_code']]['rate'] >= 60){
                        echo "<span style='color:gray;'>及&nbsp;格</span>";
                    }else{
                        echo "<span style='color:#f90217;'>不合格</span>";
                    }
                ?>
            </p>
            <div class="courseDetail">
                <p><img src="../images/courseName.png" alt=""><span class="courseName"><?php echo $res1[$s]['course_name']; ?></span></p>
                <p><img src="../images/courseTeacher.png" alt=""><span class="courseTeacher"><?php echo $res1[$s]['teach_name']; ?></span></p>
                <p><img src="../images/allTime.png" alt=""><span class="allTime"><?php echo $res1[$s]['teachs_time']; ?>学时</span></p>
                <p><img src="../images/credit.png" alt=""><span class="signDetailTime"><?php echo $res1[$s]['teachs_grade']; ?>学分</span></p>
                <p><img src="../images/phone.png" alt=""><span class="phone"><?php echo $res1[$s]['teach_phone']; ?></span></p>
            </div>
            <img src="<?php
                if($res1[$s]['teachs_status'] == 0){
                    echo "../images/teacherImg.png";
                }else{
                    echo "../images/end.png";
                }
            ?>" alt="" class="teacherImg">
            <p class="attendanceP">我的当前出勤率为: <span><?php echo $rateInfo[$res1[$s]['teachs_code']]['rate']; ?>%</span></p>
        </div>
    <?php } ?>
    <!-- 在center底部增加一个div ，起撑起底部作用 -->
    <div id="brace"></div>

</div>
<!-- 尾部 -->
<ul>
    <li><a href="signList.php?openid=<?php echo $openid; ?>"><img src="../images/1.png" alt=""><span class="bottomSpan whiteColor">签到</span></a></li>
    <li><a href="javascript:void(0)"><img src="../images/22.png" alt=""><span class="bottomSpan">课程</span></a></li>
    <li><a href="sumList.php?openid=<?php echo $openid; ?>"><img src="../images/3.png" alt=""><span class="bottomSpan whiteColor">出勤</span></a></li>
</ul>
</body>
</html>
