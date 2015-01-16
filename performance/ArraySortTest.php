<?php

#
# 经测试
# 快速排序要比冒泡排序快近千倍 大大出乎我的意料
# 原以为递归从理解上更绕，涉及到方法的循环调用，应该不会快多少的，事实非如此
#

$arr = array();

for ($i = 0; $i < 100000; $i++) {
	$arr[] = rand(0, 9999999);
}

$deal_times = array();
for ($i = 0; $i < 20; $i++) {
//	dump($arr);
	$start = microtime(true);
	$arr2 = quick_sort($arr);
	$end = microtime(true);
//	dump($arr2);
	$deal_time = $end-$start;
	echo "deal_time:".$deal_time."\n";
	$deal_times[] = $deal_time;
}
foreach ($deal_times as $k=>$deal_time) {
	if ($deal_time == max($deal_times) || $deal_time == min($deal_times)){
		echo " unset:".$deal_time."\n";
		unset($deal_times[$k]);
	}
}

$all = array_sum($deal_times);
$avg_time = $all/count($deal_times);
echo "avg time : " . $deal_time."\n";

function maopao1($arr){
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
	echo "loop : ".$t_loop."\n";
	return $arr;
}

function maopao2($arr){
	$t_loop = 0;
	$len = count($arr);
	for ($i = 0; $i < $len-1; $i++){
		for ($j = 0; $j < $len-$i -1; $j++) {
			$t_loop++;
			if ($arr[$j] > $arr[$j+1]){
				$tmp = $arr[$j];
				$arr[$j] = $arr[$j+1];
				$arr[$j+1] = $tmp;
			}
		}
	}
	echo "loop : ".$t_loop."\n";
	return $arr;
}

function maopao3($arr){
	$t_loop = 0;
	$len = count($arr);
	$flag = true;
	for ($i = 0; $flag && $i < $len; $i++){
		$flag = false;
		for ($j = 0; $j < $len-$i -1; $j++) {
			$t_loop++;
			if ($arr[$j] > $arr[$j+1]){
				$tmp = $arr[$j];
				$arr[$j] = $arr[$j+1];
				$arr[$j+1] = $tmp;
				$flag = true;
			}
		}
	}
	echo "loop : ".$t_loop."\n";
	return $arr;
}

/**
 * 快速排序
 */
function quick_sort($arr){
	$len = count($arr);
	if ($len <= 1){
		return $arr;
	}
	$leftArr = $rightArr = array();
	$key = $arr[0];
	for ($i = 1; $i < $len; $i++) {
		if ($arr[$i] <= $key){
			$leftArr[] = $arr[$i];
		}else {
			$rightArr[] = $arr[$i];
		}
	}
	$leftArr = quick_sort($leftArr);
	$rightArr = quick_sort($rightArr);
	$rs = array_merge($leftArr, array($key), $rightArr);
	return $rs;
}

function dump($arr){
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}