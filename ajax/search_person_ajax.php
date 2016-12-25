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
    $userid = $_SESSION['empl_no'];
    $name=$_SESSION['empl_name'];

    $today = getdate();
    if(!empty($_POST["year"]))
    	$year = $_POST["year"];
    else
    	$year = $today["year"] - 1911;

    $empl_no="";
    if (substr($userid,0,1)=='7'||substr($userid,0,1)=='5'){
    	//以身份証查詢
    	$sql="select empl_id_no
    	from psfempl
    	where empl_no='$userid'";
    	$data = $db -> query_array($sql);
    	$id_no = $data["EMPL_ID_NO"][0];

       //判斷今年是否曾任專案助理，查專案助理人員代號

        $sql="select crjb_empl_no
    				  from psfcrjb
    				  where crjb_empl_id_no='$id_no'
    				  and   crjb_seq='1'
    				  and   substr(crjb_empl_no,1,1) in ('3','5','7')
    				  and   substr(crjb_quit_date,1,3)=lpad('$year',3,'0')";
    	$data = $db -> query_array($sql);
    	$empl_no = $data["EMPL_ID_NO"][0];

    }


    //971013 add----------------------------
    $SQLStr2 = "select substr(crjb_title,1,1)  crjb_title
                from   psfcrjb
    			where  crjb_seq='1'
    		    and    crjb_empl_no='$userid'";
    // echo $SQLStr2;
    $data = $db -> query_array($SQLStr2);
    $title = $data["CRJB_TITLE"][0];

    //---------------------------------------

    //-------------------------------------------
    //教師以學年度統計971013 add
    //-------------------------------------------

    if ($title=='B' or $title=='C'){
    	$begin_date=$year.'0801';
    	$end_date=($year+1).'0731';
    }
    else{
    	$begin_date=$year.'0101';
    	$end_date=$year.'1231';
    }
    //-------------------------------------------
    //970826 add 專案助理轉行政助理之處理
    //-------------------------------------------

    //--------------------------------------
    $data = array('year' => $year, 'begin_date' => $begin_date, 'end_date' => $end_date, 'name' => $name, 'userid' => $userid);
    if ($_POST['oper']=="qry")
    {
    	echo json_encode($data);
    	exit;
    }

	if($_POST["oper"] == 0 )
	{
	// 	if ($title=='B' or $title=='C'){
 //    		$begin_date = $_POST["year"].'0801';
 //    		$end_date = ($_POST["year"]+1).'0731';
 //    	}
 //    	else{
 //    		$begin_date = $_POST["year"].'0101';
 //    		$end_date = $_POST["year"].'1231';
 //    	}

    	//condition ==1 已審核
    	$col=1;
		$datalist = array();
		$vtype =array('01','02','03','04','05','06','07','08','23','21','22','09','11','30','32');
		for($i = 0 ; $i < 15 ; $i++)
		{
			$pohdaye=0;
			$pohoure=0;

			$SQLStr = "SELECT substr(CODE_CHN_ITEM,1,6)  code_chn_item FROM psqcode where code_kind='0302'
			           and code_field='$vtype[$i]'";  //假別名稱

			$data = $db -> query_array($SQLStr);

			if (empty($data["message"]))
				$v = $data["CODE_CHN_ITEM"][0];

			//...........................................................
			//請假總天數及總時數，正常請假
			//...........................................................
			//$SQLStr = "SELECT pohdaye,pohoure  FROM pap0303m	 where povtype='$vtype[$i]' and pocard='$userid' and posyear='$year'";
			$SQLStr = "SELECT sum(nvl(POVDAYS,0)) POHDAYE,sum(nvl(POVHOURS,0))    POHOURE
						FROM holidayform
						where povtype='$vtype[$i]'
						and POVDATEB>='$begin_date'
						and POVDATEE<='$end_date'
						and pocard in ('$userid','$empl_no')
						and condition='1'";
					 //echo "i=".$i."<br>".$SQLStr."<br>";
			      	 $data = $db -> query_array($SQLStr);

			         if (empty($data["message"])){
			         	//if($data["POHDAYE"][0] != null)
							$pohdaye = $data["POHDAYE"][0];
						//if($data["POHOURE"][0] != null)
							$pohoure = $data["POHOURE"][0];
			         }

						//.........................................................................................
			            //請假總天數及總時數，跨年請假--去年年底至今年年初  liru add
					    //.............................................................................................
			    		//97.01.04  POVDATEB<='$begin_date' 改為POVDATEB<'$begin_date'
			  		$SQLStr = "SELECT POVDATEE,POVTIMEE ,CONTAINSAT,CONTAINSUN
							 FROM holidayform
							 where povtype='$vtype[$i]'
							 and POVDATEB<'$begin_date'
							 and POVDATEE>='$begin_date'
							 and pocard in ('$userid','$empl_no')
							 and condition='1'";

			      	$data = $db -> query_array($SQLStr);

			        if(empty($data["message"])){

						@$edate = $data["POVDATEE"];  //起始日期
						@$etime = $data["POVTIMEE"][0];  //起始時間
						@$saturday = $data["CONTAINSAT"];
						@$sunday = $data["CONTAINSUN"];

					    if ($title=='B' or $title=='C') //教師以學年度統計971013 add
							$bdate=$_POST['year'].'0801';
						else
							$bdate=$_POST['year'].'0101';

			            $btime='8';
						//...........................................................
						//半小時的轉成整數  10201 add
						if (substr($etime,2,2)=='30')
							 $etime=substr($etime,0,2);
						//...........................................................
			     	    require "../calculate_time.php";
						//$pohdaye += $tot_day;
						//$pohoure += $tot_hour;
			        }

					//........................................................................................................
			        //請假總天數及總時數，跨年請假--今年年底至明年年初  liru add
					//97.01.04  and POVDATEE>='$end_date' 改為and POVDATEE>'$end_date'
					//..........................................................................................................
			  		$SQLStr = "SELECT POVDATEB,POVTIMEB ,CONTAINSAT,CONTAINSUN
							 FROM holidayform
							 where povtype='$vtype[$i]'
							 and POVDATEB<='$end_date'
							 and POVDATEE>'$end_date'
							 and pocard in ('$userid','$empl_no')
							 and condition='1'";

			      	$data = $db -> query_array($SQLStr);

			        if (empty($data["message"])){
						@$bdate = $data["POVDATEB"];  //起始日期
						@$btime = $data["POVTIMEB"][0];  //起始時間
						@$saturday = $data["CONTAINSAT"];
						@$sunday = $data["CONTAINSUN"];

					    if ($title=='B' or $title=='C') //教師以學年度統計971013 add
							$edate=$next_year.'0731';
				        else
							$edate=$_POST['year'].'1231';

			            $etime='17';
						//...........................................................
						//半小時的轉成整數  10201 add
						if (substr($btime,2,2)=='30')
							$btime=substr($btime,0,2);
						//...........................................................
			     		require "../calculate_time.php";
						//$pohdaye += $tot_day;
						//$pohoure += $tot_hour;
			        }
						//$res = db_parse($SQLStr);
						//db_query($SQLStr,$res);
						//$date = db_fetch_array($res);
						//if($date[0]=='') $date[0]=0;
						//if($date[1]=='') $date[1]=0;
			        //時數超過八小時轉入天數
			        $temp_h = 0;
			        if ($pohoure >= 8){
						$temp_h= $pohoure % 8;
			            $pohdaye += floor($pohoure / 8 );
			            $pohoure=$temp_h;
			        }
			        if ($pohdaye==null) $pohdaye=0;
			        if ($pohoure==null) $pohoure=0;

			$datalist[0]["v"][$i] = $v;
			$datalist[0]["pohdaye"][$i] = $pohdaye;
			$datalist[0]["pohoure"][$i] = $pohoure;
		}


		//condition == 審核中
		$col=1;
		for($i=0;$i<15;$i++)
		{
			$pohdaye=0;
			$pohoure=0;

			$SQLStr = "SELECT substr(CODE_CHN_ITEM,1,6)  code_chn_item FROM psqcode where code_kind='0302'
		           and code_field='$vtype[$i]'";  //假別名稱
			//echo "sql=".$SQLStr."<br>";
			$data = $db -> query_array($SQLStr);
        	if (empty($data["message"]))
				$v=$data["CODE_CHN_ITEM"];
				//$res = db_parse($SQLStr);
				//db_query($SQLStr,$res);
				//$v = db_fetch_array($res);

			//...........................................................
			//請假總天數及總時數，正常請假
			//...........................................................
			//$SQLStr = "SELECT pohdaye,pohoure  FROM pap0303m	 where povtype='$vtype[$i]' and pocard='$userid' and posyear='$year'";
			$SQLStr = "SELECT sum(nvl(POVDAYS,0)) POHDAYE,sum(nvl(POVHOURS,0))    POHOURE
							FROM holidayform
							 where povtype='$vtype[$i]'
							 and POVDATEB>='$begin_date'
							 and POVDATEE<='$end_date'
							 and pocard in ('$userid','$empl_no')
							 and condition='0'";
		 	//echo "i=".$i."<br>".$SQLStr."<br>";
      		$data = $db -> query_array($SQLStr);
        	if (empty($data["message"])){
				$pohdaye=$data["POHDAYE"][0];
				$pohoure=$data["POHOURE"][0];
        	}

			//.........................................................................................
            //請假總天數及總時數，跨年請假--去年年底至今年年初  liru add
		    //.............................................................................................
    		//97.01.04  POVDATEB<='$begin_date' 改為POVDATEB<'$begin_date'
  			$SQLStr = "SELECT POVDATEE,POVTIMEE ,CONTAINSAT,CONTAINSUN
							 FROM holidayform
							 where povtype='$vtype[$i]'
							 and POVDATEB<'$begin_date'
							 and POVDATEE>='$begin_date'
							 and pocard in ('$userid','$empl_no')
							 and condition='0'";
		 	//echo $SQLStr."<br>";
      		$data = $db -> query_array($SQLStr);
        	if (empty($data["message"])){
				@$edate=$data["POVDATEE"][0];  //起始日期
				@$etime=$data["POVTIMEE"][0];  //起始時間
				@$saturday=$data["CONTAINSAT"][0];
				@$sunday=$data["CONTAINSUN"][0];

		    	if ($title=='B' or $title=='C') //教師以學年度統計971013 add
					$bdate=$_POST['year'].'0801';
				else
					$bdate=$_POST['year'].'0101';

            	$btime='8';
				//...........................................................
				//半小時的轉成整數  10201 add
				if (substr($etime,2,2)=='30')
					 $etime=substr($etime,0,2);
				//...........................................................
     	    	require "../calculate_time.php";
				//$pohdaye += $tot_day;
				//$pohoure += $tot_hour;
        	}

			//........................................................................................................
        	//請假總天數及總時數，跨年請假--今年年底至明年年初  liru add
			//97.01.04  and POVDATEE>='$end_date' 改為and POVDATEE>'$end_date'
			//..........................................................................................................
  			$SQLStr = "SELECT POVDATEB,POVTIMEB ,CONTAINSAT,CONTAINSUN
								 FROM holidayform
								 where povtype='$vtype[$i]'
								 and POVDATEB<='$end_date'
								 and POVDATEE>'$end_date'
								 and pocard in ('$userid','$empl_no')
								 and condition='0'";
      		 $data = $db -> query_array($SQLStr);
			 //echo $SQLStr."<br>";

         	if (empty($data["message"])){
				@$bdate=$data["POVDATEB"];  //起始日期
				@$btime=$data["POVTIMEB"][0];  //起始時間
				@$saturday=$data["CONTAINSAT"];
				@$sunday=$data["CONTAINSUN"];

		    	if ($title=='B' or $title=='C') //教師以學年度統計971013 add
					$edate=$next_year.'0731';
	        	else
	        		$edate=$_POST['year'].'1231';

	            $etime='17';
				//...........................................................
				//半小時的轉成整數  10201 add
				if (substr($btime,2,2)=='30')
					$btime=substr($btime,0,2);
				//...........................................................
     	    	require "../calculate_time.php";
				//$pohdaye += $tot_day;
				//$pohoure += $tot_hour;
         	}
			//$res = db_parse($SQLStr);
			//db_query($SQLStr,$res);
			//$date = db_fetch_array($res);
			//if($date[0]=='') $date[0]=0;
			//if($date[1]=='') $date[1]=0;
        	//時數超過八小時轉入天數
        	$temp_h = 0;
        	if ($pohoure >= 8){
				$temp_h= $pohoure % 8;
        	    $pohdaye += floor($pohoure / 8 );
        	    $pohoure=$temp_h;
        	}
        	if ($pohdaye=='') $pohdaye=0;
        	if ($pohoure=='') $pohoure=0;

			$datalist[1]["v"][$i] = $v;
			$datalist[1]["pohdaye"][$i] = $pohdaye;
			$datalist[1]["pohoure"][$i] = $pohoure;

			$col++;

		} //for

		$datalist['begin_date'] = $begin_date;
		$datalist['end_date'] = $end_date;
		$datalist['name'] = $name;
		$datalist['userid'] = $userid;
		echo json_encode($datalist);
		exit;
	}
?>