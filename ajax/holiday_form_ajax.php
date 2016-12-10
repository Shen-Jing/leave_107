<?php
  session_start();
  include '../inc/connect.php';

  // 查詢欄位
  if ($_POST['oper'] == "qry_item") {
    $data = array();
    $empl_no = $_POST['empl_no'];
    $vocdate = $_POST['vocdate'];
    $depart = $_SESSION['depart'];

    // 單位
    $sql = "SELECT dept_no, dept_full_name
            FROM  psfcrjb, stfdept
            WHERE crjb_empl_no = '$empl_no'
            AND   crjb_quit_date IS NULL
            AND   crjb_depart = dept_no
            ORDER BY crjb_seq DESC";
    $tmp_data = $db -> query_array($sql);
    $data['qry_dept'] = $tmp_data;

    // 職稱
    $sql = "SELECT code_chn_item, code_field
						FROM  psfcrjb, psqcode
						WHERE  crjb_empl_no = '$empl_no'
						AND    crjb_quit_date IS NULL
						AND    crjb_depart = '$depart'
						AND    code_kind = '0202'
						AND    code_field = crjb_title";
    $tmp_data = $db -> query_array($sql);
    $data['qry_title'] = $tmp_data;

    // 假別
    $sql = "SELECT code_field, code_chn_item
						FROM psqcode
						WHERE code_kind = '0302'
						ORDER BY code_field";
    $tmp_data = $db -> query_array($sql);
    $data['qry_vtype'] = $tmp_data;

    // 職務代理人
    // 根據目前的empl_no找出所屬部門的所有代理人與其代理人號碼
    $sql = "SELECT  empl_no, empl_chn_name
          FROM   psfempl, psfcrjb
          WHERE empl_no = crjb_empl_no
          AND   crjb_quit_date IS NULL
          AND   crjb_depart = '$depart'
          AND   (substr(crjb_title, 1, 1) != 'B' OR crjb_title = 'B60')
          AND   substr(empl_no, 1, 1) IN ('0', '7', '5', '3', '4')
          AND   empl_no != '$empl_no'
          ORDER BY crjb_depart, crjb_title, crjb_empl_no";
          // echo $sql . '\n';
    $tmp_data = $db -> query_array($sql);
    $data['qry_agentno'] = $tmp_data;

    // 職務代理人單位
    $sql = "SELECT dept_no,dept_full_name
        FROM stfdept
        WHERE use_flag IS NULL
        AND  substr(dept_no, 1, 1) BETWEEN 'A' AND 'Z'
        ORDER BY  dept_no";
    $tmp_data_1 = $db -> query_array($sql);

    $sql = "SELECT dept_no,dept_full_name
        FROM stfdept
        WHERE use_flag IS NULL
        AND  substr(dept_no, 1, 1) BETWEEN '0' AND '9'
        ORDER BY  dept_no";
    $tmp_data_2 = $db -> query_array($sql);

    $data['qry_agent_depart'][] = $tmp_data_1;
    $data['qry_agent_depart'][] = $tmp_data_2;

    // 可補休之加班時數
    $sql = "SELECT over_date, nouse_time,
            substr(over_date,1,3) || '/' || substr(over_date,4,2) || '/' || substr(over_date,6,2) over_date2
            FROM   overtime
            WHERE  empl_no='$empl_no'
            AND    person_check='1'
            AND    nouse_time > 0
            AND    due_date >= '$vocdate'
            ORDER BY over_date";
    $tmp_data = $db -> query_array($sql);
    $data['qry_nouse'] = $tmp_data;

    // 判斷是否為寒暑假期間
    $sql = "SELECT count(*) count
						FROM    t_card_time
						WHERE   '$vocdate' BETWEEN afternoon_s AND afternoon_e";
    $tmp_data = $db -> query_array($sql);
    $data['qry_voc'] = $tmp_data;

    // 判斷是否為特殊工作人員
    $sql = "SELECT nvl(empl_party,'0') empl_party
						FROM psfempl
						WHERE empl_no='$empl_no'";
    $tmp_data = $db -> query_array($sql);
    $data['qry_party'] = $tmp_data;

    // 送出表單用的serail_no
    $sql = "SELECT max(serialno) serialno
            FROM holidayform";
    $tmp_data = $db -> query_array($sql);
    $data['qry_serial'] = $tmp_data;

    echo json_encode($data);
    exit;
  }

  if ($_POST['oper'] == "qry_apps") {
    $empl_no = $_POST['empl_no'];
    $vtype = $_POST['vtype'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    // 根據vtype（勞基法特休 / 一般教職員休假）取出今年已請特休天數
    $sql = "SELECT trunc(sum(nvl(POVDAYS,0)) + sum(nvl(POVHOURS,0))/8)   days , mod(sum(nvl(POVHOURS,0)),8) hours
           FROM holidayform
           WHERE povtype = '$vtype'
           AND substr(POVDATEB,1,3) = '$year'
           AND condition IN ('0','1')
           AND pocard IN ('$empl_no', '$empl_no')";
    $tmp_data = $db -> query_array($sql);
    // 目前已休天
    $days = $tmp_data['DAYS'][0];
    // 目前已休時
    $hours = $tmp_data['HOURS'][0];
    // 勞基法特休
    if ($empl_no == '7000082' && $vtype == '23') {
      // 年資
      $sql = "SELECT nvl(empl_ser_date_beg, '$year') empl_arrive_sch_date
							FROM psfempl
							WHERE empl_no = '$empl_no'";
      $tmp_data = $db -> query_array($sql);
      $arrive_date = $tmp_data['EMPL_ARRIVE_SCH_DATE'][0];
      $sens = $year - substr($arrive_date, 0, 3) - 1;

      // 是否滿一年
      if (strlen($month) < 2)
        $m = '0' . $month;
      if (strlen($day) < 2)
        $d = '0' . $day;
      if ( substr($arrive_date, 3, 4) == $m . $d AND $sens == 0)  // 同月同日
        $apps = 7 * (13 - substr($arrive_date, 3, 2)) / 12 ;  // 是否要加 1 ?

      /*一、一年以上三年未滿者七日。
				二、三年以上五年未滿者十日。
				三、五年以上十年未滿者十四日。
				四、十年以上者，每一年加給一日，加至三十日為止*/
			// 可休天數
      if ($sens >= 1  && $sens < 3)
        $apps = 7;
      elseif ($sens >= 3 && $sens < 5)
        $apps = 10;
      elseif ($sens >= 5 && $sens < 10)
        $apps = 14;
      elseif ($sens >= 10)
        $apps = $sens + 5; // 第10年15天，第10年16天...;

      echo "至去年年底您的在校年資 $sens 年，今年有 $apps 天特休假，目前已休 $days 天 $hours 小時。";
      exit;
    }
    // 教職員特休
    if ($empl_no == '0000676' && $vtype == '06') {
      //年資及可休天數
      $sql = "SELECT holiday_senior sen, shall_holiday shall
        		  FROM  ps_senior
        		  WHERE empl_no = '$empl_no'";
      $tmp_data = $db -> query_array($sql);
      // 年資
      $sens = $tmp_data['SEN'][0];
      $apps = $tmp_data['SHALL'][0];
      /*公務人員:
				1.	服務滿一年者，第二年起，每年應給休假七日；
				2.	服務滿三年者，第四年起，每年應給休假十四日；
				3.	滿六年者，第七年起，每年應給休假二十一日；
				4.	滿九年者，第十年起，每年應給休假二十八日；
				5.	滿十四年者，第十五年起，每年應給休假三十日*/

      echo "至去年年底您的在校年資 $sens 年，今年有 $apps 天特休假，目前已休 $days 天 $hours 小時。";
      exit;
    }
  }

  if ($_POST['oper'] == "submit"){
    $empl_no = $_SESSION['empl_no'];
    $depart = $_SESSION['depart'];
    $class_depart = $_SESSION['depart'];
    $title = $_SESSION['title_id'];
    $name = $_SESSION['empl_name'];
    $dept_name = $_SESSION['dept_name'];

    $condi = 0;  // 請假是否成功
    $flag = 0;

    // 系統日期
    $sql = "SELECT lpad(to_char(sysdate, 'yyyymmdd') - '19110000', 7, '0') ndate
            FROM dual";
    $data = $db -> query_array($sql);
    $ndate = $data['NDATE'][0];

    // 正常請假
    if($_POST['check'] == 'fe'){
      $agentno = $_POST['agentno'];
      $agentsign = 0;
      $cstatus = 0;   //假單目前狀態
    }
    else if($_POST['check'] == 'ex'){ //未兼行政老師及學術單位專案助理
      $agentno = 'none';
      $agentsign = 1;
    }

    /* 表單資料 */
    $serialno = $_POST["this_serialno"];  //961109 add
    $agent_depart = $_POST['agent_depart'];
    if ($agent_depart == '')
      $agent_depart = $depart;
    // 請假開始、結束日期 ex: 2016/12/08
    list($byear, $bmonth, $bday) = explode("/", $_POST['leave_start']);
    list($eyear, $emonth, $eday) = explode("/", $_POST['leave_end']);
    // 開始、結束時間
    $btime = $_POST['btime'];
    $etime = $_POST['etime'];
    // 起訖時間 ex: 2016/12/08 20時
    list($tyear, $tmonth, $tday) = explode("/", $_POST['bus_trip_start']);
    list($syear, $smonth, $sday) = explode("/", $_POST['bus_trip_end']);
    $tday = substr($tday, 0, 2);
    $sday = substr($sday, 0, 2);
    // 會議開始、結束時間 ex: 2016/12/08
    $mtimeb = substr(explode(" ", $_POST['bus_trip_start'])[1], 0, 2);
    $mtimee = substr(explode(" ", $_POST['bus_trip_end'])[1], 0, 2);

    // 出國出入境時間 ex: 2016/12/08
    list($oyear, $omonth, $oday) = explode("/", $_POST['depart_time']);
    list($iyear, $imonth, $iday) = explode("/", $_POST['immig_time']);
    $vtype = $_POST['vtype'];
    $eplace = $_POST['eplace'];
    $extracase = $_POST['extracase'];
    // 研發經費
    $research = '0';
    // 差假期間是否有課
    $haveclass = $_POST['haveclass'];
    // 事由或服務項目
    $mark =  $_POST["mark"];
    // 是否出國
    $abroad = $_POST["abroad"];
    // 差假合計日數是否含例假日
    $saturday = $_POST["saturday"];
    $sunday = '0';
    // $this_serialno = $_POST["this_serialno"];
    $filestatus = "";
    $notefilename  = "";
    // 經費來源
    $budget = $_POST["budget"];
    // 是否刷國民旅遊卡
    $trip = ($_POST["trip"] == '') ? 0 : $_POST["trip"];
    // 奉派文號或提簽日期或填'免'
    $permit = $_POST["permit"];
    // 出差服務單位
    $on_dept = $_POST["on_dept"];
    // 出差擔任職務
    $on_duty = $_POST["on_duty"];

    // 特殊上班人員
    $party = $_POST['party'];
    // 寒暑假
    $voc = $_POST['voc'];

    // 轉換成民國格式
    $byear -= 1911;
    $byear = (strlen($byear) < 3) ? '0' . $byear : $byear;
    $eyear -= 1911;
    $eyear = (strlen($eyear) < 3) ? '0' . $eyear : $eyear;
    $tyear -= 1911;
    $tyear = (strlen($tyear) < 3) ? '0' . $tyear : $tyear;
    $syear -= 1911;
    $syear = (strlen($syear) < 3) ? '0' . $syear : $syear;
    $oyear -= 1911;
    $oyear = (strlen($oyear) < 3) ? '0' . $oyear : $oyear;
    $iyear -= 1911;
    $iyear = (strlen($iyear) < 3) ? '0' . $iyear : $iyear;

    $bdate = $byear . $bmonth . $bday;
    $edate = $eyear . $emonth . $eday;

    $tdate = $tyear . $tmonth . $tday;
    $sdate = $syear . $smonth . $sday;

    $odate = $oyear . $omonth . $oday;
    $idate = $iyear . $imonth . $iday;

    //半小時的轉成整數
    if (substr($btime, 2, 2) == '30') {
  	  $btime_bk = $btime;
      $btime = substr($btime, 0, 2);
    }
    if (substr($etime, 2, 2) == '30') {
      $etime_bk = $etime;
      $etime = substr($etime, 0, 2);
    }

    require "../calculate_time.php"; //統計此次請假總天數，提到此判斷寒暑休

    //*************************************************************************
    //**                資料檢核
    //*************************************************************************
    $count = array();
    //---------------------------------------
    // 判斷請假者是否重複請假
    //---------------------------------------
    $sql = "SELECT COUNT(*) count FROM HOLIDAYFORM
            WHERE POCARD = '$empl_no'
            AND (
                  ((POVDATEB<= '$bdate' AND POVDATEE >= '$bdate'
            		 AND POVTIMEB <= $btime AND POVTIMEE > $btime ) OR
            		(POVDATEB<= '$edate' AND POVDATEE >= '$edate'
            		 AND POVTIMEB <= $etime AND POVTIMEE >= $etime)) OR
            	  (POVDATEB<'$bdate' AND POVDATEE >'$edate')
            	)
            AND (condition = 0 OR  condition = 1 OR condition= 2)
            AND   povtimeb != $etime
            AND   povtimee != $btime";
    $data = $db -> query_array($sql);
    $count['empl_no'] = $data['COUNT'][0];

    //---------------------------------------
    // 代理人是否請假   96.01.02 liru add
    //---------------------------------------
    $sql = "SELECT COUNT(*) count FROM HOLIDAYFORM
        	  WHERE POCARD = '$agentno'
        		AND (
        		      ((POVDATEB<= '$bdate' AND POVDATEE >= '$bdate'
        				 and POVTIMEB <= $btime AND POVTIMEE > $btime ) OR
        				(POVDATEB<= '$edate' AND POVDATEE >= '$edate'
        				 and POVTIMEB <= $etime AND POVTIMEE >= $etime)) OR
        			  (POVDATEB<'$bdate' AND POVDATEE >'$edate')
        			)
        	  AND (condition = 0 OR  condition = 1 OR condition = 2)
        	  AND   povtimeb != $etime
        	  AND   povtimee != $btime";
    $data = $db -> query_array($sql);
    $count['agentno'] = $data['COUNT'][0];

    if ($vtype == '27')
      $count['agentno'] = 0;

    //-----------------------------------
    //判斷是否為一級主管之資料   96.05.04 liru add 請假者有可能是主管姓名，但是他還是職員不是一級主管故還是用教職請假作業，
    //系統必須讓其通過，他不是真正主管.不會到一級主管請假作業
    $sql = "SELECT count(*) count
            FROM dept_boss
            WHERE boss_no = '$empl_no' AND type = '2'
            AND boss_no IN (SELECT crjb_empl_no FROM psfcrjb
            WHERE crjb_empl_no = '$empl_no'  AND
            crjb_seq > '1' AND crjb_quit_date IS NULL)";
    $data = $db -> query_array($sql);
    $count['dept_boss'] = $data['COUNT'][0];

    //-----------------------------------
    //判斷出差是否使用研發處經費，需經系辦助理註記才可請出差   97.12.12 liru add
    // $sql = "SELECT COUNT(*) count
    //         FROM TEACHER
    //         WHERE TEACHER_NO = '$empl_no'
    //         AND   BEGIN_DATE = '$bdate'
    //         AND   END_DATE   = '$edate'
    //         AND   SIGN = '1'";
    // $data = $db -> query_array($sql);
    // $count['teacher'] = $data['COUNT'][0];
    // echo $sql;

    // 請假狀況驗證
    if ($count['empl_no'] > 0) {
      echo "注意！您重複請假了！";
      $_POST['notefilename'] = "no file";
      exit;
    }
    elseif ($count['agentno'] > 0) {
      echo "注意！代理人請假中！";
      exit;
    }
    elseif ($count['dept_boss'] > 0) {
      echo "您是一級主管，系統將轉至一級主管請假作業！";
      exit;
    }
    // elseif ( ($vtype == '01' || $vtype == '02') && $research == '1' && $count['teacher'] > 0) {
    //   echo "請假未成功，請先知會系辦助理，註記您要使用研發處經費！";
    //   exit;
    // }
    elseif ( ($vtype == '06' || $vtype == '21' || $vtype == '22' || $vtype == '23') && $tot_day == 0 && $tot_hour < 4) {
      echo "寒休、暑休、休假至少要請半天！";
      exit;
    }

    // 資料齊全，可以處理
    //**********************************************************
    //**                 正常請假作業--統計天數
    //**********************************************************

    // 例假日出國(29)不檢查 -- 104/04/07 add */
    // if ($tot_day < 0 || ( ($tot_day == 0 && $tot_hour == 0) && ($vtype != '29') ) ){
    //   echo "請假天數不合理，是否忘了填含例假日！";
    // 	exit;
    // }

    //事假才累計此次請假  961123
    if ($vtype == '04'){
  		$tot_day_04 = $tot_day;   //先暫存，免被覆蓋
  		$tot_hour_04 = $tot_hour;
  		require "calculate_tot.php";  //統計之前事假總天數 961123
  		$pohdaye = $pohdaye + $tot_day_04;		//加上此次請假日數
  		$pohoure = $pohoure + $tot_hour_04;
  			//時數超過八小時轉入天數
  		$temp_h = 0;
  		if ($pohoure > 8){
  			$temp_h = $pohoure % 8;
  			$pohdaye += floor($pohoure / 8 );
  			$pohoure = $temp_h;
      }
	    if ($pohdaye > 7 || ($pohdaye == 7 and $pohoure > 0)){
        echo "加上本次請假，您的事假已請：" . $pohdaye . "日" . $pohoure . "時";
        // echo "<script> alert('".$str."');	if (confirm('您的事假已超過７天，會扣錢的喲！要取消此次假單嗎？請按「確定鍵」'))	 top.r.location.href='sign.php';</script>";
	    }
	  }//事假

    //統計會議日程天數   980630 add
    if ( ($vtype == '01' || $vtype == '02' || $vtype == '03') && $abroad == '1'){//出差、公假、出國
    	$sql = "SELECT count(*) count  FROM  ps_calendar
        			WHERE  lpad(calendar_yymm || lpad(to_char(calendar_dd), 2, '0'), 7, '0')
        			BETWEEN '$tdate' AND '$sdate' ";
      $data = $db -> query_array($sql);
      $mdays = $data['COUNT'][0];
    }
    elseif ( ($vtype == '01' || $vtype == '02' || $vtype == '03') && $abroad == '0') {//出差、公假未出國
    	$odate = '';
    	$idate = '';
    }
    elseif ( !($vtype == '01' || $vtype == '02' || $vtype == '03') && $abroad == '1'){//非出差、公假但出國
    	$tdate = '';
    	$sdate = '';
    }
    else{//非(出差、公假、出國)
    	$tdate = '';
    	$sdate = '';
    	$odate = '';
    	$idate = '';
    }

  	//,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
  	// 事假（因故無法參加學校慶典）
  	//,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
  	if ($vtype == '31'){
  		$tot_day = 0;
  		$tot_hour = 0;
    }

    //,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
  	// 可否補休之判斷
  	//,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
  	if ($vtype=='11'){ //補休
  		$flag_11 = 0;        //控制能否儲存
  		$f = 0;
      $nouse_sum = 0; //累計未使用時數

  		//抓出所有可補休之加班記錄並累計可使用總時數
  		$sql = "SELECT over_date,nouse_time
  				  FROM   overtime
  				  WHERE  empl_no = '$empl_no'
  				  AND    person_check = '1'
  				  AND    nouse_time > 0
  				  AND    due_date >= '$bdate'
  				  ORDER BY over_date";
      $data = $db -> query_array($sql);
      for ($i = 0; $i < count($data['OVER_DATE']); ++$i){
        $over[$i]  = $data['OVER_DATE'][$i]; //加班日期
        $nouse[$i] = $data['NOUSE_TIME'][$i];//加班時數
        $nouse_sum = $nouse_sum + $nouse[$i];
      }

  		if ($nouse_sum < $total_over){
  		   echo "補休時數超過可請時數，請先完成加班申請作業。";
  		   exit;
  		}
  		else {
  			$flag_11 = 1; //控制能否儲存
  			$over_date = $over[0]; //記錄至請假檔 holidyform，後來請假用到那些加班日都記錄在 overtime_use，這個其實可以不必再使用
  		}
    }//補休

    //*************************************************************************
    //**                 正常請假作業審核-開始 找代理人
    //*************************************************************************
    //961218  更新功能:出差沒有電子簽核流程，只有紙本流程
    //正常請假
    if ($_POST['check'] == 'fe'){
  	  $url = "holiday_form.php";  //liru
  	  // 職務代理人處理
  	  $sql = "SELECT email FROM psfempl WHERE empl_no = '$agentno'";
      if ($data = $db -> query_array($sql))
  		  $mail_to = $data['EMAIL'][0];    //agent mail liru
    }
    //*************************************************************************
    //**                 未兼行政之教師  /教師、學術單位專案助理沒有職務代理人
    //*************************************************************************
    else if($_POST['check'] == 'ex'){  //教師沒有職務代理人 ,監考公假不需代理人
  		$url = "exholiday_form.php";
  		//---------------------------------------------
  		//找出單位主管
  		//---------------------------------------------
  		$sql = "SELECT boss_no, type FROM dept_boss
            WHERE dept_no = '$depart'
  					AND type IN ('1', '2', '3')";
      if ($data = $db -> query_array($sql)){
        $bossno = $data['BOSS_NO'][0];   //此部門主管代號
        $type = $data['TYPE'][0];     //此部門主管級別(直屬或一級)
      }
      $cstatus=$type;  //假單目前狀態

  		//---------------------------------------------
  		//判斷主管是否請假，有代理人   96.01.05 liru add
  		//---------------------------------------------
  		$flag = 0;
  		$sql = "SELECT agentno
              FROM HOLIDAYFORM
              WHERE POCARD = '$bossno'
              AND (
              ((POVDATEB<= '$bdate' AND POVDATEE >= '$bdate'
               AND POVTIMEB <= $btime AND POVTIMEE > $btime ) OR
              (POVDATEB<= '$edate' AND POVDATEE >= '$edate'
               AND POVTIMEB <= $etime AND POVTIMEE >= $etime)) OR
              (POVDATEB<'$bdate' AND POVDATEE >'$edate')
              )
              AND (condition = 0 OR  condition = 1 OR condition = 2)
              AND   povtimeb != $etime
              AND   povtimee != $btime";
  		//---------------------------------------------
      //主管請假，沒有請假以原單位主管簽核
  		//---------------------------------------------
  		if ($db -> query_array($sql) && $ndate>= $bdate){
  			//---------------------------------------------
  			//判斷主管是否兼兩個單位以上   100.08.03 add
  			//---------------------------------------------
  			$i = 0;
  			$sql = "SELECT count(*) count
    						FROM   dept_boss
    						WHERE  boss_no = '$bossno'";
  			$data = $db -> query_array($sql);
  			$i = $data['COUNT'][0]; //主管數

  			//兼兩個單位以上
        if ($i > 1){ //選請假者單位代理人
          $sql = "SELECT  agent_no
              		FROM    chief_agent
              		WHERE   boss_no = '$bossno'
              		AND    substr(dept_no,1,2) = '" . substr($dept_no, 0, 2) . "'
              		AND     $bdate BETWEEN begin_date AND end_date";

          if ($data = $db -> query_array($sql))
            $bossno = $data['AGENT_NO'][0];
          else
            $bossno = $data['AGENTNO'][0];//之前資料未寫人  chief_agent，以後可以將此段刪除  100.08.03
        }
  		  else
  			  $bossno = $data['AGENTNO'][0];//任一個單位主管之代理人
  		  $flag = 1;
  		}//$ndate>= $bdate  主管請假

      //---------------------------------------------
      //未兼行政教師及學術單位專案助理主管
  		$sql = "SELECT email FROM psfempl WHERE empl_no = '$bossno'";
  		if ($data = $db -> query_array($sql))
  		  $mail_to = $data['EMAIL'];    //主管 mail liru
	  } //未兼行政之教師

    print_r($GLOBALS);

  }

?>
