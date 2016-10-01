<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];


$sql_layout="select id,name,
(select name from department where school_id='$school_id' and year='$year' and id=substr(a.id,1,3)) dept_name ,
(select name from organize where school_id='$school_id' and year='$year' and id=substr(a.id,1,4)) organize_name ,
(select max(section) from subject where school_id='$school_id' and year='$year' and orastatus_id=a.id) section ,
(select count(*)||'_'||min(student_id)||'_'||max(student_id) from person where school_id='$school_id' and year='$year' and substr(student_id,1,5)=a.id) cnt_min_max
 from orastatus a
where school_id='$school_id' and year='$year' order by id";
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
for($j=0;$j<sizeof($layout_rows['ID']);$j++)
{ 	
	$cnt_min_max=$layout_rows['CNT_MIN_MAX'][$j]; //人數_准考證起號_准考證迄號
	$arr_cnt_min_max = explode("_",$cnt_min_max);
	$cnt = $arr_cnt_min_max[0];//人數
	$min = $arr_cnt_min_max[1];//准考證起號
	$max = $arr_cnt_min_max[2];//准考證迄號

	$dept_name=$layout_rows['DEPT_NAME'][$j];
	$organize_name = $layout_rows['ORGANIZE_NAME'][$j];
	$orastatus_name=$layout_rows['NAME'][$j];
	$section= $layout_rows['SECTION'][$j];
	
	$height=20;
	$pdf->AddPage();
	$pdf->SetXY(5,90);
	$pdf->SetFont('font1','',26);
	$pdf->Cell(80,$height,"  系所別：$dept_name",0,1,"L");
	$pdf->Cell(80,$height,"  身分別：$orastatus_name",0,1,"L");
	$pdf->Cell(80,$height,"  組別：$organize_name",0,1,"L");
	$pdf->Cell(80,$height,"  准考證號：$min ~ $max",0,1,"L");
	$pdf->Cell(80,$height,"  人數：$cnt 人",0,1,"L");
	$pdf->Cell(80,$height,"  ※考至第 $section 節※",0,1,"L");
	$pdf->Cell(80,$height,"  備註：",0,1,"L");

}

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
	
	
	
	
	
		
	
	
	
	
	
