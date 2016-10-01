<?
session_start();
include "connect.php";
$sql="select * from SCHOOL";
$stmt=oci_parse($conn,$sql);
oci_execute($stmt,OCI_DEFAULT);
$sch_rows = OCIFetchStatement($stmt,$results);


$sql_dep="select * from department";
$stmt_dep=oci_parse($conn,$sql_dep);
oci_execute($stmt_dep,OCI_DEFAULT);
$dep_rows = OCIFetchStatement($stmt_dep,$results_dep);
?>
<?php
//============================================================+
// File name   : example_004.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 004 for TCPDF class
//               Cell stretching
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Cell stretching
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 004');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 004', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(false);//Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN)
$pdf->setFooterFont(false);//Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA)

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 11);
// add a page
$pdf->AddPage();

//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

// test Cell stretching
$pdf->SetFont('msungstdlight', 'B', 25);
$pdf->Write(0, '學號班別及系所維護', '', 0, 'C', true, 1, false, false, 0);

$pdf->Cell(20, 0,' ', 0, 0, 'C',0, '', 0);
$pdf->Cell(20, 0,' ', 0, 1, 'C',0, '', 0);

$pdf->SetFont('msungstdlight', '', 15);
$pdf->SetFillColor(200, 255, 127);
$pdf->Cell(100, 0,'學生身分', 1, 0, 'C',1, '', 0);
$pdf->Cell(30, 0,'ID', 1, 1, 'C',1, '', 0);
for($i=0;$i<$sch_rows;$i++)
{
  /////////////////////////////////////////set value
  $school_id = $results['ID'][$i];
  $school_name = $results['NAME'][$i];
  $pause=iconv("BIG5","UTF-8", $school_name);
  //echo iconv("BIG5","UTF-8", $school_name);
  
  /////////////////////////////////////////print!!!
  
  $pdf->SetFillColor(255, 255, 127);
  $pdf->Cell(100, 0,$pause, 1, 0, 'C', 1, '', 0);
  $pdf->Cell(30, 0,$school_id, 1, 1, 'C', 0, '', 0);
}

$pdf->Cell(20, 0,' ', 0, 0, 'C',0, '', 0);
$pdf->Cell(20, 0,' ', 0, 1, 'C',0, '', 0);


////////////我是分隔線//////////


$pdf->SetFont('msungstdlight', '', 15);
$pdf->SetFillColor(200, 255, 127);
$pdf->Cell(100, 0,'系所名稱', 1, 0, 'C',1, '', 0);
$pdf->Cell(30, 0,'ID', 1, 1, 'C',1, '', 0);
for($j=0;$j<$dep_rows;$j++)
{
  /////////////////////////////////////////set value
  $dep_id = $results_dep['ID'][$j];
  $dep_name = $results_dep['NAME'][$j];
  $pause_dep=iconv("BIG5","UTF-8", $dep_name);
  //echo iconv("BIG5","UTF-8", $school_name);
  
  /////////////////////////////////////////print!!!
  
  $pdf->SetFillColor(255, 255, 127);
  $pdf->Cell(100, 0,$pause_dep, 1, 0, 'C', 1, '', 0);
  $pdf->Cell(30, 0,$dep_id, 1, 1, 'C', 0, '', 0);
}

//$pdf->Image("images/image_demo.jpg",'','','','','','','');//圖片(報告)


$pdf->Cell(20, 0,' ', 0, 0, 'C',0, '', 0);//空白隔行
$pdf->Cell(20, 0,' ', 0, 1, 'C',0, '', 0);


$pdf->Cell(80, 20,' 姓名: ', 1, 0, 'L',0, '', 0);
$pdf->Cell(80, 20,' 學號: ', 1, 1, 'L',0, '', 0);
$pdf->Cell(80, 15,' 性別: ', 1, 0, 'L',0, '', 0);
$pdf->Cell(80, 15,' 出生日期: ', 1, 1, 'L',0, '', 0);


$pdf->AddPage();

// example using general stretching and spacing


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_004.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+