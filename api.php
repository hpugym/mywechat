<?php
    error_reporting(0);
    $num = @$_GET['num'];//快递号
    $com = @$_GET['com'];//快递公司
    $url = "http://api.kuaidi100.com/api?id=0d4a14d8769a3a8a&com=".$com."&nu=".$num."&valicode=&show=0&muti=1&order=desc";
    $res = file_get_contents($url);
    $info = json_decode($res, true);
    //var_dump($info);
    //echo "<br/>";
    if($info['message'] == "ok"){
//        echo "<hr/>";
////        var_dump($info['data'][0]);

        //处理数据
        $company = "";
        if($com == "huitongkuaidi"){
            $company = "百世快递";
        }else if($com == "tiantian"){
            $company = "天天快递";
        }
?>


<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>物流</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body, input, textarea, select, button {
            font: 12px "Microsoft YaHei", Verdana, arial, sans-serif;
            line-height: 22px;
        }

        html, body {
            text-align: left;
            background: #3278e6;
            margin: 20px 0;
        }
        a {
            outline: none;
            text-decoration: none;
            cursor: pointer;
            color: #3278e6;
            blur: expression(this.blur());
        }
        .search-box {
            width: 1000px;
            margin: auto;
            position: relative;
            z-index: 96;
            margin:  0 auto;
        }
        /*头部*/
        .select-com {
            /*background: url("/images/spider_search_v4.png") 0px -1161px repeat-x;*/
            background-color: #fff;
            padding-left: 20px;
            height: 52px;
            line-height: 52px;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #CCCCCC;
        }
        /*百世快递*/
        .select-com span {
            margin-right: 10px;
            padding: 2px 0px 2px 24px;
            color: #828282;
            font-weight: normal;
        }
        /*官网*/
        .result-companyurl {
            border-left: 1px solid #aaaaaa;
            background: url("./api_image/spider_search_v4.png") 10px -993px no-repeat;
            color: #828282;
            font-size: 14px;
            padding-left: 28px;
            font-weight: normal;
        }
        .mr10px {
            margin-right: 10px;
        }
        /*电话*/
        .select-com .ico-tel {
            background: url("./api_image/arrowTop.png") 4px -868px no-repeat;
            color: #828282;
        }
        /*物流内容*/
        .relative {
            position: relative;
        }
        .result-top {
            width: 918px;
            height: 43px;
            background-color: #ffffff;
            /* border: 1px solid #a2bbda; */
            border-bottom: none;
        }
        .result-top .col1-down {
            width: 140px;
            text-align: center;
            font-size: 16px;
            color: #5a5a5a;
            cursor: pointer;
            padding-left: 14px;
        }
        .result-top .col2 {
            width: 303px;
            text-align: left;
            font-size: 16px;
            color: #5a5a5a;
            padding-left: 50px;
        }
        .result-top span, .result-top a {
            display: inline-block;
            height: 43px;
            line-height: 43px;
            vertical-align: middle;
            font-size: 14px;
        }
        .result-info {
            width: 1200px;
            float: left;
            font-size: 15px;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        /*td*/
        .result-info .last td, .result-info .last td a {
            color: #ff7800;
            border-bottom: none;
        }
        .result-info .row1 {
            width: 150px;
            text-align: center;
            padding-left: 14px;
            padding-right: 0;
        }
        .result-info td {
            padding: 7px;
            color: #828282;
            padding-top: 18px;
        }
        #queryContext {
            z-index: 3;
        }
        .query-box {
            width: 1200px;
            margin: 0 auto;
            float: left;
            margin-bottom: 10px;
        }
        .result-info .last td, .result-info .last td a {
            color: #ff7800;
            border-bottom: none;
        }
        .result-info .status-check {
            background: url("./api_image/spider_search_v4.png") 10px -717px no-repeat;
        }
        .result-info .status {
            width: 30px;
            background: url("./api_image/spider_search_v4.png") 13px -764px no-repeat;
        }
        .status .col2 {
            position: relative;
            z-index: -1;
        }
        .status .line1 {
            position: absolute;
            left: 6.4px;
            width: 0.57em;
            height: 5em;
            border-right: 0.08em solid #d2d2d2;
            top: -65px;
            z-index: -1;
        }
        .context {
            font-size: 14px;
            padding-left: 0px !important;
        }
        .result-info .status-first {
            background: url("api_image/circle.png") 13px -804px no-repeat;
        }
        table tr{
            /*font-size: 14px;*/
        }
        .qr-box {
            padding: 6px 20px;
            text-align: center;
            font-size: 14px;
            color: #828282;
            background-color: #fff;
        }
        a {
            outline: none;
            text-decoration: none;
            cursor: pointer;
            color: #3278e6;
            blur: expression(this.blur());
        }
    </style>
</head>
<body>
<div class="search-box" id="searchBox">
    <div id="resultHeader" class="select-com relative hidden" style="display: block;">
        <span id="companyName" class="hidden" style="display: inline;"><?php echo $company?></span>
        <a id="companyUrl" href="<?php echo $info['comurl']; ?>" target="_blank" class="mr10px result-companyurl" rel="nofollow">官网</a>
        <span id="companyTel" class="ico-tel">电话:<?php echo $info['comcontact']?></span>
    </div>
    <div id="queryContext" class="hidden relative query-box" style="display: block;">
        <div class="result-top" id="resultTop">
            <span id="sortSpan" class="col1-down">时间</span>
            <span class="col2">地点和跟踪进度</span>
        </div>
        <table id="queryResult" class="result-info" cellspacing="0">
            <tbody>
            <?php
                $k = 0;
                for($i=0; $i<count($info['data']); $i++) {
                    $k++;
                    if($k == 1){
             ?>
                    <tr class="last">
                        <td class="row1">
                            <span class="day"><?php echo $info['data'][$i]['time'] ?></span>
                        </td>
                        <td class="status status-check">&nbsp;
                            <div class="col2">
                    <span class="step">
                        <span class="line1"></span>
                        <span class="line2"></span>
                    </span>
                            </div>
                        </td>
                        <td class="context"><?php echo $info['data'][$i]['context'] ?></td>
                    </tr>

                     <?php
                        }else{
                     ?>
                <tr>
                    <td class="row1">
                        <span class="day"><?php echo $info['data'][$i]['time'] ?></span>
                    </td>
                    <td class="status">&nbsp;
                        <div class="col2">
                            <span class="step">
                                <span class="line1"></span>
                                <span class="line2"></span>
                            </span>
                        </div>
                    </td>
                    <td class="context">
                        <?php echo $info['data'][$i]['context'] ?>
                    </td>
                </tr>
<?php
                }
          }
    }else{
        echo "<script>alert('对不起，暂时未查询到物流信息，请稍后再试！')</script>";
    }
?>
            </tbody>
        </table>
    </div>
    <div id="queryPs" class="qr-box hidden" style="display: block;">
        快递有问题？请先拨打
        <a target="_blank" href="https://www.kuaidi100.com/network/plist.shtml">快递公司电话</a>
        ，若不能解决，还可到
        <a target="_blank" href="http://sswz.spb.gov.cn/?from=kuaidi100" rel="nofollow">国家邮政总局申诉</a>
        哦。
    </div>
</div>
</body>
</html>
