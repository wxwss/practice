<?php
session_start();
if(!isset($_SESSION['user'])){
     header("LOCATION:./login.php");  
}
if(isset($_POST['submit']) && isset($_POST['url'])){
    $url = $_POST['url'];
    if(!empty($url)){
        $shorturl = shorturl($url);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://url=w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>短地址 生成页面</title>
	<link rel="stylesheet" type="text/css" href="style.css" /> 
</head>
<body>
<div id="wrap">
	<div id="title">
		<div class="title">短地址 生成页面</div><div id="clear"></div>
	</div>
			<div id="main">
		<table border="0" style="font-size:15px;text-align:center;padding-left:25px;
		padding:5px;width:100%;" cellpadding="0">
        <form action="#" method="POST">
			<tr>
			  <td>请输入地址:</td>
			</tr>
						<tr><td>
                          <input name="url" type="text" class="textfield" id="url" value="" size="40" />
			</td></tr>
     <tr><td><input class="textsubmit" type="submit" name="submit" value="生成短地址" /></td></tr>     
    </form>
		</table>
	</div>
	<div id="main">
<?php
if(isset($shorturl)){
	echo "生成的短地址：",$shorturl;
}
?>
	</div>
	</div>
</div>
</body>
</html>
<?php
//生成短地址
function shorturl($url){
	$client = new SoapClient(null,array('location'=>'http://s.xdf.cn/api.php',
										'uri'      => '/api.php'));
	$result = $client->__soapCall('shorturl',array($url));
	return $result;
}
?>

