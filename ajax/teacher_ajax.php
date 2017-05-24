<?php
	session_start();
  include '../inc/connect.php';


  if (@$_POST['oper'] == "teacher"){
    $qry_date = @$_POST['qry_date'];

    if ($qry_date != null)
      list($p_menu, $m_menu) = explode("/", $qry_date); // ex: 2017/05
    $p_menu -= 1911;
    $a['data'] = "";
    $sql = "SELECT count(*) count
            FROM holidayform
            where substr(lpad(povdateb,7,'0'),1,3)= lpad('$p_menu', 3, '0')
            and   substr(lpad(povdateb,7,'0'),4,2)= lpad('$m_menu', 2, '0')
            and   class='1'
            and   condition in ('0','1')";
    $data = $db -> query_array($sql);
    if (count($data['COUNT']) == 0){
      echo json_encode($a);
      exit;
    }
    else {
      $sql ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE,
            h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
              h.serialno,h.CURENTSTATUS
            FROM psfempl p,holidayform h,psqcode pc
            WHERE substr(lpad(povdateb,7,'0'),1,3) = lpad('$p_menu', 3, '0')
            AND   substr(lpad(povdateb,7,'0'),4,2) = lpad('$m_menu', 2, '0')
            AND class='1'
            AND condition IN ('0','1')
            AND p.empl_no=h.pocard
            AND pc.CODE_KIND='0302'
            AND pc.CODE_FIELD=h.POVTYPE
            ORDER by h.POCARD,h.POVDATEB,h.POVHOURS";
      $row = $db -> query_array($sql);

      for($i = 0; $i < count($row['EMPL_CHN_NAME']); ++$i){
        $poname = $row['EMPL_CHN_NAME'][$i];
        $pocard = $row['POCARD'][$i];
        $povtype= $row['CODE_CHN_ITEM'][$i];
        $povdateB = $row['POVDATEB'][$i];
        $povdatee = $row['POVDATEE'][$i];
        $povhours = $row['POVHOURS'][$i];
        $povdays  = $row['POVDAYS'][$i];
        $povtimeb = $row['POVTIMEB'][$i];
        $povtimee = $row['POVTIMEE'][$i];
        $abroad   = $row['ABROAD'][$i];
        $agentno  = $row['AGENTNO'][$i];
        $serialno = $row['SERIALNO'][$i];
        $agentname = "";

        // 試著取agentname
        $sql = "SELECT EMPL_CHN_NAME FROM PSFEMPL WHERE EMPL_NO = '$agentno' ";
        $data = $db -> query_array($sql);

        if (count($data['EMPL_CHN_NAME']) > 0)
          $agentname = $data['EMPL_CHN_NAME'][0];
        if ($agentname == "")
          $agentname = "-";

        if ($abroad == '0')
          $abroad = "未出國";
        else
          $abroad = "出國";

        if (strlen($povtimeb) > 2)
          $povtimeb = substr($povtimeb, 0, 2) . ":" . substr($povtimeb, 2, 2);
        if (strlen($povtimee) > 2)
          $povtimee = substr($povtimee, 0, 2) . ":" . substr($povtimee, 2, 2);

        $a['data'][] = array(
          $poname,
          $povtype,
          $abroad,
          $povdateB,
          $povdatee,
          $povtimeb,
          $povtimee,
          $povdays . "天" . $povhours . "時",
          $agentname
        );
      }// for
      echo json_encode($a);
      exit;
    }// else
  }
?>
