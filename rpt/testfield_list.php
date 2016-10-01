<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];


$sql_layout="select a.classroom_id,a.start_stuid,a.end_stuid,
(select test_area_name ||'_' || building_name from classroom b,building c,test_area d
where b.school_id='3' and b.year='105' and b.school_id = c.school_id and b.year=c.year
and c.school_id=d.school_id and c.year=d.year and b.classroom_id=a.classroom_id 
and b.building_id=c.building_id and c.test_area_id =d.test_area_id) area_building,
(select name from department where school_id='$school_id' and year='$year' and id=substr(a.start_stuid,1,3)) dept_name ,
(select name from organize  where school_id='$school_id' and year='$year' and  id=substr(a.start_stuid,1,4)) organize_name ,
(select name from orastatus  where school_id='$school_id' and year='$year' and  id=substr(a.start_stuid,1,5)) orastatus_name ,
(select count(*) from seat  where school_id='$school_id' and year='$year' and classroom_id=a.classroom_id and substr(student_id,1,5)= substr(a.start_stuid,1,5)) person_cnt,
(select count(distinct section) from subject where school_id='$school_id' and year='$year' and  orastatus_id=substr(a.start_stuid,1,5) and section is not null and section<=4)  subject_cnt,
(select seat_number||'_'||comments from classroom where school_id='$school_id' and year='$year' and classroom_id=a.classroom_id) seat_comments 
from stu_number a where school_id='$school_id' and year='$year' and a.classroom_id in (select distinct classroom_id from seat where  school_id='$school_id' and year='$year')";
$layout_rows = $db -> query_array ($sql_layout);
 //echo $sql_layout ;
//exit;
$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

$title = "國立彰化師範大學 ".$title_rows['TITLE_NAME'][0] ;

require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->SetMargins(5,5);  //設定邊界(需在第一頁建立以前)
$pdf->AddUniCNShwFont('font1');
for($j=0;$j<sizeof($layout_rows['CLASSROOM_ID']);$j++)
{ 
	$area_building=$layout_rows['AREA_BUILDING'][$j]; //考區_系館
	$arr_area_building = explode("_",$area_building);
	$area_name = $arr_area_building[0];//考區
	$building_name = $arr_area_building[1];//系館

	$seat_comments=$layout_rows['SEAT_COMMENTS'][$j]; //試場總人數_備註
	$arr_seat_comments = explode("_",$seat_comments);
	$seat_number = $arr_seat_comments[0];//試場總人數註
	$comments = $arr_seat_comments[1];//備註
	if($building_name != $building_name_old)
	{
		$pdf->Cell(195,1,"","T",0,"C");	//補最後一行框線

		$pdf->AddPage();
		$pdf->SetFont('font1','',16);
		$pdf->Cell(0,10,$title,0,1,'C');
		$pdf->Cell(0,10,"$area_name 試場配置表",0,1,'C');
		$pdf->SetFontSize(8);
		$pdf->Cell(40,10,"系館：$building_name",0,1,"L");
		$pdf->Cell(10,10,"試場",1,0,"C");
		$pdf->Cell(10,10,"節數",1,0,"C");
		$pdf->Cell(50,10,"系    所",1,0,"C");
		$pdf->Cell(20,10,"組別",1,0,"C");
		$pdf->Cell(20,10,"身分",1,0,"C");
		$pdf->Cell(20,10,"起號",1,0,"C");
		$pdf->Cell(20,10,"迄號",1,0,"C");
		$pdf->Cell(10,10,"小計",1,0,"C");
		$pdf->Cell(10,10,"座位數",1,0,"C");
		$pdf->Cell(25,10,"備註",1,1,"C");
		$pdf->SetFontSize(8);
		
  	}

	$classroom_id=$layout_rows['CLASSROOM_ID'][$j];
	if($classroom_id<=9)
		$classroom_id="00".($classroom_id);
	else 
		$classroom_id="0".($classroom_id);

	$subject_cnt=$layout_rows['SUBJECT_CNT'][$j];
	$dept_name=$layout_rows['DEPT_NAME'][$j];
	$organize_name = $layout_rows['ORGANIZE_NAME'][$j];
	$orastatus_name=$layout_rows['ORASTATUS_NAME'][$j];
	$start_stuid = $layout_rows['START_STUID'][$j];
	$end_stuid= $layout_rows['END_STUID'][$j];
	$person_cnt= $layout_rows['PERSON_CNT'][$j];
	
	$height=6;
	// if($building_name != $building_name_old) //row span
	// 	$pdf->Cell(10,$height,$building_name,"LRT",0,"C");
	// else
	// 	$pdf->Cell(10,$height,"","L",0,"C");

	if($classroom_id != $classroom_id_old)//row span
		$pdf->Cell(10,$height,$classroom_id,"LRT",0,"C");
	else
		$pdf->Cell(10,$height,"","L",0,"C");

	$pdf->Cell(10,$height,$subject_cnt,1,0,"C");
	$pdf->Cell(50,$height,$dept_name,1,0,"C");
	$pdf->Cell(20,$height,$organize_name,1,0,"C");
	$pdf->Cell(20,$height,$orastatus_name,1,0,"C");
	$pdf->Cell(20,$height,$start_stuid,1,0,"C");
	$pdf->Cell(20,$height,$end_stuid,1,0,"C");
	$pdf->Cell(10,$height,$person_cnt,1,0,"C");
	if($classroom_id != $classroom_id_old){//row span
		$pdf->Cell(10,$height,$seat_number,"LRT",0,"C");
		$pdf->Cell(25,$height,$comments,"LRT",1,"C");
	}
	else{
		$pdf->Cell(10,$height,"","LR",0,"C");
		$pdf->Cell(25,$height,"","R",1,"C");
	}

	$classroom_id_old = $classroom_id ;
	$area_name_old = $area_name ;
	$building_name_old = $building_name ;

}
$pdf->Cell(195,1,"","T",0,"C");	//補最後一行框線
$pdf->Output('');

//當字串長度太長時,切成兩行顯示
function multiCell($width,$height,$text,$border,$ln,$align){
	global $pdf;	
	$text1=mb_substr($text,0,10,"UTF-8"); //第一行
	$text2=mb_substr($text,10,10,"UTF-8"); //第二行
	$CurrentX = $pdf->GetX();
	$CurrentY = $pdf->GetY() ;
	$pdf->SetXY($CurrentX,$CurrentY);
	$pdf->Cell($width,$height/2,$text1,"T",$ln,$align);
	$pdf->SetXY($CurrentX,$CurrentY+6);
	$pdf->Cell($width,$height/2,$text2,0,$ln,$align);
	$pdf->SetXY($CurrentX+$width,$CurrentY);
}
?>	
	
	
	
	
	
		
	
	
	
	
	
