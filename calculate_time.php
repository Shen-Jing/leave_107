<?php
	//--------------------961211 add  判斷是否為特殊工作人員
	if ($party == '1'){  //特殊上班人員
    $bt = 13;
    $et = 22;
  }
	else {
    $bt = 8;
    $et = 17;
  }
  //---------------------------------------------
	//判斷是否為寒暑假期間
	if ($voc == '1'){
    $bt = 8;
    $et = 16;
  }

  //---------------------------------------------
  // 計算全部區間天數
  $ln_day1 = 0;
  $tot_day = 0;
	$sql = "SELECT count(*) count FROM ps_calendar
    			WHERE  lpad(calendar_yymm || lpad(to_char(calendar_dd), 2, '0'), 7, '0')
    			BETWEEN '$bdate' AND '$edate' ";
  $data = $db -> query_array($sql);
	$ln_day1 = $data['COUNT'][0];

	// 計算請假區間之六、日天數(正常不含星期六、日)
	$ln_day2 = 0;
	$sql = "SELECT count(*) count  FROM  ps_calendar
          WHERE  lpad(calendar_yymm || lpad(to_char(calendar_dd), 2, '0'), 7, '0')
          BETWEEN '$bdate' AND '$edate'
          AND   calendar_status = '*'";
  $data = $db -> query_array($sql);
	$ln_day2 = $data['COUNT'][0];
	// print_r($GLOBALS);


  if ( $btime == $bt && $etime == $et){ // 請一整天
    $tot_hour = 0;
  	$tot_day = $ln_day1 - $ln_day2;
  }
  else{ // 1
	  if ($bdate == $edate) { //請不到一天
	  	$tot_day = 0;

      if ($etime == "1630")
        $etime = "17"; //104/08/04 add! 邱金治103/07/31~103/08/01(1630)休假,造成錯誤!!
      $tot_hour = $etime - $btime;

      if ($tot_hour < 0)
        $tot_hour = 0;  //不合理強制歸零，怕使用者亂輸

  		//----------------------------------------------
      // 960804 add  請假那天是否為星期六或星期日(社區諮商正常上下班)
  		// 請半天會變成1天4小時，但請全天或跨天不會有問題 980406 remove
  	  $ln_day3 = 0;
  		$sql = "SELECT count(*) count  FROM  ps_calendar
              WHERE  lpad(calendar_yymm || lpad(to_char(calendar_dd), 2, '0'), 7, '0')
              BETWEEN '$bdate' AND '$edate'
              AND  calendar_status = '*'";
      $data = $db -> query_array($sql);
    	$ln_day3 = $data['COUNT'][0];
  		if ($ln_day3 > 0)
  		   $tot_day = -1; //先多扣一天，後面會加回來*/
  		//----------------------------------------------
	  }
    else{ //2
      //--有一天不滿8小時
      $ln_day1 = $ln_day1 - 1;
  		if ($etime - $btime > 0)
  			$tot_hour = $etime - $btime ;
  		else {
  			$ln_day1 = $ln_day1 - 1;  //再有一天不滿8小時
  			$tot_hour = ($et - $btime) + ($etime - $bt);
      }
  		$tot_day = $ln_day1 - $ln_day2;
    } //2
  } //1

  //時數超過八小時轉入天數
  if ($voc == '1')
    $cmh = 8;
  else
    $cmh = 9;
  if ($tot_hour >= $cmh){
	  $tot_hour = $tot_hour - $cmh;
    $tot_day += 1;
  }

  //---------------------------------------------------------------------

  //若有含星期六、日再加回來===>971009改為例假日
  if ($saturday == '1') {
    $sql = "SELECT count(*) count FROM  ps_calendar
            WHERE  lpad(calendar_yymm || lpad(to_char(calendar_dd), 2, '0'), 7, '0')
            BETWEEN '$bdate' AND '$edate'
            AND    calendar_status='*'";
    $data = $db -> query_array($sql);
    $day = $data['COUNT'][0];
    $tot_day += $day;
  }
  $total = $tot_day . "日" . $tot_hour . "時";

  //補休時數  1000526 add
  if ($voc == '1')
  	$total_over = $tot_day * 8 + $tot_hour; //寒暑假期間
  else
  	$total_over = $tot_day * 9 + $tot_hour;

?>
