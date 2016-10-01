<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];
require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->AddUniCNShwFont('font1');

$sql_classroom="select classroom_id,seat_number,comments,
	 (select max(section) from subject where orastatus_id in 
		(select distinct substr(student_id,1,5) from seat where school_id='$school_id' and year='$year' and classroom_id='1')) section
	 from classroom where school_id='$school_id' and year='$year' and classroom_id in(select distinct classroom_id from seat where  school_id='$school_id' and year='$year') order by classroom_id";
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
	$pdf->SetFontSize(30); 
	$pdf->SetXY(10,60);
	$pdf->Cell(80,20,"  試場編號：第",0,0,'L');
	$pdf->SetFontSize(70); 
	$pdf->Cell(36,20,$classroom_id,0,0,'L');
	$pdf->SetFontSize(30); 
	$pdf->Cell(18,20," 試場",0,1,'L');
	$pdf->Cell(65,16,"  准考證號：",0,0,'L');

	$pdf->SetFont('font1','B',30);	

	for($i=0;$i<sizeof($seat_rows['START_STUID']);$i++) //固定7排
	{		
		$direct_label = $seat_rows['START_STUID'][$i] . "~" . $seat_rows['END_STUID'][$i] ;
		if($i>0)$pdf->Cell(65,16,"",0,0,'L');
		$pdf->Cell(150,16,$direct_label,0,1,'L');		
	}

	$seat_number = $classroom_rows['SEAT_NUMBER'][$c]; 
	$section = $classroom_rows['SECTION'][$c]; 
	$comments = $classroom_rows['COMMENTS'][$c]; 

	$pdf->Cell(80,20,"  人數：$seat_number 人",0,1,"L");
	$pdf->Cell(80,20,"  ※考至第 $section 節※",0,1,"L");
	$pdf->Cell(80,20,"  備註：$comments",0,1,"L");
}

$pdf->Output('');

?>