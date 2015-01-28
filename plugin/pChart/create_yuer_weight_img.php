<?php
 /*
  *   http://shuaiyan.dev.mmbang.com/admin/create_yuer_weight_img.php
  *   ?data={"03":66,"5":75.5,"6":76,"7":79,"8":85.9,"9":90.1}&w=680&sign=8424ae3bfd84d6eda843b168932dae87
  *   参数：sign=md5("{$_GET['data']}{$private_key}{$_GET['w']}");
  */
  // Standard inclusions
 $pChart_dir = ".";
 $img_base_dir = "./imgs/app/yuer";
 include("{$pChart_dir}/pChart/pData.class.php");
 include("{$pChart_dir}/pChart/pChart.class.php");
 $normal_circle = "{$img_base_dir}/normal_circle.png";
 $last_circle = "{$img_base_dir}/last_circle.png";
 $backgrounds = array(
 	450 => "{$img_base_dir}/450x338.png",
 	600 => "{$img_base_dir}/600x450.png",
 	680 => "{$img_base_dir}/680x510.png",
 	1020 => "{$img_base_dir}/1020x765.png",
 );
 
 $background = "{$img_base_dir}/600x450.png";
 $enable_width = array(450, 600,680,1020);
 $params = array(
 	"data"		=>	array(),
 	"width"		=>	680,
 	"height"	=>	0,
 );
$data = $_GET['data'] ? json_decode($_GET['data'], true) : array();
$width = $_GET['w'] ? $_GET['w'] : 0;
 if (empty($data) || empty($width)){
 	exit("error");
 }
 ksort($data);
 //中间的0去掉
 $check_middle_0 = false;
 foreach ($data as $key=>$v) {
 	if (!$check_middle_0 && !empty($v)){
 		$check_middle_0 = true;
 	}
 	if ($check_middle_0 && empty($v)){
 		unset($data[$key]);
 	}
 }
 
 $params = array(
 	"data"		=>	$data,
 	"width"		=>	$width,
 	"height"	=>	0,
 );
 $grid_width = $width/count($data);

 $area_margin_left = -$grid_width;
 
 $min_value = min($params['data']);
 $max_value = max($params['data']);
 $max_y_value = $max_value + 10;
 $min_y_value = $min_value - 5;

 $params['data'][0] = $min_y_value + 3;
 ksort($params['data']);
 $x_lable_arr = array_keys($params['data']);
 $params['data'] = array_values($params['data']);
 if (!in_array($params['width'], $enable_width)){
 	exit("have no this size img");
 }
 $private_key = "#%&HGJF&ITGKUylog*&^~fe";
 //8424ae3bfd84d6eda843b168932dae87
 $key = "{$_GET['data']}{$private_key}{$_GET['w']}";
 if(!isset($_GET['is_nocheck'])){
	 if (empty($_GET['sign']) || $_GET['sign'] != md5($key)){
	 	exit("sign error");
	 }
 }

 
 $width = $params['width'];
 if ($params['height']){
 	 $height = $params['height'];
 }else {
 	$height = $width * 3 / 4;
 }
 $background = $backgrounds[$width];
 $min_v_arrays = array();
 foreach ($params['data'] as $a) {
 	$min_v_arrays[] = $min_y_value;
 }

 // Dataset definition
 $DataSet = new pData;
 $DataSet->AddPoint($params['data'],"Serie3");
 $DataSet->AddPoint($min_v_arrays,"Serie4");
 $DataSet->AddPoint($x_lable_arr,"x_lable_name");
 $DataSet->SetAbsciseLabelSerie("x_lable_name");
 $DataSet->AddAllSeries();
 $DataSet->RemoveSerie("x_lable_name");
 $DataSet->SetSerieName("","Serie3");
// $DataSet->SetYAxisName("Kg");
// $DataSet->SetXAxisName("week");
 $DataSet->SetSerieSymbol("Serie3", $normal_circle);
 $DataSet->SetSerieSymbolLastOne("Serie3", $last_circle);

 // Initialise the graph
 $Test = new pChart($width, $height);
 $Test->setFontPropertiesDir("{$pChart_dir}/Fonts");
  // Add an image
 $Test->drawFromPNG($background, 0, 0);
 $Test->reportWarnings("GD");
 $Test->setFixedScale($min_y_value, $max_y_value, 5);
 $Test->setFontProperties("{$pChart_dir}/Fonts/tahoma.ttf",14);
 $Test->setGraphArea($area_margin_left, 20, $width-30, $height - 55);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,255, 255, 255,TRUE, 0,2, TRUE, 1);
 $Test->drawGrid(4, TRUE, 233, 218, 213, 0); //画网格

 // Draw the area
 $Test->drawArea($DataSet->GetData(), "Serie3", "Serie4", 239, 238, 227, 40);
 $DataSet->RemoveSerie("Serie4");

 // Draw the line graph
 $Test->setColorPalette(0, 255, 237, 237);
 $Test->setLineStyle(2,0);//设置虚线的宽度
 $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
 
 $Test->setColorPalette(0, 243, 247, 252);
 $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),5,3, 255, 255, 255);
 
// $Test->setFontProperties("{$pChart_dir}/Fonts/simfang.ttf",14);
 $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");

// $Test->Render("example15.png");
 $Test->Stroke();
 
  function dump($data){
 	echo "<pre>";
 	print_r($data);
 	echo "</pre>";
 	exit;
 }

?>