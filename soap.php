<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<?php
/*
$url = "http://home.xdf.cn/api/home/service.php?WSDL";
$client = new SoapClient($url);
$res = $client->getThreads(55,660);
print_r(json_decode($res));
 */
$client = new SoapClient(null, array('location' => "http://s.xdf.cn/api.php",
                                     'uri'      => "/api.php"));
try{
    $res = $client->__soapCall('shorturl',array('www.xdf.cn'));
}catch(SoapFault $fault){
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}
print_r($res);
