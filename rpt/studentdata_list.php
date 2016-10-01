<?php
//include_once("../inc/check.php");
session_start();
include_once("../inc/connect.php");
$school_id = $_SESSION['school_id'] ;
$year = $_SESSION['year'] ;
$campus_id = $_POST['qry_campus'];
$dept_id = $_POST['qry_dept'];

require('chinese-unicode.php');
class myPDF extends PDF_Unicode
{
	//Page header
	function Header()
	{
		global $title;
		$this->SetFontSize(10); 
		$this->Cell(150,10,$title,"BT",0,'L');
		$this->Cell(133,10,"列表日期：" . date("Y/m/d") ,"BT",1,"R");
		$this->Cell(0,3,"",0,1,'C');
		$this->SetFontSize(8); 
		$height=7;
		$this->Cell(17,$height,"身分證字號",1,0,'C');	
		$this->Cell(15,$height,"姓名",1,0,'C');	
		$this->Cell(6,$height,"性別",1,0,'C');	
		$this->Cell(28,$height,"系所",1,0,'C');	
		$this->Cell(18,$height,"組別",1,0,'C');	
		$this->Cell(20,$height,"身分",1,0,'C');	
		$this->Cell(17,$height,"生日",1,0,'C');	
		$this->Cell(40,$height,"E-Mail",1,0,'C');	
		$this->Cell(20,$height,"聯絡電話(宅)",1,0,'C');	
		$this->Cell(20,$height,"聯絡電話(公)",1,0,'C');	
		$this->Cell(18,$height,"行動電話",1,0,'C');	
		$this->Cell(16,$height,"緊急聯絡人",1,0,'C');	
		$this->Cell(18,$height,"聯絡人電話",1,0,'C');	
		$this->Cell(16,$height,"聯絡人關係",1,0,'C');	
		//$this->Cell(10,$height,"特殊需求",1,0,'C');	
		$this->Cell(15,$height,"資格審查",1,1,'C');	
	}	
}

//查詢考試全銜
$sql_title="select * from title where  school_id='$school_id' and year='$year'";
$title_rows = $db -> query_array ($sql_title);
$title = "國立彰化師範大學" . $title_rows['TITLE_NAME'][0] ." 考生資料一覽表";

if(strlen($dept_id)==3) $sql_cond = $dept_id ."%";
else if(strlen($campus_id)==2) $sql_cond = $campus_id ."%";
else $sql_cond = "%" ;
//取得該身分別之所有考生基本資料
$sql_sign="select a.id,a.name,decode(a.sex,1,'男',0,'女','') sex,b.name dept_name,c.name organize_name,
		d.name orastatus_name,to_char(a.birthday, 'YYYY-MM-DD') birthday,a.email,a.tel_h,a.tel_o,a.tel_m,a.liaisoner,a.liaison_tel,
		a.liaison_rel from signupdata a,department b,organize c,orastatus d
		where a.school_id='$school_id' and a.year='$year' and a.dept_id like '$sql_cond'
		and b.school_id=a.school_id and b.year=a.year and b.id=a.dept_id
		and c.school_id=a.school_id and c.year=a.year and c.id=a.organize_id
		and d.school_id=a.school_id and d.year=a.year and d.id=a.orastatus_id
		order by orastatus_id,id";
		//echo $sql_sign ;
$sign_rows = $db -> query_array ($sql_sign);

$pdf=new myPDF();
$pdf->SetMargins(10,5);  //設定邊界(需在第一頁建立以前)
//$pdf->AddUniCNShwFont('font1');
$pdf->AddUniCNShwFont('font1','DFKaiShu-SB-Estd-BF'); 
$pdf->SetFont('font1');
$pdf->SetFontSize(8); 

$dept_name_previous = "";
for($i=0;$i<sizeof($sign_rows['ID']);$i++)
{	
	$id=$sign_rows['ID'][$i];
	$name=$sign_rows['NAME'][$i];
	$sex=$sign_rows['SEX'][$i];
	$dept_name=$sign_rows['DEPT_NAME'][$i];
	$organize_name=$sign_rows['ORGANIZE_NAME'][$i];
	$orastatus_name=$sign_rows['ORASTATUS_NAME'][$i];
	$birthday=$sign_rows['BIRTHDAY'][$i];
	$email=$sign_rows['EMAIL'][$i];
	$tel_h=$sign_rows['TEL_H'][$i];
	$tel_o=$sign_rows['TEL_O'][$i];
	$tel_m=$sign_rows['TEL_M'][$i];
	$liaisoner=$sign_rows['LIAISONER'][$i];
	$liaison_tel=$sign_rows['LIAISON_TEL'][$i];
	$liaison_rel=$sign_rows['LIAISON_REL'][$i];	
	if ($dept_name_previous != $dept_name)
	{
		$pdf->AddPage('L','A4');
		$dept_name_previous = $dept_name ;
	}
	if (strlen($dept_name)>30) $height = 14;
    else $height = 7;
	$pdf->Cell(17,$height,$id ,1,0,"C");
	$pdf->Cell(15,$height,$name ,1,0,"C");
	$pdf->Cell(6,$height,$sex ,1,0,"C");
	if ($height==14)
		multiCell(28,$height,$dept_name,0,0,"C"); //當字串長度太長時,切成兩行顯示
	else
		$pdf->Cell(28,$height,$dept_name,1,0,"C");
	//$pdf->Cell(30,$height,$dept_name ,1,0,"C");
	$pdf->Cell(18,$height,$organize_name ,1,0,"C");
	$pdf->Cell(20,$height,$orastatus_name ,1,0,"C");
	$pdf->Cell(17,$height,$birthday ,1,0,"C");
	$pdf->Cell(40,$height,$email ,1,0,"C");
	$pdf->Cell(20,$height,$tel_h ,1,0,"C");
	$pdf->Cell(20,$height,$tel_o ,1,0,"C");
	$pdf->Cell(18,$height,$tel_m ,1,0,"C");
	$pdf->Cell(16,$height,$liaisoner ,1,0,"C");
	$pdf->Cell(18,$height,$liaison_tel ,1,0,"C");
	$pdf->Cell(16,$height,$liaison_rel ,1,0,"C");
	//$pdf->Cell(10,$height,"" ,1,0,"C");
	$pdf->Cell(15,$height,"" ,1,1,"C");
}

$pdf->Output('');


//當字串長度太長時,切成兩行顯示
function multiCell($width,$height,$text,$border,$ln,$align){
	global $pdf;	
	$text1=mb_substr($text,0,7,"UTF-8"); //第一行
	$text2=mb_substr($text,7,7,"UTF-8"); //第二行
	$CurrentX = $pdf->GetX();
	$CurrentY = $pdf->GetY() ;
	$pdf->SetXY($CurrentX,$CurrentY);
	$pdf->Cell($width,$height/2,$text1,"T",$ln,$align);
	$pdf->SetXY($CurrentX,$CurrentY+6);
	$pdf->Cell($width,$height/2+1,$text2,"B",$ln,$align);
	$pdf->SetXY($CurrentX+$width,$CurrentY);
}
?>	
