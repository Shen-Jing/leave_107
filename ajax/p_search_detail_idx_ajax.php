<?php
	session_start();
  include '../inc/connect.php';

	$today = getdate();
  $month = '';
  $day = '';

  $year  = $today["year"] - 1911;
  $month = $today["mon"];
  $day   = $today["mday"];

  if (strlen($year) < 3)
    $year = '0' . $year;

  if (strlen($month) < 2)
    $month ='0' . $month;

  if (strlen($day) < 2)
     $day = '0' . $day;


  // 查詢單位
  if ($_POST['oper'] == "qry_dept"){
    //查詢系所
    $sql = "SELECT min(dept_no) dept_no, min(dept_full_name) dept_full_name
            FROM stfdept
            WHERE use_flag IS NULL
            GROUP BY substr(dept_no, 1, 2)";
    $data = $db -> query_array ($sql);
    echo json_encode($data);
    exit;
  }

  // 根據年份、月份、人員查詢差假明細
  if ($_POST['oper'] == "detail"){
    $empl_name = @$_POST['empl_name'];
    //------------------------------------------------
    //970826 add 專案助理轉行政助理之處理
    //-----------------------------------------------
    $empl_no = "0000000";
    $userid = @$_POST['empl_no'];

    if (substr($userid, 0, 1) == '7'){
      // 以身份証查詢
      $sql = "SELECT empl_id_no
              FROM psfempl
              WHERE empl_no = '$userid'";
      $data = $db -> query_array($sql);
      if (count($data['EMPL_ID_NO']) > 0)
        $id_no = $data['EMPL_ID_NO'][0];

      // 判斷今年是否曾任專案助理，查專案助理人員代號
      $sql = "SELECT crjb_empl_no
              FROM psfcrjb
              WHERE crjb_empl_id_no = '$id_no'
              AND   crjb_seq = '1'
              AND   substr(crjb_empl_no,1, 1) = '5'
              AND   substr(crjb_quit_date, 1, 3) = lpad('$year', 3, '0')";
      $data = $db -> query_array($sql);
      if (count($data['CRJB_EMPL_NO']) > 0)
        $empl_no = $data['CRJB_EMPL_NO'][0];
    }
    //--------------------------------------

    // ex: 2017/03
    list($s_year, $s_mon) = explode("/", $_POST['start_date']);
    list($e_year, $e_mon) = explode("/", $_POST['end_date']);
    $s_year -= 1911;
    $e_year -= 1911;

    $s_date = $s_year . $s_mon;
    $e_date = $e_year . $e_mon;

    // 存明細資料，最後會傳回給datatable處理
    $a['data'] = "";
    $sql = "SELECT count(*) count
          FROM holidayform
          WHERE POCARD  IN ('$userid', '$empl_no')
          AND CONDITION<>'-1' AND condition<>'2'
          AND substr(POVDATEB, 1, 5)  BETWEEN '$s_date' AND '$e_date' ";
    $data = $db -> query_array($sql);

    if ($data['COUNT'][0] > 0){
      $sql = "SELECT substr(pc.CODE_CHN_ITEM,1,2) povtype, h.POVDATEB,h.POVDATEE,h.povtimeb,h.povtimee,nvl(h.eplace,'--') eplace,nvl(h.poremark,'--') poremark
              FROM holidayform h,psqcode pc
              WHERE h.POCARD IN ('$userid', '$empl_no')
              AND h.CONDITION<>'-1' AND condition<>'2'
              AND substr(POVDATEB,1,5)  BETWEEN '$s_date' AND '$e_date'
              AND pc.CODE_KIND = '0302'
              AND pc.CODE_FIELD = h.POVTYPE";
      $row = $db -> query_array($sql);

      for($i = 0; $i < count($row['POVTYPE']); ++$i){
        $povtype = $row['POVTYPE'][$i];
        $povdateB = $row['POVDATEB'][$i];
        $povdateE = $row['POVDATEE'][$i];
        $povtimeB = $row['POVTIMEB'][$i];
        $povtimeE = $row['POVTIMEE'][$i];
        $eplace = $row['EPLACE'][$i];
        $poremark  = $row['POREMARK'][$i];

        //...........................................................
        //10201 add
        if (strlen($povtimeB) > 2)
          $povtimeB = substr($povtimeB, 0, 2) . ":" . substr($povtimeB, 2, 2);

        if (strlen($povtimeE) > 2)
          $povtimeE = substr($povtimeE, 0, 2) . ":" . substr($povtimeE, 2, 2);
        //...........................................................

        $a['data'][] = array(
          $povtype,
        	$povdateB,
        	$povdateE,
        	$povtimeB,
        	$povtimeE,
        	$eplace,
        	$poremark
        );
      }
    }
    echo json_encode($a);
    exit;
  }

  // 根據年份、人員查詢差假統計
  if ($_POST['oper'] == "tot"){
		$empl_name = @$_POST['empl_name'];
		$p_no = @$_POST['empl_no'];

		//------------------------------------------------
		//970826 add 專案助理轉行政助理之處理
		//-----------------------------------------------
		$begin_year  = @$_POST['tot_year'];
		$begin_year -= 1911;
    $next_year = $begin_year + 1;

		if (strlen($next_year) < 3)
			$next_year = "0" . $next_year;
		if (strlen($begin_year) < 3)
			$begin_year = "0" . $begin_year;

			//--------------------------------------------
		//  身份是否為老師
		//--------------------------------------------

		if (@$_SESSION['tit2'] == '') {
			$sql = "SELECT substr(crjb_title, 1, 1) crjb_title
							FROM   psfcrjb
							WHERE  crjb_empl_no = '$p_no'
							AND    crjb_seq = '1'";
			$data = $db -> query_array($sql);
			if (count($data['CRJB_TITLE']) > 0){
				$title = $data['CRJB_TITLE'][0];
	      @$_SESSION['tit2'] = $title;
			}
	   }

		//-------------------------------------------
		//教師以學年度統計971013 add
		//-------------------------------------------
    if (@$_SESSION['tit2'] == 'B' || @$_SESSION['tit2'] == 'C'){
			$begin_date = $begin_year . '0801';
			$end_date   = $next_year . '0731';
    }//------
		else{
			$begin_date = $begin_year . '0101';
			$end_date = $begin_year . '1231';
    }

		//------------------------------------------------
		//970826 add 專案助理轉行政助理之處理
		//------------------------------------------------
		$empl_no = "0000000";
		$userid = $p_no;

		if (substr($userid, 0, 1) == '7' || substr($userid, 0, 1) == '5'){
			//以身份証查詢
			$sql = "SELECT empl_id_no
							FROM psfempl
							WHERE empl_no = '$userid'";
			$data = $db -> query_array($sql);

			if (count($data['EMPL_ID_NO']) > 0)
			  $id_no = $data['EMPL_ID_NO'][0];

		    //判斷今年是否曾任專案助理，查專案助理人員代號
	      $sql = "SELECT crjb_empl_no
	      				FROM psfcrjb
	      				WHERE crjb_empl_id_no = '$id_no'
	      				AND   crjb_seq='1'
	      				AND   substr(crjb_empl_no,1,1) IN ('3','5','7')
	      				AND   substr(crjb_quit_date,1,3) = lpad('$year',3,'0')";
				$data = $db -> query_array($sql);

				if (count($data['CRJB_EMPL_NO']) > 0)
			  	$empl_no = $data['CRJB_EMPL_NO'][0];
		}
		//--------------------------------------

		$vtype = array('01','02','03','04','05','06','07','08','23','21','22','09','11','30','32');

		/* 已簽核完成的部分 */
		// 存統計資料，最後會傳回給datatable處理
    $a['data'] = "";
		if (@$_POST['table_cat'] == "ed"){
			for($i = 0; $i < 15; $i++){
				$pohdaye = 0;
				$pohoure = 0;

				$sql = "SELECT substr(CODE_CHN_ITEM,1,6)  code_chn_item
								FROM psqcode
								WHERE code_kind = '0302'
				        AND code_field = '$vtype[$i]'";  //假別名稱
				$data = $db -> query_array($sql);
				if (count($data['CODE_CHN_ITEM']) > 0)
					$v = $data['CODE_CHN_ITEM'][0];

				//請假總天數及總時數，正常請假
				//----------------------------------------------------------------------------------
				$sql = "SELECT sum(nvl(POVDAYS,0)) POHDAYE, sum(nvl(POVHOURS,0))    POHOURE
							FROM holidayform
							WHERE povtype = '$vtype[$i]'
							AND POVDATEB >= '$begin_date'
							AND POVDATEE <= '$end_date'
							AND pocard IN ('$userid', '$empl_no')
							AND condition = '1'";
				$data = $db -> query_array($sql);
				if (count($data['POHDAYE']) > 0){
					$pohdaye = $data['POHDAYE'][0];
					$pohoure = $data['POHOURE'][0];
		    }

				//請假總天數及總時數，跨年請假--去年年底至今年年初  liru add
				//----------------------------------------------------------------------------------
				//97.01.04  POVDATEB<='$begin_date' 改為 POVDATEB<'$begin_date'
		  	$sql = "SELECT POVDATEE,POVTIMEE, CONTAINSAT, CONTAINSUN
							FROM holidayform
							WHERE povtype = '$vtype[$i]'
							AND POVDATEB < '$begin_date'
							AND POVDATEE >= '$begin_date'
							AND pocard IN ('$userid', '$empl_no')
							AND condition = '1'";
				$data = $db -> query_array($sql);
				if (count($data['POVDATEE']) > 0){
					$edate = $data['POVDATEE'][0];  //起始日期
					$etime = $data['POVTIMEE'][0];  //起始時間
					$saturday = $data['CONTAINSAT'][0];
					$sunday = $data['CONTAINSUN'][0];

				  if ($title == 'B' || $title == 'C') //教師以學年度統計971013 add
						$bdate = $begin_year . '0801';
					else
						$bdate = $begin_year . '0101'; //只算今年從1日起
		      $btime='8';
					//...........................................................
					//半小時的轉成整數  10201 add
					if (substr($etime, 2, 2) == '30')
						$etime = substr($etime, 0, 2);
					//...........................................................
		    	require "../calculate_time.php";
					$pohdaye += $tot_day;
					$pohoure += $tot_hour;
		    }

				//請假總天數及總時數，跨年請假--今年年底至明年年初  liru add
				//----------------------------------------------------------------------------------
				//97.01.04  and POVDATEE>='$end_date' 改為and POVDATEE>'$end_date'
		  	$sql = "SELECT POVDATEB,POVTIMEB,CONTAINSAT,CONTAINSUN
							FROM holidayform
							WHERE povtype='$vtype[$i]'
							AND POVDATEB<='$end_date'
							AND POVDATEE>'$end_date'
							AND pocard IN ('$userid', '$empl_no')
							AND condition='1'";
				$data = $db -> query_array($sql);
				if (count($data['POVDATEB']) > 0){
					$edate = $data['POVDATEE'][0];  //起始日期
					$etime = $data['POVTIMEB'][0];  //起始時間
					$saturday = $data['CONTAINSAT'][0];
					$sunday = $data['CONTAINSUN'][0];

					if ($title=='B' or $title=='C') //教師以學年度統計971013 add
					$edate = $next_year . '0731';
						else
					$edate = $begin_year . '1231';//只算到今年止
					$etime = '17';
					//...........................................................
					//半小時的轉成整數  10201 add
					if (substr($btime, 2, 2) == '30')
						$btime = substr($btime, 0, 2);
					//...........................................................
		     	require "../calculate_time.php";
					$pohdaye += $tot_day;
					$pohoure += $tot_hour;
		    }
		    //時數超過八小時轉入天數
		    $temp_h = 0;
		    if ($pohoure >= 8){
					$temp_h = $pohoure % 8;
		      $pohdaye += floor($pohoure / 8 );
		      $pohoure = $temp_h;
		    }
		    if ($pohdaye == '') $pohdaye = 0;
		    if ($pohoure == '') $pohoure = 0;
				if ($pohdaye != 0 || $pohoure != 0){
					$a['data'][] = array(
						$v,
						$pohdaye,
						$pohoure,
					);
				}
			}// for loop(已簽核的資料)
		}
		elseif (@$_POST['table_cat'] == "ing") {
			for($i = 0; $i < 15; $i++){
				$pohdaye=0;
				$pohoure=0;

				$sql = "SELECT substr(CODE_CHN_ITEM,1,6) code_chn_item
								FROM psqcode
								WHERE code_kind = '0302'
		    				AND code_field = '$vtype[$i]'";  //假別名稱
				$data = $db -> query_array($sql);
        if (count($data['CODE_CHN_ITEM']) > 0)
					$v = $data['CODE_CHN_ITEM'][0];

        //請假總天數及總時數，正常請假
				//----------------------------------------------------------------------------------
				$sql = "SELECT sum(nvl(POVDAYS,0)) POHDAYE,sum(nvl(POVHOURS,0)) POHOURE FROM holidayform
									WHERE povtype = '$vtype[$i]'
									AND POVDATEB >= '$begin_date'
									AND POVDATEE <= '$end_date'
									AND pocard IN ('$userid', '$empl_no')
									AND condition='0'";
				$data = $db -> query_array($sql);
        if (count($data['POHDAYE']) > 0){
					$pohdaye = $data['POHDAYE'][0];
					$pohoure = $data['POHOURE'][0];
        }

        //請假總天數及總時數，跨年請假--去年年底至今年年初  liru add
				//----------------------------------------------------------------------------------
				//97.01.04  POVDATEB<='$begin_date' 改為POVDATEB<'$begin_date'
	  		$sql = "SELECT POVDATEE,POVTIMEE ,CONTAINSAT,CONTAINSUN
						 FROM holidayform
						 WHERE povtype = '$vtype[$i]'
						 AND POVDATEB < '$begin_date'
						 AND POVDATEE >= '$begin_date'
						 AND pocard IN ('$userid', '$empl_no')
						 AND condition = '0'";
				$data = $db -> query_array($sql);
        if (count($data['POVDATEE']) > 0){
					$edate = $data['POVDATEE'][0];  //起始日期
					$etime = $data['POVTIMEE'][0];  //起始時間
					$saturday = $data['CONTAINSAT'][0];
					$sunday = $data['CONTAINSUN'][0];

			    if ($title == 'B' || $title == 'C') //教師以學年度統計971013 add
						$bdate = $begin_year . '0801';
					else
						$bdate = $begin_year . '0101';
	        $btime = '8';
					//...........................................................
					//半小時的轉成整數  10201 add
					if (substr($etime, 2, 2) == '30')
						$etime = substr($etime, 0, 2);
					//...........................................................
	     	  require "../calculate_time.php";
					$pohdaye += $tot_day;
					$pohoure += $tot_hour;
        }

        //請假總天數及總時數，跨年請假--今年年底至明年年初  liru add
				//----------------------------------------------------------------------------------
				//97.01.04  and POVDATEE>='$end_date' 改為and POVDATEE>'$end_date'
  			$sql = "SELECT POVDATEB, POVTIMEB, CONTAINSAT, CONTAINSUN
					 FROM holidayform
					 WHERE povtype = '$vtype[$i]'
					 AND POVDATEB <= '$end_date'
					 AND POVDATEE > '$end_date'
					 AND pocard IN ('$userid', '$empl_no')
					 AND condition = '0'";
				$data = $db -> query_array($sql);
        if (count($data['POVDATEB']) > 0){
					$bdate = $data['POVDATEB'][0];  //起始日期
					$btime = $data['POVTIMEB'][0];  //起始時間
					$saturday = $data['CONTAINSAT'][0];
					$sunday = $data['CONTAINSUN'][0];

			    if ($title == 'B' || $title == 'C') //教師以學年度統計971013 add
						$edate = $next_year.'0731';
			    else
						$edate = $begin_year . '1231';
		      $etime = '17';
					//...........................................................
					//半小時的轉成整數  10201 add
					if (substr($btime, 2, 2) == '30')
						$btime = substr($btime, 0, 2);
					//...........................................................
	     	  require "calculate_time.php";
					$pohdaye += $tot_day;
					$pohoure += $tot_hour;
        }
        //時數超過八小時轉入天數
        $temp_h = 0;
        if ($pohoure >= 8){
					$temp_h = $pohoure % 8;
          $pohdaye += floor($pohoure / 8 );
          $pohoure = $temp_h;
        }
        if ($pohdaye == '')
					$pohdaye = 0;
        if ($pohoure == '')
					$pohoure = 0;

				if ($pohdaye != 0 || $pohoure != 0){
					$a['data'][] = array(
						$v,
						$pohdaye,
						$pohoure,
					);
				}
			} //for (簽核中的資料)
		}
		echo json_encode($a);
    exit;
  }// oper = "tot"

	// query 人員
	// 不知為何這段放在前面會導致前面的oper取到錯誤的欄位
  if ($_POST['oper'] == 0){
    // 根據單位id查詢
    $dept_id = @$_POST['dept_id'];
    // 查詢單位人事資料
    $sql = "SELECT   empl_no, empl_chn_name, crjb_title
            FROM    psfempl, psfcrjb
            WHERE   empl_no = crjb_empl_no
            AND     substr(empl_no, 1, 1) IN ('0', '3', '5', '7')
            AND     crjb_seq = '1'
            AND     crjb_quit_date IS NULL
            AND     substr(crjb_depart, 1, 2) = substr('$dept_id', 1, 2)
            UNION
            SELECT   empl_no,empl_chn_name,crjb_title
            FROM    psfempl, psfcrjb
            WHERE   empl_no = crjb_empl_no
            AND     substr(empl_no,1,1) ='0'
            AND     crjb_seq > '1'
            AND     crjb_quit_date IS NULL
            AND     substr(crjb_depart, 1, 2) = substr('$dept_id', 1, 2)
            ORDER BY crjb_title, empl_no";
    $data = $db -> query_array ($sql);
    echo json_encode($data);
    exit;
  }
?>
