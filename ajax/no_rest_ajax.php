<?php
	session_start();
	include '../inc/connect.php';
	$user_id = $_SESSION['empl_no'];

	$today = getdate();
	$year = $today["year"] - 1911;
	$month = $today["mon"];

	if($_POST["oper"] == "2")
	{

		//***************************************************
		// 抓取要列印之人員
		//***************************************************

		if ($_POST["who"] == '1' )  //正式職員
		{
			$sql = "SELECT empl_no,empl_name,dept_short_name,holiday_senior,shall_holiday, already_holiday,apply_work_day,salary,no_rest_money,travel_money FROM ps_senior,stfdept WHERE empl_no IN (SELECT crjb_empl_no FROM  psfcrjb WHERE crjb_seq='1' AND crjb_quit_date is NULL AND substr(crjb_title,1,1) not IN ('B','C','D') AND substr(crjb_empl_no,1,1)='0') AND depart= dept_no ORDER BY depart";
		}

		else if ($_POST["who"]=='2') //兼任主管
		{
			$sql = "SELECT empl_no,empl_name,nvl(dept_short_name,'-') dept_short_name,holiday_senior,shall_holiday, already_holiday,apply_work_day,salary,no_rest_money,travel_money FROM ps_senior,stfdept	WHERE empl_no IN (SELECT crjb_empl_no FROM   psfcrjb WHERE crjb_seq > '1' AND crjb_quit_date is NULL AND substr(crjb_empl_no,1,1)='0' AND depart= dept_n ORDER BY depart";
		}

		// echo json_encode($sql);
		// exit;

		$count = $db -> query_array($sql);

		$tot_no_rest=0;   //全部總計
		$tot_travel=0;
		$t=0;$p=0;

		$a['data']="";

		for($i = 0 ; $i < count($count["EMPL_NO"]) ; $i++)
		{
			$t++;$p++;

		   	$empl_no = $count["EMPL_NO"][$i];
		   	$empl_name = $count["EMPL_NAME"][$i];
		   	$dept_name = $count["DEPT_SHORT_NAME"][$i];
		   	$holiday_senior = $count["HOLIDAY_SENIOR"][$i];       //休假年資
		   	$shall_holiday = $count["SHALL_HOLIDAY"][$i];   //應給休假日數
		   	$already_holiday = $count["ALREADY_HOLIDAY"][$i]; //已休假日數
		   	$apply_holiday = $count["APPLY_WORK_DAY"][$i];  //可申請補助休假日數
		   	$salary = $count["SALARY"][$i];          //俸給
		   	$no_rest_money = $count["NO_REST_MONEY"][$i];   //可申請不休假補助
		   	$travel_money = $count["TRAVEL_MONEY"][$i];    //超出部份領旅遊補助

		   	if ($_POST["who"] == '1')  //正式職員
		   		$sql = "SELECT code_chn_item FROM   psfcrjb,psqcode WHERE  crjb_empl_no ='$empl_no' AND    code_kind='0202' AND    code_field=crjb_title";
		   	else if ($_POST["who"] == '2')//兼任主管
		   		$sql="SELECT code_chn_item FROM   psfcrjb,psqcode WHERE  crjb_empl_no ='$empl_no' AND    code_kind='0202' AND    code_field=crjb_title AND    crjb_seq>1 AND  crjb_quit_date is NULL";

		   	$tname = $db -> query_array($sql);
   			$title_name = $tname["CODE_CHN_ITEM"][0];

			$sum = $no_rest_money + $travel_money;

			$a['data'][] = array(
				$dept_name,
			    $title_name,
			    $empl_name,
			    $holiday_senior,
			    $salary,
			    $shall_holiday,
			    $already_holiday,
			    $apply_holiday,
			    $no_rest_money,
			    $travel_money,
			    $sum
			);

			$tot_no_rest  += $no_rest_money;  //全部總計
			$tot_travel   += $travel_money;
		}

		$tot_sum=$tot_no_rest+$tot_travel;

		// echo "<tr>";
		// echo "<td align=\"center\" colspan=\"8\"><font color='darkred'>全部統計&nbsp;&nbsp;".$t."&nbsp;&nbsp;人";
		// echo "<td align=\"center\"><font color='darkred'>$tot_no_rest";
		// echo "<td align=\"center\"><font color='darkred'>$tot_travel";
		// echo "<td align=\"center\"><font color='darkred'>$tot_sum";
		// echo "</tr>";
		// echo "</table>";

		echo json_encode($a);
		exit;
	}

?>