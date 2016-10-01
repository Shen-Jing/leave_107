<?php
include_once("../inc/check.php");
  $school_id=$_SESSION['school_id'];
  $year=$_SESSION['year'];


$sql_ora="select a.name dept_name,b.name organize_name,c.name orastatus_name,c.enrollperson,c.allperson,c.resultperson,c.resultscore,c.secondperson,c.secondscore,c.remark 
from department a,organize b,orastatus c
where  a.school_id='$school_id' and a.year='$year' and a.school_id=b.school_id and a.year=b.year  and a.school_id=c.school_id and a.year=c.year and a.id =substr(b.id,1,3) and b.id=substr(c.id,1,4) order by c.id";
$ora_rows = $db -> query_array ($sql_ora);


$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

$title = $title_rows['TITLE_NAME'][0] ." 錄取標準一覽表";

require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->SetMargins(5,5);  //設定邊界(需在第一頁建立以前)
$pdf->AddPage();
$pdf->AddUniCNShwFont('font1');
$pdf->SetFont('font1');

$pdf->SetFontSize(16); 
$pdf->Cell(0,10,$title,0,1,'C');
$pdf->SetFontSize(9);
$pdf->Cell(50,10);
$pdf->Cell(0,10,"列印日期：" . date("Y/m/d"),0,1,'R'); 
$pdf->Cell(35,10,"系所名稱",1,0,"C");
$pdf->Cell(17,10,"組 別",1,0,"C");
$pdf->Cell(22,10,"身 分",1,0,"C");
$pdf->Cell(15,10,"報名人數",1,0,"C");
$pdf->Cell(20,10,"預定錄取人數",1,0,"C");
$pdf->Cell(15,10,"正取人數",1,0,"C");
$pdf->Cell(17,10,"正取最低分",1,0,"C");
$pdf->Cell(15,10,"備取人數",1,0,"C");
$pdf->Cell(17,10,"備取最低分",1,0,"C");
$pdf->Cell(25,10,"備註",1,1,"C");

for($j=0;$j<sizeof($ora_rows['DEPT_NAME']);$j++)
{
	$dept_name=$ora_rows['DEPT_NAME'][$j];
	$organize_name=$ora_rows['ORGANIZE_NAME'][$j];
	$orastatus_name=$ora_rows['ORASTATUS_NAME'][$j];
	$allperson=$ora_rows['ALLPERSON'][$j];
	$enrollperson=$ora_rows['ENROLLPERSON'][$j];
	$resultperson=$ora_rows['RESULTPERSON'][$j];
	$resultscore=$ora_rows['RESULTSCORE'][$j];
	$secondperson=$ora_rows['SECONDPERSON'][$j];
	$secondscore=$ora_rows['SECONDSCORE'][$j];
	$remark=$ora_rows['REMARK'][$j];

	if (strlen($dept_name)>30) $height = 20;
    else $height = 10;
    if ($height==20)
		multiCell(35,$height,$dept_name,0,0,"C"); //當字串長度太長時,切成兩行顯示
	else
		$pdf->Cell(35,$height,$dept_name,1,0,"C");
	//$pdf->Cell(30,$height,$dept_name,1,0,"C");
	$pdf->Cell(17,$height,$organize_name,1,0,"C");
	$pdf->Cell(22,$height,$orastatus_name,1,0,"C");
	$pdf->Cell(15,$height,$allperson,1,0,"C");
	$pdf->Cell(20,$height,$enrollperson,1,0,"C");
	$pdf->Cell(15,$height,$resultperson,1,0,"C");
	$pdf->Cell(17,$height,$resultscore,1,0,"C");
	$pdf->Cell(15,$height,$secondperson,1,0,"C");
	$pdf->Cell(17,$height,$secondscore,1,0,"C");
	$pdf->Cell(25,$height,$remark,1,1,"C");
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
	$pdf->Cell($width,$height/2,$text1,"LT",$ln,$align);
	$pdf->SetXY($CurrentX,$CurrentY+10);
	$pdf->Cell($width,$height/2,$text2,"L",$ln,$align);
	$pdf->SetXY($CurrentX+$width,$CurrentY);
}
?>