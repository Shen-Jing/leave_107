<?php
  session_start();
  include '../inc/connect.php';

  // 查詢年份
  if ($_POST['oper'] == "qry_dept"){
    $data = array();

    // 查詢年份
    $sql = "SELECT dept_no, dept_full_name
          FROM stfdept
          WHERE (substr(dept_no,2,2)='00' AND substr(dept_no,1,1) between '1' AND '9')
          OR dept_no in ('720','MA0','M35')
          ORDER BY dept_no";
    $data = $db -> query_array($sql);
    echo json_encode($data);
    exit;
  }

  // 根據年份查詢差假資料
  if ($_POST['oper'] == 0){
  //  if ($_POST['dept'] == '')
      $college = '';
  //  elseif (substr($_POST['dept'], 1, 2) == '00')
  //    $college = "AND substr(class_depart = '$_POST['dept']')"

		$sql = "SELECT empl_chn_name, h.POCARD, substr(pc.CODE_CHN_ITEM, 1, 2) code_chn_item, h.POVDATEB, h.POVDATEE,
            h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
              h.serialno,h.CURENTSTATUS,h.class_depart
            FROM psfempl p,holidayform h,psqcode pc
            WHERE class='1' ".$college." AND acadm_date is null
            AND POVDATEB > '1000101'
            AND (CONDITION='1' OR (CONDITION!='1' AND CONDITION!='-1' AND CURENTSTATUS > 2))
            AND p.EMPL_NO=h.POCARD
            AND pc.CODE_KIND='0302'
            AND pc.CODE_FIELD=h.POVTYPE
            ORDER BY h.POCARD,h.POVDATEB, h.POVHOURS";
    $tmp_data = $db -> query_array($sql);
    $data['class_apply'] = $tmp_data;

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
?>
