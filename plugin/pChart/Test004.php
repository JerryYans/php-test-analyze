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
 // Standard inclusions      
 include("pChart/pData.class.php");   
 include("pChart/pChart.class.php");
 
 // Dataset definition    
 $DataSet = new pData;
// $DataSet->AddPoint(array(10,9.4,7.7,5,1.7,-1.7,-5,-7.7,-9.4,-10,-9.4,-7.7,-5,-1.8,1.7),"Serie1");
// $DataSet->AddPoint(array(0,3.4,6.4,8.7,9.8,9.8,8.7,6.4,3.4,0,-3.4,-6.4,-8.6,-9.8,-9.9),"Serie2");
 $DataSet->AddPoint(array(7.1,9.1,10,9.7,8.2,5.7,2.6,-0.9,-4.2,-7.1,-9.1,-10,-9.7,-8.2,-5.8),"Serie3");
// $DataSet->AddPoint(array("Jan","Jan","Jan","Feb","Feb","Feb","Mar","Mar","Mar","Apr","Apr","Apr","May","May","May"),"Serie4");
$DataSet->AddPoint(array(-12,-12,-12,-12,-12,-12,-12,-12,-12,-12,-12,-12,-12,-12,-12),"Serie4");
 $DataSet->AddAllSeries();
// $DataSet->SetAbsciseLabelSerie("Serie4");
// $DataSet->SetSerieName("Max Average","Serie1");
// $DataSet->SetSerieName("Min Average","Serie2");
 $DataSet->SetSerieName("Temperature","Serie3");
 $DataSet->SetYAxisName("Kg");
 $DataSet->SetXAxisName("week");
 $DataSet->SetSerieSymbol("Serie3", "/home/ys/www/mmbang/www/sources/admin/pChart/Sample/normal_circle.png");
  
 // Initialise the graph   
 $Test = new pChart(600,230);
  // Add an image
$Test->drawFromPNG("Sample/background.png",0,0);
 $Test->reportWarnings("GD");
 $Test->setFixedScale(-12,13,5);//
 $Test->setFontProperties("Fonts/tahoma.ttf",8);   
  $Test->setGraphArea(45,20,550,195);   
 //$Test->drawFilledRoundedRectangle(7,7,693,223,5,243,249,249);   
// $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);   
// $Test->drawGraphArea(243,249,249,TRUE);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,255, 237, 237,TRUE,0,2,TRUE,3);   
 $Test->drawGrid(4,TRUE,230,230,230,5);

 // Draw the 0 line   
// $Test->setFontProperties("Fonts/tahoma.ttf",6);   
// $Test->drawTreshold(0,143,55,72,TRUE,TRUE);   
  
 // Draw the area
// $DataSet->RemoveSerie("Serie4");
 $Test->drawArea($DataSet->GetData(),"Serie3","Serie4",239,238,227,50);
 $DataSet->RemoveSerie("Serie4");
// $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());   

 // Draw the line graph
 $Test->setColorPalette(0, 255, 237, 237);
 $Test->setLineStyle(2,0);//255,237,237
// $DataSet->RemoveAllSeries();
// $DataSet->AddSerie("Serie3");
 $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());   
 $Test->setColorPalette(0, 243, 247, 252);

 $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),5,3, 255, 255, 255);   
 // Write values on Serie3
 $Test->setFontProperties("Fonts/tahoma.ttf",10);   
 $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");   
  
 // Finish the graph   
 $Test->setFontProperties("Fonts/tahoma.ttf",8);   
// $Test->drawLegend(590,90,$DataSet->GetDataDescription(),255,255,255);   
 $Test->setFontProperties("Fonts/tahoma.ttf",10);   
// $Test->drawTitle(60,22,"example 15",50,50,50,585);


 // Render the chart
 $Test->Render("example15.png");   
 

?>