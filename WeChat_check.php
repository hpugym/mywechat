<?php
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
class WeChat{
    public function check(){
        //1.将timestamp,nonce,token按照字典序排序
        $timestamp  = @$_GET['timestamp'];
        $nonce      = @$_GET['nonce'];
        $token      = @'guoshi';
        $signature  = @$_GET['signature'];
        $echostr    = @$_GET['echostr'];
        $array = array($timestamp, $nonce, $token);
        sort($array);
        //2.将排序之后的三个字符拼接之后使用sha1加密
        $tmpstr = implode('', $array);
        $tmpstr = sha1($tmpstr);
        //3.将加密后的字符串与signature进行对比，判断该请求是否来自微信
        if ($tmpstr == $signature && $echostr) {
            echo $echostr;
            exit;
        }else{
            $this->DataAction();
        }
    }
    //封装数据处理函数
    public function DataAction(){
        //接收微信推送过来的post数据包
        $postArr = file_get_contents('php://input');
        //处理消息类型
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postArr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $MsgType = strtolower($postObj->MsgType);
        $time = date("Y-m-d:H:i:s");
        $tmp = file_get_contents("log.text");
        $str = $tmp."\n推送数据postStr:".$postArr.$time."事件：".$MsgType;
        file_put_contents("log.text",$str);


        switch ($MsgType) {
            case "event": //事件推送
                # code...
                $Event = strtolower($postObj->Event);
                $EventKey = strtolower($postObj->EventKey);
                //如果是关注事件
                if($Event == "subscribe"){
                    if(!empty($EventKey)){//来源于扫码
                        //推送关注回复以及扫码回复
                        $this->SubscribeResponse($postObj->FromUserName,$postObj->ToUserName);
                        //$this->EventResponse($postObj->FromUserName,$postObj->ToUserName,$EventKey,"欢迎关注微信在线签到服务公众号\n\n");
                    }else{
                        //推送关注回复
                        $this->SubscribeResponse($postObj->FromUserName,$postObj->ToUserName);
                    }
                }else if($Event == "scan"){//已关注，推送扫码回复
                    $this->EventResponse($postObj->FromUserName,$postObj->ToUserName,$EventKey);
                }else if($Event == "location"){
                    $this->LocationDeal($postObj->FromUserName,$postObj->Longitude,$postObj->Latitude);
                }
                break;
            case "text": //文本消息推送
                #code
                $this->NewsResponse($postObj->FromUserName,$postObj->ToUserName,$postObj->Content);
                break;
            case "image": //图片消息推送
                #code
                break;
            case "voice": //语音消息推送
                #code
                break;
            case 'video': //视频消息推送
                #code
                break;
            case "link": //连接消息推送
                #code
                break;
            case "shortvideo": //小视频消息推送
                #code
                break;
            case "location": //地理位置推送
                #code
                break;
            default:
                # code...
                break;
        }
    }
    //关注回复
    public function SubscribeResponse($From,$To){
        //回复用户消息
        $FromUserName   = $To;//微信公众号的
        $ToUserName     = $From;//关注用户的
        $Time           = time();
        $MsgType        = 'text';
        $Content        = "欢迎关注微信在线签到服务公众号";
        $TextTpl        = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    </xml>";
        $ResultStr      = sprintf($TextTpl, $ToUserName, $FromUserName, $Time, $MsgType, $Content);
        echo $ResultStr;
    }
    //事件回复
    public function EventResponse($From,$To,$Id){

        $FromUserName   = $To;//微信公众号的
        $ToUserName     = $From;//关注用户的
        $EventKey       = $Id;
        $Time           = time();
        $MsgType        = 'news';
        $ArticleCount   = 1;
        $Title          = "";
        $Description    = "";
        $PicUrl         = "http://sign.goalschina.com/sign/images/news.png";
        $Url            = "http://sign.goalschina.com/sign/mysign/signing.php?id=".$EventKey."&openid=".$ToUserName;

        $TextTpl        ="<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <ArticleCount>%s</ArticleCount>
                                <Articles>
                                <item>
                                <Title><![CDATA[%s]]></Title> 
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                                </item>
                                </Articles>
                                </xml>";
        $ResultStr      = sprintf($TextTpl, $ToUserName, $FromUserName, $Time, $MsgType, $ArticleCount, $Title, $Description, $PicUrl,$Url);
        echo $ResultStr;
    }
    //消息回复
    public function NewsResponse($From,$To,$Key){
        $FromUsername   = $To;
        $ToUsername     = $From;
        $KeyWord = trim($Key);
        $Time = time();
        $TextTpl   ="<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>";
        /*接受推送的消息格式：文本消息
         *<xml>
         <ToUserName><![CDATA[toUser]]></ToUserName>
         <FromUserName><![CDATA[fromUser]]></FromUserName>
         <CreateTime>1348831860</CreateTime>
         <MsgType><![CDATA[text]]></MsgType>
         <Content><![CDATA[this is a test]]></Content>
         <MsgId>1234567890123456</MsgId>
         </xml>
         */
        if(!empty( $KeyWord ))
        {
//            //定义敏感词
//            $str        = file_get_contents('preg.text');
//            $template   = "/".$str."/i";
//            //$template ="/你大爷|你大爷的|打野|我靠|我日|操你妈|妈了个逼|傻吊|傻逼/i";
//            if(preg_match($template, $KeyWord)){
//                $Content = "请注意文明用语";
//            }else{
//                $Content = $KeyWord;
//            }
            //这里做数字关键字处理
            switch ($KeyWord){
                case 1:
                    $Content = "亲爱的小主，您输入数字为1";
                    break;
                case 2:
                    $Content = "亲爱的小主，您输入数字为2";
                    break;
                case 3:
                    $Content = "亲爱的小主，您输入数字为3";
                    break;
                case 4:
                    $Content = "亲爱的小主，您输入数字为4";
                    break;
                case 5:
                    $Content = "亲爱的小主，您输入数字为5";
                    break;
                case 6:
                    $Content = "亲爱的小主，您输入数字为6";
                    break;
                default:
                    //接入图灵机器人
                    $Info   = urldecode($KeyWord);
                    $Key    = "56e1e2552f4749508f5b0afc187dfeb4";
                    $Url    = "http://www.tuling123.com/openapi/api?key=".$Key."&info=".$Info;
                    $res    = file_get_contents($Url);
                    $tmp    = json_decode($res, true);
                    if($tmp['code'] == "100000"){
                        $Content = $tmp['text'];
                    }else if($tmp['code'] == "200000"){//链接类
                        $Content = $tmp['text']."请点击一下链接：\n\n".$tmp['url'];
                    }else if($tmp['code'] == "302000"){//新闻类
                        $list = $tmp['list'];
                        $Content = $tmp['text'];
                        for($i = 1; $i< 6; $i++){
                            $str = "\n\n".$i."：新闻标题：".$list[$i]['article']."\n\n来源：".$list[$i]['source']."\n\n链接：".$list[$i]['detailurl'];
                            $Content =$Content.$str;
                        }
                    }else if($tmp['code'] == "308000"){//菜谱类
                        $list = $tmp['list'];
                        $Content = $tmp['text'];
                        for($i = 1; $i< 6; $i++){
                            $str = "\n\n".$i."：菜名：".$list[$i]['name']."\n\nInfo:".$list[$i]['info']."\n\n点击链接查看：".$list[$i]['detailurl'];
                            $Content =$Content.$str;
                        }
                    }else{
                        $Content = "对不起，亲爱的小主，签到小薇暂时帮不了你了！";
                    }
                    break;
            }
            $MsgType = "text";
            //$contentStr = "Welcome to wechat world!";
            $resultStr = sprintf($TextTpl, $ToUsername, $FromUsername, $Time, $MsgType, $Content);
            echo $resultStr;
            $time = date("Y-m-d:H:i:s");

            $tmp = file_get_contents("log.text");
            $str = $tmp."\n关键字回复：".$resultStr.$time;
            file_put_contents("log.text",$str);
        }else{
            echo "Input something...";
        }
    }

