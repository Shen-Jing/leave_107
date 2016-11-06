<?php
  session_start();
  include '../inc/connect.php';
	//加班作業  1000429
	//**********************************************************
    //require("check.php");
    $empl_no = $_SESSION['empl_no'];
    $empl_name = $_SESSION['empl_name'];

	//------------------------------------------------------------
	//抓職稱名稱
	//------------------------------------------------------------
	$title = $_SESSION['title_id'];
	$SQLStr = "SELECT code_chn_item
		FROM  psqcode
		where code_kind = '0202'
		and code_field = '$title'";

	$data = $db -> query_array($SQLStr);

	$tname = $data['CODE_CHN_ITEM'][0];

	//------------------------------------------------------------
	//抓單位名稱
	//------------------------------------------------------------
	$depart = $_SESSION['depart'];
	$sql = "select dept_short_name
			from   stfdept
			where  dept_no = '$depart'";

	$data1 = $db -> query_array($sql);

	$dname = $data1['DEPT_SHORT_NAME'][0];

	//------------------------------------------------------------
    //今日日期
	//------------------------------------------------------------
	$today = getdate();
	$year  = $today["year"] - 1911;
	$month = $today["mon"];
	$day   = $today["mday"];

	//------------------------------------------------------------
    // session 設定
	//------------------------------------------------------------
	/**session_register('byear');	session_register('bmonth');  session_register('bday');
	session_register('eyear');	session_register('emonth');  session_register('eday');
	session_register('uyear');	session_register('umonth');  session_register('uday');//提簽日期
	session_register('btime'); 	session_register('etime'); //下班刷卡時間分及時
	session_register('tot');**/

	$_SESSION["tot"] = 0;

	//------------------------------------------------------------
	// 設定初值或給值
	//------------------------------------------------------------

	//------------------------------------------------------------
	// 選擇的日期
	//------------------------------------------------------------
	if(strlen(@$_POST["bmonth"])<2)
		@$_POST["bmonth"]='0'.@$_POST["bmonth"];
	if(strlen(@$_POST["bday"])<2)
		@$_POST["bday"]='0'.@$_POST["bday"];

	if(strlen(@$_POST["emonth"])<2)
		@$_POST["emonth"]='0'.@$_POST["emonth"];
	if(strlen(@$_POST["eday"])<2)
		@$_POST["eday"]='0'.@$_POST["eday"];

	if(strlen(@$_POST["umonth"])<2)
		@$_POST["umonth"]='0'.@$_POST["umonth"];
	if(strlen(@$_POST["uday"])<2)
		@$_POST["uday"]='0'.@$_POST["uday"];

	 $over_date =@$_POST["byear"].@$_POST["bmonth"].@$_POST["bday"];
	 $over_date2=@$_POST["eyear"].@$_POST["emonth"].@$_POST["eday"];//加班跨隔日
	 $draw_date =@$_POST["uyear"].@$_POST["umonth"].@$_POST["uday"];//提簽日期

if($_POST['oper'] == "qry_first")
{

    $data = array("year" => $year,"month" => $month,"date" => $day,"empl_no" => $empl_no,"empl_name" => $empl_name,"dname" => $dname,"tname" => $tname );

	echo json_encode($data);
    exit;

}
if( $_POST['oper'] == "btime" )
{
	$sql=" select  substr(do_time,1,2)||':'||substr(do_time,3,2) do_time2,do_time
	                   from   ps_card_data p
	                   where  empl_no='$empl_no'
	                   and    do_dat='$over_date'
	                   order  by do_time";
	$data = $db -> query_array($sql);

	echo json_encode($data);
	exit;

}
if( $_POST['oper'] == "btime_cn" )
{
	//----------------------------------------------
	//   1000629 add  判斷是否為寒暑假期間
	//----------------------------------------------

	$SQLStr2 = "select  count(*) count
				from    t_card_time
				where   '$over_date' between afternoon_s and afternoon_e";

	$data2 = $db -> query_array($SQLStr2);

	echo json_encode($data2);
	exit;

}
if( $_POST['oper'] == "etime")
{
	//-------------------
	//尋找加班上班刷卡記錄
	$sql=" select  substr(do_time,1,2)||':'||substr(do_time,3,2) do_time2,do_time
			from   ps_card_data p
			where  empl_no='$empl_no'
			and    do_dat='$over_date2'
			order  by do_time";
	$data = $db -> query_array($sql);

	echo json_encode($data);
	exit;

}

