<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ofo单车密码分享</title>
    <script type="text/javascript" src="jquery-1.8.3-min.js"></script>
    <style type="text/css">
        body{
            background-color: #45ed91;
        }
        .image{
            width: 98%;
            height: 40%;
            border:1px solid transparent;
            margin-top:50px;
            text-align: center;
        }
        .image img{
            border:1px solid silver;
            border-radius: 500px;
        }
        .select{
            width: 98%;
            height: 60%;
            border:1px solid transparent;
            text-align: center;
        }
        .select input{
            width: 200px;
            height: 40px;
            border: 1px solid transparent;
            border-radius: 15px;
            font-size: 26px;
        }
    </style>

</head>
<body>
<?php
    session_start();
?>
<div class="image">
    <img src="<?php echo @$_SESSION['imgurl'];?>">
    <br/><div class="name"><?php echo @$_SESSION['nick'] ?></div>
</div>
<div class="select">
    <input type="tel" id="content1" placeholder="输入车牌号码"><br/><br/>
    <input type="tel" id="content2" placeholder="输入车牌密码"><br/><br/>
    <input type="button" id="get" value="确认分享">
</div>

</body>
<script type="text/javascript">
    $(function(){
        $("#get").click(function(){
            if($("#content1").val()==""){
                alert("请输入车牌号码");
                $("#content1").focus();
            }else if($("#content2").val()==""){
                alert("请输入车牌密码");
                $("#content1").focus();
            }else{
                var num  = $("#content1").val();
                var pass = $("#content2").val();

                $.get("conn.php",
                    {
                        number: num,
                        pass: pass,
                        action: 'share'
                    },
                    function (data, status) {
                        //alert("Data: " + data + "\nStatus: " + status);
                        if (status == "success") {
//                               var Json = eval_r("("+data+")");
                            if(data == 1){
                                alert("恭喜您，车子共享成功！");
                                window.location.href="./my.php";
                            }else if(data == 3){
                                alert("对不起，该车子已经被分享！");
                                window.location.href="./my.php";
                            }else{
                                alert("对不起，共享失败，将重新进入助手！");
                                window.location.href="./index.php";
                            }
                        } else {//请求失败
                            alert("请求失败，请重试！");
                        }
                    });
            }
        });
    });
</script>
</html>
<?php
