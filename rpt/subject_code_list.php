<?php
include_once("../inc/check.php");
$school_id=$_SESSION['school_id'];
$year=$_SESSION['year'];


$sql_sub="select a.name depart_name,b.name test_type,c.name flag,d.id,d.name,rate,qualified,compare from department a ,organize b,orastatus c,subject d
where  a.school_id='$school_id' and a.year='$year' and a.school_id=b.school_id and a.year=b.year  and a.school_id=c.school_id and a.year=c.year  and a.school_id=d.school_id and a.year=d.year and a.id =substr(b.id,1,3) and b.id=substr(c.id,1,4) and c.id=substr(d.id,1,5) order by d.id";
$sub_rows = $db -> query_array ($sql_sub);

$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);

$title = $title_rows['TITLE_NAME'][0] ." 科目代碼一覽表";

require('chinese-unicode.php');

$pdf=new PDF_Unicode();
$pdf->SetMargins(6,6);  //設定邊界(需在第一頁建立以前)
$pdf->AddPage();
$pdf->AddUniCNShwFont('font1');
//$pdf->AddBig5Font('font1','標楷體');
$pdf->SetFont('font1');

$pdf->SetFontSize(16); 
$pdf->Cell(0,10,$title,0,1,'C');
$pdf->SetFontSize(10);
$pdf->Cell(50,10);
$pdf->Cell(0,10,"列印日期：" . date("Y/m/d"),0,1,'R'); 
$pdf->Cell(66,12,"系所名稱",1,0,"C");
$pdf->Cell(18,12,"組別",1,0,"C");
$pdf->Cell(25,12,"身分",1,0,"C");
$pdf->Cell(16,12,"科目代碼",1,0,"C");
$pdf->Cell(43,12,"科目名稱",1,0,"C");
$pdf->Cell(10,6,"計分","TLR",0,"C");
$pdf->Cell(10,6,"科目","TLR",0,"C");
$pdf->Cell(10,6,"同分","TLR",1,"C");
$CurrentX = $pdf->GetX();
$CurrentY = $pdf->GetY() ;
$pdf->SetXY($CurrentX+168,$CurrentY);
$pdf->Cell(10,6,"比例","BLR",0,"C");
$pdf->Cell(10,6,"檢定","BLR",0,"C");
$pdf->Cell(10,6,"參酌","BLR",1,"C");

for($j=0;$j<sizeof($sub_rows['ID']);$j++)
{   
	$depart_name=$sub_rows['DEPART_NAME'][$j];
	$sub_type=$sub_rows['TEST_TYPE'][$j];
	$sub_flag=$sub_rows['FLAG'][$j];
	$sub_id = $sub_rows['ID'][$j];
	$sub_name=$sub_rows['NAME'][$j];
	$sub_rate = $sub_rows['RATE'][$j];
	$sub_compare= $sub_rows['COMPARE'][$j];
	$qualified = $sub_rows['QUALIFIED'][$j];
	$arr_qualified = array("無","頂標","前標","均標","後標","底標","底標");
	$sub_qualified=$arr_qualified[$qualified];
	
    if (strlen($sub_name)>36) $height = 20;
    else $height = 10;
	$pdf->Cell(66,$height,$depart_name,1,0,"C");
	$pdf->Cell(18,$height,$sub_type,1,0,"C");
	$pdf->Cell(25,$height,$sub_flag,1,0,"C");
	$pdf->Cell(16,$height,$sub_id,1,0,"C");
	if ($height==20)
		multiCell(43,$height,$sub_name,0,0,"C"); //當字串長度太長時,切成兩行顯示
	else
		$pdf->Cell(43,$height,$sub_name,1,0,"C");

	$pdf->Cell(10,$height,$sub_rate,1,0,"C");
	$pdf->Cell(10,$height,$sub_qualified,1,0,"C");
	$pdf->Cell(10,$height,$sub_compare,1,1,"C");

}

$pdf->Output('');

//當字串長度太長時,切成兩行顯示
function multiCell($width,$height,$text,$border,$ln,$align){
	global $pdf;	
	$text1=mb_substr($text,0,12,"UTF-8"); //第一行
	$text2=mb_substr($text,12,12,"UTF-8"); //第二行
	$CurrentX = $pdf->GetX();
	$CurrentY = $pdf->GetY() ;
	$pdf->SetXY($CurrentX,$CurrentY);
	$pdf->Cell($width,$height/2,$text1,"T",$ln,$align);
	$pdf->SetXY($CurrentX,$CurrentY+6);
	$pdf->Cell($width,$height/2,$text2,0,$ln,$align);
	$pdf->SetXY($CurrentX+$width,$CurrentY);
}
?>	
	
	
	
	
	
		
	
	
	
	
	
