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
		// global $title ;
		// $this->SetFont('font1','B',12); 
		// $this->Cell(0,8,$title,"BT",1,'C');			
	}	
}

$school_id = $_SESSION['school_id'] ;
$year = $_SESSION['year'] ;
$campus_id = $_POST['qry_campus'];
$dept_id = $_POST['qry_dept'];

//查詢考試全銜
$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);
$title = "國立彰化師範大學" . $title_rows['TITLE_NAME'][0] ."成績通知單";

//查詢成績單備註說明
$sql_score="select remark from scoreremark where school_id='$school_id' and year='$year' ";
		$score_rows = $db -> query_array ($sql_score);

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
		orastatus_name,allperson,enrollperson,resultscore,secondscore from orastatus a,department b,organize c
		where a.school_id='$school_id' and a.year='$year' and a.id like '$sql_cond'
		and b.school_id=a.school_id and b.year=a.year and b.id=substr(a.id,1,3)
		and c.school_id=a.school_id and c.year=a.year and c.id=substr(a.id,1,4)
		order by a.id";
$ora_rows = $db -> query_array ($sql_ora);

$pdf=new myPDF();
$pdf->SetMargins(15,5);  //設定邊界(需在第一頁建立以前)
$pdf->SetAutoPageBreak(1, 1) ;
//$pdf->AddUniCNShwFont('font1');
$pdf->AddUniCNShwFont('font1','DFKaiShu-SB-Estd-BF'); 
$pdf->SetFont('font1');
$width =62 ;
for($i=0;$i<sizeof($ora_rows['ID']);$i++)
{	
	$orastatus_id = $ora_rows['ID'][$i];
	$dept_name = $ora_rows['DEPT_NAME'][$i] ;
	$organize_name = $ora_rows['ORGANIZE_NAME'][$i] ;
	$orastatus_name = $ora_rows['ORASTATUS_NAME'][$i] ;
	$resultscore = $ora_rows['RESULTSCORE'][$i] ;
	$secondscore = $ora_rows['SECONDSCORE'][$i] ;
	
	$sql_per="select student_id,name,zip,address ,allscore,allratescore ,mainnumber,backnumber from person a,signupdata b 
		where a.school_id='$school_id' and a.year='$year' and substr(a.student_id,1,5)='$orastatus_id' 
		and b.school_id=a.school_id and b.year=a.year and b.id=a.id and 
		b.orastatus_id =substr(a.student_id,1,5)
		order by student_id";
	$per_rows = $db -> query_array ($sql_per);

	for($j=0;$j<sizeof($per_rows['NAME']);$j++)
	{
		$student_id = $per_rows['STUDENT_ID'][$j];
		$name = $per_rows['NAME'][$j];
		$zip = $per_rows['ZIP'][$j];
		$address = $per_rows['ADDRESS'][$j];
		$allratescore = $per_rows['ALLRATESCORE'][$j];
		$mainnumber = $per_rows['MAINNUMBER'][$j];
		$backnumber = $per_rows['BACKNUMBER'][$j];
		$pdf->AddPage();
		$pdf->SetFont('font1','B',16);
		$pdf->SetXY(60,50);
		$pdf->Cell(0,10,$zip .$address,0,1,"L");
		$pdf->SetXY(80,60);
		$pdf->Cell(0,10,"   " .$name ." 君收",0,1,"L");
		$pdf->SetXY(15,110);
		$pdf->SetFont('font1','',12);
		$pdf->Cell(0,10,$title,1,1,"L");
		$pdf->Cell(110,9,"系所別：$dept_name",1,0,"L");
		$pdf->Cell(70,9,"組別：$organize_name",1,1,"L");
		$pdf->Cell(110,9,"准考證：$student_id",1,0,"L");
		$pdf->Cell(70,9,"姓名：$name ($orastatus_name)",1,1,"L");
		$pdf->Cell(90,9,"科目",1,0,"C");
		$pdf->Cell(25,9,"原始分數",1,0,"C");
		$pdf->Cell(25,9,"科目比重",1,0,"C");
		$pdf->Cell(40,9,"指定科目檢定標準",1,1,"C");
		$sql_sub="select name,rate,nvl(top_stan,0) top_stan,nvl(front_stan,0) front_stan,nvl(standard,0) standard,nvl(beyond_stan,0) beyond_stan,nvl(base_stan,0) base_stan,nvl(qualified, 0) qualified, nvl(b.result, 0) result,disobey,remark
			 from subject a,persub b 
			 where a.school_id='$school_id' and a.year='$year' and b.school_id=a.school_id and 
			 b.year=a.year and a.id = b.subject_id and b.person_student_id ='$student_id' 
			 order by a.id";
		$sub_rows = $db -> query_array ($sql_sub);
		for($k=0;$k<sizeof($sub_rows['NAME']);$k++)
		{
			$subject_name = $sub_rows['NAME'][$k];
			$result = $sub_rows['RESULT'][$k];
			$rate = $sub_rows['RATE'][$k];
			$qualified = $sub_rows['QUALIFIED'][$k];
			//"無","頂標","前標","均標","後標","底標"
			$arr_qualified = array("-",$sub_rows['TOP_STAN'][$k],$sub_rows['FRONT_STAN'][$k],$sub_rows['STANDARD'][$k],$sub_rows['BEYOND_STAN'][$k],$sub_rows['BASE_STAN'][$k]);
			$qualified_score = $arr_qualified[$qualified];
			$pdf->Cell(90,9,$subject_name,1,0,"L");
			$pdf->Cell(25,9,$result ,1,0,"C");
			$pdf->Cell(25,9,$rate . "%",1,0,"C");
			$pdf->Cell(40,9,$qualified_score,1,1,"C");
		}
		$pdf->Cell(90,9,"總分",1,0,"L");
		$pdf->Cell(90,9,$allratescore,1,1,"C");

		if(in_array($orastatus_id,$union_rows['OPTION_ID'])) //聯合招生系所
		{
			$sql_result="select option_name, nvl(mainnumber,0) mainnumber, nvl(backnumber,0) backnumber
			from union_priority_all where school_id='$school_id' and year='$year' and student_id = '$student_id' and (mainnumber>0 or backnumber>0) order by priority";
			$result_rows = $db -> query_array ($sql_result);
			if(sizeof($result_rows['OPTION_NAME'])==0) 
				$pdf->Cell(40,7,"評定結果：未達錄取標準(含指定科目檢定不合格)" ,0,1,"L");
			else
				$pdf->Cell(22,7,"評定結果：" ,0,0,"L");
			for($k=0;$k<sizeof($result_rows['OPTION_NAME']);$k++)
			{
				$option_name = $result_rows['OPTION_NAME'][$k];
				$union_mainnumber = $result_rows['MAINNUMBER'][$k];
				$union_backnumber = $result_rows['BACKNUMBER'][$k];
				if($k>0) $pdf->Cell(22,7,"    " ,0,0,"L");
				if ($union_mainnumber>0) 
					$pdf->Cell(40,7,$option_name ."-正取" ,0,1,"L");
				if ($union_backnumber>0) 
					$pdf->Cell(40,7,$option_name ."-備取第 " .$union_backnumber." 名",0,1,"L");
			}
			
		}
		else
		{
			if ($mainnumber>0) $pdf->Cell(40,7,"評定結果：正取" ,0,1,"L");
			else if ($backnumber>0) $pdf->Cell(40,7,"評定結果：備取第 " .$backnumber." 名" ,0,1,"L");
			else $pdf->Cell(40,7,"評定結果：未達錄取標準(含指定科目檢定不合格)" ,0,1,"L");
		}

		if(in_array($orastatus_id,$add_rows['ID'])) //擇優錄取系所
		{
			$sql_back2="select b.name dept_name, decode(c.name, '不分組', ' ', c.name) organize_name, a.add_number from add_enroll a, department b, organize c where a.school_id='$school_id' and a.year='$year' and a.student_id = '$student_id' and b.school_id=a.school_id and b.year=a.year and b.id=substr(a.id, 1,3) and c.school_id=a.school_id and c.year=a.year and c.id=substr(a.id, 1,4) 
				order by a.id";
			$back2_rows = $db -> query_array ($sql_back2);
			for($k=0;$k<sizeof($back2_rows['DEPT_NAME']);$k++)
			{
				$dept_name_back2 = $back2_rows['DEPT_NAME'][$k];
				$organize_name_back2 = $back2_rows['ORGANIZE_NAME'][$k];
				$add_number = $back2_rows['ADD_NUMBER'][$k];
				$pdf->Cell(40,8,"          " .$dept_name_back2 . $organize_name_back2 ."-備取第 " .$add_number." 名",0,1,"L");
			}
		}

		//違反試場規則
		for($k=0;$k<sizeof($sub_rows['NAME']);$k++)
		{
			$subject_name = $sub_rows['NAME'][$k];
			$disobey= $sub_rows['DISOBEY'][$k];
			$remark= $sub_rows['REMARK'][$k];
			if($disobey>0) 
				$pdf->Cell(40,7,"          **應考「 $subject_name 」$remark,扣該科成績 $disobey 分",0,1,"L");
		}
		$pdf->SetFontSize(12);

		$pdf->Cell(40,7,"備註：",0,1,"L");
		if ($resultscore ==null) 
			$pdf->Cell(40,7,"1.正取從缺",0,1,"L");
		else if ($secondscore == null)
			$pdf->Cell(40,7,"1.正取總分最低分： $resultscore        不列備取",0,1,"L");
		else if ($secondscore<10) 
			$pdf->Cell(40,7,"1.正取總分最低分：$resultscore        備取總分最低分請洽各招生系所",0,1,"L");
		else
			$pdf->Cell(40,7,"1.正取總分最低分：$resultscore        備取總分最低分：$secondscore",0,1,"L");

		//成績單備註說明
		if(sizeof($score_rows['REMARK'])>0)
		{
			$scoreremark = $score_rows['REMARK'][0];
			$pdf->MultiCell(180,7,$scoreremark,0,1,"L");
		}
	}
}

$pdf->Output('');

?>	
