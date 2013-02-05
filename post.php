<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<?php
function Post($url, $post = null)  
{  
    $context = array();  
  
    if (is_array($post))  
    {  
        ksort($post);  
  
        $context['http'] = array  
        (  
            'method' => 'POST',  
            'header' => "Content-type: application/x-www-form-urlencoded ",
            'content' => http_build_query($post, '', '&'),  
        );  
    }  
    print_r($context);
    echo '<br>'; 
    return file_get_contents($url, false, stream_context_create($context));  
}  
  
$param['schoolId'] = 1;
$param['classCode'] = "TFJL13221";
//$data['paramJson'] = json_encode($param);

$data = array  
(  
    'method' => 'Class.GetClassByCode',  
    'appKey' => 'test',  
    'timestamp' => date("Y-m-d H:i:s",time()),  
    'paramJson' => json_encode($param)
);  

$post = Post('http://souke.staff.xdf.cn/Api/Product/Class.ashx', $data);  
$post = json_decode($post,true);
if($post['Error'] == null){
    print_r($post);
}else{
    echo 'error';
}
?>  

