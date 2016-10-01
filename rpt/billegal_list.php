<?php
include_once("../inc/check.php");
  $school_id=$_SESSION['school_id'];
  $year=$_SESSION['year'];


$sql_persub="select a.person_student_id id, a.subject_id, a.score, a.disobey, a.remark,
(select name from signupdata where school_id='$school_id' and year='$year' and id=b.id) name,
(select name from department where school_id='$school_id' and year='$year' and id=substr(a.person_student_id,1,3)) dept_name,
(select name from organize where school_id='$school_id' and year='$year' and id=substr(a.person_student_id,1,4)) organize_name,
(select name from orastatus where school_id='$school_id' and year='$year' and id=substr(a.person_student_id,1,5)) orastatus_name,
(select section||'_'||name from subject where school_id='$school_id' and year='$year' and id=a.subject_id) section_subject
from persub a, person b 
where a.school_id='$school_id' and a.year='$year' and a.disobey <> 0 and a.person_student_id=b.student_id ";
		// echo $sql_persub;
		// exit;
$persub_rows = $db -> query_array ($sql_persub);

$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

$title = $title_rows['TITLE_NAME'][0] ." 違規考生名單一覽表";

require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->SetMargins(5,5);  //設定邊界(需在第一頁建立以前)
$pdf->AddPage();
$pdf->AddUniCNShwFont('font1');
$pdf->SetFont('font1');

$pdf->SetFontSize(16); 
$pdf->Cell(0,10,$title,0,1,'C');
$pdf->SetFontSize(10);
$pdf->Cell(50,10);
$pdf->Cell(0,10,"列印日期：" . date("Y/m/d"),0,1,'R'); 
$pdf->Cell(10,10,"序號",1,0,"C");
$pdf->Cell(40,10,"系所組身分",1,0,"C");
$pdf->Cell(20,10,"准考證號碼",1,0,"C");
$pdf->Cell(20,10,"考生姓名",1,0,"C");
$pdf->Cell(30,10,"考試科目",1,0,"C");
$pdf->Cell(10,10,"節次",1,0,"C");
$pdf->Cell(15,10,"原始分數",1,0,"C");
$pdf->Cell(15,10,"建議扣分",1,0,"C");
$pdf->Cell(35,10,"備註",1,1,"C");


for($j=0;$j<sizeof($persub_rows['ID']);$j++)
{
	$id=$persub_rows['ID'][$j];//准考證號碼
	$name=$persub_rows['NAME'][$j];//考生姓名
	$dept_name=$persub_rows['DEPT_NAME'][$j];//系所名稱
	$organize_name=$persub_rows['ORGANIZE_NAME'][$j];//組別
	$orastatus_name=$persub_rows['ORASTATUS_NAME'][$j];//身分別
	$dept_group = $dept_name . $organize_name .  $orastatus_name ;
	$disobey=$persub_rows['DISOBEY'][$j];//建議扣分
	$score=$persub_rows['SCORE'][$j];//原始分數
	$remark=$persub_rows['REMARK'][$j];//備註
	$section_subject=$persub_rows['SECTION_SUBJECT'][$j];//節次_科目名稱
	$arr_section_subject = explode("_",$section_subject);
	$section=$arr_section_subject[0];//節次
	$subject_name=$arr_section_subject[1];//科目名稱
		
	if (strlen($dept_group)>30 || strlen($subject_name)>30 || strlen($remark)>30) $height = 20;
    else $height = 10;

	$pdf->Cell(10,$height,$j+1,1,0,"C");

	if ($height==20)
		multiCell(40,$height,$dept_group,0,0,"C"); //當字串長度太長時,切成兩行顯示
	else
		$pdf->Cell(40,$height,$dept_group,1,0,"C");

	$pdf->Cell(20,$height,$id,1,0,"C");
	$pdf->Cell(20,$height,$name,1,0,"C");
	if ($height==20)
		multiCell(30,$height,$subject_name,0,0,"C"); //當字串長度太長時,切成兩行顯示
	else
		$pdf->Cell(30,$height,$subject_name,1,0,"C");

	//$pdf->Cell(40,$height,$subject_name,1,0,"C");
	$pdf->Cell(10,$height,$section,1,0,"C");
	$pdf->Cell(15,$height,$score,1,0,"C");
	$pdf->Cell(15,$height,$disobey,1,0,"C");
	if ($height==20)
		multiCell(35,$height,$remark,0,0,"C"); //當字串長度太長時,切成兩行顯示
	else
		$pdf->Cell(35,$height,$remark,1,0,"C");
	$pdf->Cell(1,$height,"","L",1,"C");
}
$pdf->Cell(195,$height,"","T",1,"C"); //補multicell的下方框線
$pdf->Output('');



//當字串長度太長時,切成兩行顯示
function multiCell($width,$height,$text,$border,$ln,$align){
	global $pdf;	
	$text1=mb_substr($text,0,9,"UTF-8"); //第一行
	$text2=mb_substr($text,9,10,"UTF-8"); //第二行
	$CurrentX = $pdf->GetX();
	$CurrentY = $pdf->GetY() ;
	$pdf->SetXY($CurrentX,$CurrentY);
	$pdf->Cell($width,$height/2,$text1,"T",$ln,$align);
	$pdf->SetXY($CurrentX,$CurrentY+6);
	$pdf->Cell($width,$height/2,$text2,0,$ln,$align);
	$pdf->SetXY($CurrentX+$width,$CurrentY);
}
?>