    //地理位置的处理
    public function LocationDeal($openid,$lon,$lat){
        require_once ("./sign/mysign/conn.php");
        $localA = new Connection();
        $localA_sql = "select * from `sign_stulocal` where `openid` ='".$openid."'";
        $res = $localA->Query($localA_sql);

        $deal = new Connection();
        $deal_sql ="";
        if($res['status'] == 1){
            $deal_sql = "update `sign_stulocal` set `lon` =".$lon.",`lat` =".$lat."where `openid` ='".$openid."'";
            $res1 = $deal->Update($deal_sql);
            $tmp = file_get_contents("log.text");
            $time = date("Y-m-d H:i:s",time());
            $str = $tmp."操作状态：".$res1['status'].".更新经纬度：".$lon.",".$lat."\n----".$time;
            file_put_contents("log.text",$str);

        }else{
            $deal_sql = "insert into `sign_stulocal`(`openid`,`lon`,`lat`) values('".$openid."',".$lon.",".$lat.")";
            $res1 = $deal->Insert($deal_sql);
            $tmp = file_get_contents("log.text");
            $time = date("Y-m-d H:i:s",time());
            $str = $tmp."操作状态：".$res1['status'].".新增经纬度：".$lon.",".$lat."\n----".$time;
            file_put_contents("log.text",$str);
        }

    }
}
//实例化
date_default_timezone_set('Asia/Shanghai');
$wechat = new WeChat();
$wechat->check();
//?>





