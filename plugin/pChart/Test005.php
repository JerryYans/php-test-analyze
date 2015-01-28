<?php   
 /*
     Example15 : Playing with line style & pictures inclusion
 */
 function dump($data){
 	echo "<pre>";
 	print_r($data);
 	echo "</pre>";
 	exit;
 }
 $normal_circle = "/home/ys/www/mmbang/www/sources/admin/pChart/Sample/normal_circle.png";
 $backgrounds = array(
 	450 => "Sample/450x338.png",
 	600 => "Sample/600x450.png",
 	680 => "Sample/680x510.png",
 	1020 => "Sample/1020x765.png",
 );
 $background = "Sample/600x450.png";
 $enable_width = array(450, 600,680,1020);
 $params = array(
 	"data"		=>	array(3=>66.0, 5=>75.5, 6=>76, 7=>79.0, 8=>85.9, 9=>90.1),
 	"width"		=>	680,
 	"height"	=>	0,
 );
 $min_value = min($params['data']);
 $max_value = max($params['data']);
 $max_y_value = $max_value + 5;
 $min_y_value = $min_value - 5;
 
 $params['data'][0] = $min_y_value;
 ksort($params['data']);
 $x_lable_arr = array_keys($params['data']);
 $params['data'] = array_values($params['data']);
 if (!in_array($params['width'], $enable_width)){
// 	exit("aa");
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
 // Standard inclusions      
 include("pChart/pData.class.php");   
 include("pChart/pChart.class.php");   
  
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
  
 // Initialise the graph   
 $Test = new pChart($width, $height);
  // Add an image
 $Test->drawFromPNG($background, 0, 0);
 $Test->reportWarnings("GD");
 $Test->setFixedScale($min_y_value, $max_y_value, 5);
 
 $Test->setFontProperties("Fonts/tahoma.ttf",14);
 $Test->setGraphArea(-100,20,$width-30,$height - 55);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,255, 237, 237,TRUE, 0,2, TRUE, 1);   
 $Test->drawGrid(4,TRUE,230,230,230,5);

 // Draw the area
 $Test->drawArea($DataSet->GetData(),"Serie3","Serie4",239,238,227,50);
 $DataSet->RemoveSerie("Serie4");

 // Draw the line graph
 $Test->setColorPalette(0, 255, 237, 237);
 $Test->setLineStyle(2,0);//设置虚线的宽度
 $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());   
 $Test->setColorPalette(0, 243, 247, 252);
 $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),5,3, 255, 255, 255);   
 
 // Write values on Serie3
 $Test->setFontProperties("Fonts/tahoma.ttf",14);   
// $Test->setFixedScale(0, 10);
 $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");   
  
 // Finish the graph   
// $Test->setFontProperties("Fonts/tahoma.ttf",8);   
// $Test->drawLegend(590,90,$DataSet->GetDataDescription(),255,255,255);   
// $Test->setFontProperties("Fonts/tahoma.ttf",10);   
// $Test->drawTitle(60,22,"example 15",50,50,50,585);


 // Render the chart
 $Test->Render("example15.png");   
 

?>