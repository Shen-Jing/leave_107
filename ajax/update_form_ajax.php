<?php
  session_start();
  include '../inc/connect.php';

  // 查詢欄位
  if ($_POST['oper'] == "qry_item") {
    $data = array();
    $empl_no = $_POST['empl_no'];
    // 當天年月日，例：106/04/26
    $vocdate = $_POST['vocdate'];
    // 此為第一次qry_item，則採用登入系統時取得的單位SESSION資料
    $depart = $_POST['depart'];
    // 之後單位有變動，則有另外動態更新對應職稱的程式碼(qry_title)

    // 單位
    $sql = "SELECT dept_no, dept_full_name
            FROM  psfcrjb, stfdept
            WHERE crjb_empl_no = '$empl_no'
            AND   crjb_quit_date IS NULL
            AND   crjb_depart = dept_no
            ORDER BY crjb_seq DESC";
    $tmp_data = $db -> query_array($sql);
    $data['qry_dept'] = $tmp_data;

    // 抓職稱名稱，根據單位有不一樣的職稱 103.04.22 加上 and crjb_quit_date is null(舊記錄保留，防抓到)
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

    echo json_encode($data);
    exit;
  }

  // 若有指定職務代理人單位，叫出該單位所有職務代理人
  if ($_POST['oper'] == "qry_agent") {
    $empl_no = $_POST['empl_no'];
    $depart = $_POST['depart'];
    $sql = "SELECT  empl_no, empl_chn_name
			FROM   psfempl, psfcrjb
			WHERE empl_no = crjb_empl_no
			AND   crjb_quit_date IS NULL
			AND   crjb_depart = '$depart'
			AND   substr(empl_no, 1, 1) IN ('0', '7', '5', '3', '4')
			AND   empl_no != '$empl_no'
			ORDER BY crjb_depart, crjb_title, crjb_empl_no";
    $data = $db -> query_array($sql);
    echo json_encode($data);
    exit;
  }
  
    // 若改變所屬單位選擇，則查詢該請假者在該單位下的職稱
  if ($_POST['oper'] == "qry_title") {
    $empl_no = $_POST['empl_no'];
    $depart = $_POST['depart'];
    // 抓職稱名稱，根據單位有不一樣的職稱 103.04.22 加上 and crjb_quit_date is null(舊記錄保留，防抓到)
    $sql = "SELECT code_chn_item, code_field
            FROM  psfcrjb, psqcode
            WHERE  crjb_empl_no = '$empl_no'
            AND    crjb_quit_date IS NULL
            AND    crjb_depart = '$depart'
            AND    code_kind = '0202'
            AND    code_field = crjb_title";
    $data = $db -> query_array($sql);
    echo json_encode($data);
    exit;
  }
  
  // 勞基特休 / 教職休假
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
           AND substr(POVDATEB, 1, 3) = '$year'
           AND condition IN ('0', '1')
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

  if ($_POST['oper'] == "refresh_form"){
    $empl_no = $_POST['empl_no'];
    $vocdate = $_POST['vocdate'];

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

    echo json_encode($data);
    exit;
  }
  
  if ($_POST['oper'] == "qry_oldform"){
	$serialno = $_POST['sn'];

  //是否來自查詢及修改同仁假單
  //$sqlstr_all = !isset($_POST['fra']) ? " AND	h.pocard=$empl_no" : "";

	$sql="SELECT depart,agentno,agent_depart,povdateb,povdatee,povtimeb,povtimee,meetdateb,meetdatee,meettimeb,meettimee,
				       exit_date,back_date,povtype,eplace,extracase,class,poremark,abroad,containsat,budget,trip,permit_commt,on_dept,on_duty
		    FROM  HOLIDAYFORM H 
		    WHERE SERIALNO=$serialno"
		    ;
	$data = $db -> fetch_row_assoc($sql);

	
	$form = array();
	$form["depart"]       = $data["DEPART"];
	$form["agentno"]      = $data["AGENTNO"];
	$form["agent_depart"] = $data["AGENT_DEPART"];
	$form["btime"]        = $data["POVTIMEB"];
	$form["etime"]        = $data["POVTIMEE"];
	$form["vtype"]        = $data["POVTYPE"];
	$form["eplace"]       = $data["EPLACE"];
	$form["extracase"]    = $data["EXTRACASE"];
	$form["haveclass"]    = $data["CLASS"];
	$form["mark"]         = $data["POREMARK"];
	$form["abroad"]       = $data["ABROAD"];
	$form["saturday"]     = $data["CONTAINSAT"];
	$form["budget"]       = $data["BUDGET"];
	$form["trip"]         = ($data["TRIP"] == 0) ? '' : $data["TRIP"];
	$form["permit"]       = $data["PERMIT_COMMT"];
	$form["on_dept"]      = $data["ON_DEPT"];
	$form["on_duty"]      = $data["ON_DUTY"];
	
	$form["leave_start"]    = (int)substr($data["POVDATEB"], 0, 3) + 1911 . substr($data["POVDATEB"], 3);
	$form["leave_end"]      = (int)substr($data["POVDATEE"], 0, 3) + 1911 . substr($data["POVDATEE"], 3);
	
	$form["bus_trip_start"] = (!$data["MEETDATEB"]) ? '' : (int)substr($data["MEETDATEB"], 0, 3) + 1911 . substr($data["MEETDATEB"], 3) . " " . $data["MEETTIMEB"] . "時";
	$form["bus_trip_end"]   = (!$data["MEETDATEE"]) ? '' : (int)substr($data["MEETDATEE"], 0, 3) + 1911 . substr($data["MEETDATEE"], 3) . " " . $data["MEETTIMEE"] . "時";
	
	$form["depart_time"]    = (!$data["EXIT_DATE"]) ? '' : (int)substr($data["EXIT_DATE"], 0, 3) + 1911 . substr($data["EXIT_DATE"], 3);
	$form["immig_time"]     = (!$data["BACK_DATE"]) ? '' : (int)substr($data["BACK_DATE"], 0, 3) + 1911 . substr($data["BACK_DATE"], 3);

	echo json_encode($form);
	exit;
  }
  
  
  if ($_POST['oper'] == "submit"){
	//驗證該serialno的假單申請人
	if(!isset($_POST['sn'])){
	  exit;
	}
	if(preg_match("/^\d+$/", $_POST['sn']) !== 1){
	  exit;
	}
	// 修改假單的假單序列號
	$serialno = $_POST['sn'];
	
	$empl_no = $_POST['empl_no'];
	
	$sql = "SELECT count(*) count
			    FROM holidayform
			    WHERE POCARD='$empl_no'
			    AND serialno='$serialno'
			    AND condition in ('0','2')";
	$count = $db -> fetch_cell($sql);
	if($count != 1){
	  exit;
	}
	
	
	//----------------------------------
	//  今日日期 -- 作為修改日期
	//----------------------------------
	$today = getdate();
	$month='';
	$day='';

	$year  = $today["year"] - 1911;
	$month = $today["mon"];
	$day   = $today["mday"];

	if (strlen($year)<3)
	  $year ='0'.$year;

	if (strlen($month)<2)
	  $month ='0'.$month;

	if (strlen($day)<2)
	   $day = '0'.$day;

	$thisday=$year.$month.$day;

	//-----------------------------------------
	//查詢假單舊有資料
	//------------------------------------------
	$sql="SELECT POVTYPE ,POVDATEB,POVDATEE,POVTIMEB,POVTIMEE,POVTIMEB,POVTIMEE,CURENTSTATUS
				 AGENTNO ,MEETDATEB,MEETDATEE,EXIT_DATE,BACK_DATE,POVHOURS,POVDAYS,CONDITION
		  FROM  PSFEMPL P,HOLIDAYFORM H 
		  WHERE SERIALNO=$serialno 
		  AND   p.empl_no=h.pocard";
	$old_form = $db -> fetch_row_assoc($sql);
	  
	
    // 送出假單時所選取的單位
    $depart = $_POST['depart'];
    // 登入系統時的單位，舊系統命名為class_depart
    $class_depart = $_SESSION['depart'];
    // 送出假單的單位下該請假者的對應職稱id
    $title_id = $_POST['title_id'];
    $name = $_SESSION['empl_name'];
    // 照理說應該是送出假單的單位中文名，可是舊頁面註解掉了，目前找不到有修改到dept_name的SESSION的code
    // 所以先維持原樣（取登入系統時的單位中文）
    $dept_name = $_SESSION['dept_name'];
    // 有別於submit_result的表單結果，remind是較為不重要的提醒（如請事假提醒惠幾天、會扣錢）
    $submit_remind = "";
    $condi = 0;  // 請假是否成功
    $flag = 0;

    // 系統日期
    $sql = "SELECT lpad(to_char(sysdate, 'yyyymmdd') - '19110000', 7, '0') ndate
            FROM dual";
    $data = $db -> query_array($sql);
    $ndate = $data['NDATE'][0];

    // 正常請假
    if ($_POST['check'] == 'fe'){
      $agentno = @$_POST['agentno'];
      $agentsign = 0;
      $cstatus = 0;   //假單目前狀態
    }
    else if ($_POST['check'] == 'ex'){ //未兼行政老師及學術單位專案助理
      $agentno = 'none';
      $agentsign = 1;
    }

    /* 表單資料 */
    $agent_depart = @$_POST['agent_depart'];
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
    // 若沒有傳送自行輸入差假地點之值
    if (@$_POST['eplace_text'] === null)
      $eplace = @$_POST['eplace'];
    else
      $eplace = @$_POST['eplace_text'];
    $extracase = @$_POST['extracase'];
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

    // 半小時的轉成整數
    if (substr($btime, 2, 2) == '30') {
  	  $btime_bk = $btime;
      $btime = substr($btime, 0, 2);
    }
    if (substr($etime, 2, 2) == '30') {
      $etime_bk = $etime;
      $etime = substr($etime, 0, 2);
    }
    require "../calculate_time.php"; // 統計此次請假總天數，提到此判斷寒暑休

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
            AND  serialno <> '$serialno'";
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
        	  AND (condition = 0 OR  condition = 1 OR condition = 2)";
    $data = $db -> query_array($sql);
    $count['agentno'] = $data['COUNT'][0];

    //if ($vtype == '27') //監考等不參考代理人
      $count['agentno'] = 0;

    //-----------------------------------
    //判斷出差是否使用研發處經費，需經系辦助理註記才可請出差   97.12.12 liru add
    $sql = "SELECT COUNT(*) count
            FROM TEACHER
            WHERE TEACHER_NO = '$empl_no'
            AND   BEGIN_DATE = '$bdate'
            AND   END_DATE   = '$edate'
            AND   SIGN = '1'";
    $data = $db -> query_array($sql);
    $count['teacher'] = $data['COUNT'][0];

    // 請假狀況驗證
    if ($count['empl_no'] > 0) {
      $_POST['notefilename'] = "no file";
      $message = array("error_code" => $count['empl_no'],
      "error_message" => "注意！您重複請假了！");
      echo json_encode($message);
      exit;
    }
    elseif ($count['agentno'] > 0) {
      $message = array("error_code" => $count['agentno'],
      "error_message" => "注意！代理人請假中！");
      echo json_encode($message);
      exit;
    }
    elseif ( ($vtype == '01' || $vtype == '02') && $research == '1' && $count['teacher'] == 0) {
      $message = array("error_code" => $count['teacher'],
      "error_message" => "請假未成功，請先知會系辦助理，註記您要使用研發處經費！");
      echo json_encode($message);
      exit;
    }
    elseif ( ($vtype == '06' || $vtype == '21' || $vtype == '22' || $vtype == '23') && $tot_day == 0 && $tot_hour < 4) {
      $message = array("error_code" => 1,
      "error_message" => "寒休、暑休、休假至少要請半天！");
      echo json_encode($message);
      exit;
    }

    // 資料齊全，可以處理
    //**********************************************************
    //**                 正常請假作業--統計天數
    //**********************************************************

    if ($tot_day < 0 || ($tot_day == 0 && $tot_hour == 0) ){
      $message = array("error_code" => 1,
      "error_message" => "請假天數不合理，是否忘了填含例假日！");
      echo json_encode($message);
    	exit;
    }

    // 統計會議日程天數   980630 add
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
		$old_form["EXIT_DATE"] = '';
		$old_form["BACK_DATE"] = '';
    }
    elseif ( !($vtype == '01' || $vtype == '02' || $vtype == '03') && $abroad == '1'){//非出差、公假但出國
    	$tdate = '';
    	$sdate = '';
		$old_form["MEETDATEB"] = '';
		$old_form["MEETDATEE"] = '';
    }
    else{//非(出差、公假、出國)
    	$tdate = '';
    	$sdate = '';
    	$odate = '';
    	$idate = '';
		$old_form["EXIT_DATE"] = '';
		$old_form["BACK_DATE"] = '';
		$old_form["MEETDATEB"] = '';
		$old_form["MEETDATEE"] = '';
    }

    //,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
  	// 可否補休之判斷
  	//,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
	/*
  	if ($vtype == '11'){ //補休
  		$flag_11 = 0;        //控制能否儲存
  		$cnt_nouse = 0; // 有多少個加班日期
      $nouse_sum = 0; //累計未使用時數

  		//抓出所有可補休之加班記錄並累計可使用總時數
  		$sql = "SELECT over_date, nouse_time
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
        $nouse_sum += $nouse[$i];
      }
      $cnt_nouse = $i;

  		if ($nouse_sum < $total_over){
        $message = array("error_code" => 1,
        "error_message" => "補休時數超過可請時數，請先完成加班申請作業。");
        echo json_encode($message);
        exit;
  		}
  		else {
  			$flag_11 = 1; //控制能否儲存
  			$over_date = $over[0]; //記錄至請假檔 holidyform，後來請假用到那些加班日都記錄在 overtime_use，這個其實可以不必再使用
  		}
    }//補休
	*/
	

	//   假單被退回
	//---------------------------------------
 	$sign_status='';
	if ($old_form['CONDITION']=='2'){
		switch($old_form['CURENTSTATUS'])
		{
		case '1':
			$sign_status=" ,bossone='0',  onesignd=null,condition='0'";//二級主管
			break;
		case '2':
			$sign_status=" ,bosstwo='0',  twosignd=null,condition='0'";//一級主管
			break;
		case '3':
			$sign_status=" ,bossthree='0',threesignd=null,condition='0'";//院長
			break;
		case '4':
			$sign_status=" ,perone=null , perone_signd=null,condition='0'";//人事承辦員
			break;
		case '5':
			$sign_status=" ,pertwo=null , pertwo_signd=null,condition='0'";//人事主任
			break;
		case '6':
			$sign_status=" ,secone=null , secone_signd=null,condition='0'";//秘書室承辦員
			break;
		case '0':
			$sign_status=" ,condition='0'";//代理人
			break;
		default:
			break;
		}
	}
