<?php
if(isset($_REQUEST['ip'])){
    $ip['ip'] = $_REQUEST['ip'];
}else{
    $ip['ip'] = $_SERVER["REMOTE_ADDR"];
}

$ipnum  = explode('.',trim($ip['ip']));
if(count($ipnum) != 4){
    exit();
}
foreach ($ipnum as $num){
    if(!is_numeric($num)){
        exit();
    }
}

$schools = array ("北京" => "1", "上海" => "2", "广州" => "3", "武汉" => "4", "天津" => "5", "西安" => "6", "南京" => "7", "深圳" => "8", "沈阳" => "9",
 "重庆" => "10", "成都" => "11", "襄阳" => "14", "哈尔滨" => "15", "长沙" => "16", "长春" => "18", "杭州" => "19", "郑州" => "20", "太原" => "21",
 "济南" => "22", "苏州" => "23", "石家庄" => "24", "合肥" => "25", "福州" => "26", "昆明" => "27", "鞍山" => "28", "株洲" => "29", "佛山" => "30", 
 "宜昌" => "31", "无锡" => "32", "荆州" => "34", "南昌" => "35", "大连" => "36", "黄石" => "37", "宁波" => "38", "兰州" => "40", "厦门" => "41", 
 "青岛" => "44", "南宁" => "45", "徐州" => "46", "湘潭" => "48", "镇江" => "49", "吉林" => "50", "南通" => "52", "洛阳" => "53", "呼和浩特" => "54", 
 "乌鲁木齐" => "55", "唐山" => "56", "贵阳" => "57", "十堰" => "58");

$provinces = array("河北省","河南省","云南省","辽宁省","黑龙江省","湖南省","安徽省","山东省","江苏省","浙江省","江西省","湖北省","甘肃省","山西省","陕西省",
"吉林省","福建省","贵州省","广东省","青海省","四川省","海南省","台湾省","内蒙古","广西","新疆","宁夏","西藏");

$ip['school'] = $ip['schoolid'] = '';
$ip['area'] = convertip_full ( $ip['ip'], 'qqwry.dat' );

foreach ($provinces as $p){
    if(strpos($ip['area'],$p) !== false){
        $ip['area'] = preg_replace("/$p/","",$ip['area']);
    }
}

$ip['area'] = preg_replace("/市/","",$ip['area']);

foreach ($schools as $sk => $s){
    if($ip['area'] == $sk){
        $ip['school'] = $sk;
        $ip['schoolid'] = $s;
    }
}

echo  json_encode($ip);
?>

