<?php
    session_start();
    include '../inc/connect.php';
    $empl_no = $_SESSION['empl_no'];

    $today = getdate();
    $year = $today["year"] - 1911;
    $month = $today["mon"];


    if ($_POST['oper']=="qry_year")
    {
        $data = array('year' => $year, 'month' => $month );
        echo json_encode($data);
        exit;
    }

    else if ($_POST['oper']=="qry_dpt")
    {
      $sql2 = "select dept_no,dept_full_name
              from stfdept
              where (substr(dept_no,2,2)='00'
              and   substr(dept_no,1,1) between '1' and '9')
              OR    dept_no in ('720','MA0','M35')
              order by dept_no";

        $data = $db -> query_array($sql2);

        echo json_encode($data);
        exit;
    }
    if($_POST['oper'] == 0)
    {
      $s_dept=$_POST['c_menu'];

      if ($_POST['c_menu']=='')
	       $college='';
      else if (substr($_POST['c_menu'],1,2)=='00')
	       $college="and  substr(class_depart,1,1)= substr('$_POST[c_menu]',1,1)";
      else
	       $college="and  class_depart = '$_POST[c_menu]'";
      $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE, h.POVDAYS,h.ABROAD,h.AGENTNO,h.serialno,h.CURENTSTATUS,h.class_depart,
	              nvl(h.acadm_date,'-') acadm_date, nvl(h.acadm2_date,'-') acadm2_date, nvl(h.acadm3_date,'-')  acadm3_date
	              FROM   psfempl p,holidayform h,psqcode pc
	              where  class='1' ".$college.
	              " and substr(lpad(povdateb,7,'0'),1,3)= lpad('$_POST[p_year]',3,'0')
	              and   substr(lpad(povdateb,7,'0'),4,2)= lpad('$_POST[p_month]',2,'0')
	              and   p.empl_no=h.pocard
	              and   pc.CODE_KIND='0302'
	              and   pc.CODE_FIELD=h.POVTYPE
	              order by h.POCARD,h.POVDATEB,h.POVHOURS";
      $row = $db -> query_array($SQLStr);
      $a['data']="";
      $row_c=0;
      for($i = 0; $i < sizeof($row['EMPL_CHN_NAME']); ++$i)
      {
        $poname = $row['EMPL_CHN_NAME'][$i];
		    $pocard = $row['POCARD'][$i];
		    $povtype= $row['CODE_CHN_ITEM'][$i];
		    $povdateB = $row['POVDATEB'][$i];
		    $povdatee = $row['POVDATEE'][$i];
		    $povhours = $row['POVHOURS'][$i];
		    $povdays  = $row['POVDAYS'][$i];
		    $povtimeb = $row['POVTIMEB'][$i];  //liru add
		    $povtimee = $row['POVTIMEE'][$i];  //liru add
		    $abroad   = $row['ABROAD'][$i];
		    $agentno  = $row['AGENTNO'][$i];
		    $serialno = $row['SERIALNO'][$i];
		    $depart   = $row['CLASS_DEPART'][$i];
	      $acadm_date   =$row['ACADM_DATE'][$i];
	      $acadm2_date  =$row['ACADM2_DATE'][$i];
	      $acadm3_date  =$row['ACADM3_DATE'][$i];
        //echo $poname;
        //本次請假捕的課是教務處的?
        $sql = "SELECT * FROM haveclass
	              where  class_serialno=$serialno and substr(class_code,1,1) ='D'";

        $tmp = $db -> query_array($sql);
        if(sizeof($tmp)>0)
        {
          $row_c++;
          if ($abroad =='0')
				      $abroad='未出國';
			    else
				      $abroad='出國';
          $SQLStr2 = "SELECT substr(DEPT_SHORT_NAME,1,14) dept_short_name
			                FROM stfdept
			                where dept_no='$depart'";
          $tmp2 = $db -> query_array($SQLStr2);
          if(sizeof($tmp2)>0)
            $deptname=$tmp2['DEPT_SHORT_NAME'][0];

          if (strlen($povtimeb) > 2)
			       $povtimeb=substr($povtimeb,0,2).":".substr($povtimeb,2,2);

		      if (strlen($povtimee) > 2)
			       $povtimee=substr($povtimee,0,2).":".substr($povtimee,2,2);
          $time=$povdays."天".$povhours."時";
          $tt="待補";
          $a['data'][] = array(
            $deptname,
            $poname,
            $povtype,
            $abroad,
            $povdateB,
            $povdatee,
            $povtimeb,
            $povtimee,
            $time,
            $acadm_date,
            $acadm2_date,
            $acadm3_date,
            $tt
          );
        }
      }
      echo json_encode($a);
    }




?>
