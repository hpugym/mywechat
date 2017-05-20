<?php
/**
 * Created by PhpStorm.
 * User: 不倾国倾城_只倾你
 * Date: 2017/3/2
 * Time: 9:55
 */
header("Content-type:text/html;charset=utf-8");
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
    public function Insert($sql){
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
session_start();
$action = $_GET['action'];
if($action == 'get'){
    //echo "get";
    $number = trim($_GET['number']);
    $my = new Connection();
    $sql = "select `car_pass` from `bicyles` where `car_num`=".$number;
    $res = $my->Query($sql);
    //var_dump($res);
    if($res['status'] == 1){
        echo $res[0]['car_pass'];
    }else{
        echo "AAAA";
    }
}else if($action = 'share'){
    $num =trim($_GET['number']);
    $pass = trim($_GET['pass']);
    $time = time();
    $my1 = new Connection();
    $sql1 = "select `car_pass` from `bicyles` where `car_num`=".$num;
    $res1 = $my1->Query($sql1);
    $tmp = file_get_contents("log.text");
    $str = $tmp."\n查到session：".$_SESSION['openid'].date("Y-m-d H:i:s",$time);
    file_put_contents("log.text",$str);

    if($res1['status'] == 1){
        echo 3;
    }else{
        $my = new Connection();
        $sql = "insert into `bicyles` (`car_num`,`car_pass`,`share_name`,`share_date`) values(".$num.",".$pass.",'".$_SESSION['openid']."',".$time.")";
        $res = $my->Insert($sql);
        $tmp = file_get_contents("log.text");
        $str = $tmp."\n插入".$res.date("Y-m-d H:i:s",$time);
        file_put_contents("log.text",$str);
        if($res){

            echo 1;
        }else{
            echo 2;
        }
    }
}

?>