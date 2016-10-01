<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];


$sql_ora="select c.id id,a.name name,b.name test_type,c.name flag from department a,organize b,orastatus c
where  a.school_id='$school_id' and a.year='$year' and a.school_id=b.school_id and a.year=b.year  and a.school_id=c.school_id and a.year=c.year and a.id =substr(b.id,1,3) and b.id=substr(c.id,1,4) order by c.id";
$ora_rows = $db -> query_array ($sql_ora);


$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

$title = $title_rows['TITLE_NAME'][0] ." 系所代碼一覽表";

require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->AddPage();
$pdf->AddUniCNShwFont('font1');
$pdf->SetFont('font1');

$pdf->SetFontSize(16); 
$pdf->Cell(0,10,$title,0,1,'C');
$pdf->SetFontSize(12);
$pdf->Cell(50,10);
$pdf->Cell(0,10,"列印日期：" . date("Y/m/d"),0,1,'R'); 
$pdf->Cell(30,10,"系所代碼",1,0,"C");
$pdf->Cell(80,10,"系所名稱",1,0,"C");
$pdf->Cell(40,10,"組 別",1,0,"C");
$pdf->Cell(40,10,"身 分",1,1,"C");

for($j=0;$j<sizeof($ora_rows['ID']);$j++)
{
	$ora_id=$ora_rows['ID'][$j];
	$ora_name=$ora_rows['NAME'][$j];
	$ora_type=$ora_rows['TEST_TYPE'][$j];
	$ora_flag=$ora_rows['FLAG'][$j];

	$pdf->Cell(30,10,$ora_id,1,0,"C");
	$pdf->Cell(80,10,$ora_name,1,0,"C");
	$pdf->Cell(40,10,$ora_type,1,0,"C");
	$pdf->Cell(40,10,$ora_flag,1,1,"C");
}

$pdf->Output('');
?>