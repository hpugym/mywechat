<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 user-scalable=no">
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
    <script>
      window.onload = function () {
          $.ajax({
              url:"./signing.php",
              type:'post',
              data:{"da":"sdf"},
              async : false, //默认为true 异步
              dataType:'json'
              error:function(){
                  alert('error');
              },
              success:function(data){
                  alert(1);
              }
          });
      };
    </script>

</head>
<body>

</body>
</html>