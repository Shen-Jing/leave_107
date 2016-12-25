<?
	function datentos($datenum)
	{
		$year=substr($datenum,0,3);
		while(substr($year,0,1)=='0')
		{
			$year=substr($year,1);
		}
		if($year=="")
			$year=0;
		$month=substr($datenum,3,2);
		while(substr($month,0,1)=='0')
		{
			$month=substr($month,1);
		}
		$day=substr($datenum,5,2);
		return $year."年".$month."月".$day."日";
	}

	function timentos($timenum)
	{
		while(substr($timenum,0,1)=='0')
		{
			$timenum=substr($timenum,1);
		}

		if($year=="")
			$year=0;
		return $timenum."時";
	}
	session_start();
    include '../inc/connect.php';
    $empl_no = $_SESSION['empl_no'];

    $today = getdate();
    $year = $today["year"] - 1911;
    $month = $today["mon"];


    if ($_POST['oper']=="qry")
    {
        $data = array('year' => $year, 'month' => $month );
        echo json_encode($data);
        exit;
    }

	if($_POST["oper"] == 0 )
	{
			$data = array();
			$sql = "SELECT count(*) count
					FROM holidayform
					where substr(lpad(povdateb,7,'0'),1,3)= lpad('$_POST[year]',3,'0')
					and   substr(lpad(povdateb,7,'0'),4,2)=	lpad('$_POST[month]',2,'0')
					and CONDITION in ('0','2')
					and POCARD='$empl_no'";
			//echo $sql;
			$data_c = $db -> query_array($sql);

			if($data_c['COUNT'][0] > 0)
			{
				$SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,
							h.POVDATEB,h.POVDATEE, h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,
							h.AGENTNO, h.serialno,h.CURENTSTATUS,h.depart,h.over_date,h.povtype
						FROM psfempl p,holidayform h,psqcode pc
						where substr(lpad(povdateb,7,'0'),1,3)=	lpad('$_POST[year]',3,'0')
						and   substr(lpad(povdateb,7,'0'),4,2)= lpad('$_POST[month]',2,'0')
						and CONDITION in ('0','2')
						and POCARD='$empl_no'
						and p.empl_no=h.pocard
						and pc.CODE_KIND='0302'
						and pc.CODE_FIELD=h.POVTYPE
						order by h.POVDATEB desc ,h.POVHOURS desc";

			    $data_unsigned = $db -> query_array($SQLStr);

				for($i = 0 ; $i < count($data_unsigned["POCARD"]) ; $i++)
				{
					$agentno   = $data_unsigned['AGENTNO'][$i];;

					$SQLStr2 = "SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO='$agentno' ";
					$data_unsigned2 = $db -> query_array($SQLStr2);

					if ($data_unsigned2["EMPL_CHN_NAME"][0] == '' )
			        	$data_unsigned2["EMPL_CHN_NAME"][0] = "無";

			        $data[0][0][$i] = $data_unsigned['EMPL_CHN_NAME'][$i];
			        $data[0][1][$i] = $data_unsigned['CODE_CHN_ITEM'][$i];
			        $data[0][2][$i] = $data_unsigned['POVDATEB'][$i];
			        $data[0][3][$i] = $data_unsigned['POVDATEE'][$i];
			        $data[0][4][$i] = $data_unsigned['POVTIMEB'][$i];
			        $data[0][5][$i] = $data_unsigned['POVTIMEE'][$i];
			        $data[0][6][$i] = $data_unsigned['POVDAYS'][$i];
			        $data[0][7][$i] = $data_unsigned['POVHOURS'][$i];
			        $data[0][8][$i] = $data_unsigned2['EMPL_CHN_NAME'][0];
				}
			}
			else
				$data[0][0]='';

			//2
			$sql = "SELECT count(*) count
					FROM holidayform
					where substr(lpad(povdateb,7,'0'),1,3)=	lpad('$_POST[year]',3,'0')
					and   substr(lpad(povdateb,7,'0'),4,2)=	lpad('$_POST[month]',2,'0')
					and CONDITION='1'
					and POCARD='$empl_no'";

			$data_c2 = $db -> query_array($sql);

			if($data_c2['COUNT'][0] > 0)
			{
				$SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,
							h.POVDATEB,h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,
							h.ABROAD,h.AGENTNO,	h.serialno,h.CURENTSTATUS,h.depart,h.over_date,h.povtype
						FROM psfempl p,holidayform h,psqcode pc
						where substr(lpad(povdateb,7,'0'),1,3)=	lpad('$_POST[year]',3,'0')
						and   substr(lpad(povdateb,7,'0'),4,2)=	lpad('$_POST[month]',2,'0')
						and CONDITION='1'
						and POCARD='$empl_no'
						and p.empl_no=h.pocard
						and pc.CODE_KIND='0302'
						and pc.CODE_FIELD=h.POVTYPE
						order by h.POVDATEB desc ,h.POVHOURS desc";

				$data_signed = $db -> query_array($SQLStr);

				for($i = 0 ; $i < count($data_signed["POCARD"]) ; $i++)
				{
					$agentno2 = $data_signed['AGENTNO'][$i];


					$SQLStr2 = "SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO='$agentno2' ";
					$data_signed2 = $db -> query_array($SQLStr2);

					if ($data_signed2["EMPL_CHN_NAME"][0] == '' )
			        	$data_signed2["EMPL_CHN_NAME"][0] = "無";

			        $data[1][0][$i] = $data_signed['EMPL_CHN_NAME'][$i];
			        $data[1][1][$i] = $data_signed['CODE_CHN_ITEM'][$i];
			        $data[1][2][$i] = $data_signed['POVDATEB'][$i];
			        $data[1][3][$i] = $data_signed['POVDATEE'][$i];
			        $data[1][4][$i] = $data_signed['POVTIMEB'][$i];
			        $data[1][5][$i] = $data_signed['POVTIMEE'][$i];
			        $data[1][6][$i] = $data_signed['POVDAYS'][$i];
			        $data[1][7][$i] = $data_signed['POVHOURS'][$i];
			        $data[1][8][$i] = $data_signed2['EMPL_CHN_NAME'][0];
				}
			}
			else
				$data[1][0]='';

			echo json_encode($data);
        	exit;
	}
	else if($_POST["oper"] == 2)
	{

		$id = substr($_POST["old_id"],0,1);
		$no = substr($_POST["old_id"],1);

		if($id == 1)
		{
			$SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,
						h.POVDATEB,h.POVDATEE, h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,
						h.AGENTNO, h.serialno,h.CURENTSTATUS,h.depart,h.over_date,h.povtype
						FROM psfempl p,holidayform h,psqcode pc
						where substr(lpad(povdateb,7,'0'),1,3)=	lpad('$_POST[year]',3,'0')
						and   substr(lpad(povdateb,7,'0'),4,2)= lpad('$_POST[month]',2,'0')
						and CONDITION in ('0','2')
						and POCARD='$empl_no'
						and p.empl_no=h.pocard
						and pc.CODE_KIND='0302'
						and pc.CODE_FIELD=h.POVTYPE
						order by h.POVDATEB desc ,h.POVHOURS desc";

			$data = $db -> query_array($SQLStr);

			$serialno = $data["SERIALNO"][$no];
			$povtype  = $data["POVTYPE"][$no];
			$povday   = $data["POVDAYS"][$no];
			$povhour  = $data["POVHOURS"][$no];
			$over_date  = $data["OVER_DATE"][$no];
			$userid     = $data["POCARD"][$no];

			$sql="select lpad(to_char(sysdate,'yyyymmdd')-'19110000',7,'0') ndate
			from dual";

			$ndate = $db -> query_array($sql);
			$nd = $ndate['NDATE'][0];
			$SQLStr2 = "update holidayform set CONDITION='-1' ,THREESIGND='$nd' where serialno = $serialno ";

			if ($db -> query($SQLStr2))
			{
			        //*************
					//補休時數恢復
			        //*************
					if ($povtype =='11')
					{
						//設定使用者按"回覆"時要顯示的e-mail  Reply-To
						$mail_headers  = "From: edoc@cc.ncue.edu.tw\r\n";
						$mail_headers .= "Reply-To:lucy@cc.ncue.edu.tw\r\n";
						$mail_headers .= "X-Mailer: PHP\r\n"; // mailer
						$mail_headers .= "Return-Path: edoc@cc2.ncue.edu.tw\r\n";
						$mail_headers .= "Content-type: text/html; charset=big5\r\n";

						//抓出此取消假單補休時用到的加班日期及時數
						$sql="select *
						      from   overtime_use
						      where  serialno= $serialno";
			            //echo $sql."<br>";
						$stmt2=ociparse($conn,$sql);
						ociexecute($stmt2,OCI_DEFAULT);
						while (OCIFETCH($stmt2))
						{
							$over_date = OCIRESULT($stmt2,OVER_DATE);
						   	$use_hour = OCIRESULT($stmt2,USE_HOUR);

						   	$SQLStr2=  " update overtime o
										set    nouse_time= o.nouse_time + $use_hour
										where  empl_no= '$userid'
										and    over_date= '$over_date'";

						   	$db -> query($SQLStr2);
						   	$mail_subject =$userid."--".$serialno."--update補休時數恢復通知";
						   	$mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
			   			   	// @mail('bob@cc.ncue.edu.tw',$mail_subject, $SQLStr2, $mail_headers);
						  	//@mail($mail_to, $mail_subject, $mail_body, $mail_headers)
			            }
			            	//刪除之前補休時所使用的加班記錄
						   	$SQLStr2="delete from  overtime_use
						                   where  serialno= $serialno";
			               	//echo $SQLStr2;
						   $db -> query($SQLStr2); //liru update
						  	/* $mail_subject =$userid."--".$serialno."--delete補休時數恢復通知";
						   	$mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
			   			  	@mail('bob@cc.ncue.edu.tw',$mail_subject, $SQLStr2, $mail_headers); */
					}
					//**********************************
				    echo json_encode("本假單取消成功，假別為『補休』時，加班時數同步加回資料庫！！");
					exit;
			}
		}
		else if($id == 2)
		{
			//錯誤
			$str = "ist";
			$sqlup=  "update holidayform set CONDITION='4' where pocard = '000000' and POVDATEB = '1051212' and CONDITION = '2'";
			$ck_3 = $db -> query_trsac($sqlup);
			if(!isset($ck_3)){
				echo json_encode($ck_3);
				exit;
			}
			//$m = $db -> create_savepoint("ist");
			//oci_commit($db ->rp);

			$sqlist2=  "insert into holidayform (POCARD,POVDATEB,POVDATEE,CONDITION) values ('000000','1051212','1051212','2')";
			$ck_2 = $db -> query_trsac($sqlist2);
			if(!empty($ck_2['message'])){
				echo json_encode($ck_2['message']);
				exit;
			}
			//$db -> create_savepoint("ist2");
			//$check = $db -> end_trsac();

			$sqlist=  "insert into holidayform (POCARD,POVDATEB,POVDATEE,CONDITION) values ('111111111111111111111111111111','1051212','1051212','1')";
			//<script>alert(aaa)</script>
			// $sqlist=  "insert into holidayform (POCARD,POVDATEB,POVDATEE,CONDITION) values ('000000','1051212','1051212','1')";
			// $ck_1 = $db -> query_trsac($sqlist);

			// if(!empty($ck_1['message'])){
			// 	echo json_encode($ck_1['message']);
			// 	exit;
			// }

			
			//$check = $db -> end_trsac();
			// echo"OK";
			// exit;
			//$db -> rb_to_savepoint("ist");

			// if(!$check)
			// 	$db -> rb_to_savepoint("ist");
			// echo json_encode($ck_1);
			// exit;

			$check = $db -> end_trsac();
			if($check){
				echo json_encode("系統已自動通知人事室執行真正取消動作！！請您不必再知會人事室");
				exit;
			}


			// else{
			// 		$check = $db -> end_trsac();
			// 		echo json_encode("系統已自動通知人事室執行真正取消動作！！請您不必再知會人事室");
			// 		exit;
			// }
			exit;
			// $SQLStr2=  "update holidayform set CONDITION='1' where serialno = 109236 ";
			// $data_update = $db -> query($SQLStr2);
			// echo json_encode("value");
			// exit;
			$sql="select lpad(to_char(sysdate,'yyyymmdd')-'19110000',7,'0') ndate
			from dual";
			$ndate = $db -> query_array($sql);

			$SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,
							h.POVDATEB,h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,
							h.ABROAD,h.AGENTNO,	h.serialno,h.CURENTSTATUS,h.depart,h.over_date,h.povtype
						FROM psfempl p,holidayform h,psqcode pc
						where substr(lpad(povdateb,7,'0'),1,3)=	lpad('$_POST[year]',3,'0')
						and   substr(lpad(povdateb,7,'0'),4,2)=	lpad('$_POST[month]',2,'0')
						and CONDITION='1'
						and POCARD='$empl_no'
						and p.empl_no=h.pocard
						and pc.CODE_KIND='0302'
						and pc.CODE_FIELD=h.POVTYPE
						order by h.POVDATEB desc ,h.POVHOURS desc";
			$data = $db -> query_array($SQLStr);
			$serialno = $data["SERIALNO"][$no];
			$povtype  = $data["POVTYPE"][$no];
			$nd = $ndate['NDATE'][0];
			echo json_encode($data);
			exit;
			$SQLStr2=  "update holidayform set CONDITION='3' where serialno = $serialno ";
			$data_update = $db -> query($SQLStr2);
			if ( empty($data_update["message"]) )
			{
			        //*************
					//補休時數恢復
			        //*************
					if ($povtype =='11')
					{
						//設定使用者按"回覆"時要顯示的e-mail  Reply-To
						$mail_headers  = "From: edoc@cc.ncue.edu.tw\r\n";
						$mail_headers .= "Reply-To:lucy@cc.ncue.edu.tw\r\n";
						$mail_headers .= "X-Mailer: PHP\r\n"; // mailer
						$mail_headers .= "Return-Path: edoc@cc2.ncue.edu.tw\r\n";
						$mail_headers .= "Content-type: text/html; charset=big5\r\n";

						//抓出此取消假單補休時用到的加班日期及時數
						$sql="select *
						      from   overtime_use
						      where  serialno= $serialno";
			            //echo $sql."<br>";
						$data_temp = $db -> query_array($sql);
						// echo json_encode($data_temp);
						// exit;
						for($i = 0 ; $i < count($data_temp) ; $i++)
						{
						   $over_date = $data_temp["OVER_DATE"][$i];
						   $use_hour = $data_temp["USE_HOUR"][$i];
						   $SQLStr2=  " update overtime o
										set    nouse_time= o.nouse_time + $use_hour
										where  empl_no= '$empl_no'
										and    over_date= '$over_date'";

						   $db -> query($SQLStr2);
						   $mail_subject =$empl_no."--".$serialno."--update補休時數恢復通知";
						   $mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
			   			    //@mail('bob@cc.ncue.edu.tw',$mail_subject, $SQLStr2, $mail_headers);
			            }
			            //刪除之前補休時所使用的加班記錄

						   $SQLStr2="delete from  overtime_use
						             where  serialno= $serialno";
			               //echo $SQLStr2;
						   $db -> query($SQLStr2);
						   /*$mail_subject =$userid."--".$serialno."--delete補休時數恢復通知";
						   $mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
			   			  @mail('bob@cc.ncue.edu.tw',$mail_subject, $SQLStr2, $mail_headers);*/
					}
					//**********************************
				    echo json_encode("系統已自動通知人事室執行真正取消動作！！請您不必再知會人事室");
					exit;
			}

		}
	}
?>