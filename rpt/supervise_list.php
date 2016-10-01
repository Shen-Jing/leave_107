<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];
require('chinese-unicode.php');

$pdf=new PDF_Unicode();
//$pdf->AddUniCNShwFont('font1');
$pdf->AddUniCNShwFont('font1','DFKaiShu-SB-Estd-BF'); 
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
	$sql_seat="select a.seat_id,b.student_id,c.id,c.name,d.name dept_name,e.name organize_name,f.name orastatus_name
 from seat a,person b,signupdata c,department d,organize e,orastatus f
 where a.school_id='$school_id' and a.year='$year' 
 and a.school_id=b.school_id and a.year=b.year and b.school_id=c.school_id and b.year=c.year 
 and c.school_id=d.school_id and c.year=d.year and d.school_id=e.school_id and d.year=e.year
 and a.student_id=b.student_id and b.id =c.id and d.id=substr(b.student_id,1,3) 
 and e.id=substr(b.student_id,1,4) and f.id=substr(b.student_id,1,5) and  a.classroom_id='$classroom_id'  order by seat_id";
	$seat_rows = $db -> query_array ($sql_seat);

	if($classroom_id<=9)
		$classroom_id="00".($classroom_id);
	else 
		$classroom_id="0".($classroom_id);

	$total_page = ceil(sizeof($seat_rows['SEAT_ID']) /12); //總頁數
	
	for($i=0;$i<sizeof($seat_rows['SEAT_ID']);$i++) //一頁12筆,左右各6筆
	{		
		$page = ($i /12) +1; //目前頁數
		if($i%12==0) //頁首
		{
			$pdf->AddPage();
			$pdf->SetFont('font1','',16);
			$pdf->Cell(0,10,"國立彰化師範大學",0,1,'C');
			$pdf->Cell(0,10,$title,0,1,'C');
			$pdf->Cell(130,10,"第 $classroom_id 試場 考生資料",0,0,'R');
			$pdf->SetFontSize(12);
			$pdf->Cell(60,10,"第 $page 頁(本試場共 $total_page 頁)",0,0,'R');
			$pdf->SetXY(60,265);
			$pdf->Cell(120,10,"※監試人員請詳細核對：桌角名牌、准考證及身分證明文件",0,0,'R');
			$pdf->SetFontSize(13);
		}

		$student_id = $seat_rows['STUDENT_ID'][$i] ;
		$id = $seat_rows['ID'][$i] ;
		$name = $seat_rows['NAME'][$i] ;
		$dept_name = $seat_rows['DEPT_NAME'][$i] ;
		$organize_name = $seat_rows['ORGANIZE_NAME'][$i] ;
		$orastatus_name = $seat_rows['ORASTATUS_NAME'][$i] ;
		
		if ($i%12<6){//左邊
			$X = 10;
			$Y = (41+($i%12) * 37);
		}
		else{//右邊
			$X = 110;
			$Y = (41+($i%12 -6) * 37);
		}
		$height = 7.4;
		$pdf->SetXY($X,$Y);
		$pdf->Cell(90,$height,"准考證號：$student_id ","LTR",1,'L');	
		$pdf->SetX($X);	
		$pdf->Cell(90,$height,"姓名：$name ","LR",1,'L');
		$pdf->SetX($X);			
		$pdf->Cell(90,$height,"身分證號：$id ","LR",1,'L');
		$pdf->SetX($X);		
		$pdf->Cell(90,$height,"$dept_name ","LR",1,'L');	
		$pdf->SetX($X);	
		$pdf->Cell(90,$height,"$organize_name $orastatus_name ","LBR",1,'L');		
	}
}

$pdf->Output('');

?>