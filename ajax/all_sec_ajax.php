<?php
  session_start();
  include '../inc/connect.php';

  // 查詢年份與系所
  if ($_POST['oper'] == "qry_ye_mon_dept"){
    $data = array();

    // 查詢年份
    $sql = "SELECT substr(to_char(SYSDATE, 'yyyymmdd'), 1, 4) - '1911' end_year,
    substr(to_char(SYSDATE, 'yyyymmdd'), 5, 2) end_month FROM dual";
    $tmp_data = $db -> query_array($sql);
    $data['qry_year'] = $tmp_data;

    // 查詢系所
    $sql = "SELECT min(dept_no) dept_no, min(dept_full_name) dept_full_name
  					FROM stfdept
  					WHERE use_flag IS NULL
  					GROUP BY substr(dept_no, 1, 2)";
    $tmp_data = $db -> query_array($sql);
    $data['qry_dept'] = $tmp_data;

    // 直接取出所有部門縮寫的轉換資料
    $sql = "SELECT dept_no, substr(DEPT_SHORT_NAME, 1, 14) dept_short_name
                FROM stfdept";
    $tmp_data = $db -> query_array($sql);
    // 並轉換成key, value的對應形式
    $dept_to_short = array();
    for ($i = 0; $i < count($tmp_data['DEPT_NO']); ++$i){
      $key = $tmp_data['DEPT_NO'][$i];
      $value = $tmp_data['DEPT_SHORT_NAME'][$i];

      $dept_to_short[$key] = $value;
    }
    $data['qry_short_dept'] = $dept_to_short;

    echo json_encode($data);
    exit;
  }

	// 根據年份、月份、系所查詢差假狀況
  if ($_POST['oper'] == 0){
		$year = $_POST['year'];
    $month = $_POST['month'];
		$dept = $_POST['dept'];
    // 若是「請選擇」、什麼都沒選 => 將所有列出
		if ($dept == ""){
			$sql = "SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2) code_chn_item,h.POVDATEB, h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD, h.AGENTNO,h.serialno,h.CURENTSTATUS,h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd
              FROM psfempl p,holidayform h,psqcode pc
              WHERE substr(lpad(povdateb, 7, '0'), 1, 3) = lpad('$year', 3, '0')
              AND   substr(lpad(povdateb, 7, '0'), 4, 2) = lpad('$month', 2 ,'0')
              AND condition IN ('0','1')
              AND curentstatus IN ('5','6')
              AND p.empl_no=h.pocard
              AND pc.CODE_KIND='0302'
              AND pc.CODE_FIELD=h.POVTYPE
              ORDER BY h.POVDATEB DESC, h.POVHOURS DESC";
		}
		else {
			$sql = "SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2) code_chn_item,h.POVDATEB, h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD, h.AGENTNO,h.serialno,h.CURENTSTATUS,h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd
              FROM psfempl p,holidayform h,psqcode pc
              WHERE substr(lpad(povdateb, 7, '0'), 1, 3) = lpad('$year', 3, '0')
              AND   substr(lpad(povdateb, 7, '0'), 4, 2) = lpad('$month', 2, '0')
              AND   substr(depart, 1, 2) = substr('$dept', 1, 2)
              AND condition IN ('0', '1')
              AND curentstatus IN ('5', '6')
              AND p.empl_no=h.pocard
              AND pc.CODE_KIND='0302'
              AND pc.CODE_FIELD=h.POVTYPE
              ORDER BY h.POVDATEB DESC, h.POVHOURS DESC";
		}
    $data = $db -> query_array($sql);

    echo json_encode($data);
    exit;
  }

?>
