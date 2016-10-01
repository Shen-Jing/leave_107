<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];

$sql_person="select distinct c.id target,a.name depart_name,b.name test_type,c.name flag ,
(select count(*) ||'_' || min(student_id)||'_' || max(student_id) from person where substr(student_id,1,5)=c.id) cnt_min_max
from department a ,organize b,orastatus c
where  a.school_id='$school_id' and a.year='$year' and a.school_id=b.school_id and a.year=b.year and a.school_id=c.school_id and a.year=c.year  and  a.id =substr(b.id,1,3) and b.id=substr(c.id,1,4)  order by c.id";
 
	 //echo $sql_person ;
	// exit;
$person_rows = $db -> query_array ($sql_person);

$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);
$title = $title_rows['TITLE_NAME'][0] ." 報名人數統計表";

require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->AddPage();
$pdf->AddUniCNShwFont('font1');
//$pdf->AddBig5Font('font1','標楷體');
$pdf->SetFont('font1');

$pdf->SetFontSize(16); 
$pdf->Cell(0,10,$title,0,1,'C');
$pdf->SetFontSize(10);
$pdf->Cell(50,10);
$pdf->Cell(0,10,"列印日期：" . date("Y/m/d"),0,1,'R'); 
$pdf->Cell(68,10,"系(所)別",1,0,"C");
$pdf->Cell(28,10,"組別",1,0,"C");
$pdf->Cell(25,10,"身分",1,0,"C");
$pdf->Cell(20,10,"報名人數",1,0,"C");
$pdf->Cell(25,10,"准考證起號",1,0,"C");
$pdf->Cell(25,10,"准考證迄號",1,1,"C");

for($i=0;$i<sizeof($person_rows['TEST_TYPE']);$i++)
{	
		$people_testtype = $person_rows['TEST_TYPE'][$i];
		$people_name = $person_rows['DEPART_NAME'][$i];
		$people_flag  = $person_rows['FLAG'][$i];

		$cnt_min_max = $person_rows['CNT_MIN_MAX'][$i];
		$arr_cnt_min_max = explode("_",$cnt_min_max);
		$people_count=$arr_cnt_min_max[0];
		$min_id=$arr_cnt_min_max[1];
		$max_id=$arr_cnt_min_max[2];

		$pdf->Cell(68,10,$people_name,1,0,"C");
		$pdf->Cell(28,10,$people_testtype,1,0,"C");
		$pdf->Cell(25,10,$people_flag,1,0,"C");
		$pdf->Cell(20,10,$people_count,1,0,"C");
		$pdf->Cell(25,10,$min_id,1,0,"C");
		$pdf->Cell(25,10,$max_id,1,1,"C");

}

$pdf->Output('');

?>