if( $_POST['oper'] == "timesum" )
{

	$btime = $_POST["btime"];
	$etime = $_POST["etime"];
	$bmonth = $_POST["bmonth"];
	$bday = $_POST["bday"];
	$emonth = $_POST["emonth"];
	$eday = $_POST["eday"];
	$umonth = $_POST["umonth"];
	$uday = $_POST["uday"];
	$byear = $_POST["byear"];
	$eyear = $_POST["eyear"];
	$uyear = $_POST["uyear"];
	$reason = $_POST["reason"];
	//$reason = "";
	//$reason='echo"<script>document.holiday.reason.value;</script>"';

	//for($i = 0 ; $i < count($reasonstr) ; $i++)
	//	$reason .= chr( $reasonstr[$i] );

	//------------------------------------------------------------
    //確定送出處理求加班時數
	//------------------------------------------------------------

	//------------------------------------------------------------
	// 選擇的日期
	//------------------------------------------------------------

	if(strlen($bmonth)<2)
		$bmonth='0'.$bmonth;
	if(strlen($bday)<2)
		$bday='0'.$bday;

	if(strlen($emonth)<2)
		$emonth='0'.$emonth;
	if(strlen($eday)<2)
		$eday='0'.$eday;

	if(strlen($umonth)<2)
		$umonth='0'.$umonth;
	if(strlen($uday)<2)
		$uday='0'.$uday;

	 $over_date =$byear.$bmonth.$bday;
	 $over_date2=$eyear.$emonth.$eday;//加班跨隔日
	 $draw_date =$uyear.$umonth.$uday;//提簽日期

	/* $time_1=$btime;
		$time_2=$etime;

	echo json_encode($reason);
    exit;*/
   	if( !( empty($btime) ) && !( empty($etime) ) && ! ( empty( $reason ) ) )
	{

		//------------
		//計算加班時數
		//------------
        if ($over_date == $over_date2)
        {  // 加班在同一天
			if (substr($etime,2,2) >= substr($btime,2,2)) //下班分 > 上班分
				$tot= substr($etime,0,2) - substr($btime,0,2) ;
			else
				$tot= substr($etime,0,2) - substr($btime,0,2) - 1;  //借時
		}
		else
		{   //加班過淩晨，不同天
			if (substr($etime,2,2) >= substr($btime,2,2)) //下班分 > 上班分
				$tot= substr($etime,0,2) + 24 - substr($btime,0,2)  ;
			else
				$tot= substr($etime,0,2) + 24 - substr($btime,0,2) - 1;  //借時
        }

		$_SESSION["tot"]=$tot; //採計到時
		$time_1=$btime;
		$time_2=$etime;

        //104/08/26 update! 改成6個月
		if (substr($over_date,3,2)>'06')
			$due_date = substr($over_date,0,3)+1 . substr($over_date,3,2)-6 . substr($over_date,5,2);
		else
			$due_date = substr($over_date,0,3) . substr($over_date,3,2)+6 . substr($over_date,5,2);
     	//寫入檔案
	  	$SQLStr = "insert into overtime (EMPL_NO,OVER_DATE,DO_TIME_1,DO_TIME_2,NOUSE_TIME,PERSON_CHECK,DRAW_DATE,DUE_DATE,ALL_TIME,REASON) values ('$empl_no','$over_date','$time_1','$time_2','$tot','0','$draw_date','$due_date','$tot','$reason')";

      	$value = $db -> query($SQLStr);

/*echo json_encode("$SQLStr");
    exit;*/
      	//$s=print_r($value);

      	//echo"<script>console.log($s); </script>";

      	if ( !empty($value["message"])  )
		 	$data = array("資料重複申請或儲存有問題，請洽管理者。");

		else
			$data = array("資料儲存完畢。");


	}
	else if ( empty( $reason ) )
	{
		$data = array("請輸入加班原因!");
	}
	else
	{
		$data = array("請選擇刷卡時間!");
	}

	echo json_encode($data);
    exit;
}
