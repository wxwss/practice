<?php

$server = new SoapServer(null,array('uri'=>"/api.php"));
$server->setClass("server");
$server->handle();

class server{
    //连接数据库
    function __construct(){
        include 'db.php';
    }
    //生成短地址
    function shorturl($url){
        $url = addslashes($url);
        $res = mysql_query("select * from url where url='$url'");
        if($u = mysql_fetch_array($res)){
            return 'http://s.xdf.cn/'.$u['surl'];
        }else{
            $surl = $this->parseurl($url);
            mysql_query("insert into `url`(surl,url) values('$surl','$url')");
            $flag = mysql_affected_rows();
            if($flag){
                return 'http://s.xdf.cn/'.$surl;
            }else{
                return 'error';
            }
        }
    }
    //生成随机串
    function parseurl($url) {
        $base32 = array ('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h','i', 'j', 'k', 'l', 'm', 'n', 'o', 'p','q', 'r', 's', 't', 'u', 'v', 'w', 'x','y', 'z',
        0,1,2,3,4,5,6,7,8,9);

        $hex = md5($url);
        $hexLen = strlen($hex);
        $subHexLen = $hexLen / 8;
        $output = array();

        for ($i = 0; $i < $subHexLen; $i++) {
            $subHex = substr ($hex, $i * 8, 8);
            $int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
            $out = '';

            for ($j = 0; $j < 6; $j++) {
                $val = 0x0000001F & $int;
                $out .= $base32[$val];
                $int = $int >> 5;
            }

            $output[] = $out;
        }

        return $output[0];
    } 
}
?>
