<?php
//include_once("../inc/check.php");
session_start();
include_once("../inc/connect.php"); 
$school_id = $_SESSION['school_id'] ;
$year = $_SESSION['year'] ;
$classroom_s = $_POST['classroom_s'];
$classroom_e = $_POST['classroom_e'];

if ($_POST['oper']=="classroom")//依試場起訖號列印桌角名條
{
  	$sql_seat="select a.classroom_id,a.seat_id,a.student_id,c.name,d.name dept_name,e.name orastatus_name from seat a,person b,signupdata c,department d,orastatus e
 where a.school_id='$school_id' and a.year='$year' and a.classroom_id between $classroom_s and $classroom_e
 and b.school_id= a.school_id and b.year = a.year and b.student_id = a.student_id
 and c.school_id= b.school_id and c.year = b.year and c.id=b.id and c.orastatus_id = substr(b.student_id,1,5)
 and d.school_id= a.school_id and d.year = a.year and d.id = substr(a.student_id,1,3)
 and e.school_id= a.school_id and e.year = a.year and e.id = substr(a.student_id,1,5)
 order by a.classroom_id,a.seat_id ";
}

if ($_POST['oper']=="spare")//列印備用桌角名條(1張共16筆)
{
	$sql_seat="select '' classroom_id,'' seat_id,'' student_id,'' name,'' dept_name,'' orastatus_name from seat where school_id='$school_id' and year='$year' and rownum<=16";
}


$seat_rows = $db -> query_array ($sql_seat);

$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

$title = $title_rows['TITLE_NAME'][0] ;

require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->SetMargins(3,3);  //設定邊界(需在第一頁建立以前)
$pdf->SetAutoPageBreak(1, 0.1) ; //auto break ; bottom margin
//$pdf->AddPage();
$pdf->AddUniCNShwFont('font1');
$pdf->SetFont('font1');

$pdf->SetFontSize(9);
$count=-1;
for($i=0;$i<sizeof($seat_rows['DEPT_NAME']);$i++)
{
	$classroom_id=$seat_rows['CLASSROOM_ID'][$i];
	$dept_name=$seat_rows['DEPT_NAME'][$i];
	$name=$seat_rows['NAME'][$i];
	$orastatus_name=$seat_rows['ORASTATUS_NAME'][$i];
	$student_id=$seat_rows['STUDENT_ID'][$i];
	$count++;
	if($count%16==0 || $classroom_id !=$classroom_id_previous) //不同試場或滿16筆換頁
	{
		$pdf->AddPage();
		$count=0;
	}
	
	$classroom_id_previous = $classroom_id ;	

	if ($count%16<8){//左邊
		$X = 10;
		$Y = (4+($count%16) * 37.5);
	}
	else{//右邊
		$X = 110;
		$Y = (4+($count%16 -8) * 37.5);
	}
	$height = 4.8;
	$pdf->SetXY($X,$Y);
	$pdf->SetFontSize(10);
	$pdf->Cell(90,$height,"國立彰化師範大學",0,1,"C");
	$pdf->SetX($X);
	$pdf->Cell(90,$height,$title,0,1,"C");
	$pdf->SetX($X);
	$pdf->SetFontSize(12);
	$pdf->Cell(90,$height,$dept_name . $orastatus_name,0,1,"C");
	$pdf->SetX($X);
	$pdf->SetFont('font1','B',13);
	$pdf->Cell(90,6,$student_id."   " . $name,0,1,"C");
	$pdf->SetFont('font1','',11);
	$pdf->SetX($X);
	if ($_POST['oper']=="classroom")
		$pdf->Cell(90,$height,"◎資料如有誤，請通知監試人員◎",0,1,"C");
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