<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
        <?
        $userid = $_SESSION['_ID'];
        $empl_no = $_SESSION['empl_no'];
        $empl_name = $_SESSION['empl_name'];
				$title_id = $_SESSION['title_id'];
        $depart = $_SESSION['depart'];

        $today  = getdate();
        $year = $today["year"] - 1911;
        $month = $today["mon"];
        $day = $today["mday"];

        $vocdate = '';

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
                                            <!-- 特休之類的訊息 -->
                                            <div class="alert alert-info">
                                                <i class="fa fa-info" style="float:left"></i>
                                                  <ul>
                                                  </ul>
                                            </div>
    			                                </div>
    			                                <div class="panel panel-primary">
    			                                    <div class="panel-heading">
    			                                        國立彰化師範大學 教職員請假/出差作業
    			                                    </div>
    			                                    <div class="panel-body">
    			                                        <table class="table table-condensed table-hover table-bordered">
    			                                  					<tr>
      			                                  						<td class="td1">員工編號</td>
      			                                              <td><span id="empl_no"><?=$empl_no ?></span></td>
      			                                              <td class="td1">姓名</td>
      			                                              <td><span id="empl_name"><?=$empl_name ?></span></td>
    			                                  					</tr>
    			                                  					<tr>
      			                                  						<td class="td1">請選擇單位</td>
      			                                              <td>
  																													<select name="depart" id="qry_dept" class="form-control">
                                                            </select>
      			                                              </td>
      			                                  						<td class="td1">職稱</td>
      			                                  						<td id="qry_title"><input type="hidden" name="title" value="<?=$title_id ?>" ></td>
    			                                  					</tr>
    			                                  					<tr>
    			                                  						<td class="td1">假別</td>
    			                                  						<td>
																													<select name="vtype" id="qry_vtype" class="form-control">
    			                                  							</select>
    			                                  						</td>
    			                                  						<td class="td1">職務代理人</td>
    			                                              <td>
                                                          <!-- 根據目前的empl_no找出所屬部門的所有代理人與其代理人號碼 -->
                                                          <select name="agentno" id="qry_agentno" class="form-control">
    			                                                </select>
                                                        </td>
    			                                  					</tr>
                                            					<tr id="agent_depart">
                                      						      <td class="td1"><span style="color: red;">請選職務代理人單位</span></td>
                                            						<td colspan="3">
                                                            <select name="depart" id="qry_agent_depart" class="form-control">
                                              						  </select>
                                                        </td>
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
      																									<td><input type="text" name="permit" size="30"></td>
    																								    <td class='td1'>差假合計日數是否含例假日</td>
    																										<td>
                                                          <input type='radio' name='saturday' value='1'>是
                                                          <input type='radio' name='saturday' value='0' checked>否
    																										</td>
    																									</tr>
      																				          <?
                                                        }
                                                        ?>
    			                                  					<tr>
    																										<td class="td1">差假期間是否有課</td>
    																										<td>
                                              						<input type='radio' name='haveclass' value='1'>是
                                              						<input type='radio' name='haveclass' value='0' checked>否
    																										</td>
    																										<td class="td1">是否出國</td>
    			                                  						<td>
                                              						<input type='radio' name='abroad' value='1'>是
                                              						<input type='radio' name='abroad' value='0' checked>否
    			                                  						</td>
    			                                  					</tr>
                                            					<tr id="trip">
                                            						<td class="td1"><span style="color: red;">是否刷國民旅遊卡</span></td>
                                            						<td>
                                            						  <input type='radio' name='trip' value='1'>是
                                            						  <input type='radio' name='trip' value='0' checked>否
                                            						</td>
                                            					</tr>
                                                      <?

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
                                                      <tr id="nouse">
                                            						<td class="td1"><span style="color: red;">可補休之加班時數</span></td>
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
                                                        <span style="color: red;">
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
