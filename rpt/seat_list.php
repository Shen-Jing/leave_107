<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];

require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->AddUniCNShwFont('font1');
$pdf->SetMargins(5,5);  //設定邊界(需在第一頁建立以前)

$sql_classroom="select distinct classroom_id from seat 
			where  school_id='$school_id' and year='$year' 
			order by classroom_id";
$classroom_rows = $db -> query_array ($sql_classroom);

$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

$title = $title_rows['TITLE_NAME'][0] ;

for($c=0;$c<sizeof($classroom_rows['CLASSROOM_ID']);$c++)
{
	$classroom_id = $classroom_rows['CLASSROOM_ID'][$c]; //教室編號
	$sql_seat="select classroom_id,seat_id,student_id from seat 
			where  school_id='$school_id' and year='$year' and classroom_id='$classroom_id' order by seat_id";			
	$seat_rows = $db -> query_array ($sql_seat);

	if($classroom_id<=9)
		$classroom_id="00".($classroom_id);
	else 
		$classroom_id="0".($classroom_id);

	$classroom_label ="第 $classroom_id 試場  座位表";
	
	$pdf->AddPage();
	$pdf->SetFont('font1');
	$pdf->SetFontSize(16); 
	$pdf->Cell(0,10,"國立彰化師範大學",0,1,'C');
	$pdf->SetFontSize(16); 
	$pdf->Cell(0,10,$title,0,1,'C');
	$pdf->SetFontSize(28); 
	$pdf->Cell(0,12,$classroom_label,0,1,'C');

	$pdf->SetFontSize(12);
	$pdf->SetXY(88,50);
	$pdf->Cell(30,10,"講  台",1,0,'C');

	$seat_cnt = sizeof($seat_rows['SEAT_ID']) ; //共幾個座位
	$col = ceil($seat_cnt / 7 ); //每排幾人
	$now_flag = -1 ;

	for($i=1;$i<=7;$i++) //固定7排
	{
		for($j=1;$j<=$col;$j++) //由右到左順序排列
		{
			if( ($j * 7 - $i) >= $seat_cnt ) //最後一排多餘的空位
				continue;

			$now_flag ++ ;
			$student_id = $seat_rows['STUDENT_ID'][$now_flag] ;
			$X = 205 - ($i * 28);
			$Y = 50 + ($j * 18) ;
			$pdf->SetXY($X,$Y);
			$pdf->Cell(21,10,$student_id,1,0,'C');
		}
	}
}

$pdf->Output('');

?>