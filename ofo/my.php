<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ofo单车分享助手</title>
    <script type="text/javascript" src="jquery-1.8.3-min.js"></script>
    <style type="text/css">
        body{
            background-color: #45ed91;
        }
        .image{
            width: 98%;
            height: 40%;
            border:1px solid transparent;
            margin-top:80px;
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
            height: 50px;
            border: 1px solid transparent;
            border-radius: 15px;
            font-size: 26px;
        }
    </style>

</head>
<body>
<?php
    session_start();
    if(empty($_SESSION['imgurl'])){
        header("Location:http://sign.goalschina.com/ofo/");
        exit(0);
    }
?>
<div class="image">
    <img src="<?php echo @$_SESSION['imgurl'];?>">
    <br/><div class="name"><?php echo @$_SESSION['nick'] ?></div>
</div>
<div class="select">
    <input type="button" id="get" value="我要密码"><br/><br/>
    <input type="button" id="put" value="我要分享">
</div>

</body>
<script type="text/javascript">
    $(function(){
        $("#get").click(function(){
            window.location.href="./show.php";
        })
        $("#put").click(function(){
            window.location.href="./share.php";
        })
    });
</script>
</html>
<?php

