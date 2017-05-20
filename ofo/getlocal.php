<?php
$jssdk = new JSSDK("wxafa5f3c55b3a7617", "bbde89d0d696cee2fb01e3054d1d7ee8");
$signPackage = $jssdk->GetSignPackage();

var_dump($signPackage);

?>
<html>
    <head>
        <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
        <script type="text/javascript">
            window.onload = function () {

                wx.config({
                    debug: false,
                    appId: '<?php echo $signPackage["appId"];?>',
                    timestamp: <?php echo $signPackage["timestamp"];?>,
                    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
                    signature: '<?php echo $signPackage["signature"];?>',
                    jsApiList: [
                        // 所有要调用的 API 都要加到这个列表中
                        'checkJsApi',
                        'openLocation',
                        'getLocation',
                        'scanQRCode',
                        'onMenuShareTimeline',
                        'onMenuShareAppMessage'
                    ]
                });
                wx.ready(function () {
                    wx.checkJsApi({
                        jsApiList: [
                            'getLocation'
                        ],
                        success: function (res) {
                            // alert(JSON.stringify(res));
                            // alert(JSON.stringify(res.checkResult.getLocation));
                            if (res.checkResult.getLocation == false) {
                                alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
                                return;
                            }else{
                                //有点不准确
//                                wx.getLocation({
//                                    type: 'gcj02',
//                                    success: function (res) {
//                                        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
//                                        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
//                                        var speed = res.speed; // 速度，以米/每秒计
//                                        var accuracy = res.accuracy; // 位置精度
//                                        alert("latitude:"+latitude+"\nlongitude:"+longitude+"\nspeed:"+speed+"\naccuracy:"+accuracy);
////                                        wx.openLocation({
////                                            latitude: latitude, // 纬度，浮点数，范围为90 ~ -90
////                                            longitude: longitude, // 经度，浮点数，范围为180 ~ -180。
////                                            name: '不知道', // 位置名
////                                            address: '', // 地址详情说明
////                                            scale: 20, // 地图缩放级别,整形值,范围从1~28。默认为最大
////                                            infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
////                                        });
//                                        //wx.closeWindow();
//
//                                    },
//                                    cancel: function (res) {
//                                        alert('用户拒绝授权获取地理位置');
//                                    }
//                                });

                                wx.scanQRCode({
                                    needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                                    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                                    success: function (res) {
                                        var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                                        //alert(result);
                                    }
                                });
                            }
                        }
                    });

                });
                wx.error(function(res){
                    // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
                    alert("验证失败，请重试！");
                    wx.closeWindow();
                });
            }
            function scan() {
                    wx.scanQRCode({
                        needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                        scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                        success: function (res) {
                            var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                            alert(result);
                        }
                    });
            }

        </script>
    </head>
    <body>
    <input type="button" value="我要签到" onclick="javascript:scan()">

    </body>

</html>
<?php
/**
 * 获取地理位置
 */
class JSSDK {
    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("jsapi_ticket.json"),true);
        if (@$data->expire_time < time()) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                @$data->expire_time = time() + 7000;
                @$data->jsapi_ticket = $ticket;
                $fp = fopen("jsapi_ticket.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }

    private function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("access_token.json"));
        if ($data->expire_time < time()) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen("access_token.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
}




