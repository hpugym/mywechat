<?php
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
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
if(@$data['error']==2){
    echo "<script>alert('加载错误,将重新加载');window.location.href='../getauthorize.php';</script>";
    exit(0);
}else{
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
            height: 40px;
            display: table;
            line-height: 40px;
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
            font-size: 0.27rem;
            color:darkslategrey;
            margin-top: -10px;
            color: #014d64;
        }
        #center input:nth-child(3){
            margin-top: .3rem;
        }
        #center input:nth-child(6){
            margin-bottom:.2rem;
        }
        input{
            display: block;
            width: 76.4%;
            margin-left: 10%;
            background-color: #b3ccd5;
            color:#fff;
            height: 0.7rem;
            border: none;
            margin-bottom: 0.25rem;
            border-radius: 0.15rem;
            border: 1px solid #b3ccd5;
            font-size: 0.27rem;
            padding-left: 3.6%;
        }
        input::-webkit-input-placeholder {
            color: #fff;
        }
        input:-moz-placeholder  {
            color: #fff;
        }
        input:-ms-input-placeholder{
            color: #fff;
        }
        #getCode{
            display: block;
            color: #014d64;
            margin-left: 70%;
            font-size: 0.27rem;
            text-decoration: none;
        }
        #time{
            color: #f6774b;
            font-size: 0.27rem;
            margin-left: 72%;
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
            margin-top: .2rem;
            margin-bottom: .2rem;
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
<!-- 头部 -->
<div class="table">
    <p id="title">个人信息绑定</p>
</div>
<!-- 中间 -->
<div id="center">
    <div id="centerChild">
        <img src="<?php echo @$data['img'] ?>" alt="">
        <p id="name"><?php echo @$data['nickname']?></p>
        <form action="dealing.php?action=binding" method="post" onsubmit="return checkData();">
            <input type="hidden" name="stu_openid" id="stu_openid" value="<?php echo @$data['openid']?>">
            <input type="hidden" name="stu_image" id="stu_image" value="<?php echo @$data['img']?>">
            <input type="tel" name="stu_num" placeholder="请输入学号" onkeyup="ValidateValue(this)" id="studentID">
            <input type="text" placeholder="请输入姓名" id="xingming" name="stu_name">
            <input type="tel" placeholder="请输入手机号" onkeyup="ValidateValue(this)" id="phone" name="stu_phone">
            <input type="tel" placeholder="请输入校验码" maxlength="4" onkeyup="ValidateValue(this)" id="identify" name="stu_validate">
            <div id="clickBtn">
                <a href="#" id="getCode">获取验证码</a>
                <p id="time"></p>
            </div>
            <input style="text-align: center" type="submit" name="submit" value="提&nbsp;&nbsp;&nbsp;交" id="submit">
        </form>
    </div>
</div>
<!-- 尾部 -->
<div class="table">
    <p id="footer">版权所有&copy;&nbsp;&nbsp;&nbsp;河南理工大学</p>
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
    // 使输入的字体只能是数字。
    function ValidateValue(textbox) {
        var IllegalString = "\`~@#;,.!#$%^&*()+{}|\\:\"<>?-=/,\'";
        var textboxvalue = textbox.value;
        var index = textboxvalue.length - 1;
        var s = textbox.value.charAt(index);
        if(IllegalString.indexOf(s) >= 0) {
            s = textboxvalue.substring(0, index);
            textbox.value = s;
        }
    }
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
    // 隐藏loading提示
    function hideLoading(){
        $('.overlay').hide();
        $("#loadingTip").hide();
        $(document).scroll(function(){
            return true;
        })
    }
    $(function(){
        // document.documentElement.clientHeight:获取设备的高度
        var deviceH=document.documentElement.clientHeight-$(".table").height()*2+"px";
        // 设置中间部分的高度
        $("#center").attr("style","height:"+deviceH);
        // 点击"获取验证码"
        $("#getCode").click(function(){
            // 判断手机格式是够正确
            var phone=$("#phone")[0].value;
            if (!(/^1[34578]\d{9}$/.test(phone))) {
                alert("手机号格式不正确");
                $("#phone").focus();
                return false;
            }
            // 手机格式正确，发送验证码
            $.ajax({
                url:'sending.php',
                type:"post",
                dataType:"json",
                async:'false',
                data:{
                    "to" : phone
                },
                success:function(data){
                    //alert(data.resp.respCode);
                    //验证码短信成功发送到用户手机上以后，进行计时
                    // 设置此时的a标签不可用
//                    var data2 = (Function("","return "+data))();
                    if(data.resp.respCode == "000000"){
                        $("#getCode").css("display","none");
                        $("#time").html(60+"s");
                        console.log("点击了一次");
                        var i=60;
                        var stop=setInterval(function(){
                            if (i==0) {
                                clearInterval(stop);
                                $("#getCode").css("display","block");
                                $("#time").html("");
                                $("#getCode").html('重发校验码');
                                // $("#time")[0]：表示jquery对象转化为js对象  ，js对象有innerHTML
                                // $("#time")[0].innerHTML=" ";
                            }else{
                                i--;
                                // $("#time")是jquery对象 它的html方法
                                $("#time").html(i+'s');
                            }
                        },1000);
                    }else{
                        alert("验证码发送失败!  ");
                    }
                },
                error:function(errorThrown){
                    alert(errorThrown);
                }
            })
        });
    })
    var check=0;
    // 点击"提交"按钮
    function checkData() {
        var stu_num = $("input[name='stu_num']");
        var stu_name = $("input[name='stu_name']");
        var stu_phone = $("input[name='stu_phone']");
        var stu_validate = $("input[name='stu_validate']");

        if($.trim(stu_num.val()) =="" || stu_num.val() ==null){
            alert("学号不能为空");
            stu_num.focus();
            return false;
        }else if(stu_num.val().length != 12){
            alert("学号为12位");
            stu_num.focus();
            return false;
        }else if($.trim(stu_name.val()) =="" || stu_name.val() ==null){
            alert("姓名不能为空");
            stu_name.focus();
            return false;
        }else if($.trim(stu_phone.val()) =="" || stu_phone.val() ==null){
            alert("手机号不能为空");
            stu_phone.focus();
            return false;
        }else if (!(/^1[34578]\d{9}$/.test(stu_phone.val()))) {
            alert("手机号格式不正确");
            stu_phone.focus();
            return false;
        }else if($.trim(stu_validate.val()) =="" || stu_validate.val() ==null){
            alert("验证码不能为空");
            stu_validate.focus();
            return false;
        }else{
            showLoading();
            setTimeout(function () {
                return true;
            },1000);
        }
    }
</script>
</body>
</html>
<?php } ?>