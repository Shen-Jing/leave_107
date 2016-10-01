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
		global $title ;
		$this->SetFont('font1','B',12); 
		$this->Cell(0,8,$title,"BT",1,'L');			
	}	
}

$school_id = $_SESSION['school_id'] ;
$year = $_SESSION['year'] ;
$campus_id = $_POST['qry_campus'];
$dept_id = $_POST['qry_dept'];

//查詢考試全銜
$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);
$title = "國立彰化師範大學" . $title_rows['TITLE_NAME'][0] ."成績業經評定完竣，茲公布正取、備取名單如下";

//將聯合招生的系所放入union_dept陣列
$sql_union="select distinct option_id from union_priority_all where  school_id='$school_id' and year='$year'";
$union_rows = $db -> query_array ($sql_union);

//將擇優錄取的系所放入add_dept陣列
$sql_add="select distinct id from add_enroll where  school_id='$school_id' and year='$year'";
$add_rows = $db -> query_array ($sql_add);


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

$pdf=new myPDF();
$pdf->SetMargins(10,5);  //設定邊界(需在第一頁建立以前)
//$pdf->AddUniCNShwFont('font1');
$pdf->AddUniCNShwFont('font1','DFKaiShu-SB-Estd-BF'); 
$pdf->SetFont('font1');
$width =62 ;
$orastatus_id_previous="";
for($i=0;$i<sizeof($ora_rows['ID']);$i++)
{	
	$orastatus_id = $ora_rows['ID'][$i];
	$dept_name = $ora_rows['DEPT_NAME'][$i] ;
	$organize_name = $ora_rows['ORGANIZE_NAME'][$i] ;
	$orastatus_name = $ora_rows['ORASTATUS_NAME'][$i] ;
	if(substr($orastatus_id_previous,0,3) != substr($orastatus_id,0,3))
		$pdf->AddPage();
	$orastatus_id_previous = $orastatus_id;
	$pdf->Ln();$pdf->Ln();
	$pdf->SetFont('font1','B',14);
	$pdf->Cell($width,7,"系所別：".$dept_name ."(" . $organize_name . ")",0,1,"L");
	
	//*****取得該身分別之所有正取生*****
	if(in_array($orastatus_id,$union_rows['OPTION_ID'])) //聯合招生系所
	{
		$sql_main="select student_id, (select name from signupdata where  school_id='$school_id' and year='$year' and signup_sn=a.sn) name from union_priority_all a where a.school_id='$school_id' and a.year='$year' and option_id = '$orastatus_id' and mainnumber>0 and backnumber=0 order by mainnumber";
	}
	else //一般招生系所
	{
		$sql_main="select student_id,mainnumber,allnumber,b.name from person a,signupdata b
		where a.school_id='$school_id' and a.year='$year' and substr(student_id,1,5)='$orastatus_id' and mainnumber>0
		and b.school_id=a.school_id and b.year=a.year and b.id=a.id and b.orastatus_id=substr(a.student_id,1,5)
		order by substr(student_id,1,5),allnumber ";
	}
	$main_rows = $db -> query_array ($sql_main);
	$pdf->Ln();
	$pdf->SetFont('font1','B',14);
	$pdf->Cell($width*3,10,"正取 $orastatus_name 依成績高低由左至右排序",1,1,"L");
	if(sizeof($main_rows['STUDENT_ID'])==0)
		$pdf->Cell($width*3,7,"從缺",1,1,"L");
	for($k=0;$k<sizeof($main_rows['STUDENT_ID']);$k++)
	{  
		$student_id=$main_rows['STUDENT_ID'][$k];
		$mainnumber=$main_rows['MAINNUMBER'][$k];
		$name=$main_rows['NAME'][$k];		
		if ($k>0 && $k%3==0) 
			$pdf->Ln();	

		$pdf->SetFont('font1',"",12);
		$pdf->Cell($width,7,$student_id ."  " . $name,1,0,"L");
	}
	$filled =  sizeof($main_rows['STUDENT_ID']) % 3 ; 
	if ($filled>0) 
		for($k=$filled;$k<3;$k++) //補該行剩下的框線
			$pdf->Cell($width,7,"",1,0,"L");

	//*****取得該身分別之所有備取生*****
	if(in_array($orastatus_id,$union_rows['OPTION_ID'])) //聯合招生系所
	{
		$sql_back="select student_id,backnumber, (select name from signupdata where  school_id='$school_id' and year='$year' and signup_sn=a.sn) name from union_priority_all a where a.school_id='$school_id' and a.year='$year' and option_id = '$orastatus_id' and backnumber>0 order by backnumber";
	}
	else //一般招生系所
	{
		$sql_back="select student_id,backnumber,allnumber,b.name from person a,signupdata b
		where a.school_id='$school_id' and a.year='$year' and substr(student_id,1,5)='$orastatus_id' and backnumber>0
		and b.school_id=a.school_id and b.year=a.year and b.id=a.id and b.orastatus_id=substr(a.student_id,1,5)
		order by substr(student_id,1,5),allnumber ";
	}

	$back_rows = $db -> query_array ($sql_back);
	$pdf->Ln();
	$pdf->SetFont('font1','B',14);
	$pdf->Cell($width*3,10,"備取 $orastatus_name 依成績高低排序",1,1,"L");
	if(sizeof($back_rows['STUDENT_ID'])==0)
		$pdf->Cell($width*3,7,"不列備取",1,1,"L");
	for($k=0;$k<sizeof($back_rows['STUDENT_ID']);$k++)
	{  
		$student_id=$back_rows['STUDENT_ID'][$k];
		$backnumber=$back_rows['BACKNUMBER'][$k];
		$name=$back_rows['NAME'][$k];		
		if ($k>0 && $k%3==0) 
			$pdf->Ln();	

		$pdf->SetFont('font1',"",12);
		$pdf->Cell($width,7,$student_id ." " . $name . "(備取" .$backnumber .")",1,0,"L");
	}

	$filled =  sizeof($back_rows['STUDENT_ID']) % 3 ; 
	if ($filled>0) 
		for($k=$filled;$k<3;$k++) //補該行剩下的框線
			$pdf->Cell($width,7,"",1,0,"L");

	//*****取得該身分別之所有擇優錄取生*****
	if(in_array($orastatus_id,$add_rows['ID'])) //擇優錄取系所
	{
		$sql_back2 = "select add_enroll.student_id,signupdata.name,add_enroll.add_number from signupdata a,add_enroll b where a.school_id='$school_id' and a.year='$year' and b.school_id=a.school_id and b.year=a.year and a.id = b.person_id and b.id = '$orastatus_id' order by add_number";		
		$back2_rows = $db -> query_array ($sql_back2);
		$pdf->Ln();
		$pdf->SetFont('font1','B',14);
		$pdf->Cell($width*3,10,"擇優錄取方案備取 $orastatus_name 依成績高低排序",1,1,"L");
		for($k=0;$k<sizeof($back2_rows['STUDENT_ID']);$k++)
		{  
			$student_id=$back2_rows['STUDENT_ID'][$k];
			$addnumber=$back2_rows['ADD_NUMBER'][$k];
			$name=$back2_rows['NAME'][$k];		
			if ($k>0 && $k%3==0) 
				$pdf->Ln();	

			$pdf->SetFont('font1',"",12);
			$pdf->Cell($width,7,$student_id ." " . $name . "(備取" .$addnumber .")",1,0,"L");
		}

		$filled =  sizeof($back2_rows['STUDENT_ID']) % 3 ; 
		if ($filled>0) 
			for($k=$filled;$k<3;$k++) //補該行剩下的框線
				$pdf->Cell($width,7,"",1,0,"L");

		}
}

$pdf->Output('');

?>	
