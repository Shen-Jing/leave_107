<?php
    session_start();
    include '../inc/connect.php';
    $empl_no = $_SESSION['empl_no'];

    $today = getdate();
    $year = $today["year"] - 1911;
    $month = $today["mon"];
    $day   = $today["mday"];
    if (strlen($month)<2)
	 	$month ='0'.$month;
	if (strlen($day)<2)
	  	$day = '0'.$day;
	$sysdate=$year.$month.$day;

    if ($_POST['oper'] == "qry_dpt")
    {
    	$sql2 = "SELECT min(dept_no) dept_no,min(dept_full_name) dept_full_name
    			FROM stfdept
    			WHERE use_flag is null
    			GROUP BY substr(dept_no,1,2)";
        $data = $db -> query_array($sql2);
        echo json_encode($data);
        exit;
    }

    else if($_POST['oper'] == "edit")
    {
    	$edit_empl_no = $_POST['EMPL_NO'];
    	$str_sec = explode(" ",$edit_empl_no);
    	$sql ="UPDATE psfempl SET  empl_ser_date_beg = '$_POST[SENIOR]',retire_month = '$sysdate',empl_id_error ='1' WHERE  empl_no ='$str_sec[0]' ";
		$data = $db -> query($sql);
		$message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);

		echo json_encode($message);
		exit;
    }

    else if ($_POST['oper'] == 0)
    {
      	if ($_POST['dpt'] == 'null' || $_POST['dpt'] == "請選擇單位" )
    		$sql ="SELECT empl_no,empl_chn_name,empl_arrive_sch_date, crjb_assi_date,crjb_depart,dept_full_name,
    				empl_ser_date_beg, retire_month , psfpart_remark
    				FROM    psfempl e,psfcrjb c, stfdept
    				WHERE  e.empl_no=c.crjb_empl_no
    				AND      substr(empl_no,1,1) IN ('3','5','7')
    				AND      empl_id_error  is null
    				AND      (lpad(empl_arrive_sch_date,7,'0')  <> nvl(empl_ser_date_beg,'0000000')  OR empl_arrive_sch_date is null)
    				AND      crjb_seq='1'
    				AND      crjb_quit_date is null
    				AND      crjb_depart=dept_no
    				ORDER BY  crjb_depart";
     	else
    		$sql ="SELECT empl_no,empl_chn_name,empl_arrive_sch_date, crjb_assi_date,crjb_depart,dept_full_name,
    				empl_ser_date_beg, retire_month , psfpart_remark
    				FROM    psfempl e,psfcrjb c, stfdept
    				WHERE  e.empl_no=c.crjb_empl_no
    				AND      substr(empl_no,1,1) IN ('3','5','7')
    				AND      empl_id_error  is null
                    AND      (lpad(empl_arrive_sch_date,7,'0')  <> nvl(empl_ser_date_beg,'0000000')  OR empl_arrive_sch_date is null)
    				AND      crjb_seq='1'
    				AND      crjb_quit_date is null
    				AND      substr(crjb_depart,1,2)=substr('$_POST[dpt]',1,2)
    				AND      crjb_depart=dept_no
    				ORDER BY  crjb_depart";

    	$row = $db -> query_array($sql);

    	$a['rows']="";

    	for($i = 0; $i < count($row['CRJB_DEPART']) ; $i++)
    	{
    		$depart = $row['CRJB_DEPART'][$i];
			$dept_name = $row['DEPT_FULL_NAME'][$i];
			$empl_no = $row['EMPL_NO'][$i];
			$poname = $row['EMPL_CHN_NAME'][$i];
			$sch_date = $row['EMPL_ARRIVE_SCH_DATE'][$i];
			$assi_date = $row['CRJB_ASSI_DATE'][$i];
			$senior = $row['EMPL_SER_DATE_BEG'][$i];
			$up_date = $row['RETIRE_MONTH'][$i];
			$remark = $row['PSFPART_REMARK'][$i];

			$yy = $year- substr($sch_date,0,3)-1;   //計算至去年年底
         	if ($yy < 0)
         		$yy = 0;

        	if ($sch_date == '')
        		$sch_date = '-';
        	if ($assi_date == '')
        		$assi_date = '-';
        	if ($up_date == '')
        		$up_date = '-';
        	if ($remark == '')
        		$remark = '-';

        	$a['rows'][] = array(
                $depart.$dept_name,
                $empl_no." ".$poname ,
                $assi_date,
                $yy,
                $up_date,
                $remark,
                $sch_date,
                $senior
            );
    	}

    	echo json_encode($a);
    	exit;
    }


?>