/*
    //*************************************************************************
    //**                 正常請假作業審核-開始 找代理人
    //*************************************************************************
    //961218  更新功能:出差沒有電子簽核流程，只有紙本流程
    //正常請假
    if ($_POST['check'] == 'fe'){
  	  $url = "update_form.php";  //liru
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
      $cstatus = $type;  //假單目前狀態

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
  		  $mail_to = $data['EMAIL'][0];    //主管 mail liru
	  } //未兼行政之教師

    //*************************************************************************
    //**                抓請假者email
    //*************************************************************************
  	$sql = "SELECT email FROM psfempl
            WHERE empl_no='$empl_no'";
    if ($data = $db -> query_array($sql))
      $mail_from = $data['EMAIL'][0];

    // 設定使用者按"回覆"時要顯示的e-mail  Reply-To
  	// $mail_headers = "From: $mail_from\r\nReply-To:lucy@cc.ncue.edu.tw\r\n";
    $mail_headers  = "From: edoc@cc2.ncue.edu.tw\r\n";
    $mail_headers .= "Reply-To:lucy@cc.ncue.edu.tw\r\n";
    $mail_headers .= "X-Mailer: PHP\r\n"; // mailer
    //設定有錯誤時自動回覆的e-mail  Return-Path :liru
    $mail_headers .= "Return-Path: edoc@cc2.ncue.edu.tw\r\n";
    $mail_headers .= "Content-type: text/html; charset=big5\r\n";
  	// $mail_headers="From: $mail_from";
    // $ip = ($_SERVER[HTTP_X_FORWARDED_FOR] ? $_SERVER[HTTP_X_FORWARDED_FOR] : $_SERVER["REMOTE_ADDR"]);
    // if ($ip != "120.107.178.158"){
    // 	if ($_POST["check"] == "fe"){   //正常請假   ****
    // 		$mail_subject = "職務代理人簽核通知";
    //         $mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
    // 		$mail_body = "您好，$name 欲於民國 $byear 年 $bmonth 月 $bday 日 至 $eyear 年 $emonth 月 $eday 日 請假<br>				 請您於請假期間代理其職務<br> 請至 人事請假系統 作簽核<br>				 網址 :<a href='https://apss.ncue.edu.tw/leave/nocdclogin.php' target='_blank'> https://apss.ncue.edu.tw/leave/nocdclogin.php </a>";
    //     }
    //     else {  //教師請
    // 		$mail_subject = "人事線上差假系統請假--主管簽核通知";
    //         $mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
    // 		$mail_body =	"您好，$name 於民國 $byear 年 $bmonth 月 $bday 日 至 $eyear 年 $emonth 月 $eday 日 請假<br> 請至 人事請假系統 作簽核<br> 網址 :
    // 		<a href='https://apss.ncue.edu.tw/leave/nocdclogin.php' target='_blank'> https://apss.ncue.edu.tw/leave/nocdclogin.php </a>";
    //     }
    // }
*/
	
	//--------------------------------------------------------
	//   修改過的假單儲存
	//--------------------------------------------------------
    //半小時的轉成整數 資料還原 10201 add
    if (@substr($btime_bk, 2, 2) == '30')
    	$btime = $btime_bk;

    if (@substr($etime_bk, 2, 2) == '30')
    	$etime = $etime_bk;
	
	// mdays是若需統計會議日程天數才會賦值
    if (!isset($mdays))
		$mdays = "";
    // over_date只有在補休才會賦值
    if (!isset($over_date))
		$over_date = "";
	
	$mark      = str_replace("'", "", $mark);
    $permit    = str_replace("'", "", $permit);
    $extracase = str_replace("'", "", $extracase);
    $eplace    = str_replace("'", "", $eplace);
    //........................................................


      //if ($vtype != '11' || ($vtype == '11' && $flag_11 == '1')) //有夠用的加班時數才能請補休
        $sql = "update holidayform
				  set povtype='$vtype',
					   depart ='$depart',
					   agentno='$agentno',
					   povdateb='$bdate',
					   povdatee='$edate',
					   povtimeb='$btime',
					   povtimee='$etime',
					   containsat='$saturday',
					   containsun='$sunday',
					   class ='$haveclass',
					   abroad='$abroad',
					   eplace='$eplace',
					   research='$research',
					   extracase='$extracase',
					   on_dept='$on_dept',
					   on_duty='$on_duty',
					   povdays ='$tot_day',
					   povhours='$tot_hour',
					   note    ='$notefilename',
					   poremark='$mark',
					   update_date='$thisday',
					   budget='$budget',
					   trip  =$trip,
					   agent_depart='$agent_depart',
					   permit_commt='$permit',
					   meetdateb='$tdate',
					   meetdatee='$sdate',
					   meetdays ='$mdays',
					   exit_date='$odate',
					   back_date='$idate',
					   meettimeb='$mtimeb',
					   meettimee='$mtimee'".
					  $sign_status." where serialno='$serialno'";


    $data = $db -> query($sql);
    // 請假成功後，補休才能扣除
    // 若沒錯誤
    if (empty($data['message'])){
      // 系統自動由最前面的日期扣除相等於補休之時數
			//---------------------------------------------------------------
			if ($vtype == '11'){ //補休
			   $nouse_sum = 0;       //累計到目前的時數
			   for ($i = 0; $i < $cnt_nouse; $i++) {// 尋找日期那一天就夠用
            $nouse_sum = $nouse_sum + $nouse[$i]; // 由第一筆開始尋找，該天夠用就扣除補休時數
            if ($nouse_sum >= $total_over){ //A
              $nouse_sum = $nouse_sum - $total_over; // 剩餘時數 = 累計時數 - 補休時數，歸入第 i 日期

              //更新資料庫，第 i 日之前，全部扣光
              //-----------------------------------------------
              $sql = "UPDATE overtime
              		  SET    nouse_time = 0
              		  WHERE  empl_no = '$empl_no'
              		  AND    over_date < '" . $over[$i] . "'
              		  AND    over_date >= '" . $over[0] . "'"; //--10207加此，
              	  //over_time table有些記錄其due_date與請假起始日期比已過時了，這些記錄不能當次使用
              	  //但這些當次不能使用的記錄其 due_date 卻 > 系統日期，表示可以供其它的加班補休使用
              	  //這些記錄 nouse_time 不能歸零
              $data = $db -> query($sql);

              $liru_subject = $empl_no . "--" . $serialno . "--process.php 異動到的 overtime 記錄";
              $liru_subject = "=?big5?B?" . base64_encode($liru_subject) . "?=";
              //@mail('liru@cc.ncue.edu.tw',$liru_subject, $sql, $mail_headers);

              //更新資料庫，第 i 日，將剩餘時數存回資料庫
              //-----------------------------------------------------------
              $sql = "UPDATE overtime
                      SET    nouse_time = $nouse_sum
                      WHERE  empl_no = '$empl_no'
                      AND    over_date = '" . $over[$i] . "'";
              $data = $db -> query($sql);

              //存入資料庫overtime_use，第 i 日之前，補休時用掉那些加班日及時數
              //------------------------------------------------------------------------------------------
              $i_use = 0;
              if ($i > 0){
              	for ($p = 0; $p < $i; $p++){
              		$i_use = $i_use + $nouse[$p];//第 i日之前用掉多少
              		$sql = "INSERT INTO overtime_use(EMPL_NO, OVER_DATE, SERIALNO, USE_HOUR)
              			      VALUES('$empl_no', '" . $over[$p] . "', $serialno, " . $nouse[$p] . ")";
                  $data = $db -> query($sql);
              	}
              	$i_use = $total_over - $i_use;
              	$sql = "INSERT INTO overtime_use(EMPL_NO, OVER_DATE, SERIALNO, USE_HOUR)
                        VALUES('$empl_no', '" . $over[$i] . "', $serialno, $i_use)";
                $data = $db -> query($sql);
              }
              else {
              	$sql = "INSERT INTO overtime_use(EMPL_NO,OVER_DATE,SERIALNO,USE_HOUR)
              		  VALUES('$empl_no','".$over[$i]."',$serialno,".$total_over.")";
                $data = $db -> query($sql);
              }
              break;
    				}	//if A
			   }//for
			}//補休
      //--------------------------------------------------------------------
  	  // if(@mail($mail_to, $mail_subject, $mail_body, $mail_headers)){
  		// 	if ($agentsign == 0)
  		// 		$str = "請假成功，" . $filestatus . "並已寄發email通知職務代理人";
  		// 	if ($agentsign == 1)
  		// 		$str = "請假成功，" . $filestatus . "並已寄發email通知直屬主管";
  	  // }
  	  // else
  		$submit_result = "修改成功！" . $filestatus;//."，但寄發email失敗";
      $message = array("error_code" => $data['code'],
      "error_message" => $data['message'], "submit_result" => $submit_result,
      "submit_remind" => $submit_remind);
      echo json_encode($message);
      exit;
    }
    else{
      // @mail('bob@cc.ncue.edu.tw', '請假者資料無法儲存', $SQLStr, $mail_headers);
  	  if ($data['code'] == 1) //ORA-00001 unique...
  		  $submit_result = "相同假單已存在，請勿重複送出！";
  	  else {
  		  $submit_result = "資料儲存有問題，請洽管理者！";
  		  // mail('bob@cc.ncue.edu.tw', '請假資料寫入失敗!(/leave/process.php)', $sql . $data['message'], $mail_headers);
  	  }
      $message = array("error_code" => $data['code'],
      "error_message" => $data['message'], "submit_result" => $submit_result,
      "submit_remind" => $submit_remind);
      echo json_encode($message);
      exit;
    }
    // print_r($GLOBALS);

  }


?>
