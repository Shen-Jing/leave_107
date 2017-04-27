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
    $year ='0'.$year;

  if (strlen($month) < 2)
    $month ='0'.$month;

  if (strlen($day) < 2)
     $day = '0'.$day;

	// 根據年份、月份、系所查詢差假狀況
  if ($_POST['oper'] == 0){
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

    // ex: 2017/03/29
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

?>
