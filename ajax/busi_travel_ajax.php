<?php
   session_start();
   include '../inc/connect.php';
   $empl_no = $_SESSION['empl_no'];

   $today = getdate();
   $year = $today["year"] - 1911;
   $month = $today["mon"];

   if ($_POST['oper']=="qry_first"){
      $data = array();
      $data['year'] = $year;
      $data = array('year' => $year, 'month' => $month );

      $sql2 = "select min(dept_no) dept_no,min(dept_full_name) dept_full_name
          from stfdept
          where use_flag is null
          group by substr(dept_no,1,2)";

      $data_dpt = $db -> query_array($sql2);
      for($i = 0 ; $i < count($data_dpt["DEPT_NO"]) ; $i++)
      {
         $depart = $data_dpt["DEPT_NO"][$i];
         $dept_name = $data_dpt["DEPT_FULL_NAME"][$i];
         $data["dpt"]["DEPT_NO"][$i] = $depart;
         $data["dpt"]["DEPT_FULL_NAME"][$i] = $dept_name;
      }

      echo json_encode($data);
      exit;
   }

   else if($_POST['oper']==0)
   {
      $p_year = $_POST['year'];
      $p_month = $_POST['month'];
      $p_dpt = $_POST['department'];
      if ( $_POST['department'] == '' || $_POST['department'] == "請選擇單位" )
      {
         $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE, h.POVDAYS,
                                      h.serialno ,h.depart,h.eplace,h.poremark,substr(DEPT_SHORT_NAME,1,14) dept_short_name
                     FROM psfempl p,holidayform h,psqcode pc,stfdept s
                     where substr(lpad(povdateb,7,'0'),1,3)   =     lpad('$_POST[year]',3,'0')
                        and   substr(lpad(povdateb,7,'0'),4,2) =   lpad('$_POST[month]',2,'0')
                     and povtype in ('01','02')
                     and travel is null
                     and condition=1
                     and p.empl_no=h.pocard
                     and pc.CODE_KIND='0302'
                     and pc.CODE_FIELD=h.POVTYPE
                     and s.dept_no=h.depart
                     order by h.depart,h.POVDATEB desc ,h.POVHOURS desc";
      }

      else
      {
         $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE, h.POVDAYS,
                                   h.serialno ,h.depart,h.eplace,h.poremark,substr(DEPT_SHORT_NAME,1,14) dept_short_name
                   FROM psfempl p,holidayform h,psqcode pc,stfdept s
                   where substr(lpad(povdateb,7,'0'),1,3)    =  lpad('$_POST[year]',3,'0')
                      and   substr(lpad(povdateb,7,'0'),4,2) =   lpad('$_POST[month]',2,'0')
                      and   substr(depart,1,2)=substr('$_POST[department]',1,2)
                   and povtype in ('01','02')
                   and condition=1
                   and travel is null
                   and p.empl_no=h.pocard
                   and pc.CODE_KIND='0302'
                   and pc.CODE_FIELD=h.POVTYPE
                   and s.dept_no=h.depart
                   order by h.depart,h.POVDATEB desc ,h.POVHOURS desc";
      }

      $a['data']="";
      $row = $db -> query_array($SQLStr);

      for($i = 0 ; $i < count($row['EMPL_CHN_NAME']) ; $i++)
      {
         $poname = $row['EMPL_CHN_NAME'][$i];
         $pocard = $row['POCARD'][$i];
         $povtype= $row['CODE_CHN_ITEM'][$i];
         $povdateB = $row['POVDATEB'][$i];
         $povdatee = $row['POVDATEE'][$i];
         $povdays  = $row['POVDAYS'][$i];
         $eplace  = $row['EPLACE'][$i];
         $poremark  = $row['POREMARK'][$i];
         $serialno = $row['SERIALNO'][$i];
         $depart  = $row['DEPART'][$i];
         $deptname = $row['DEPT_SHORT_NAME'][$i];

         $a['data'][] = array(
            $row['DEPT_SHORT_NAME'][$i],
            $row['EMPL_CHN_NAME'][$i],
            $row['POVDATEB'][$i],
            $row['POVDATEE'][$i],
            $row['POVDAYS'][$i]."天",
            $row['EPLACE'][$i],
            $row['POREMARK'][$i],
            "<button type='button' class='btn-danger' name='delete' id='delete' onclick='CRUD(2,$serialno)' title='銷核'>銷核</button>"
         );

      }
      echo json_encode($a);
      exit;
   }
   else if($_POST['oper'] == '2')
   {
      $userid = $_SESSION["empl_no"];

      $sql = "select lpad(to_char(sysdate,'yyyymmdd')-'19110000',7,'0')|| to_char(sysdate,'hhmi') ndate from dual";
      $data = $db -> query_array($sql);
      $ndate = $data["NDATE"][0];

      $SQLStr2=  "update holidayform set travel='1' ,travel_date = $ndate where serialno = '$_POST[SERIALNO]' ";
      $update_data = $db -> query($SQLStr2);

      if(!empty($update_data["message"]))
         $result = 0;
      else
         $result = 1;

      echo json_encode($result);
      exit;
  }


?>
