<?php
  session_start();
  include '../inc/connect.php';
  $empl_no = $_SESSION['empl_no'];
  $empl_name = $_SESSION['empl_name'];

  if ($_POST['oper'] == "qry_year"){
      $sql = "SELECT substr(to_char(SYSDATE, 'yyyymmdd'), 1, 4) - '1911' end_year,
      substr(to_char(SYSDATE, 'yyyymmdd'), 5, 2) end_month FROM dual";

      $data = $db -> query_array($sql);
      echo json_encode($data);
      exit;
  }
  else if ($_POST['oper'] == 0){
      $p_year = $_POST['year'];
      $p_month = $_POST['month'];

      $sql = "SELECT empl_chn_name,h.POCARD,pc.CODE_CHN_ITEM,h.POVDATEB,h.POVDATEE,
            h.POVHOURS,h.POVDAYS,h.ABROAD,h.serialno,h.CURENTSTATUS,h.poremark
            FROM psfempl p,holidayform h,psqcode pc
            WHERE substr(lpad(povdateb,7, '0'), 1, 3) = lpad('$p_year', 3, '0')
            AND   substr(lpad(povdateb,7, '0'), 4, 2) = lpad('$p_month', 2, '0')
            AND   pocard = '$empl_no'
            AND   povtype IN ('01', '02')
            AND   condition = '1'
            AND p.empl_no = h.pocard
            AND pc.CODE_KIND = '0302'
            AND pc.CODE_FIELD = h.POVTYPE
            ORDER BY h.serialno DESC";
      $data = $db -> query_array($sql);
      echo json_encode($data);
      exit;
  }


?>
