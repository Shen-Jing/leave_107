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
    }else if($_POST['oper'] == "view")
    {
      $sn=$_POST['serialno'];

      $data = array();
      if ($sn !=''){ //來自 class_apply.php
        $sql="select empl_chn_name,poremark,
              substr(CODE_CHN_ITEM,1,2)  code_chn_item
		          from holidayform,psfempl,psqcode
		          where  empl_no=pocard
		          and  serialno='$sn'
		          and CODE_KIND='0302'
		          and CODE_FIELD=POVTYPE";
		            //echo $sql;
        $row = $db -> query_array($sql);
        $name=$row["EMPL_CHN_NAME"][0];
        $povtype=$row["CODE_CHN_ITEM"][0];
        $poremark=$row["POREMARK"][0];

      $data = array('NAME'=> $name, 'POVTYPE' => $povtype, 'POREMARK' => $poremark );
      }

      //echo $sn;
      if (substr($level,0,1)=='n')
		    $SQLStr ="select * from haveclass
					       where class_serialno='$sn'
					       and   substr(class_code,1,1) ='N' ";
      else
		    $SQLStr ="select * from haveclass
					       where class_serialno='$sn'
					       and   substr(class_code,1,1) !='N' ";
      $row = $db -> query_array($SQLStr);
      $class_memo='-';
		  $class_name    = '-';
		  $class_subject = '-';
		  $class_date    = '-';
		  $class_date2   = '-';
		  $class_room    = '-';
		  $class_section = '-';
		  $class_code    = '-';
		  $class_week    = '-';
		  $class_week2   = '-';
		  $class_memo    = '-';
		  $class_selcode = '-';
	    $sign_name    ='-';
	    $acadm_date   ='-';
	    $acadm2_date  ='-';
	    $acadm3_date  ='-';
	    $acadm_reason ='-';
	    $acadm2_reason='-';
	    $acadm3_reason='-';
      for($i = 0 ; $i < count($row['CLASS_NAME']) ; $i++)
      {
        $class_memo='';
		    $data["dtl"]['CLASS_NAME'][$i]    = $row['CLASS_NAME'][$i];
		    $data["dtl"]['CLASS_SUBJECT'][$i] = $row['CLASS_SUBJECT'][$i];
		    $data["dtl"]['CLASS_DATE'][$i]   = $row['CLASS_DATE'][$i]."(".$row['CLASS_WEEK'][$i].")";
		    $data["dtl"]['CLASS_DATE2'][$i]   = $row['CLASS_DATE2'][$i]."(".$row['CLASS_WEEK2'][$i].")";
		    $data["dtl"]['CLASS_ROOM'][$i]    = $row['CLASS_ROOM'][$i];
		    $data["dtl"]['CLASS_SECTION2'][$i] = $row['CLASS_SECTION2'][$i];
		    $data["dtl"]['CLASS_SELCODE'][$i] = $row['CLASS_SELCODE'][$i];
        if($row['CLASS_MEMO'][$i]!="")
        {
          $data["dtl"]['CLASS_MEMO'][$i] = $row['CLASS_MEMO'][$i];
        }else {
          $data["dtl"]['CLASS_MEMO'][$i]=$class_memo;
        }

      }

      echo json_encode($data);
      exit;
    }else if($_POST['oper']=="sign")
    {
      $sn=$_POST['serialno'];
      $level=$_PSOT['level'];
      if (substr($level,0,1)=='n')
			$SQLStr ="select  nvl(empl_chn_name,'-') empl_chn_name,
							  nvl(night_date,'-') acadm_date,
							  nvl(night2_date,'-')	acadm2_date,
							  nvl(night3_date,'-')  acadm3_date,
							  nvl(night_reason,'-') acadm_reason,
							  nvl(night2_reason,'-') acadm2_reason,
							  nvl(night3_rreason,'-') acadm3_reason
					      from   holidayform,psfempl
					      where  night_sign = empl_no
					      and    serialno='$sn'";
      else
			$SQLStr ="select  nvl(empl_chn_name,'-') empl_chn_name,
							 nvl(acadm_date,'-')    acadm_date,
							 nvl(acadm2_date,'-')   acadm2_date,
							 nvl(acadm3_date,'-')   acadm3_date,
							 nvl(acadm_reason,'-')  acadm_reason,
							 nvl(acadm2_reason,'-') acadm2_reason,
							 nvl(acadm3_rreason,'-') acadm3_reason
					     from   holidayform,psfempl
					     where  acadm_sign = empl_no
					     and    serialno='$sn'";
      $row = $db -> query_array($SQLStr);
      echo json_encode($row);
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
            "<button type='button' class='btn-primary' name='view' id='view' onclick='View($serialno,$s_dept)' title='查看內容'>查看內容</button>"
          );
        }
      }
      echo json_encode($a);
      exit;
    }






?>
