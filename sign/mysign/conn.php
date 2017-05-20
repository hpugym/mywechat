<?php
/**
 * Created by PhpStorm.
 * User: 不倾国倾城_只倾你
 * Date: 2017/3/2
 * Time: 9:55
 */
header("Content-type:text/html;charset=utf-8");
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
//$mysqli = new mysqli("120.27.159.53", "gym", "guo941102", "sign");
//var_dump($mysqli);
//$sql = "INSERT INTO `shares` VALUES ('3', '18336800667', '4c03651a270d202fe9e57d1293306c', '郭月盟', '2017');";
//$result = $mysqli->query($sql);
//var_dump($result);
//var_dump($mysqli->affected_rows);
////$data = array();
////$result->data_seek(0); #重置指针到起始
////while($row = $result->fetch_assoc())
////{
////    $data[] = $row;
////}
////var_dump($data);
///* free result set */
//$result->free();
//
///* close connection */
//$mysqli->close();
class Connection{
    private $host;
    private $user;
    private $pass;
    private $dataname;

    /**
     * Connection constructor.
     */
    function __construct(){
        $this->host = "120.27.159.53";
        $this->user = "gym";
        $this->pass = "guo941102";
        $this->dataname = "sign";
        //$mysqli = new mysqli("120.27.159.53", "gym", "guo941102", "sign");
    }

    /**
     * @param $sql
     * @return array
     */
    public function Query($sql){
        $mysqli = new mysqli($this->host,$this->user,$this->pass,$this->dataname);
        $result = $mysqli->query($sql);
        if($mysqli->affected_rows){
            $data['status'] = 1;
            $result->data_seek(0);
            while($row = $result->fetch_array()) {
                $data[] = $row;
            }
            $result->free();
        }else{
            $data['status'] = 2;
        }
        $mysqli->close();
        return $data;
    }

    /**
     * @param $sql
     * @return int
     */
    public  function Insert($sql){
        $mysqli = new mysqli($this->host,$this->user,$this->pass,$this->dataname);
        $result = $mysqli->query($sql);
        if($result){
            $data = $mysqli->affected_rows;
            //$result->free();
        }else{
            $data = 0;
        }
        $mysqli->close();
        return $data;
    }

    /**
     * @param $sql
     * @return int
     */
    public function Update($sql){
        $mysqli = new mysqli($this->host,$this->user,$this->pass,$this->dataname);
        $result = $mysqli->query($sql);
        if($result){
            $data = $mysqli->affected_rows;
            //$result->free();
        }else{
            $data = 0;
        }
        $mysqli->close();
        return $data;
    }

    /**
     * @param $sql
     * @return int
     */
    public function Delete($sql){
        $mysqli = new mysqli($this->host,$this->user,$this->pass,$this->dataname);
        $result = $mysqli->query($sql);
        if($result){
            $data = $mysqli->affected_rows;
            //$result->free();
        }else{
            $data = 0;
        }
        $mysqli->close();
        return $data;
    }
}
//$stu_num = "311309030121";
//$stu_openid = "12312313123";
//$stu = new Connection();
//
//$sql = "select `stu_openid` from `sign_students` where `stu_num` =".@$stu_num;
//$res = $stu->Query($sql);
//var_dump($res);
//if(empty($res[0]['stu_openid'])){
//    echo 1;
//}
//else{
//    echo 2;
//}
//$sql2 = "update `sign_students` set `stu_openid` = NULL , `stu_image` = NULL , `stu_phone` = NULL where(`stu_num` = ".$stu_num.")";
//$res2 = $stu->Update($sql2);
//var_dump($res2);
//
//echo str_replace("http:\/\/wx.qlogo.cn\/mmopen\/AbruuZ3ILCmTibtwJJ6IVQCHwN5fzplbhSiaDrI6pfjO6n4CibQ9ERCiabWGDP61Bric4xRwu8hgErqribBU6taQkiaW1B6ibHEy6Z7F\/0","\/","/");
//echo str_replace("\/","/","http:\/\/wx.qlogo.cn\/mmopen\/AbruuZ3ILCmTibtwJJ6IVQCHwN5fzplbhSiaDrI6pfjO6n4CibQ9ERCiabWGDP61Bric4xRwu8hgErqribBU6taQkiaW1B6ibHEy6Z7F\/0");
?>