<?php
	session_start();
	include '../inc/connect.php';
	$user_id = $_SESSION['empl_no'];

	$today = getdate();
	$year = $today["year"] - 1911;
	$month = $today["mon"];

	if($_POST["oper"] == "0")
	{
		$month = "";

		if ($_POST['base_month'] == "0")
		{
			if ($_POST["who"]=='1') $month="12";
			else $month="7";
		}
		else if ($_POST['base_month']!="0")
			$month = $_POST['base_month'];
		else
			$month = $today["mon"];

		$day   = $today["mday"];

		if (strlen($year)<3)
		  $year ='0'.$year;

		if (strlen($month)<2)
		  $month ='0'.$month;

		if (strlen($day)<2)
		   $day = '0'.$day;

		$today=$year.$month.$day;

		$before_year=$year-1;  //去年

		if(strlen($before_year)<3)
		  $before_year="0".$before_year;

		//***************************************************
		// 抓取要計算之人員
		//***************************************************

		if ($_POST["who"]=='1')  //正式職員
			$sql="select empl_no,holiday_senior,crjb_depart,shall_holiday
				from   ps_senior,psfcrjb
				where  empl_no=crjb_empl_no
				and    crjb_seq='1'
				and    crjb_quit_date is null
				and    substr(crjb_title,1,1) not in ('B','C','D')
				and    substr(crjb_empl_no,1,1)='0'";

		else  if ($_POST["who"]=='2')//兼任主管
			 $sql="select * from ps_senior
				  where empl_no in (select crjb_empl_no
							from   psfcrjb
							where crjb_seq > '1'
							and   crjb_quit_date is null
							and   substr(crjb_empl_no,1,1)='0')";


		$data = $db -> query_array($sql);

		for($i = 0 ; $i < count($data["EMPL_NO"]) ; $i++)
		{
			$empl_no = $data["EMPL_NO"][$i];
		   	$holiday_senior = $data["HOLIDAY_SENIOR"][$i];
		   	$depart = $data["CRJB_DEPART"][$i];
		   	$shall_holiday = $data["SHALL_HOLIDAY"][$i];

		   	$permit_days =0; //可休日數
		   	$already_days=0; //已休日數
		   	$must_days   =0; //必扣日數

			//-------------------------------------
			// 1.應給休假日數
			//-------------------------------------

			if ($holiday_senior>=14)
				$permit_days=30;
			else if ($holiday_senior>=9)
		  		$permit_days=28;
			else if ($holiday_senior>=6)
		  		$permit_days=21;
			else if ($holiday_senior>=3)
		  		$permit_days=14;
			else if ($holiday_senior>=1)
				$permit_days=7;


			//-------------------------------------
			// 2.統計已休日數
			//-------------------------------------

			//教師以學年度統計

			if ($_POST['who'] == '2'){
				$begin_date = $before_year.'0801';
				$end_date = $year.'0731';
			}
			else{
				$begin_date = $year.'0101';
				$end_date = $year.'1231';
			}

			//A.請假總天數及總時數，正常請假
			$SQLStr = "SELECT sum(nvl(POVDAYS,0)) POHDAYE,sum(nvl(POVHOURS,0)) POHOURE FROM holidayform where povtype='06' and POVDATEB>='$begin_date' and POVDATEE<='$end_date' and pocard ='$empl_no' and condition in ('0','1')";

			// echo json_encode($SQLStr);
		 	// exit;

			$data_poh = $db -> query_array($SQLStr);
			$pohdaye = $data_poh["POHDAYE"][0];
			$pohoure = $data_poh["POHOURE"][0];

			//B.請假總天數及總時數，跨年請假--去年年底至今年年初
			$SQLStr = "SELECT POVDATEE,POVTIMEE ,CONTAINSAT,CONTAINSUN FROM holidayform where povtype='06' and     POVDATEB<'$begin_date' and     POVDATEE>='$begin_date' and     pocard ='$empl_no' and     condition in ('0','1')";

			// echo json_encode($SQLStr);
			// exit;

			$edate = '';
			$etime = '';
			$saturday = '';
			$sunday = '';

			$data_tot = $db ->query_array($SQLStr);
			if (empty($data_tot["message"])){
				$edate = $data_tot["POVDATEE"][0];  //起始日期
				$etime = $data_tot["POVTIMEE"][0];  //起始時間
				$saturday = $data_tot["CONTAINSAT"][0];
				$sunday = $data_tot["CONTAINSUN"][0];

				if ($_POST["who"] == '2') //主管以學年度統計971013 add
					$bdate = $before_year.'0801';
				else
					$bdate = $year.'0101';  //只算今年從1日起

				$btime = 8;

				require "../calculate_time.php";

				$pohdaye += $tot_day;
				$pohoure += $tot_hour;
			}

			//C.請假總天數及總時數，跨年請假--今年年底至明年年初  liru add

			$SQLStr = "SELECT POVDATEB,POVTIMEB ,CONTAINSAT,CONTAINSUN FROM holidayform where povtype='06' and     POVDATEB<='$end_date' and     POVDATEE>'$end_date' and     pocard ='$empl_no' and     condition in ('0','1')";

			$data_time = $db -> query_array($SQLStr);

			$bdate = '';
			$btime = '';
			$saturday = '';
			$sunday = '';
			if (empty($data_time["message"])){
				$bdate = $data_time["POVDATEB"][0];  //起始日期
				$btime = $data_time["POVTIMEB"][0];  //起始時間
				$saturday = $data_time["CONTAINSAT"][0];
				$sunday = $data_time["CONTAINSUN"][0];

				if ($_POST["who"]=='2') //主管以學年度統計971013 add
					$bdate=$year.'0731';
				else
					$bdate=$year.'1231';  //只算至今年1231日止

				$etime = 17;

				require "../calculate_time.php";

				$pohdaye += $tot_day;
				$pohoure += $tot_hour;
			}

			//時數超過八小時轉入天數
			$temp_h = 0;
			if ($pohoure >= 8){
				$temp_h= $pohoure % 8;
				$pohdaye += floor($pohoure / 8 );
				$pohoure=$temp_h;
			}
			if ($pohdaye=='') $pohdaye=0;
			if ($pohoure=='') $pohoure=0;
			//已休假日數
			if ($pohoure==4)
				$already_days=$pohdaye+0.5;
			else
				$already_days=$pohdaye;

			//-------------------------------------
			// 3.計算一日所得
			//-------------------------------------

			if ($_POST["who"] == '1')  //正式職員
			{
				$SQLStr = "SELECT  saly_salary_n,saly_reasearch_al_n,nvl(saly_chief_al_n,0) chief from school.tot_sal where   saly_empl_no ='$empl_no' and     saly_yy='$year' and     saly_mm='$month'";
			}

			else
			{
				$SQLStr = "SELECT  saly_salary_n,saly_reasearch_al_n,nvl(saly_chief_al_n,0) chief from     school.tot_sal where   saly_empl_no ='$empl_no' and     saly_yy='$year' and     saly_mm='$month'";   //主管
			}

			// echo json_encode($SQLStr);
			// exit;

			$data2 = $db -> query_array($SQLStr);

			if (empty($data2["message"])){
		 		$salary=$data2["SALY_SALARY_N"];
				$research=$data2["SALY_REASEARCH_AL_N"];
				$chief=$data2["CHIEF"];
			    $sum  =$salary+$research+$chief;
			}


			$day_money=($salary+$research+$chief)/30;   //一日所得

			//-------------------------------------
			// 4.計算不休假獎金
			//-------------------------------------
			$travel_money=0; //超過以旅遊補助方式

			if ($permit_days <= 14){  ////不補助
				$no_rest_money=0;
				$apply_days=0;
		    }
		    else
		    {
				if ($already_days <= 14)
		            $must_days=14;
				else
					$must_days=$already_days;  //必扣日數

			    $apply_days=$permit_days-$must_days;  //可申請日數

				if ($must_days>14)
		            $travel_money=($must_days-14)*600;//超過14天

		        $no_rest_money= floor($day_money * $apply_days); //不休假加班費
		    }


			//-------------------------------------
			// 5.寫入資料庫
			//-------------------------------------
			if ($_SESSION["who"]=='1')  //正式職員
				$SQLStr = "UPDATE ps_senior set shall_holiday=$permit_days, already_holiday=$already_days, apply_work_day=$apply_days, no_rest_money=$no_rest_money, travel_money=$travel_money, salary   = $sum, modify_date='$today', depart='$depart' where empl_no='$empl_no'";
			else
				$SQLStr = "UPDATE ps_senior set  shall_holiday=$permit_days, already_holiday=$already_days, apply_work_day=$apply_days, no_rest_money=$no_rest_money, travel_money=$travel_money, salary   = $sum, modify_date='$today' where empl_no='$empl_no'";

			$check = $db -> query_trsac($SQLStr);

			if(!$check)
			{
				$err = $check;
		        mail('bob@cc.ncue.edu.tw', '資料異動失敗!'.$_SERVER['PHP_SELF'], $SQLStr . $err['message'], $headers);
				echo json_encode('資料異動失敗!');
				exit();
			}
			$cnt ++;
		}

		$db -> end_trsac();
		echo json_encode($empl_no." 計算完畢 , 共". $cnt ."筆 !");

	}

	else if($_POST["oper"] == "2")
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
			$sql = "SELECT empl_no,empl_name,nvl(dept_short_name,'-') dept_short_name,holiday_senior,shall_holiday, already_holiday,apply_work_day,salary,no_rest_money,travel_money FROM ps_senior,stfdept	WHERE empl_no IN (SELECT crjb_empl_no FROM   psfcrjb WHERE crjb_seq > '1' AND crjb_quit_date is NULL AND substr(crjb_empl_no,1,1)='0') AND depart= dept_no ORDER BY depart";
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

		$a["spans"]["total"] = $t;
		$a["spans"]["tot_no_rest"] = $tot_no_rest;
		$a["spans"]["tot_travel"] = $tot_travel;
		$a["spans"]["tot_sum"] = $tot_sum;

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