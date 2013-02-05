<?php
include 'qr/qrlib/qrlib.php';
//7 11 15  
QRcode::png("http://s.xdf.cn/123456","测试.png", "M", 99);
echo "<img src='./test.png'>";
