<?php
include '../inc/check.php';

if(!isset($_POST["oper"]))
	exit;

$userid = $_SESSION['empl_no'];


// 查詢未完成假單
if( $_POST["oper"] == "qry" )
{
	$sql = "SELECT  empl_chn_name, substr(pc.CODE_CHN_ITEM,1,2) code_chn_item,
					h.POVDATEB, h.POVDATEE, h.POVTIMEB, h.POVTIMEE,
					h.POVDAYS || '天' || h.POVHOURS || '時' AS AGGRETIME,
					NVL( (SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO=h.AGENTNO), '無' ) AGENTNAME,
					h.serialno
			FROM  psfempl p,holidayform h,psqcode pc
			WHERE h.POCARD='$userid' 
			and   condition in ('0','2')
			and   p.empl_no=h.pocard
			and   pc.CODE_KIND='0302'
			and   pc.CODE_FIELD=h.POVTYPE";

	$data['data'] = $db -> query_array ($sql, true);

	echo json_encode($data);
	exit;
}
// 查詢已完成假單
if( $_POST["oper"] == "qry2" )
{
	$sql = "SELECT  empl_chn_name, substr(pc.CODE_CHN_ITEM,1,2) code_chn_item,
					h.POVDATEB, h.POVDATEE, h.POVTIMEB, h.POVTIMEE,
					h.POVDAYS || '天' || h.POVHOURS || '時' AS AGGRETIME,
					NVL( (SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO=h.AGENTNO), '無' ) AGENTNAME,
					h.serialno
			FROM  psfempl p,holidayform h,psqcode pc
			WHERE h.POCARD='$userid' 
			and   condition='1'
			and   p.empl_no=h.pocard
			and   pc.CODE_KIND='0302'
			and   pc.CODE_FIELD=h.POVTYPE";

	$data['data'] = $db -> query_array ($sql, true);

	echo json_encode($data);
	exit;
}

// 修改/取消
if(!isset($_POST["sn"])) exit;

$proc_serialno = $_POST["sn"];
$sql = "SELECT count(*) count
        FROM holidayform 
        WHERE POCARD='$userid'
        AND serialno='$proc_serialno'
        AND condition in ('0','2')";
$count = $db -> fetch_cell($sql);
if($count != 1){
	echo "假單序列號有誤！！";
	exit;
}

// 取得資料庫系統時間
$sql = "select lpad(to_char(sysdate,'yyyymmdd')-'19110000',7,'0') ndate from dual";
$sys_date = $db -> fetch_cell($sql);


if( $_POST["oper"] == "edit" )
{
	
}
// 取消
else if ( $_POST["oper"] == "cancel" )
{
	// 未完成取消
	if( $_POST["flag"] == "0" ) {
		$SQLStr = " SELECT  h.POVHOURS,h.POVDAYS,h.over_date,h.povtype
					FROM psfempl p,holidayform h,psqcode pc
					where CONDITION in ('0','2')
					and serialno='$proc_serialno'
					and POCARD='$userid'
					and p.empl_no=h.pocard
					and pc.CODE_KIND='0302'
					and pc.CODE_FIELD=h.POVTYPE";
				
		$data = $db -> fetch_row_assoc($SQLStr);
		
		$povtype   = $data["POVTYPE"];
		$povday    = $data["POVDAYS"];
		$povhour   = $data["POVHOURS"];
		$over_date = $data["OVER_DATE"];
		
		// 進行取消
		$SQLStr2 = "update holidayform set CONDITION='-1' ,THREESIGND='$sys_date' where serialno = '$proc_serialno' ";
		$data_update = $db -> query($SQLStr2);
		if (empty($data_update["message"]) )
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
					  where  serialno='$proc_serialno'";
				//echo $sql."<br>";
				$data_temp = $db -> query_array($sql);
				for( $i = 0 ; $i < count($data_temp) ; $i++)
				{
					$over_date = $data_temp["OVER_DATE"];
					$use_hour = $data_temp["USE_HOUR"];

					$SQLStr2=  " update overtime o
								set    nouse_time= o.nouse_time + $use_hour
								where  empl_no= '$proc_serialno'
								and    over_date= '$over_date'";

					$db -> query($SQLStr2);
					$mail_subject = $userid."--".$proc_serialno."--update補休時數恢復通知";
					$mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
					// @mail('bob@cc.ncue.edu.tw',$mail_subject, $SQLStr2, $mail_headers);
					//@mail($mail_to, $mail_subject, $mail_body, $mail_headers)
				}
					//刪除之前補休時所使用的加班記錄
					$SQLStr2="delete from overtime_use
								where serialno='$proc_serialno'";
					//echo $SQLStr2;
				   $db -> query($SQLStr2); //liru update
					/* $mail_subject =$userid."--".$serialno."--delete補休時數恢復通知";
					$mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
					@mail('bob@cc.ncue.edu.tw',$mail_subject, $SQLStr2, $mail_headers); */
			}
			//**********************************
			echo "本假單取消成功，假別為『補休』時，加班時數同步加回資料庫！！";
			exit;
		}
	}
	// 已完成取消註記
	else if( $_POST["flag"] == "1" ) {
		$SQLStr ="SELECT h.povtype
						 FROM psfempl p,holidayform h,psqcode pc
						 where CONDITION='1'
						 and POCARD='$userid'
						 and p.empl_no=h.pocard
						 and pc.CODE_KIND='0302'
						 and pc.CODE_FIELD=h.POVTYPE";
		$data = $db -> fetch_row_assoc($SQLStr);
		$povtype = $data["POVTYPE"];

		$SQLStr2=  "update holidayform set CONDITION='3' where serialno = $proc_serialno ";
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
						  where  serialno= $proc_serialno";
					//echo $sql."<br>";
					$data_temp = $db -> query_array($sql);
					// echo json_encode($data_temp);
					// exit;
					for($i = 0 ; $i < count($data_temp) ; $i++)
					{
					   $over_date = $data_temp["OVER_DATE"][0];
					   $use_hour = $data_temp["USE_HOUR"][0];
					   $SQLStr2=  " update overtime o
									set    nouse_time= o.nouse_time + $use_hour
									where  empl_no= '$userid'
									and    over_date= '$over_date'";

					   $db -> query($SQLStr2);
					   $mail_subject =$userid."--".$proc_serialno."--update補休時數恢復通知";
					   $mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
						//@mail('bob@cc.ncue.edu.tw',$mail_subject, $SQLStr2, $mail_headers);
					}
					//刪除之前補休時所使用的加班記錄

					   $SQLStr2="delete from  overtime_use
								 where  serialno= $proc_serialno";
					   //echo $SQLStr2;
					   $db -> query($SQLStr2);
					   /*$mail_subject =$userid."--".$serialno."--delete補休時數恢復通知";
					   $mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
					  @mail('bob@cc.ncue.edu.tw',$mail_subject, $SQLStr2, $mail_headers);*/
				}
				//**********************************
				echo "系統已自動通知人事室執行真正取消動作！！請您不必再知會人事室";
				exit;
		}
	}
}


?>