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
    $empl_no = $_POST['empl_no'];
    $depart = $_SESSION["depart"];
    // $class_depart = $_SESSION["class_depart"];
    // $vocdate = $_POST['vocdate'];
    $title = $_SESSION['title_id'];
    $name = $_POST['empl_name'];    // 95/06/20 liru
    $vtype = $_SESSION["vtype"];  //come from hliday_form.php  liru
    $dept_name = $_POST['dept_name'];
    $serialno = $_POST["this_serialno"];  //961109 add
    $condi = 0;  // 請假是否成功
    $flag = 0;

    $agent_depart = $_POST['agent_depart'];
    if ($agent_depart == '')
      $agent_depart = $depart;
    // 系統日期
    $sql = "SELECT lpad(to_char(sysdate, 'yyyymmdd') - '19110000', 7, '0') ndate
            FROM dual";
    $data = $db -> query_array($sql);
    $ndate = $data['NDATE'][0];

    $eplace = $_POST['eplace'];
    $extracase = $_POST['extracase'];
    // 研發經費
    $research = '0';
    $haveclass = $_SESSION["haveclass"];
    $mark =  $_POST["mark"];
    $abroad = $_POST["abroad"];
    $saturday = $_POST["saturday"];
    $sunday = '0';//$_SESSION["sunday"];
    $this_serialno = $_POST["this_serialno"];
    $filestatus = $_POST["filestatus"];
    $notefilename  = $_POST["notefilename"];
    $budget = $_POST["budget"];
    $trip = $_POST["trip"];
    $permit = $_POST["permit"];
    $on_dept = $_POST["on_dept"];
    $on_duty = $_POST["on_duty"];








  }

?>
