<?php
$server = new SoapServer(null,array('uri'=>"/soapsr.php"));
$server->setClass("server");
$server->addFunction("getuserinfo");
$server->handle();
class server{
    function getuserinfo($y){
        return "luan";
    } 
}
