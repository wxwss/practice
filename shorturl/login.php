<?php
session_start();
if(isset($_POST['submit'])){
    if($_POST['name'] == 'neworiental' && $_POST['pw'] == 'shorturl'){
        $_SESSION['user'] = 1;
        header("LOCATION:./makeurl.php");
    }else{
        echo '用户名或密码错误，请重新输入！';
    } 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://url=w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>短地址</title>
	<link rel="stylesheet" type="text/css" href="style.css" /> 
</head>
<body>                                                                           
<h3>登陆</h3>
<form action="" method="POST">
用户名：<input type="text" name="name"></br>
密  码：  <input type="password" name="pw"></br>
<input type="submit" name="submit" value="登陆">
</form>
</body>
</html>
