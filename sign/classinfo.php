<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 user-scalable=no">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
    <link rel="stylesheet" type="text/css" href="./css/tools.css">
    <link rel="stylesheet" type="text/css" href="./css/main.css">
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
    <script>
        $(function () {
            //弹出框的关闭
            $(".close").click(function () {
                $(".dialog-ok").hide();
                $(".dialog-er").hide();
                $(".cover").hide();
            });

            //ajax提交验证
            $("#submit").click(function () {
               var courser
            });
        });
        $(document).on("pageinit","#pageone",function(){
            //弹出层的前期处理
            var h = document.body.clientHeight;
            var w = document.body.clientWidth;
            $(".cover").css("width",w);
            $(".cover").css("height",h);

        });
        //弹出框的控制
        function show_Dialog(type) {
            if(type=="success"){
                $(".cover").show();
                $(".dialog-ok").show();
            }else{
                $(".cover").show();
                $(".dialog-er").show();
            }
        }
    </script>

</head>
<body style="height: 100%; width: 100%; border: 1px solid red; background: blue;">

<div data-role="page" id="pageone">

    <div data-role="content" data-theme="e" class="info_container">
        <div class="info_top">
            <img src="./images/top_banner.jpg">
        </div>
        <div class="info_content">
            <p><b>课程号：</b><span>09008808890</span></p>
            <p><b>课程名：</b><span>数据库原理与应用</span></p>
            <p><b>教师名：</b><span>吴岩</span></p>
            <p><b>教师电话：</b><span>18336800665</span></p>
            <p><b>上课时间：</b><span>周五</span></p>
            <p><b>上课地点：</b><span>jsj101</span></p>
            <p><b>学生姓名：</b><span>郭月盟</span></p>
            <p><b>学生学号：</b><span>311309030118</span></p>
        </div>
        <br>
        <!-- 根据openid和签到标识码qrcode_code去更改状态-->
        <input type="hidden" name="qrcode_code" value="dsfsdfsdfsdfsdf" id="sign_qrcode_code">
        <input type="hidden" name="openid" value="sdfsdfssdfsdfsdfsdfsdfsd" id="sign_stu_openid">
        <input type="button" data-inline="false" value="一键签到" id="sign" data-theme="b">

    </div>
    <div data-role="footer">
        <h1>版权所有：河南理工大学 &copy;2017</h1>
    </div>
    <!-- 弹出框 -->
    <div class="cover"></div>
    <div class="dialog-ok">
        <div data-role="button" data-icon="delete" data-iconpos="notext" data-theme="b" class="close"></div>
        <div class="dialog-ok-content">
            操作成功
            <img src="./images/success.png">
        </div>
    </div>
    <div class="dialog-er">
        <div data-role="button" data-icon="delete" data-iconpos="notext" data-theme="e" class="close"></div>
        <div class="dialog-er-content">
            操作失败
            <img src="./images/error.png">
        </div>
    </div>
    <!-- 弹出框 -->
</div>
</body>
</html>

