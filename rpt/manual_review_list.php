<?php
//include_once("../inc/check.php");
session_start();
include_once("../inc/connect.php"); 
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];

$subject_id=$_SESSION['subject_id'];
if(strlen($subject_id)!=7){
	echo "<script>alert('您尚未登入!');window.close();</script>";
	exit;
}

$sql_persub="select a.person_student_id id,a.result,a.on_off_exam,b.name subject_name,c.name dept_name,d.name organize_name
		from persub a,subject b,department c,organize d
		where a.school_id='$school_id' and a.year='$year' and a.subject_id='$subject_id' and a.result is not null
		and b.school_id=a.school_id and b.year=a.year and b.id=a.subject_id
		and c.school_id=a.school_id and c.year=a.year and c.id=substr(a.subject_id,1,3)
		and d.school_id=a.school_id and d.year=a.year and d.id=substr(a.subject_id,1,4)
		order by a.person_student_id";
		// echo $sql_persub;
		// exit;
$persub_rows = $db -> query_array ($sql_persub);

$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

$title = $title_rows['TITLE_NAME'][0] ." 一般考試分項成績表";

require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->AddPage();
$pdf->AddUniCNShwFont('font1');
$pdf->SetFont('font1');

$pdf->SetFontSize(16); 
$pdf->Cell(0,10,$title,0,1,'C');
$pdf->SetFontSize(12);
//$pdf->Cell(50,10);
$pdf->Cell(0,10,"系所：".$persub_rows['DEPT_NAME'][0]."    組別：". $persub_rows['ORGANIZE_NAME'][0] ."    考試科目：".$persub_rows['SUBJECT_NAME'][0] ,0,1,'L'); 
//$pdf->Cell(0,10,"列印日期：" . date("Y/m/d"),0,1,'R'); 
$pdf->Cell(30,10,"序號",1,0,"C");
$pdf->Cell(50,10,"准考證號",1,0,"C");
$pdf->Cell(60,10,"姓 名",1,0,"C");
$pdf->Cell(50,10,"成 績",1,1,"C");

for($j=0;$j<sizeof($persub_rows['ID']);$j++)
{
	$id=$persub_rows['ID'][$j];
	$result=$persub_rows['RESULT'][$j];
	$on_off_exam=$persub_rows['ON_OFF_EXAM'][$j];
	if($on_off_exam==1) $result="缺考";

	$sql_person="select b.name from person a,signupdata b
		where a.school_id='$school_id' and a.year='$year' and a.student_id='$id'
		and b.school_id=a.school_id and b.year=a.year and b.id=a.id";
	$person_rows = $db -> query_array ($sql_person);
	$name=$person_rows['NAME'][0];
	
	$pdf->Cell(30,10,$j+1,1,0,"C");
	$pdf->Cell(50,10,$id,1,0,"C");
	$pdf->Cell(60,10,$name,1,0,"C");
	$pdf->Cell(50,10,$result,1,1,"C");
}

$pdf->Cell(0,10,"註：本表請列印蓋系所戳章後，彌封由專人送交教學資源組。",0,1,"C");
$pdf->Cell(0,10,"",0,1,"C");
$pdf->Cell(0,10,"(系所戳章)",0,1,"R");

$pdf->Output('');
?>