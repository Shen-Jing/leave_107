<?php
  session_start();
  include '../inc/connect.php';

  // 查詢年份
  if ($_POST['oper'] == "qry_year"){
    $data = array();

    // 查詢年份
    $sql = "SELECT substr(to_char(sysdate, 'yyyymmdd'), 1, 4) - '1911' end_year,
          substr(to_char(sysdate, 'yyyymmdd'), 5, 2) end_month
          FROM dual";
    $data = $db -> query_array($sql);
    echo json_encode($data);
    exit;
  }

  // 根據年份查詢差假資料
  if ($_POST['oper'] == 0){
		$year = $_POST['year'];
    // 用來取得單位縮寫
    $depart = $_SESSION['depart'];

		$sql = "SELECT empl_chn_name, h.POCARD, substr(pc.CODE_CHN_ITEM, 1, 2) code_chn_item, h.POVDATEB, h.POVDATEE,
            h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
              h.serialno,h.CURENTSTATUS,h.depart
            FROM psfempl p,holidayform h,psqcode pc
            WHERE substr(lpad(povdateb, 7, '0'), 1, 3) = lpad('$year', 3, '0')
            AND CONDITION = '3'
            AND p.empl_no = h.pocard
            AND pc.CODE_KIND = '0302'
            AND pc.CODE_FIELD = h.POVTYPE
            ORDER BY h.POVDATEB, h.POVHOURS";
    $tmp_data = $db -> query_array($sql);
    $data['call_off'] = $tmp_data;

    $sql = "SELECT substr(DEPT_SHORT_NAME,1,14) dept_short_name
            FROM stfdept
            WHERE dept_no = '$depart'";
    $tmp_data = $db -> query_array($sql);
    $data['short_dept'] = $tmp_data['DEPT_SHORT_NAME'][0];

    // 直接取出所有職務代理編號 -> 職務代理人的轉換資料

    // 並轉換成key, value的對應形式
    $arr_agent = array();
    for ($i = 0; $i < count($data['call_off']['AGENTNO']); ++$i){
      $agentname = "";
      $agentno = $data['call_off']['AGENTNO'][$i];
      $sql = "SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO = '$agentno'";
      $tmp_data = $db -> query_array($sql);
      $agentname = $tmp_data['EMPL_CHN_NAME'][0];
      if ($agentname == "")
			  $agentname = '無';
      $arr_agent[] = $agentname;
    }
    $data['agent'] = $arr_agent;

    echo json_encode($data);
    exit;
  }

  // 取消假單
  if ($_POST['oper'] == 4){
    $empl_no = $_SESSION['empl_no']; // 950620 liru change 登錄此系統者
    $serialno = $_POST['serialno'];
    // 系統日期
    $sql = "SELECT lpad(to_char(SYSDATE,'yyyymmdd')-'19110000',7,'0') ndate
            FROM dual";
    $data = $db -> query_array($sql);
    $ndate = $data['NDATE'][0];

    $ndate2 = $empl_no . $ndate;

    // $mail_from = "cdc@mail.ncue.edu.tw";
    $mail_from = "S0354037@mail.ncue.edu.tw";
    //設定使用者按"回覆"時要顯示的e-mail  Reply-To
    $mail_headers = "From: $mail_from\nReply-To:lucy@cc.ncue.edu.tw";
    $mail_headers .= "X-Mailer: PHP\n"; // mailer
    //設定有錯誤時自動回覆的e-mail  Return-Path :liru
    $mail_headers .= "Return-Path: edoc@cc2.ncue.edu.tw\r\n";
    $mail_subject = "人事差假管理系統 - 取消假單申請結果通知";

    $sql = "SELECT empl_chn_name, email, povtype, over_date, pocard, povhours, povdays
            FROM psfempl, holidayform
            WHERE pocard = empl_no
            AND   serialno = $serialno";
    $data = $db -> query_array($sql);

    $app_name  = $data['EMPL_CHN_NAME'][0];
    $mail_to   = $data['EMAIL'][0];
    $povtype   = $data['POVTYPE'][0];
    $over_dte  = $data['OVER_DATE'][0];
    $userid    = $data['POCARD'][0];
    $povday    = $data['POVDAYS'][0];
    $povhour   = $data['POVHOURS'][0];

    $sql = "UPDATE holidayform
            SET condition = '-1', threesignd = '$ndate', manager_date = '$ndate2'
            WHERE serialno = $serialno";
    $mail_body = "$app_name 您好! \r\n 您的假單人事已取消 \r\n";
    if( mail($mail_to, $mail_subject, $mail_body, $mail_headers) )
		  $submit_result = "已寄出email通知請假者\n";
    else
      $submit_result = "email寄出失敗\n";
    $data = $db -> query($sql);

    //*************
    //補休時數恢復
    if ($povtype =='11'){
    	//抓出此取消假單補休時用到的加班日期及時數
    	$sql = "SELECT *
              FROM   overtime_use
              WHERE  serialno=$serialno";
      $data = $db -> query_array($sql);
      for ($i = 0; $i < count($data['OVER_DATE']); ++$i){
          $over_date = $data['OVER_DATE'][0];
          $use_hour = $data['USE_HOUR'][0];

          $sql = "UPDATE overtime o
                SET    nouse_time = o.nouse_time + $use_hour
                WHERE  empl_no = '$userid'
                AND    over_date = '$over_date'";
          $data = $db -> query($sql);
      }
      //刪除之前補休時所使用的加班記錄
      $sql = "DELETE FROM  overtime_use
              WHERE serialno = $serialno";
      $data = $db -> query($sql);
    }
    //**********************************
    $message = array("error_code" => $data['code'],
      "error_message" => $data['message'],
      "sql" => $sql, "submit_result" => $submit_result);
    echo json_encode($message);
    exit;
  }
?>