<?php
function convertip_full($ip, $ipdatafile) {
    
    if (! $fd = @fopen ( $ipdatafile, 'rb' )) {
        return '- Invalid IP data file';
    }
    
    $ip = explode ( '.', $ip );
    $ipNum = $ip [0] * 16777216 + $ip [1] * 65536 + $ip [2] * 256 + $ip [3];
    
    if (! ($DataBegin = fread ( $fd, 4 )) || ! ($DataEnd = fread ( $fd, 4 )))
        return;
    @$ipbegin = implode ( '', unpack ( 'L', $DataBegin ) );
    if ($ipbegin < 0)
        $ipbegin += pow ( 2, 32 );
    @$ipend = implode ( '', unpack ( 'L', $DataEnd ) );
    if ($ipend < 0)
        $ipend += pow ( 2, 32 );
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
    
    $BeginNum = $ip2num = $ip1num = 0;
    $ipAddr1 = $ipAddr2 = '';
    $EndNum = $ipAllNum;
    
    while ( $ip1num > $ipNum || $ip2num < $ipNum ) {
        $Middle = intval ( ($EndNum + $BeginNum) / 2 );
        
        fseek ( $fd, $ipbegin + 7 * $Middle );
        $ipData1 = fread ( $fd, 4 );
        if (strlen ( $ipData1 ) < 4) {
            fclose ( $fd );
            return '- System Error';
        }
        $ip1num = implode ( '', unpack ( 'L', $ipData1 ) );
        if ($ip1num < 0)
            $ip1num += pow ( 2, 32 );
        
        if ($ip1num > $ipNum) {
            $EndNum = $Middle;
            continue;
        }
        
        $DataSeek = fread ( $fd, 3 );
        if (strlen ( $DataSeek ) < 3) {
            fclose ( $fd );
            return '- System Error';
        }
        $DataSeek = implode ( '', unpack ( 'L', $DataSeek . chr ( 0 ) ) );
        fseek ( $fd, $DataSeek );
        $ipData2 = fread ( $fd, 4 );
        if (strlen ( $ipData2 ) < 4) {
            fclose ( $fd );
            return '- System Error';
        }
        $ip2num = implode ( '', unpack ( 'L', $ipData2 ) );
        if ($ip2num < 0)
            $ip2num += pow ( 2, 32 );
        
        if ($ip2num < $ipNum) {
            if ($Middle == $BeginNum) {
                fclose ( $fd );
                return '- Unknown';
            }
            $BeginNum = $Middle;
        }
    }
    
    $ipFlag = fread ( $fd, 1 );
    if ($ipFlag == chr ( 1 )) {
        $ipSeek = fread ( $fd, 3 );
        if (strlen ( $ipSeek ) < 3) {
            fclose ( $fd );
            return '- System Error';
        }
        $ipSeek = implode ( '', unpack ( 'L', $ipSeek . chr ( 0 ) ) );
        fseek ( $fd, $ipSeek );
        $ipFlag = fread ( $fd, 1 );
    }
    
    if ($ipFlag == chr ( 2 )) {
        $AddrSeek = fread ( $fd, 3 );
        if (strlen ( $AddrSeek ) < 3) {
            fclose ( $fd );
            return '- System Error';
        }
        $ipFlag = fread ( $fd, 1 );
        if ($ipFlag == chr ( 2 )) {
            $AddrSeek2 = fread ( $fd, 3 );
            if (strlen ( $AddrSeek2 ) < 3) {
                fclose ( $fd );
                return '- System Error';
            }
            $AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
            fseek ( $fd, $AddrSeek2 );
        } else {
            fseek ( $fd, - 1, SEEK_CUR );
        }
        
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr2 .= $char;
        
        $AddrSeek = implode ( '', unpack ( 'L', $AddrSeek . chr ( 0 ) ) );
        fseek ( $fd, $AddrSeek );
        
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr1 .= $char;
    } else {
        fseek ( $fd, - 1, SEEK_CUR );
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr1 .= $char;
        
        $ipFlag = fread ( $fd, 1 );
        if ($ipFlag == chr ( 2 )) {
            $AddrSeek2 = fread ( $fd, 3 );
            if (strlen ( $AddrSeek2 ) < 3) {
                fclose ( $fd );
                return '- System Error';
            }
            $AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
            fseek ( $fd, $AddrSeek2 );
        } else {
            fseek ( $fd, - 1, SEEK_CUR );
        }
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr2 .= $char;
    }
    fclose ( $fd );
    
    if (preg_match ( '/http/i', $ipAddr2 )) {
        $ipAddr2 = '';
    }
    //$ipaddr = "$ipAddr1 $ipAddr2";
    $ipaddr = "$ipAddr1";
    $ipaddr = preg_replace ( '/CZ88\.NET/is', '', $ipaddr );
    $ipaddr = preg_replace ( '/^\s*/is', '', $ipaddr );
    $ipaddr = preg_replace ( '/\s*$/is', '', $ipaddr );
    if (preg_match ( '/http/i', $ipaddr ) || $ipaddr == '') {
        $ipaddr = '- Unknown';
    }
    
    return iconv('gb2312','utf-8',$ipaddr);

}

/*
$provinces = array("北京"=>"北京","上海"=>"上海 ","广东"=>"广州 ","湖北"=>"武汉 ","天津"=>"天津 ","陕西"=>"西安 ","江苏"=>"南京 ","广东"=>"深圳","辽宁"=>"沈阳 ",
"重庆"=>"重庆 ","四川"=>"成都 ","湖北"=>"襄阳","黑龙江"=>"哈尔滨 ","湖南"=>"长沙 ","吉林"=>"长春 ","浙江"=>"杭州 ","河南"=>"郑州 ","山西"=>"太原 ","﻿山东"=>"济南 ",
"江苏"=>"苏州","河北"=>"石家庄 ","安徽"=>"合肥 ","福建"=>"福州 ","云南"=>"昆明 ","辽宁"=>"鞍山","湖南"=>"株洲","广东"=>"佛山","湖北"=>"宜昌","江苏"=>"无锡",
"湖北"=>"荆州","江西"=>"南昌 ","辽宁"=>"大连","湖北"=>"黄石","浙江"=>"宁波","甘肃"=>"兰州 ","厦门"=>"厦门","山东"=>"青岛","广西"=>"南宁 ","江苏"=>"徐州",
"湖南"=>"湘潭","重庆"=>"镇江","吉林"=>"吉林","江苏"=>"南通","河南"=>"洛阳","内蒙古"=>"呼和浩特 ","新疆"=>"乌鲁木齐 ","河北"=>"唐山","贵州"=>"贵阳 ","湖北"=>"十堰");
*/
?>