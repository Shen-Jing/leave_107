<?
$today  = getdate();
$year    = $today["year"] - 1911;
$month = $today["mon"];
$day     = $today["mday"];
?>
<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
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
																										<?
																										  $sql = "SELECT dept_no,dept_full_name
																														FROM  psfcrjb, stfdept
																														WHERE crjb_empl_no = '$empl_no'
																														AND   crjb_quit_date IS NULL
																														AND  crjb_depart = dept_no
																														ORDER BY crjb_seq DESC";
																										?>
			                                              <td>
			                                                <?=$_SESSION['dept_name'][0] ?>
			                                              </td>
			                                  						<td class="td1">職稱</td>
																										<?
																										//抓職稱名稱，根據單位有不一樣的職稱  103.04.22 加上  and   crjb_quit_date is null(舊記錄保留，防抓到)
																										$depart = $_SESSION['depart'][0];
																										$sql = "SELECT code_chn_item, code_field
																															FROM  psfcrjb, psqcode
																															where  crjb_empl_no = '$empl_no'
																															and      crjb_quit_date IS NULL
																															and      crjb_depart = '$depart'
																															and      code_kind = '0202'
																															and      code_field = crjb_title";
																										$data = $db -> query_array($sql);
																										$tname = $data['CODE_CHN_ITEM'][0];
																										$title_code = $data['CODE_FIELD'][0];
												                            // $_SESSION[title_name]=$tname;
												                            // $_SESSION[title]=$title_code;
																										?>
			                                  						<td><input type='hidden' name='title' value='<?=$_SESSION['title'] ?>'><?=$tname ?></td>
			                                  					</tr>
                                                  <?
                                        					 if (@$_POST["agentno"] == '0000000' or @$_POST['agent_flag'] == '1' ) {
                                        					   @$_POST['agent_flag'] = '1';
                                                     ?>
                                        					<tr>
                                        						 <td class="td1"><span style="color: red;">請選職務代理人單位</span></td>
                                        						<?
                                      						      $sql = "SELECT dept_no,dept_full_name
                                      											FROM stfdept
                                      											where use_flag is null
                                      											and  substr(dept_no, 1, 1) between 'A' and 'Z'
                                      											order by  dept_no";
                                                        $data_1 = $db -> query_array($sql);

                                      							    $sql = "SELECT dept_no,dept_full_name
                                      											FROM stfdept
                                      											where use_flag is null
                                      											and  substr(dept_no, 1, 1) between '0' and '9'
                                      											order by  dept_no";
                                                        $data_2 = $db -> query_array($sql);

                                        						?>
                                        						 <td class="td1" colspan="3">
                                        	              <select name='agent_depart' onChange='document.holiday.submit();'>
                                        						    <option value=''>請選擇</option>
                                                    <?
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
			                                  							<select class="selectpicker" data-style="btn-default" name="vtype" id="leave" onChange='document.holiday.submit();'>
																											<?
																										  $sql = "SELECT code_field, code_chn_item
																											FROM psqcode
																											where code_kind = '0302'
																											order by code_field";
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
			                                  						<td class="td1">職務代理人</td>
			                                              <td>
			                                                <select class="selectpicker" data-style="btn-default" name='agentno' onChange='document.holiday.submit();'>
			                                                  <option value='' selected="">請選擇</option>
																												<?
																												// 根據目前的empl_no找出所屬部門的所有代理人與其代理人號碼
																												if (@$_POST['agentno'] == '0000000' or @$_POST['agent_flag'] == '1'){
																														$sql = "SELECT  empl_no, empl_chn_name
																																	FROM   psfempl, psfcrjb
																																	where empl_no = crjb_empl_no
																																	and   crjb_quit_date is null
																																	and   crjb_depart = '$depart'
																																	and   substr(empl_no, 1, 1) in ('0', '7', '5', '3', '4')
																																	and   empl_no != '$empl_no'
																																	order by crjb_depart, crjb_title, crjb_empl_no";
																												}
																												else {
																													$sql = "SELECT  empl_no, empl_chn_name
																																FROM   psfempl, psfcrjb
																																where empl_no = crjb_empl_no
																																and   crjb_quit_date IS NULL
																																and   crjb_depart = '$depart'
																																and   (substr(crjb_title, 1, 1) != 'B' or
																																	   crjb_title = 'B60')
																																and   substr(empl_no, 1, 1) in ('0', '7', '5', '3', '4')
																																and   empl_no != '$empl_no'
																																order by crjb_depart, crjb_title, crjb_empl_no";
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
			                                                <select class="selectpicker" data-style="btn-default" name='byear' onChange='document.holiday.submit();'>
			                                    						<?
			                                    						for ($i = $year - 1; $i <= $year + 1; $i++)
			                                                	echo "<option value='$i'" . (($_SESSION['byear'] == $i) ? 'selected' : '') . ">$i</option>";
			                                    						?>
			                                                </select>年
			                                                <select class="selectpicker" data-style="btn-default" name='bmonth' onChange='document.holiday.submit();'>
			                                                <?
																											// 若跨年
			                                    						if ($_SESSION["byear"] > $year)
			                                    						   for ($i = 1; $i <= 12; $i++)
																												 		echo "<option value='$i'" . (($_SESSION['bmonth'] == $i) ? 'selected' : '') . ">$i</option>";
			                                    						else
			                                    						   for ($i = 1; $i <= 12; $i++)
																												 		echo "<option value='$i'" . (($_SESSION['bmonth'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
																											</select>月

																											<?
			                                    						$monthday = array("31", "28", "31", "30", "31", "30", "31", "31", "30", "31", "30", "31");

			                                    						$bmd = $monthday[$_SESSION["bmonth"] - 1];	//開始該月的日數
			                                    						if ($_SESSION["bmonth"] == 2 && date('L', mktime(0, 0, 0, 1, 1, $_SESSION["byear"])) )//閏年且為二月
			                                    							$bmd = $bmd + 1;

			                                    						$emd = $monthday[$_SESSION["emonth"] - 1];	//結束該月的日數
			                                    						if ($_SESSION["emonth"] == 2 && date('L', mktime(0, 0, 0, 1, 1, $_SESSION["byear"])) )//閏年且為二月
			                                    							$emd = $emd + 1;
																											?>
			                                    						<select class="selectpicker" data-style="btn-default" name='bday' onChange='document.holiday.submit();'>
																											<?
																											//
			                                    						if ($_SESSION["bmonth"] == $month && $_SESSION["byear"] == $year)
			                                    								for ($i = 1; $i <= $bmd; $i++)
																												 		echo "<option value='$i'" . (($_SESSION['bday'] == $i) ? 'selected' : '') . ">$i</option>";
			                                    						else
																													for ($i = 1; $i <= $bmd; $i++)
																												 		echo "<option value='$i'" . (($_SESSION['bday'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
			                                    						</select>日

			                                    					</td>
		                                  							<td class="td1">請假開始時間</td>
			                                  						<td>
			                                  							<select class="selectpicker" data-width="fit" data-style="btn-default" id="leave_start_h" data-live-search="true">
																												<option value='8' selected>8</option>
																												<option value='9'>9</option>
																												<option value='10'>10</option>
																												<option value='11'>11</option>
																												<option value='12'>12</option>
																												<option value='13'>13</option>
																												<option value='14'>14</option>
																												<option value='15'>15</option>
																												<option value='16'>16</option>
			                                  							</select>時
			                                  						</td>
			                                  					</tr>
			                                  					<tr>
			                                  						<td class="td1">請假結束日期</h3>
			                                  						</td>
																										<td>
																											<select class="selectpicker" name='eyear' onChange='document.holiday.submit();'>
																											<?
																											for ($i = $byear; $i <= ($year + 1); $i++)
																												echo "<option value='$i'" . (($_SESSION['eyear'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
																											</select>年

																											<select class="selectpicker" name='emonth' onChange='document.holiday.submit();'>
																											<?
																											if ($_SESSION["eyear"] > $_SESSION["byear"])//跨年
																												for ($i = 1; $i <= 12; $i++)
																													echo "<option value='$i'" . (($_SESSION['emonth'] == $i) ? 'selected' : '') . ">$i</option>";
																											else//同年
																												for ($i = $_SESSION["bmonth"]; $i <= 12; $i++)
																													echo "<option value='$i'" . (($_SESSION['emonth'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
																											</select>月

																											<select class="selectpicker" name='eday' onChange='document.holiday.submit();'>
																											<?
																											if ($_SESSION["emonth"] == $_SESSION["bmonth"] && $_SESSION["eyear"] == $_SESSION["byear"]) //同年同月開始且結束
																									   		for ($i = $_SESSION["bday"]; $i <= $emd; $i++)
																										 			echo "<option value='$i'" . (($_SESSION['eday'] == $i) ? 'selected' : '') . ">$i</option>";
																											else
																												for ($i = 1; $i <= $emd; $i++)
																													echo "<option value='$i'" . (($_SESSION['eday'] == $i) ? 'selected' : '') . ">$i</option>";
																											?>
																											</select>日
																										</td>
																							      <td class="td1">請假結束時間</td>
																								   	<td>
																											<select class="selectpicker" data-width="fit" data-style="btn-default" id="leave_start_h" data-live-search="true">
																												<option value='8' selected>8</option>
																												<option value='9'>9</option>
																												<option value='10'>10</option>
																												<option value='11'>11</option>
																												<option value='12'>12</option>
																												<option value='13'>13</option>
																												<option value='14'>14</option>
																												<option value='15'>15</option>
																												<option value='16'>16</option>
			                                  							</select>時
																										</td>
			                                  					</tr>
																									<?
																									if (in_array(@$_POST['vtype'], array('01', '02', '03', '06', '15', '17', '21', '22') )
                                                  || @$_POST['depart'] =='M47' || @$_POST['depart'] == 'N20' || substr(@$_POST['depart'], 0, 2)=='M6'){ ?>
																									<tr>
  																									<td class='td1'>奉派文號或提簽日期或填'免'</td>
  																									<td><input type='text' name='permit' size='30'  value=<?=@$_POST["permit"]?> ></td>
																								    <td class='td1'>差假合計日數是否含例假日</td>
																										<td>
																										<?
                                                    if (@$_POST['saturday'] == '1'){
                                                      ?>
                                                      <input type='radio' name='saturday' value='1' checked >是
                                                      <?
                                                    }
																										 else {
                                                       ?>
                                                      <input type='radio' name='saturday' value='1' checked >是
                                                       <?
                                                     }

                                                     if (@$_POST['saturday'] == '0'){
                                                       ?>
                                                       <input type='radio' name='saturday' value='0' checked >否
                                                       <?
                                                     }
 																										 else {
                                                        ?>
                                                       <input type='radio' name='saturday' value='0' checked >否
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
																										<td >
																											<input type='radio' name='haveclass' value='1'>是
																											<input type='radio' name='haveclass' value='0' checked >否
																										</td>
																										<td class="td1">是否出國</td>
			                                  						<td>
																											<input type='radio' name='abroad' value='1' onClick='document.holiday.submit();'>是
																											<input type='radio' name='abroad' value='0' onClick='document.holiday.submit();' checked>否
			                                  						</td>
			                                  					</tr>
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
