<html>
    <head>
        <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
        <title>ofo单车密码获取</title>
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
    ?>
        <div class="image">
            <img src="<?php echo @$_SESSION['imgurl'];?>">
            <br/><div class="name"><?php echo @$_SESSION['nick'] ?></div>
        </div>
        <div class="select">
              <input type="tel" id="content" placeholder="输入车牌号码"><br/><br/>
              <input type="button" id="get" value="查询密码">
        </div>

    </body>
    <script type="text/javascript">
        $(function(){
            $("#get").click(function(){
                if($("#content").val()==""){
                    alert("请输入车牌号码");
                    $("#content").focus();
                }else{
                    var num = $("#content").val();
                    $.get("conn.php",
                        {
                            number: num,
                            action: 'get'
                        },
                        function (data, status) {
                            //alert("Data: " + data + "\nStatus: " + status);
                            if (status == "success") {
//                               var Json = eval_r("("+data+")");
                                if(data=="AAAA"){
                                    alert("对不起，该车子未找到！");
                                }else{
                                    alert('车子的密码是：'+data);
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
