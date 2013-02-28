<?php
include 'db.php';

$surl = addslashes(substr($_SERVER['REQUEST_URI'],-6));
$res = mysql_query("select * from url where surl='$surl' limit 1");
$url = mysql_fetch_array($res);
if($url != false){
    if(strpos($url['url'],'http://')){
        header("LOCATION:$url[url]");
    }else{
        header("LOCATION:http://$url[url]");
    }
}else{
     header("LOCATION:http://www.xdf.cn/404.php");
}
?>
