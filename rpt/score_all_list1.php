<?php
//include_once("../inc/check.php");
session_start();
include_once("../inc/connect.php");

require('chinese-unicode.php');
class myPDF extends PDF_Unicode
{
	//Page header
	function Header()
	{
		global $title,$dept_name,$organize_name,$orastatus_name,$allperson,$enrollperson,$subject_rows,$width ;
		$this->SetFontSize(12); 
		$this->Cell(0,8,$title."    " .$dept_name."(" . $orastatus_name.")" .$organize_name,"T",1,'L');
		$this->SetFontSize(12);
		$this->Cell(0,8,"列表日期：" . date("Y/m/d") . "  招生人數 : $enrollperson 人  報名人數 : $allperson 人   #表示不合格","B",1,"L");
		$this->Cell(0,5,"",0,1,'C');
		$this->SetFont('font1','B',10);
		$width = ceil(272 / (sizeof($subject_rows['NAME']) +4));
		$max =0;
		//找出科目名稱最長者,以決定欄高
		for($j=0;$j<sizeof($subject_rows['NAME']);$j++) 
		{
			$len = strlen($subject_rows['NAME'][$j]);
			if($len >$max) $max=$len ;
		}
		$wordLimit = 12 - sizeof($subject_rows['NAME']) ; //每欄一行的字數限制
		$rows = ceil($max /($wordLimit*3)) +1 ;//每個中文字長度=3
		$height = $rows * 5 ;		
		$this->Cell($width,$height,"准考證號",1,0,'C');	
		$this->Cell($width,$height,"姓名",1,0,'C');	
		$this->Cell($width,$height,"名次",1,0,'C');	
		$CurrentX = $this->GetX();
		$CurrentY = $this->GetY() ;
		for($j=0;$j<sizeof($subject_rows['NAME']);$j++) 
		{
			$subject_show = $subject_rows['NAME'][$j] ."\r\n(" . $subject_rows['RATE'][$j] ."%)" ;
			$this->SetXY($CurrentX+$width*$j,$CurrentY);
			$this->Cell($width,$height,"",1,0,'C'); //補邊框
			$this->SetXY($CurrentX+$width*$j,$CurrentY);
			$this->MultiCell($width,5,$subject_show,"TLR","C"); 
		}
		$this->SetXY($CurrentX+$width*$j,$CurrentY);
		$this->Cell($width,$height,"總分(加權後)",1,1,'C');
		
	}	
}

$school_id = $_SESSION['school_id'] ;
$year = $_SESSION['year'] ;
$campus_id = $_POST['qry_campus'];
$dept_id = $_POST['qry_dept'];

//查詢考試全銜
$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

if(strlen($dept_id)==3) $sql_cond = $dept_id ."%";
else if(strlen($campus_id)==2) $sql_cond = $campus_id ."%";
else $sql_cond = "%" ;
//step 1: 取得所有身分別(orastatus)
$sql_ora="select a.id,b.name dept_name,c.name organize_name,a.name 	
		orastatus_name,allperson,enrollperson from orastatus a,department b,organize c
		where a.school_id='$school_id' and a.year='$year' and a.id like '$sql_cond'
		and b.school_id=a.school_id and b.year=a.year and b.id=substr(a.id,1,3)
		and c.school_id=a.school_id and c.year=a.year and c.id=substr(a.id,1,4)
		order by a.id";
$ora_rows = $db -> query_array ($sql_ora);

$title = "國立彰化師範大學" . $title_rows['TITLE_NAME'][0] ." 成績檢核總表";

$pdf=new myPDF();
$pdf->SetMargins(10,5);  //設定邊界(需在第一頁建立以前)
//$pdf->AddUniCNShwFont('font1');
$pdf->AddUniCNShwFont('font1','DFKaiShu-SB-Estd-BF'); 
//$pdf->AddBig5Font('font1','標楷體');
$pdf->SetFont('font1');

for($i=0;$i<sizeof($ora_rows['ID']);$i++)
{
	if($i>0)
		$pdf->Cell(0,7,"共有：" . $allperson ." 人",0,0,"L");
	//step 2: 取得該身分別之所有考科(subject)
	$orastatus_id = $ora_rows['ID'][$i] ;
	$dept_name = $ora_rows['DEPT_NAME'][$i] ;
	$organize_name = $ora_rows['ORGANIZE_NAME'][$i] ;
	$orastatus_name = $ora_rows['ORASTATUS_NAME'][$i] ;
	$allperson = $ora_rows['ALLPERSON'][$i] ;
	$enrollperson = $ora_rows['ENROLLPERSON'][$i] ;
	$sql_subject="select id,name,rate from subject where school_id='$school_id' and year='$year' and orastatus_id='$orastatus_id' order by id";	
	$subject_rows = $db -> query_array ($sql_subject);
	
	$pdf->AddPage('L','A4');

	$sql_persub ="";
	for($j=0;$j<sizeof($subject_rows['ID']);$j++) //產生查詢各科成績的SQL
	{
		$subject_id = $subject_rows['ID'][$j];
		$sql_persub .= ",	(select result || '_' || on_off_exam || '_' || note from persub where school_id='$school_id' and year='$year' and person_student_id=a.student_id and subject_id='$subject_id') score$j"; 
	}
	//step 3: 取得該身分別之所有考生各科成績及排名(person,persub)
	$sql_per="select student_id,decode(allnumber,0,999,allnumber) allnumber,allscore,allratescore ,
		(select max(name) from signupdata where school_id='$school_id' and year='$year' and id=a.id and orastatus_id=substr(a.student_id,1,5)) stu_name  $sql_persub
		from person a
		where school_id='$school_id' and year='$year'
		and substr(student_id,1,5)='$orastatus_id' order by allnumber,allratescore desc,id";
		//echo $sql_per;
	$per_rows = $db -> query_array ($sql_per);
	$width = ceil(272 / (sizeof($subject_rows['NAME']) +4));	
	for($k=0;$k<sizeof($per_rows['STUDENT_ID']);$k++)
	{   
		$allnumber=$per_rows['ALLNUMBER'][$k];
		$student_id=$per_rows['STUDENT_ID'][$k];
		$stu_name=$per_rows['STU_NAME'][$k];
		if($allnumber==999) $allnumber = "-" ;
		$allratescore=$per_rows['ALLRATESCORE'][$k];
		$pdf->Cell($width,7,$student_id ,1,0,"C");
		$pdf->Cell($width,7,$stu_name ,1,0,"C");
		$pdf->Cell($width,7,$allnumber,1,0,"C");
		for($j=0;$j<sizeof($subject_rows['ID']);$j++) 
		{
			$result_exam = $per_rows["SCORE$j"][$k];//ex. score0 ,score1 ...
			$arr_result_exam = explode("_",$result_exam);
			$result=$arr_result_exam[0]; //成績
			$on_off_exam=$arr_result_exam[1]; //是否缺考 1:缺考
			$note=$arr_result_exam[2]; //是否符合標準 0:不符
			if($on_off_exam==1)
				$pdf->Cell($width,7,"--",1,0,"L");//缺考
			else if ($note==1)
				$pdf->Cell($width,7,$result ."#",1,0,"L");//不符標準
			else
				$pdf->Cell($width,7,$result,1,0,"L");
		}		
		$pdf->Cell($width,7,$allratescore,1,1,"L");
		
	}
}
$pdf->Cell(0,7,"共有：" . $allperson ." 人",0,0,"L");

$pdf->Output('');

?>	
