<?php
$zip = new ZipArchive;
if ($zip->open('wangxu.zip',ZipArchive::CREATE) === TRUE) {
    $zip->addFile('qr.php');
	$zip->addFromString('test.txt', 'file content goes here');
    $zip->close();
    echo 'ok';
} else {
    echo 'failed';
}
?>

