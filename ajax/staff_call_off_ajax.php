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
      $sql = "SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO = '$agentno' ";
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
?>
