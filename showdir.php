<?php
function showdir($path){
    if(is_dir($path)){
        if($dh = opendir($path)){
            while(($file = readdir($dh)) !== false){
                if($file == '.' || $file == '..'){
                    continue;
                }
                if(is_dir($path.'/'.$file)){
                    echo $file.'<br/>';
                    showdir($path.'/'.$file);
                }else{
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$file.'<br/>';
                }
            }
        }
    }else{
        echo '-1';
    }
} 
showdir("D:/web/qr/.git/");
