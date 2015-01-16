<?php

//证明从新定义一个新数组  此时只是把$arr的引用赋予$tmp,并没有给$tmp开辟实际的内存空间
//但是当$arr or $tmp 发生改变的时候， 或者当$arr/$tmp 当作引用传递给方法的时候， 此时会把$tmp copy一份独立的出来，为其开辟新的内存空间
//使用unset可以释放掉变量内存
// 如果使用 $tmp = &$arr; 则tmp和arr内存指向一致 要改变一起改变，不会开辟新内存出来

//php引用传递比值传递效率更好 但引用传递容易出错，要小心
$g1 = memory_get_usage();
echo "start global mem :".$g1."\n";

$arr = array();

for ($i = 0; $i < 10000; $i++) {
	$arr[] = rand(0, 9999999);
}

$g2 = memory_get_usage();
echo "start define arr global mem g2:".$g2.".  used ".($g2-$g1)."\n";


$tmp = $arr;
$g3 = memory_get_usage();
echo "start define arr global mem g3:".$g3.".  used ".($g3-$g2)."\n";

$arr[] = 99999;
$g4 = memory_get_usage();
echo "start define arr global mem g4:".$g4.".  used ".($g4-$g3)."\n";

unset($arr);
$g5 = memory_get_usage();
echo "start define arr global mem g5:".$g5.".  used ".($g5-$g4)."\n";

$f1 = memory_get_usage();
$deal_times = array();
for ($i = 0; $i < 2; $i++) {
//	dump($arr);
	$start = microtime(true);
	
	maopao1_reference($arr);
	//maopao1($arr);
	
	$m2 = memory_get_usage();
	echo "global foreach mem : {$m2}, used memory: ".($m2-$f1)."\n";
	
	//dump($arr);
	$end = microtime(true);
//	dump($arr2);
	$deal_time = $end-$start;
	echo "deal_time:".$deal_time."\n";
	$deal_times[] = $deal_time;
	$m3 = memory_get_usage();
	echo "global foreach end mem : {$m3}, array deal_times used memory: ".($m3-$f1)."\n";
}
exit("------------------------end------------------\n");

$all = array_sum($deal_times);
$avg_time = $all/count($deal_times);
echo "avg time : " . $deal_time."\n";

function maopao1($arr){
	$m1 = memory_get_usage();
	echo "function start now mem :{$m1}\n";
	$t_loop = 0;
	$len = count($arr);
	for ($i = 0; $i < $len; $i++){
		for ($j = $i; $j < $len; $j++) {
			$t_loop++;
			if ($arr[$i] > $arr[$j]){
				$tmp = $arr[$i];
				$arr[$i] = $arr[$j];
				$arr[$j] = $tmp;
			}
		}
	}
	$m2 = memory_get_usage();
	echo "function end now mem :{$m2}, used memory:".($m2-$m1)."\n";
	return $arr;
}

function maopao1_reference(&$arr){
	$m1 = memory_get_usage();
	echo "function start now mem :{$m1}\n";
	$t_loop = 0;
	$len = count($arr);
	for ($i = 0; $i < $len; $i++){
		for ($j = $i; $j < $len; $j++) {
			$t_loop++;
			if ($arr[$i] > $arr[$j]){
				$tmp = $arr[$i];
				$arr[$i] = $arr[$j];
				$arr[$j] = $tmp;
			}
		}
	}
	$m2 = memory_get_usage();
	echo "function end now mem :{$m2}, used memory:".($m2-$m1)."\n";
}

