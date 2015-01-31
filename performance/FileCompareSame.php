<?php

//sha1_file 要比 md5_file  获取时间 大于1.2倍
//sha1_file 长度为40字节
//md5_file  长度为32字节
//如果添加第二个字段 true， 返回的是  16 字符二进制格式
//默认第二个字段为false  十六进制字符


$filename1 = "/home/ys/Pictures/pic/pretty_gril1.png";
$filename2 = "/home/ys/Pictures/pic/pretty_gril2.jpg";
//$md51 = md5_file($filename1);
//$start = microtime(true);
//for ($i = 0; $i < 10000; $i++) {
//	$sha_1 = sha1_file($filename1);
//}
//$end = microtime(true);
//$used = $end - $start;
//dump($used);


$md51 = md5_file($filename1);
$sha_1 = sha1_file($filename1);
dump(strlen($md51));
dump(strlen($sha_1));

dump($md51);
dump($sha_1);
dump('---------');
$md51 = md5_file($filename2);
$sha_1 = sha1_file($filename2);
dump($md51);
dump($sha_1);
dump('---------');
$img_info1 = getimagesize($filename1);
$img_info2 = getimagesize($filename2);
dump($img_info1);
dump($img_info2);


function dump($str){
	if (is_array($str)){
		print_r($str);
		echo "\n";
	}
	echo $str."\n";
}