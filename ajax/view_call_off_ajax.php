<?php
  include_once("../inc/connect.php");

  // 查詢請假雨刷卡資訊
  if ($_POST['oper'] == "qry_data"){
    $serialno = $_POST['serialno'];
    $sql = "SELECT lpad(to_char(SYSDATE,'yyyymmdd')-'19110000',7,'0') ndate
            FROM dual";
    $data = $db -> query_array($sql);
    $ndate = $data['NDATE'][0];

    // 查詢請假開始/結束的時間/時刻
    $sql = "SELECT pocard,lpad(povdateb,7,'0') povdateb,lpad(povdatee,7,'0') povdatee,
            povtimeb,povtimee,depart
            FROM holidayform
            WHERE serialno=$serialno";
    $data = $db -> query_array($sql);
    $pocard = $data['POCARD'][0];
    $povdateb = $data['POVDATEB'][0];
    $povdatee = $data['POVDATEE'][0];
    $povtimeb = $data['POVTIMEB'][0];
    $povtimee = $data['POVTIMEE'][0];
    $depart = $data['DEPART'][0];

    // 查詢職位、人名
    $sql = "SELECT  empl_chn_name,
          (SELECT code_chn_item
            FROM psqcode WHERE  code_kind='0202'
            AND  code_field=c.crjb_title) title_name
            FROM  psfempl p,psfcrjb  c
            WHERE empl_no='$pocard'
            AND   empl_no=crjb_empl_no
            AND   crjb_seq>'1'
            AND   crjb_depart='$depart'
            AND   crjb_quit_date IS NULL";
    $data = $db -> query_array($sql);
    if (count($data['EMPL_CHN_NAME']) != 0){
      $name   = $data['EMPL_CHN_NAME'][0];
      $title_name  = $data['TITLE_NAME'][0];
    }
    else {
      $sql = "SELECT empl_chn_name,
              (SELECT code_chn_item
              FROM psqcode WHERE  code_kind='0202'
              AND  code_field=c.crjb_title) title_name
              FROM  psfempl p,psfcrjb  c
              WHERE empl_no='$pocard'
              AND   empl_no=crjb_empl_no
              AND   crjb_seq='1'
              AND   crjb_depart='$depart'
              AND   crjb_quit_date IS NULL";
      $data = $db -> query_array($sql);
      $name   = $data['EMPL_CHN_NAME'][0];
      $title_name  = $data['TITLE_NAME'][0];
    }
    $data = array();
    // 請假資料
    $holli_data = array();
    $holli_data['ndate'] = $ndate;
    $holli_data['name'] = $name;
    $holli_data['title_name'] = $title_name;
    $holli_data['POCARD'] = $pocard;
    $holli_data['POVDATEB'] = $povdateb;
    $holli_data['POVDATEE'] = $povdatee;
    $holli_data['POVTIMEB'] = $povtimeb;
    $holli_data['POVTIMEE'] = $povtimee;
    $holli_data['DEPART'] = $depart;

    $data['holli_data'] = $holli_data;

    // 查詢刷卡記錄
    $sql = "SELECT do_dat,decode(do_time,'0000','未刷卡',do_time) do_time,memo
        FROM  ps_card_data
        WHERE empl_no='$pocard'
        AND lpad(do_dat,7,'0') BETWEEN '$povdateb' AND '$povdatee'
        ORDER BY do_dat,do_time";
    $card_data = $db -> query_array($sql);
    $data['card_data'] = $card_data;
    
    echo json_encode($data);
    exit;
  }
 ?>
