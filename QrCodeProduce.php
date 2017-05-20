<?php
header('content-type:text/html; charset=utf8');
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
$code = new QrCode();
$code->accessToken_get();
echo "获得的accessTkoen是：".$code->accessToken."<hr>";
$code->ticket_get();
echo "获得的ticket是：".$code->ticket."<hr>";
echo "获取的二维码如下：";
echo "<img src='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$code->QR_change()."'>";
echo "<br/> 菜单";
$code->MenuCreate();

class QrCode{
    public $token;
    public $appID;
    public $appSecret;
    public $accessToken ;
    public $ticket;

    //初始化
    public function __construct(){
        $this->token        = "guoshi";
        $this->appID        = "wxafa5f3c55b3a7617";
        $this->appSecret    = "bbde89d0d696cee2fb01e3054d1d7ee8";
    }


    //获取accessToken
    public function accessToken_get(){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appID.'&secret='.$this->appSecret;
        $tmp = json_decode($this->file_get($url),true);
        if(@array_key_exists('access_token',$tmp)){
            $this->accessToken = $tmp['access_token'];
            return true;
        }else{
            return flase;
        }
    }
    //获取ticket
    public function ticket_get(){
        $qrcodeID   ="123";
        $qrcodeType = "QR_SCENE";
        $tempJson = '{"expire_seconds": 60, "action_name": "'.$qrcodeType.'", "action_info": {"scene": {"scene_id": '.$qrcodeID.'}}}';
        $access_token = $this->accessToken;
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
        //$tempArr = json_decode($this->JsonPost($url, $tempJson), true);
        $tmp = $this->JsonPost($url, $tempJson);
       // var_dump($tmp);
       // echo "<hr>";
        $arr = json_decode($tmp,true);
        var_dump($arr);
        //echo "<hr>";
        //echo $arr["ticket"];
       // die;
        if(array_key_exists("ticket",$arr)){
            $this->ticket = $arr["ticket"];
            return true;
        }else{
            return false;
        }
    }
    //发送post请求
    public function JsonPost($url,$jsonData){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            var_dump(curl_error($curl));
        }
        curl_close($curl);
	    //var_dump($result);die;
        return $result;
    }
    //获取信息
    public function file_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    //发送post请求
    public function post_($url){
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data = array(
            "username" => "coder",
            "password" => "12345"
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return($data);
    }
    public function QR_change(){
        return urlencode($this->ticket);
    }
    //自定菜单
    public function MenuCreate(){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=". $this->accessToken;
        $data = "{
                \"button\": [
                    {
                        \"type\": \"view\", 
                        \"name\": \"我要签到\", 
                        \"url\": \"http://sign.goalschina.com/QrScan.php\"
                    }, 
                    {
                        \"name\": \"个人中心\", 
                        \"sub_button\": [
                            {
                                \"type\": \"view\", 
                                \"name\": \"我的签到\", 
                                \"url\": \"http://sign.goalschina.com/sign/openidOAuth.php\"
                            }, 
                            {
                                \"type\": \"view\", 
                                \"name\": \"信息绑定\", 
                                \"url\": \"http://sign.goalschina.com/sign/getauthorize.php\"
                            },
                            {
                                \"type\": \"view\", 
                                \"name\": \"ofo单车助手\", 
                                \"url\": \"http://sign.goalschina.com/ofo/\"
                            }
                        ]
                    }
                ]
            }";
        $res = $this->JsonPost($url ,$data);
        var_dump($res);
    }
}
?>
