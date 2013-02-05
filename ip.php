<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
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

$schools = array ("����" => "1", "�Ϻ�" => "2", "����" => "3", "�人" => "4", "���" => "5", "����" => "6", "�Ͼ�" => "7", "����" => "8", "����" => "9",
    "����" => "10", "�ɶ�" => "11", "����" => "14", "������" => "15", "��ɳ" => "16", "����" => "18", "����" => "19", "֣��" => "20", "̫ԭ" => "21",
    "����" => "22", "����" => "23", "ʯ��ׯ" => "24", "�Ϸ�" => "25", "����" => "26", "����" => "27", "��ɽ" => "28", "����" => "29", "��ɽ" => "30", 
    "�˲�" => "31", "����" => "32", "����" => "34", "�ϲ�" => "35", "����" => "36", "��ʯ" => "37", "����" => "38", "����" => "40", "����" => "41", 
    "�ൺ" => "44", "����" => "45", "����" => "46", "��̶" => "48", "��" => "49", "����" => "50", "��ͨ" => "52", "����" => "53", "���ͺ���" => "54", 
    "��³ľ��" => "55", "��ɽ" => "56", "����" => "57", "ʮ��" => "58");

$provinces = array("�ӱ�ʡ","����ʡ","����ʡ","����ʡ","������ʡ","����ʡ","����ʡ","ɽ��ʡ","����ʡ","�㽭ʡ","����ʡ","����ʡ","����ʡ","ɽ��ʡ","����ʡ",
    "����ʡ","����ʡ","����ʡ","�㶫ʡ","�ຣʡ","�Ĵ�ʡ","����ʡ","̨��ʡ","���ɹ�","����","�½�","����","����");

$ip['school'] = $ip['schoolid'] = '';
$ip['area'] = convertip_full ( $ip['ip'], 'qqwry.dat' );

foreach ($provinces as $p){
    if(strpos($ip['area'],$p) !== false){
        $ip['area'] = preg_replace("/$p/","",$ip['area']);
    }
}

$ip['area'] = preg_replace("/��/","",$ip['area']);

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
$provinces = array("����"=>"����","�Ϻ�"=>"�Ϻ� ","�㶫"=>"���� ","����"=>"�人 ","���"=>"��� ","����"=>"���� ","����"=>"�Ͼ� ","�㶫"=>"����","����"=>"���� ",
"����"=>"���� ","�Ĵ�"=>"�ɶ� ","����"=>"����","������"=>"������ ","����"=>"��ɳ ","����"=>"���� ","�㽭"=>"���� ","����"=>"֣�� ","ɽ��"=>"̫ԭ ","?ɽ��"=>"���� ",
"����"=>"����","�ӱ�"=>"ʯ��ׯ ","����"=>"�Ϸ� ","����"=>"���� ","����"=>"���� ","����"=>"��ɽ","����"=>"����","�㶫"=>"��ɽ","����"=>"�˲�","����"=>"����",
"����"=>"����","����"=>"�ϲ� ","����"=>"����","����"=>"��ʯ","�㽭"=>"����","����"=>"���� ","����"=>"����","ɽ��"=>"�ൺ","����"=>"���� ","����"=>"����",
"����"=>"��̶","����"=>"��","����"=>"����","����"=>"��ͨ","����"=>"����","���ɹ�"=>"���ͺ��� ","�½�"=>"��³ľ�� ","�ӱ�"=>"��ɽ","����"=>"���� ","����"=>"ʮ��");
 */
?>

