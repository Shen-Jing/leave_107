<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
        <?
        $userid = $_SESSION['_ID'];
        $empl_no = $_SESSION['empl_no'];
        $empl_name = $_SESSION['empl_name'];
				$title_id = $_SESSION['title_id'];
        $depart = $_SESSION['depart'];
        ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                      <div id="message" style="display: none;">
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
                              <form name="holidayform" id="holidayform" class="form-inline col-sm-12 table-responsive" data-bv-live="enabled" data-bv-trigger="change">
                                <table class="table table-condensed table-hover table-bordered">
                          					<tr>
                                        <td id="hide-depart" style="display:none"><?=$depart ?></td>
                                        <td id="hide-serial" style="display:none"></td>
                                        <td id="hide-check" style="display:none">fe</td>
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
                                        <div class="form-group">
                                          <select name="vtype" id="qry_vtype" class="form-control">
                            							</select>
                                        </div>
                          						</td>
                          						<td class="td1">職務代理人</td>
                                      <td>
                                        <div class="form-group">
                                          <!-- 根據目前的empl_no找出所屬部門的所有代理人與其代理人號碼 -->
                                          <select name="agentno" id="qry_agentno" class="form-control">
                                          </select>
                                        </div>
                                      </td>
                          					</tr>
                          					<tr id="agent_depart">
                    						      <td class="td1"><span style="color: red;">請選職務代理人單位</span></td>
                          						<td colspan="3">
                                        <div class="form-group">
                                          <select name="agent_depart" id="qry_agent_depart" class="form-control">
                            						  </select>
                                        </div>
                                      </td>
                                    </tr>
                          					<tr>
                          						<td class="td1">請假開始日期</td>
                                      <td>
                                        <div class="form-group">
                                          <input type='text' class="form-control" id='leave-start' name="leave_start">
                                        </div>
                            					</td>
                        							<td class="td1">請假開始時間</td>
                          						<td>
                                        <span id="party" style="display: none;"></span>
                                        <span id="vocation" style="display: none;"></span>
                                        <div class="form-group">
                                          <select name="btime" id="btime" class="form-control">
                            							</select>時
                                        </div>
                          						</td>
                          					</tr>
                          					<tr>
                          						<td class="td1">請假結束日期</h3>
                                      <td>
                                        <div class="form-group">
                                          <input type='text' class="form-control" id='leave-end' name="leave_end">
                                        </div>
                            					</td>
  															      <td class="td1">請假結束時間</td>
  																   	<td>
                                        <div class="form-group">
                                          <select name="etime" id="etime" class="form-control">
                            							</select>時
                                        </div>
  																		</td>
                          					</tr>
  																	<tr id="permit-row" style="display: none;">
  																		<td class='td1'><span style="color: red;">奉派文號或提簽日期或填「免」</span></td>
  																		<td>
                                        <div class="form-group">
                                          <input type="text" class="form-control" size="50" name="permit" value="" maxlength="50"></td>
                                        </div>
  																    <td class='td1'><span style="color: red;">差假合計日數是否含例假日</span></td>
  																		<td>
                                        <input type='radio' name='saturday' value='1'>是
                                        <input type='radio' name='saturday' value='0' checked>否
  																		</td>
  																	</tr>
                          					<tr>
  																		<td class="td1"><span style="color: red;">差假期間是否有課</span></td>
  																		<td>
                            						<input type='radio' name='haveclass' value='1'>是
                            						<input type='radio' name='haveclass' value='0' checked>否
  																		</td>
  																		<td class="td1"><span style="color: red;">是否出國</span></td>
                          						<td>
                            						<input type='radio' name='abroad' value='1'>是
                            						<input type='radio' name='abroad' value='0' checked>否
                          						</td>
                          					</tr>
                          					<tr id="trip-row" style="display: none;">
                          						<td class="td1"><span style="color: red;">是否刷國民旅遊卡</span></td>
                          						<td>
                          						  <input type='radio' name='trip' value='1'>是
                          						  <input type='radio' name='trip' value='0' checked>否
                          						</td>
                          					</tr>
                                    <tr id="place-row" style="display: none;">
                                      <td class="td1"><span style="color: red;">出差/公假地點</span></td>
                                      <td>
                                        <select name="eplace" id="qry_eplace" class="form-control">
                                        </select>
  																	  <td colspan="2">
  																			<input type="text" class="form-control" size="50" name="eplace_text" value="" maxlength="50" placeholder="請自行輸入地點" disabled="true">
  																		</td>
                                    </tr>
                                    <tr id="nouse" style="display: none;">
                          						<td class="td1"><span style="color: red;">可補休之加班時數</span></td>
                                      <td colspan="3">
                                        <span id="qry_nouse"></span><br />
                                        <span style="color: red;">
                            						顯示資料格式：加班日期(可補休剩餘時數)。<br>
                            						注意！人事室審核過的加班日期才會顯示，系統自動由最前面的日期扣除補休之時數。
                                        </span>
                                      </td>
                          				  </tr>
                                    <tr id="budget" style="display: none;">
                            					<td class="td1"><span style="color: red;">經費來源</td>
                            				  <td colspan="3">
                                        <div class="form-group">
                                          <input type="text" class="form-control" size="50" name="budget" value="" maxlength="50" placeholder="限50個中文字">
                                        </div>
                            				  </td>
                                    </tr>
                                    <tr class="bus-trip">
                            				  <td colspan="4">
                                        <div class="alert alert-warning">
                                          <i class="fa fa-warning" style="float:left"></i>
                                          以下資料將提供「教師學術歷程檔案」使用
                                        </div>
                            				  </td>
                            				</tr>
                                    <tr class="bus-trip">
                          				    <td class="td1">
                                        <span style="color:blue">出差原因類型</span>
                            				  </td>
                            				  <td colspan="3">
                                        <select name='extracase' id="qry_extracase" class="form-control">
                                        </select>
                            				  </td>
                        				    </tr>
                          				  <tr class="bus-trip">
                      				        <td class="td1">
                                        <span style="color:blue">出差服務單位</span>
                      				        </td>
                      				        <td colspan="3">
                                        <div class="form-group">
                                          <input type="text" class="form-control" size="50" name="on_dept" value="" maxlength="25" placeholder="限25個中文字">
                                        </div>
                                      </td>
                          				  </tr>
                          				  <tr class="bus-trip">
                          				    <td class="td1">
                                        <span style="color:blue">出差擔任職務</span>
                          				    </td>
                          				    <td colspan="3">
                                        <div class="form-group">
                                          <input type="text" class="form-control" size="50" name="on_duty" value="" maxlength="25" placeholder="限25個中文字">
                                        </div>
        				                      </td>
                          				  </tr>
                      				      <tr class="bus-trip">
                                      <td class="td1">
                                        <span style="color:blue">事由或服務項目</span>
                                      </td>
                                      <td colspan="3">
                                        <div class="form-group">
                                          <input type="text" class="form-control" size="50" name="mark" value="" maxlength="50" placeholder="限50個中文字">
                                        </div>
                                      </td>
                      				      </tr>
                                    <tr id="bus-trip-time" style="display: none;">
                          						<td class="td1">
                                        <span style="color:blue">起訖時間</span>
                                      </td>
                                      <td colspan="3">
                                        <div class='col-md-6'>
                                          <div class="form-group">
                                            <input type='text' class="form-control" placeholder="出差開始時間" id='bus-trip-start' name="bus_trip_start">
                                          </div>
                                        </div>
                                        <div class='col-md-6'>
                                          <div class="form-group">
                                            <input type='text' class="form-control" placeholder="出差結束時間" id='bus-trip-end' name="bus_trip_end">
                                          </div>
                                        </div>
                            					</td>
                                    </tr>
                                    <tr id="depart-immig" style="display: none;">
                                      <td class="td1">
                                        <span style="color: red">出國出入境時間</span>
                                      </td>
                                      <td colspan="3">
                                        <div class='col-md-6'>
                                          <input type='text' class="form-control" placeholder="出境時間" id='depart-time' name="depart_time">
                                        </div>
                                        <div class='col-md-6'>
                                          <input type='text' class="form-control" placeholder="入境時間" id='immig-time' name="immig_time">
                                        </div>
                                      </td>
                                    </tr>
                                    <tr id="meeting-date" style="display: none;">
                                      <td class="td1">
                                        <span style="color: red">出國會議(研究)日程</span>
                                      </td>
                                      <td colspan="3">
                                        <div class='col-md-6'>
                                          <input type='text' class="form-control" placeholder="出境時間" id='meeting-start' name="meeting_start">
                                        </div>
                                        <div class='col-md-6'>
                                          <input type='text' class="form-control" placeholder="入境時間" id='meeting-end' name="meeting_end">
                                        </div>
                                      </td>
                                    </tr>
                          					<tr id="remark">
  																		<td class="td1">備註</td>
  																	  <td colspan="3">
                                        <div class="form-group">
  																			  <input type="text" class="form-control" size="50" name="remark" value="" maxlength="50" placeholder="限50個中文字">
                                        </div>
  																		</td>
                          					</tr>
                          					<tr>
                          						<td colspan="4" style="text-align: center;">
                          							<button type="submit" id="holiday_btn" class="btn btn-primary">確 定</button>
                          							<button type="reset" class="btn btn-warning" onclick="return confirm_reset();">重 填</button>
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
                              </form>
                          </div>
                          <!-- panel-body -->
                      </div>
                      <!-- panel -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
