<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];
require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->AddUniCNShwFont('font1');

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
	$sql_seat="select start_stuid,end_stuid from stu_number 
			where  school_id='$school_id' and year='$year' and classroom_id='$classroom_id' order by rowid";			
	$seat_rows = $db -> query_array ($sql_seat);

	if($classroom_id<=9)
		$classroom_id="00".($classroom_id);
	else 
		$classroom_id="0".($classroom_id);
	
	$pdf->AddPage();
	$pdf->SetFont('font1','B');	
	$pdf->SetFontSize(50); 
	$pdf->Cell(40,30,"",0,0,'C');
	$pdf->Cell(18,30,"第",0,0,'C');
	$pdf->SetFontSize(120); 
	$pdf->Cell(75,30,$classroom_id,0,0,'C');
	$pdf->SetFontSize(50); 
	$pdf->Cell(18,30," 試場",0,1,'C');
	$pdf->Cell(0,30,"准考證號",0,1,'C');

	$pdf->SetFont('font1','B',55);	

	for($i=0;$i<sizeof($seat_rows['START_STUID']);$i++) //固定7排
	{		
		$direct_label = $seat_rows['START_STUID'][$i] . "─" . $seat_rows['END_STUID'][$i] ;
		$pdf->Cell(0,25,$direct_label,0,1,'C');		
	}
}

$pdf->Output('');

?>