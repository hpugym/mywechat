<?php
    class getInfo{
        private $appId  = "wxafa5f3c55b3a7617";
        private $appSecret = "bbde89d0d696cee2fb01e3054d1d7ee8";
        private $openid;
        /**curl发送get请求获取信息
         * @param $url
         * @return mixed
         */
        private function getData($url){
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_HEADER,0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;
        }
        /**
         * 获取access_token
         * @return mixed
         */
        public function getUserInfo($code) {
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.'&secret='.$this->appSecret.'&code='.$code.'&grant_type=authorization_code';
            $res = $this->getData($get_token_url);
            $json_obj = json_decode($res,true);
            //根据openid和access_token查询用户信息
            $access_token = @$json_obj['access_token'];
            $openid = @$json_obj['openid'];
            $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
            $res = $this->getData($get_user_info_url);
            $a = json_decode($res, true);
            $headurl = @$a['headimgurl'];
            $nickname =@$a['nickname'];
            $len = substr($headurl, 0, strlen($headurl)-1);
            $newheadurl = $len."96";
            if(!empty($nickname)){
                $error = 1;
            }else{
                $error = 2;
            }
            $arr = array('error'=>$error,'img'=>$newheadurl,'nickname'=>$nickname,'openid'=>$openid);
            return json_encode($arr);
        }
    }
    $info = new getInfo();
    $code = $_GET['code'];
    $res = $info->getUserInfo($code);
    $data = json_decode($res,true);
    if($data['error']==2){
        echo "<script>alert('加载错误,将重新加载');window.location.href='./getauthorize.php';</script>";
        exit(0);
    }else{
?>
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
            //全局处理数据
            var openid  =$("#stu_openid");
            var num     = $("#stu_num");
            var name    = $("#stu_name");
            var phone   = $("#stu_phone");
            var verify  = $("#stu_verify");
            num.focus(function(){
                $("#num_notice").html("");
            });

            name.focus(function(){
                $("#name_notice").html("");
            });

            phone.focus(function(){
                $("#phone_notice").html("");
            });
            verify.focus(function(){
                $("#verify_notice").html("");
            });

            //弹出框的关闭
            $(".close").click(function () {
                $(".dialog-ok").hide();
                $(".dialog-er").hide();
                $(".cover").hide();
            });
            //获取验证码的处理
            $("#getVerify").click(function(){
                //alert("ok");
                var t = 4;
                var get = $("#getVerify");
                var wait = $("#showVerify");
                //先进行手机号的验证
                var myphone   = $("#stu_phone");
                if(myphone.val() == ""){
                    $("#phone_notice").html("请输入手机号");
                }else if(!(/^1[34578]\d{9}$/.test(myphone.val()))){
                    $("#phone_notice").html("手机号不合法");
                }else {
                    //这里使用ajax进行验证码的发送



                    var Timer = setInterval(function () {
                        get.css("display", "none");
                        wait.css("display", "inline-block");
                        t--;
                        if (t == 0) {
                            get.css("display", "inline-block");
                            wait.css("display", "none");
                            clearInterval(Timer);
                        } else {
                            wait.html(t + "秒后获取");
                        }
                    }, 1000);
                }
            });
            //ajax提交验证
            $("#submit").click(function () {
                //alert(num.val())
                if(num.val() == ""){
                    $("#num_notice").html("学号不能为空");
                }else if((num.val()).length != 12 ){
                    $("#num_notice").html("请输入正确的学号");
                }else if(name.val() == ""){
                    $("#name_notice").html("姓名不能为空");
                }else if(phone.val() == ""){
                    $("#phone_notice").html("手机号不能为空");
                }else if(!(/^1[34578]\d{9}$/.test(phone.val()))){
                    $("#phone_notice").html("手机号不合法");
                }else if(verify.val() == ""){
                    $("#verify_notice").html("请输入验证码");
                }else if(verify.val() != "1234"){
                    $("#verify_notice").html("验证码不正确");
                }else{
                    $("#num_notice").html("");
                    $("#name_notice").html("");
                    $("#phone_notice").html("");
                    //这里进行数据的保存
                    $.ajax({
                        url:"a.php",
                        type:"get",
                        data:{},
                        async:"false",
                        dataType:"html",
                        success:function (data) {

                             var data2=JSON.parse(data);
                            alert('成功！'+typeof(data)+data);
                            alert(typeof(data2)+data2.name+"; "+data2.sex);
                        },
                        error:function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });



                }
            });
        });
        $(document).on("pageinit","#pageone",function(){
            //弹出层的前期处理
//            var h = document.body.clientHeight;
//            var w = document.body.clientWidth;
//            $(".cover").css("width","100%");
//            $(".cover").css("height","100%");

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
<body>

<div data-role="page" id="pageone">
    <div data-role="header">
        <h1>个人信息绑定</h1>
    </div>

        <div data-role="content" data-theme="e">
            <div class="blind_img"><img src="<?php echo $data['img'] ?>"></div>
            <div class="blind_nick"><?php echo $data['nickname']?></div>
            <form method="post" action="#">
                <div data-role="fieldcontain">
                    <input type="hidden" name="stu_openid" id="stu_openid" value="<?php echo $data['openid']?>">

                    <label for="stu_num">学号：<span id="num_notice" style="color: red; margin-left: 10px;"></span></label>
                    <input type="tel" name="stu_num" id="stu_num" placeholder="请输入学号..." data-theme="c">
                    <br>

                    <label for="stu_name">姓名：<span id="name_notice" style="color: red; margin-left: 10px;"></span></label>
                    <input type="text" name="stu_name" id="stu_name" placeholder="请输入姓名..." data-theme="c">
                    <br>

                    <label for="stu_phone">手机号：<span id="phone_notice" style="color: red; margin-left: 10px;"></span></label>
                    <input type="tel" name="stu_phone" id="stu_phone" placeholder="请输入手机号..." data-theme="c">

                    <br>
                    <label>
                        验证码：
                        <span id="verify_notice" style="color: red; margin-left: 10px;"></span>
                        <span style="display: inline-block; float: right; border: 1px solid transparent; color: blue" id="getVerify">获取验证码</span>
                        <span style="display: none; float: right; border: 1px solid transparent; color: red" id="showVerify"></span>
                    </label>
                    <input type="tel" name="stu_verify" id="stu_verify" placeholder="请输入验证码..." data-theme="c">
                </div>
                <input type="button" data-inline="false" value="提交" id="submit" data-theme="b">
            </form>
        </div>
        <div data-role="footer">
            <h1>版权所有：河南理工大学 &copy;2017</h1>
        </div>
<?php }?>
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

