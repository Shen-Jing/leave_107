
<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? INclude("inc/sidebar.php"); ?>
        <?
        $today  = getdate();
        $year = $today["year"] - 1911;
        $month = $today["mon"];
        $day = $today["mday"];

        $vocdate='';

        if (strlen(@$_POST['bmonth']) < 3)
          $vocdate = '0' . @$_POST['byear'];
        else
          $vocdate = @$_POST['byear'];

        if (strlen(@$_POST['bmonth']) < 2)
          $vocdate = $vocdate . '0' . @$_POST['bmonth'];
        else
         	$vocdate = $vocdate . @$_POST['bmonth'];

        if (strlen(@$_POST['bday']) < 2)
          $vocdate = $vocdate . '0' . @$_POST['bday'];
        else
          $vocdate = $vocdate . @$_POST['bday'];

        //----------------------------------------------
        //   970813 add  判斷是否為寒暑假期間
        //----------------------------------------------
        $sql = "SELECT count(*) count
        						FROM    t_card_time
        				    WHERE   '$vocdate' BETWEEN afternoon_s AND afternoon_e";
        $data = $db -> query($sql);
        $cn = $data['COUNT'];
        if ($cn > 0)
        	$vocation = '1';
        else
        	$vocation = '0';

        @$_POST['vocation'] = $vocation;
        ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <div class="row">
                            <form name="holiday" action="<?=$_SERVER['PHP_SELF'] ?>" class="form-horizontal"  method="POST">
															<fieldset>
																<div class="form-group">
																	<div class="col-lg-12">
			                                <div id="message">
			                                </div>
			                                <div class="panel panel-primary">
			                                    <div class="panel-heading">
			                                        國立彰化師範大學 教職員請假/出差作業
			                                    </div>
																					<?
                                          $userid = $_SESSION['_ID'];
																					$empl_no = $_SESSION['empl_no'][0];
																					$empl_name = $_SESSION['empl_name'][0];
																					 ?>
			                                    <div class="panel-body">
			                                        <table class="table table-condensed table-hover table-bordered">
			                                  					<tr>
			                                  						<td class="td1">員工編號</td>
			                                              <td><input type="hidden" name="userid"><?=$empl_no ?></td>
			                                              <td class="td1">姓名</td>
									                                  <td><input type='hidden' name='name'><?=$empl_name ?></td>
			                                  					</tr>
			                                  					<tr>
			                                  						<td class="td1">請選擇單位</td>
			                                              <td>
                                                      <select class="selectpicker" name="depart" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
                                                      <option value=''>請選擇</option>
                                                    <?
                                                      $sql = "SELECT dept_no, dept_full_name
                                                            FROM  psfcrjb, stfdept
                                                            WHERE crjb_empl_no = '$empl_no'
                                                            AND   crjb_quit_date IS NULL
                                                            AND   crjb_depart = dept_no
                                                            ORDER BY crjb_seq DESC";
                                                      $data = $db -> query_array($sql);
                                                      for ($i = 0; $i < count($data['DEPT_NO']); $i++){
                                                        $depart = $data['DEPT_NO'][$i];
                                                        $dept_name = $data['DEPT_FULL_NAME'][$i];

                                                        if ($depart == @$_POST["depart"])
                                                          echo "<option value='$depart' selected>$dept_name</option>";
                                                        else
                                                          echo "<option value='$depart'>$dept_name</option>";
                                                      }
                                                    ?>
                                                      </select>
			                                              </td>
			                                  						<td class="td1">職稱</td>
																										<?
																										//抓職稱名稱，根據單位有不一樣的職稱  103.04.22 加上  AND   crjb_quit_date is null(舊記錄保留，防抓到)
																										$depart = $_SESSION['depart'][0];
																										$sql = "SELECT code_chn_item, code_field
																														FROM  psfcrjb, psqcode
																														WHERE  crjb_empl_no = '$empl_no'
																														AND    crjb_quit_date IS NULL
																														AND    crjb_depart = '$depart'
																														AND    code_kind = '0202'
																														AND    code_field = crjb_title";
																										$data = $db -> query_array($sql);
																										$tname = $data['CODE_CHN_ITEM'][0];
																										$title_code = $data['CODE_FIELD'][0];
												                            // $_SESSION[title_name]=$tname;
												                            // $_SESSION[title]=$title_code;
																										?>
			                                  						<td><input type='hidden' name='title' value='<?=$_SESSION['title'] ?>'><?=$tname ?></td>
			                                  					</tr>
                                                  <?
                                        					 if (@$_POST["agentno"] == '0000000' || @$_POST['agent_flag'] == '1' ) {
                                        					   @$_POST['agent_flag'] = '1';
                                                  ?>
                                        					<tr>
                                        						 <td class="td1"><span style="color: red;">請選職務代理人單位</span></td>
                                        						 <td class="td1" colspan="3">
                                        	              <select class="selectpicker" name="agent_depart" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
                                        						    <option value=''>請選擇</option>
                                                    <?
                                                        $sql = "SELECT dept_no,dept_full_name
                                                            FROM stfdept
                                                            WHERE use_flag IS NULL
                                                            AND  substr(dept_no, 1, 1) BETWEEN 'A' AND 'Z'
                                                            ORDER BY  dept_no";
                                                        $data_1 = $db -> query_array($sql);

                                                        $sql = "SELECT dept_no,dept_full_name
                                                            FROM stfdept
                                                            WHERE use_flag IS NULL
                                                            AND  substr(dept_no, 1, 1) BETWEEN '0' AND '9'
                                                            ORDER BY  dept_no";
                                                        $data_2 = $db -> query_array($sql);

                                        						    for ($i = 0; $i < count($data_1['DEPT_NO']); $i++){
                                                          $depart_i = $data_1['DEPT_NO'][$i];
                                                          $dept_name_i = $data_1['DEPT_FULL_NAME'][$i];

                                          								if ($depart == @$_POST["agent_depart"])
                                          									echo "<option value='$depart_i' selected>$dept_name</option>";
                                          								else
                                                            echo "<option value='$depart_i'>$dept_name</option>";
                                        							  }

                                                        for ($i = 0; $i < count($data_2['DEPT_NO']); $i++){
                                                          $depart_i = $data_2['DEPT_NO'][$i];
                                                          $dept_name_i = $data_2['DEPT_FULL_NAME'][$i];

                                          								if ($depart == @$_POST["agent_depart"])
                                          									echo "<option value='$depart_i' selected>$dept_name</option>";
                                          								else
                                                            echo "<option value='$depart_i'>$dept_name</option>";
                                        							  }
                                        						  ?>
                                        						    </select>
                                                      </td>
                                                  </tr>
                                        					<?
                                                  }
                                                  ?>
			                                  					<tr>
			                                  						<td class="td1">假別</td>
			                                  						<td>
			                                  							<select class="selectpicker" name="vtype" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
																											<?
																										  $sql = "SELECT code_field, code_chn_item
																											FROM psqcode
																											WHERE code_kind = '0302'
																											ORDER BY code_field";
																										  $data = $db -> query_array($sql);
																											for ($i = 0; $i < count($data['CODE_FIELD']); $i++){
																												$code = $data['CODE_FIELD'][$i];
																												$item = $data['CODE_CHN_ITEM'][$i];

																												if ($code == @$_POST['vtype'])
																													  echo "<option value='$code' selected>$item</option>";
																												else
																														echo "<option value='$code'>$item</option>";
																											}
																											?>
			                                  							</select>
			                                  						</td>
                                                    <?
                                                    if ($empl_no == '7000082' && @$_POST["vtype"] == '23'){  // 今年 已請特休假天數  103.04. 21 add
                                                        $sql = "SELECT    trunc(sum(nvl(POVDAYS,0)) + sum(nvl(POVHOURS,0))/8) days , mod(sum(nvl(POVHOURS,0)),8) hours
                                                              FROM       holidayform
                                                              WHERE      povtype='23'
                                                              AND        substr(POVDATEB,1,3)='@$_POST[byear]'
                                                              AND        condition IN ('0','1')
                                                              AND        pocard IN ('$userid', '$empl_no')";
                                                        $data = $db -> query_array($sql);
                                                        $days = $data['DAYS'][0];
                                                        $hours = $data['HOURS'][0];

                                                        //年資  empl_ser_date_beg
                                                        //$sql="select    nvl(empl_arrive_sch_date,'$year')  empl_arrive_sch_date
                                                        $sql = "SELECT   nvl(empl_ser_date_beg, '$year')  empl_arrive_sch_date
                                                              FROM    psfempl
                                                              WHERE  empl_no='$empl_no'";
                                                        $data = $db -> query_array($sql);
                                                        $arrive_date = $data['EMPL_ARRIVE_SCH_DATE'][0];
                                                        $sens = $year - substr($arrive_date, 0, 3) - 1;

                                                        //---------------------------------------------------
                                                        // 是否滿第一年
                                                        //^^^^^^^^
                                                        if (strlen($month) < 2)
                                                          $m = '0' . $month;
                                                        if (strlen($day) < 2)
                                                          $d = '0' . $day;
                                                        if ( substr($arrive_date,3,4) == $m.$d  AND $sens == 0)  //同月同日
                                                          $apps = 7 * (13 - substr($arrive_date, 3, 2)) / 12 ;  //是否要加 1 ?
                                                        //----------------------------------------------------------------------------
                                                        /*一、一年以上三年未滿者七日。
                                                        二、三年以上五年未滿者十日。
                                                        三、五年以上十年未滿者十四日。
                                                        四、十年以上者，每一年加給一日，加至三十日為止*/
                                                        //可休天數
                                                        if ($sens >= 1  && $sens < 3)
                                                          $apps = 7;
                                                        elseif ($sens >= 3 && $sens < 5)
                                                          $apps = 10;
                                                        elseif ($sens >= 5 && $sens < 10)
                                                          $apps =14;
                                                        elseif ($sens >= 10)
                                                          $apps = $sens + 5;   // 第10年15天，第10年16天.......;

                                                         echo "<span style='font-size:11pt;color:red'>至去年年底您的在校年資 $sens 年，今年有 $apps 天特休假，目前已休 $days 天 $hours 小時。</span>";
                                                      }
                                                        //正式職員
                                                        if ($userid == '0000676' && @$_POST["vtype"] == '06'){  // 今年 已請特休假天數  103.04. 21 add
                                                        $sql=" SELECT    trunc(sum(nvl(POVDAYS,0)) + sum(nvl(POVHOURS,0))/8)   days, mod(sum(nvl(POVHOURS,0)),8) hours
                                                              FROM       holidayform
                                                              WHERE      povtype='06'
                                                              AND        substr(POVDATEB,1,3)='@$_POST[byear]'
                                                              AND        condition IN ('0','1')
                                                              AND        pocard IN ('$userid','$empl_no')";
                                                        $data = $db -> query_array($sql);
                                                        $days = $data['DAYS'][0];
                                                        $hours = $data['HOURS'][0];

                                                        //年資 及可休天數
                                                        $sql="SELECT    holiday_senior   sen, shall_holiday shall
                                                             FROM   ps_senior
                                                             WHERE  empl_no = '$userid'";
                                                        $sens = $data['SEN'][0]; // 年資
                                                        $apps = $data['SHALL'][0]; // 可休天數
                                                          /*公務人員:
                                                            1.	服務滿一年者，第二年起，每年應給休假七日；
                                                            2.	服務滿三年者，第四年起，每年應給休假十四日；
                                                            3.	滿六年者，第七年起，每年應給休假二十一日；
                                                            4.	滿九年者，第十年起，每年應給休假二十八日；
                                                            5.	滿十四年者，第十五年起，每年應給休假三十日
                                                          */
                                                        echo "<span style='font-size:11pt;color:red'>您目前在校年資  $sens 年，今年有 $apps 天休假，目前已休 $days 天  $hours 小時。</span>";
                                                  }
                                                  ?>

			                                  						<td class="td1">職務代理人</td>
			                                              <td>
			                                                <select class="selectpicker" name="agentno" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
			                                                  <option value='' selected="">請選擇</option>
																												<?
																												// 根據目前的empl_no找出所屬部門的所有代理人與其代理人號碼
																												if (@$_POST['agentno'] == '0000000' or @$_POST['agent_flag'] == '1'){
																														$sql = "SELECT  empl_no, empl_chn_name
																																	FROM   psfempl, psfcrjb
																																	WHERE empl_no = crjb_empl_no
																																	AND   crjb_quit_date IS NULL
																																	AND   crjb_depart = '$depart'
																																	AND   substr(empl_no, 1, 1) IN ('0', '7', '5', '3', '4')
																																	AND   empl_no != '$empl_no'
																																	ORDER BY crjb_depart, crjb_title, crjb_empl_no";
																												}
																												else {
																													$sql = "SELECT  empl_no, empl_chn_name
																																FROM   psfempl, psfcrjb
																																WHERE empl_no = crjb_empl_no
																																AND   crjb_quit_date IS NULL
																																AND   crjb_depart = '$depart'
																																AND   (substr(crjb_title, 1, 1) != 'B' OR
																																	   crjb_title = 'B60')
																																AND   substr(empl_no, 1, 1) IN ('0', '7', '5', '3', '4')
																																AND   empl_no != '$empl_no'
																																ORDER BY crjb_depart, crjb_title, crjb_empl_no";
																												}
																												$data = $db -> query_array($sql);
																												for ($i = 0; $i < count($data['EMPL_NO']); $i++) {
																													$agentno_i = $data['EMPL_NO'][$i];
							   																					$agent_name = $data['EMPL_CHN_NAME'][$i];
																													echo "<option value='$agentno_i' " . (($agentno_i == @$_POST['agentno']) ? 'selected' : '') . ">" . $agent_name . "</option>";
																												}
																												?>
			                                                </select>
			                                  					</tr>
			                                  					<tr>
			                                  						<td class="td1">請假開始日期</td>
			                                              <td>
			                                                <select class="selectpicker" name="byear" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
			                                    						<?
                                                      if (@$_POST['byear'] === null){
                                                        @$_POST['byear'] = $year;
                                                      }

			                                    						for ($i = $year - 1; $i <= $year + 1; $i++)
			                                                	echo "<option value='$i'" . ((@$_POST['byear'] == $i) ? 'selected' : '') . ">$i</option>";
			                                    						?>
			                                                </select>年
			                                                <select class="selectpicker" name='bmonth' data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
			                                                <?
                                                      if ( @$_POST['bmonth'] === null ){
                                                        @$_POST['bmonth'] = $month;
                                                      }

																											// 若跨年
			                                    						if (@$_POST["byear"] > $year)
			                                    						   for ($i = 1; $i <= 12; $i++)
																												 		echo "<option value='$i'" . ((@$_POST['bmonth'] == $i) ? 'selected' : '') . ">$i</option>";
			                                    						else
			                                    						   for ($i = 1; $i <= 12; $i++)
																												 		echo "<option value='$i'" . ((@$_POST['bmonth'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
																											</select>月
			                                    						<select class="selectpicker" name="bday" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
																											<?
                                                      $monthday = array("31", "28", "31", "30", "31", "30", "31", "31", "30", "31", "30", "31");
			                                    						$bmd = $monthday[@$_POST['bmonth'] - 1];	//開始該月的日數
			                                    						if (@$_POST["bmonth"] == 2 && date('L', mktime(0, 0, 0, 1, 1, @$_POST["byear"])) )//閏年且為二月
			                                    							$bmd = $bmd + 1;

                                                      if ( @$_POST['bday'] === null ){
                                                        @$_POST['bday'] = $day;
                                                      }

			                                    						if (@$_POST["bmonth"] == $month && @$_POST["byear"] == $year)
			                                    								for ($i = 1; $i <= $bmd; $i++)
																												 		echo "<option value='$i'" . ((@$_POST['bday'] == $i) ? 'selected' : '') . ">$i</option>";
			                                    						else
																													for ($i = 1; $i <= $bmd; $i++)
																												 		echo "<option value='$i'" . ((@$_POST['bday'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
			                                    						</select>日

			                                    					</td>
		                                  							<td class="td1">請假開始時間</td>
			                                  						<td>
			                                  							<select class="selectpicker" name="btime" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>

                                                    <?
                                                      /* 1021101 update */
                                                      if (@$_POST['vtype'] == '01' or @$_POST['vtype'] == '02' or @$_POST['vtype'] == '03'){
                                                        $bt = 8;
                                                        $et = 23;
                                                      }
                                                      elseif ($vocation == '1'){
                                                        $bt = 8;
                                                        $et = 15;
                                                      }
                                                      elseif ($party=='1'){
                                                        //特殊上班人員
                                                        $bt = 13;
                                                        $et = 21;
                                                      }
                                                      else {
                                                        $bt= 8;
                                                        $et = 16;
                                                      }
                                                      if ($vocation == '1') {
                                                        //寒暑假
                                                        if (@$_POST['vtype'] == '06' || @$_POST['vtype'] == '21' || @$_POST['vtype'] == '22') {
                                                          //休假、寒暑休
                                                          for ($i = $bt; $i <= 12; $i = $i + 4)
                                                            echo "<option value='$i' " . ((@$_POST['btime'] == $i) ? 'selected' : '') . ">" . $i . "</option>";

                                                          echo "<option value='1230'" . ((@$_POST['btime'] == '1230') ? 'selected' : '') . ">12:30</option>";//10201 add
                                                        }
                                                        else {
                                                          for ($i = $bt; $i <= $et; $i++) {
                                                            echo "<option value='$i' " . ((@$_POST['btime'] == $i) ? 'selected' : '') . ">" . $i . "</option>";
                                                            if ($i >= 12){
                                                              $bi = $i . "30";
                                                              echo "<option value='$bi' " . ((@$_POST['btime'] == $bi) ? 'selected' : '') . ">" . $i . ":30</option>";
                                                            }
                                                          }// for
                                                        }// else
                                                      }
                                                      else {
                                                        if(@$_POST['vtype'] == '06' || @$_POST['vtype'] == '21' || @$_POST['vtype'] == '22')
                                                          for ($i = $bt; $i <= 13; $i = $i + 5){
                                                            echo "<option value='$i' " . ((@$_POST['btime'] == $i) ? 'selected' : '') . ">" . $i . "</option>";
                                                          }
                                                        else
                                                          for ($i = $bt; $i <= $et; $i++){
                                                            echo "<option value='".$i."'".(($_SESSION["btime"]==$i)?'selected':'').">" . $i . "</option>";
                                                          }
                                                      }
                                                    ?>
			                                  							</select>時
			                                  						</td>
			                                  					</tr>
			                                  					<tr>
			                                  						<td class="td1">請假結束日期</h3>
			                                  						</td>
																										<td>
                                                      <select class="selectpicker" name="eyear" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
																											<?
																											for ($i = $year; $i <= ($year + 1); $i++)
																												echo "<option value='$i'" . ((@$_POST['eyear'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
																											</select>年

																											<select class="selectpicker" name="emonth" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
																											<?
																											if (@$_POST["eyear"] > @$_POST["byear"])//跨年
																												for ($i = 1; $i <= 12; $i++)
																													echo "<option value='$i'" . ((@$_POST['emonth'] == $i) ? 'selected' : '') . ">$i</option>";
																											else//同年
																												for ($i = @$_POST["bmonth"]; $i <= 12; $i++)
																													echo "<option value='$i'" . ((@$_POST['emonth'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
																											</select>月

																											<select class="selectpicker" name='eday' data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
																											<?
                                                      if ( @$_POST['emonth'] === null ){
                                                        @$_POST['emonth'] = @$_POST['bmonth'];
                                                      }

                                                      $emd = $monthday[@$_POST['emonth'] - 1];	//結束該月的日數
			                                    						if (@$_POST['emonth'] == 2 && date('L', mktime(0, 0, 0, 1, 1, @$_POST["byear"])) )//閏年且為二月
			                                    							$emd = $emd + 1;

																											if (@$_POST["emonth"] == @$_POST["bmonth"] && @$_POST["eyear"] == @$_POST["byear"]) //同年同月開始且結束
																									   		for ($i = @$_POST['bday']; $i <= $emd; $i++)
																										 			echo "<option value='$i'" . ((@$_POST['eday'] == $i) ? 'selected' : '') . ">$i</option>";
																											else
																												for ($i = 1; $i <= $emd; $i++)
																													echo "<option value='$i'" . ((@$_POST['eday'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
																											</select>日
																										</td>
																							      <td class="td1">請假結束時間</td>
																								   	<td>
																											<select class="selectpicker" name="etime" data-width="fit" data-style="btn-default" data-live-search="true" onChange='document.holiday.submit();'>
                                                    <?
                                                    if ($vocation == '1') {
                                                      //寒暑假
                                                      if (@$_POST['vtype'] == '06' || @$_POST['vtype'] == '21' || @$_POST['vtype'] == '22') {
                                                        //休假、寒暑休
                                                        for ($i = 12; $i <= 16; $i = $i + 4){
                                                          echo "<option value='$i' " . ((@$_POST['etime'] == $i) ? 'selected' : '') . ">" . $i . "</option>";
                                                        }
                                                        echo "<option value='1630'" . ((@$_POST['etime'] == '1230') ? 'selected' : '') . ">16:30</option>"; //10201 add
                                                      }
                                                      else {
                                                        for ($i = $bt + 1; $i <= $et + 1; $i++){
                                                          echo "<option value='$i' " . ((@$_POST['etime'] == $i) ? 'selected' : '') . ">" . $i . "</option>";
                                                          if ($i >=12){
                                                            $bi = $i . "30";
                                                            echo "<option value='$i' " . ((@$_POST['etime'] == $i) ? 'selected' : '') . ">" . $i . "</option>";
                                                          }
                                                        }//for
                                                      }//else
                                                    }
                                                    else {
                                                      //正常時間
                                                      if (@$_POST['vtype'] == '06' || @$_POST['vtype'] == '21' || @$_POST['vtype'] == '22') {
                                                        for ($i=12 ; $i <= 17; $i = $i + 5){
                                                          echo "<option value='$i' " . ((@$_POST['etime'] == $i) ? 'selected' : '') . ">" . $i . "</option>";
                                                        }
                                                      }
                                                      else {
                                                        for ($i = $bt + 1; $i <= $et + 1; $i++){
                                                          echo "<option value='$i' " . ((@$_POST['etime'] == $i) ? 'selected' : '') . ">" . $i . "</option>";
                                                        }//for
                                                      }//else
                                                    }
                                                    ?>
			                                  							</select>時
																										</td>
			                                  					</tr>
																									<?
																									if (in_array(@$_POST['vtype'], array('01', '02', '03', '06', '15', '17', '21', '22') )
                                                  || @$_POST['depart'] =='M47' || @$_POST['depart'] == 'N20' || substr(@$_POST['depart'], 0, 2)=='M6'){ ?>
																									<tr>
  																									<td class='td1'>奉派文號或提簽日期或填「免」</td>
  																									<td><input type="text" name="permit" size="30" value=<?=@$_POST["permit"]?> ></td>
																								    <td class='td1'>差假合計日數是否含例假日</td>
																										<td>
																										<?
                                                    if (@$_POST['saturday'] == '1'){
                                                      ?>
                                                      <input type='radio' name='saturday' value='1' checked>是
                                                      <?
                                                    }
																										 else {
                                                       ?>
                                                      <input type='radio' name='saturday' value='1' checked>是
                                                       <?
                                                     }

                                                     if (@$_POST['saturday'] == '0'){
                                                       ?>
                                                       <input type='radio' name='saturday' value='0' checked>否
                                                       <?
                                                     }
 																										 else {
                                                        ?>
                                                       <input type='radio' name='saturday' value='0' checked>否
                                                        <?
                                                      }
                                                      ?>
																										</td>
																									</tr>
  																				          <?
                                                    }
                                                    ?>
			                                  					<tr>
																										<td class="td1">差假期間是否有課</font></td>
																										<td>
                                                    <?
                                                      if (@$_POST['haveclass'] == '1')
                                          						  echo "<input type='radio' name='haveclass' value='1' checked >是";
                                          						else
                                          						  echo "<input type='radio' name='haveclass' value='1'  >是";

                                          						if (@$_POST['haveclass'] == '0')
                                          						  echo "<input type='radio' name='haveclass' value='0' checked >否";
                                          						else
                                          						  echo "<input type='radio' name='haveclass' value='0'  >否";
                                                    ?>
																										</td>
																										<td class="td1">是否出國</td>
			                                  						<td>
                                                    <?
                                                      if (@$_POST['abroad'] == '1')
                                          						  echo "<input type='radio' name='abroad' value='1' onClick='document.holiday.submit();' checked>是";
                                          						else
                                          						  echo "<input type='radio' name='abroad' value='1'   onClick='document.holiday.submit();'>是";

                                          						if (@$_POST['abroad'] == '0')
                                          						  echo "<input type='radio' name='abroad' value='0' onClick='document.holiday.submit();' checked> 否";
                                          						else
                                          						  echo "<input type='radio' name='abroad' value='0'   onClick='document.holiday.submit();'> 否";
                                                    ?>
			                                  						</td>
			                                  					</tr>
                                                  <?
                                                    if (@$_POST['vtype'] == '06'){
                                        					?>
                                        					<tr>
                                        						<td><span style="color: darkred;"> 是否刷國民旅遊卡</span></td>
                                        						<td>
                                        					<?
                                                    if (@$_POST['trip'] == '1')
                                        						  echo "<input type='radio' name='trip' value='1' checked >是";
                                        						else
                                        						  echo "<input type='radio' name='trip' value='1'>是";

                                        						if (@$_POST['trip']  == '0')
                                        						  echo "<input type='radio' name='trip' value='0' checked >否";
                                        						 else
                                        						  echo "<input type='radio' name='trip' value='0'>否";
                                                  ?>
                                        						</td>
                                        					</tr>
                                                  <?
                                                }// if (@$_POST['vtype'] == '06')

                                      					if (@$_POST['vtype'] =='01' || @$_POST['vtype'] == '02' || @$_POST['vtype'] == '03'){
                                      						$place = array('請選擇或填寫', '基隆市', '台北市', '新北市', '桃園市', '新竹縣', '新竹市',
                                                  '苗栗縣', '台中市', '彰化縣', '彰化市', '南投縣', '雲林縣', '嘉義縣',
                                                  '嘉義市', '台南市', '高雄市', '屏東縣', '宜蘭縣', '花蓮縣', '台東縣',
                                                  '連江縣', '澎湖縣', '金門縣', '自己輸入');
                                      						echo "<tr>"; //此段 logic changed by liru
                                      						echo "<td class='td1'><span style='color: darkred;''>出差(公假)地點</span></td>";
                                      						echo "<td colspan='3'>";
                                      						if(@$_POST['abroad'] == '0') //未出國
                                      						{
                                      	            if (@$_POST['eplace'] == '自己輸入'){
                                      								echo	"<input type='text' name='eplace' value='" . @$_POST['eplace']."'>";
                                      								echo "</td>";
                                      							}
                                      							else {
                                      								echo "<select class='selectpicker' name='eplace' data-width='fit' data-style='btn-default' data-live-search='true' onChange='document.holiday.submit();'>";//liru move
                                      								for ($i = 0; $i < count($place); $i++)
                                      								  echo "<option value='". $place[$i]. "'". ((@$_POST['eplace'] == $place[$i]) ? 'selected' : '') . ">" . $place[$i] . "</option>";
                                      								echo "</select>";
                                                    }
                                      						}
                                      						else{
                                      						  echo	"<input type='text' name='eplace' value='" . @$_POST['eplace'] . "'>";
                                      						  echo	"</td>";
                                      						}

                                      						  //echo	"<td align='center'><font color='darkred'>是否使用研發處經費</td>";
                                      						  //echo	"<td align='center'>";
                                      						 // echo	"<input type='radio' name='research' value='1'"; if($_SESSION["research"]==1) echo checked; echo ">是";
                                      						  //echo	"<input type='radio' name='research' value='0'"; if($_SESSION["research"]==0) echo checked; echo ">否";
                                      						  //echo	"</td>";
                                      						  //103/10/17 麗秋要求取消"是否使用研發處經費"選項
                                      						  echo "<input type='hidden' name='research' value='0'>";
                                      						  echo	"</tr>";
                                        					}
                                                            //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
                                        				    if (@$_POST['vtype'] == '11'){  //補休
                                        					?>
                                                  <tr>
                                        						<td class="td1"><span style="color: darkred;">可補休之加班時數</span></td>
                                        						<td colspan="3">
                                        						<?
                                        						  $sql = "SELECT over_date,nouse_time,
                                        					            substr(over_date,1,3) || '/' || substr(over_date,4,2) || '/' || substr(over_date,6,2) over_date2
                                        						          FROM   overtime
                                            								  WHERE  empl_no='$empl_no'
                                            								  AND    person_check='1'
                                            								  AND    nouse_time > 0
                                            								  AND    due_date >= '$vocdate'
                                            								  ORDER BY over_date";
                                        						  $data = $db -> query($sql);
                                        						   //echo "<option value=''>請選擇</option>";
                                        						  $f = 0;
                                        						  for ($i = 0; $i < count($data['OVER_DATE']); $i++){
                                          							$over  = $dat['OVER_DATE'][$i];
                                          							$over2 = $dat['OVER_DATE2'][$i];
                                          							$nouse = $dat['NOUSE_TIME'][$i];
                                                        if ( ($i + 1) % 5 == 0)
                                         				   			  echo $over2 .'('.$nouse .')<br>';
                                        							  else
                                         				   			  echo $over2 .'('.$nouse .')';
                                                      }
                                        						?>
                                        						<br>
                                                    <span style="color: darkred;">
                                        						以上顯示資料格式：加班日期(可補休剩餘時數)。<br>
                                        						注意！人事室審核過的加班日期才會顯示，系統自動由最前面的日期扣除補休之時數。
                                                    </span>
                                                    </td>
                                        				   </tr>
                                                           <?
                                                            //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
                                        					} ?>
			                                  					<tr>
																										<td class="td1">備註</td>
																									  <td colspan="3">
																											<input type="text" class="form-control" size="50" name="mark" value="" maxlength="50" placeholder="限50個中文字">
																										</td>
			                                  					</tr>
			                                  					<tr>
			                                  						<td colspan="4" style="text-align: center;">
			                                  							<button type="submit" class="btn btn-default">確 定</button>
			                                  							<button type="reset" class="btn btn-default">重 填</button>
			                                  						</td>
			                                  					</tr>
			                                  					<tr>
			                                  						<td colspan="4">
																											<div class="alert alert-warning">
							                                            <i class="fa fa-warning" style="float:left"></i>
							                                            <ul>
							                                                <li>
							                                                差假注意事項
																																<ol>
																																	<li>參加<span style="color: red;">國內訓練或講習性質</span>之各項<span style="color: red;">研習會</span>、
																																		<span style="color: red;">座談會</span>、<span style="color: red;">研討會</span>、
																																		<span style="color: red;">檢討會</span>、<span style="color: red;">觀摩會</span>、
																																		<span style="color: red;">說明會</span>等，假別種類請點選<span style="color: red;">公假（受訓或研習）</span>。
																																	<li>出差期間及行程，應視事實之需要，儘量利用便捷之交通工具縮短行程。往返行程，以不超過一日為原則。
																																	<li>監試等任務之「公假」，假別種類請點選「公假（監考、試務等工作）」。
																																</ol>
							                                                </li>
							                                            </ul>
							                                        </div>
			                                  						</td>
			                                  					</tr>
			                                            <tbody id="_content">

			                                            </tbody>
			                                        </table>
			                                        <div id="loading" class="text-center" style="display:none">
			                                            <img src="images/loading.gif">
			                                        </div>
			                                    </div>
			                                </div>
			                            </div>
																</div>
															</fieldset>
                            </form>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
