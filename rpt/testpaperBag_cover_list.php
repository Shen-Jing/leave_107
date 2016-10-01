<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];
require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->AddUniCNShwFont('font1');
$pdf->SetMargins(5,5);  //設定邊界(需在第一頁建立以前)


//取得教室編號
$sql_classroom="select distinct classroom_id  from seat where school_id='$school_id' and year='$year'  order by classroom_id";
$classroom_rows = $db -> query_array ($sql_classroom);

$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

$title = $title_rows['TITLE_NAME'][0] ;

for($c=0;$c<sizeof($classroom_rows['CLASSROOM_ID']);$c++)
{
	$classroom_id = $classroom_rows['CLASSROOM_ID'][$c]; //教室編號
	$sql_seat="select distinct id,name,section,
(select name from department where school_id='$school_id' and year='$year' and id=substr(a.id,1,3)) dept_name ,
(select name from organize where school_id='$school_id' and year='$year' and id=substr(a.id,1,4)) organize_name ,
(select name from orastatus where school_id='$school_id' and year='$year' and id=substr(a.id,1,5)) orastatus_name ,
(select count(*) from persub where school_id='$school_id' and year='$year' and subject_id=a.id and person_student_id in 
 (select distinct student_id from seat where school_id='$school_id' and year='$year' and classroom_id='$classroom_id') ) cnt 
from subject a where a.school_id='$school_id' and a.year='$year' and a.orastatus_id in 
(select distinct substr(student_id,1,5) from seat where school_id='$school_id' and year='$year' and classroom_id='$classroom_id')
order by id";
	
	$seat_rows = $db -> query_array ($sql_seat);

	if($classroom_id<=9)
		$classroom_id="00".($classroom_id);
	else 
		$classroom_id="0".($classroom_id);

	for($i=0;$i<sizeof($seat_rows['ID']);$i++) 
	{		
		$cnt = $seat_rows['CNT'][$i] ;
		$section = $seat_rows['SECTION'][$i] ;
		$id = $seat_rows['ID'][$i] ;
		$name = $seat_rows['NAME'][$i] ;
		$dept_name = $seat_rows['DEPT_NAME'][$i] ;
		$organize_name = $seat_rows['ORGANIZE_NAME'][$i] ;
		$orastatus_name = $seat_rows['ORASTATUS_NAME'][$i] ;
		
		if($cnt<=0 || $section>4) continue; //無人報考 , 口試

		$row++;
		if($row%2) //一頁2筆//上面
		{
			$pdf->AddPage();
			$X = 10;
			$Y = 10;
		}
		else{//下面
			$X = 10;
			$Y = 150;
		}
		//備題份數
		if($cnt>10) $ubi=2;
		else if($cnt>40) $ubi=3;
		else $ubi=1;
		$height = 12;
		$pdf->SetXY($X,$Y);
		$pdf->SetFont('font1','',23);
		$pdf->Cell(0,20,$title,0,1,'C');
		$pdf->SetFont('font1','',32);
		$pdf->Cell(70,10,"",0,0,'C');
		$pdf->Cell(70,18,"第 $classroom_id 試場",1,1,'C');
		$pdf->SetFontSize(20);
		$pdf->Cell(0,20,"",0,1,'C');
		$pdf->Cell(150,$height,"   節    次：第 $section 節",0,1,'L');	
		$pdf->Cell(46,$height,"   系所組別：",0,0,'L');
		$pdf->SetFontSize(17);
		$pdf->Cell(120,$height,"$dept_name $organize_name $orastatus_name",0,1,'L');
		$pdf->SetFontSize(20);
		$pdf->Cell(150,$height,"   考試科目：$name ",0,1,'L');
		$pdf->Cell(150,$height,"   考題份數：$cnt 份",0,1,'L');	
		$pdf->Cell(150,$height,"   備題份數：$ubi 份",0,1,'L');	
	}
}

$pdf->Output('');

?>