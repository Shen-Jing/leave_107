<?php		//事假總天數及總時數，正常請假   961123 add
	$year = $_POST['year'];
	if (strlen($year) < 3)
		$year = "0" . $year;
	$begin_date = $year . '0101';
	$end_date = $year . '1231';
	$pohdaye = 0;
  $pohoure = 0;

	$sql = "SELECT sum(nvl(POVDAYS,0)) POHDAYE,sum(nvl(POVHOURS,0))    POHOURE FROM holidayform
            WHERE povtype = '04'
            AND POVDATEB >= '$begin_date'
            AND POVDATEE <= '$end_date'
            AND pocard = '$empl_no'
            AND condition = '1'";
  $data = $db -> query_array($sql);
  $pohdaye = $data['POHDAYE'][0];
  $pohoure = $data['POHOURE'][0];

  // 請假總天數及總時數，跨年請假--去年年底至今年年初  liru add
	$sql = "SELECT POVDATEE, POVTIMEE, CONTAINSAT, CONTAINSUN
				 FROM holidayform
				 WHERE povtype = '04'
				 AND POVDATEB <= '$begin_date'
				 AND POVDATEE >= '$begin_date'
				 AND pocard = '$empl_no'
				 AND condition = '1'";
  $data = $db -> query_array($sql);
  $edate = @$data['POVDATEE'][0]; //起始日期
  $etime = @$data['POVTIMEE'][0]; //起始時間
  $saturday = @$data['CONTAINSAT'][0];
  $sunday = @$data['CONTAINSUN'][0];
  $bdate = $year . "0101";
	if ($party == '1')  //特殊上班人員
    $btime = '13';
  else
    $btime = '8';
  require "calculate_time.php";
	$pohdaye += $tot_day;
	$pohoure += $tot_hour;

  //事假總天數及總時數，跨年請假--今年年底至明年年初  liru add
  $sql = "SELECT POVDATEB, POVTIMEB, CONTAINSAT, CONTAINSUN
      	 FROM holidayform
      	 WHERE povtype = '04'
      	 AND POVDATEB <= '$end_date'
      	 AND POVDATEE >= '$end_date'
      	 AND pocard = '$empl_no'
      	 AND condition = '1'";
  $data = $db -> query_array($sql);
	$bdate = $data['POVDATEB'];  //起始日期
	$btime = $data['POVTIMEB'];  //起始時間
	$saturday = $data['CONTAINSAT'];
	$sunday = $data['CONTAINSUN'];
	$edate = $year . '1231';
	if ($party == '1')  //特殊上班人員
    $etime = '22';
  else
    $etime = '17';
	// echo $edate;
  require "calculate_time.php";
	$pohdaye += $tot_day;
	$pohoure += $tot_hour;
  //時數超過八小時轉入天數
	if ($_SESSION["vocation"]=='1')
		$wd = 8 ;//寒暑假期間
	else
		$wd = 9 ;

  $temp_h = 0;
  if ($pohoure > $wd){
    $temp_h= $pohoure % $wd;
    $pohdaye += floor($pohoure / $wd );
    $pohoure = $temp_h;
  }

?>
