<?
session_start();
$today  = getdate();
$year    = $today["year"] - 1911;
$month = $today["mon"];
$day     = $today["mday"];

(@!$_POST["byear"])  ? $byear=$year   : $byear=$_POST["byear"];
(@!$_POST["bmonth"]) ? $bmonth=$month : $bmonth=$_POST["bmonth"];
(@!$_POST["bday"])   ? $bday=$day     : $bday=$_POST["bday"]; //$bday-->temp

(@!$_POST["eyear"])  ? $eyear =$year   : $eyear =$_POST["eyear"];
(@!$_POST["emonth"]) ? $emonth=$month  : $emonth=$_POST["emonth"];
(@!$_POST["eday"])   ? $eday  =$day    : $eday  =$_POST["eday"];

//--------------------------------------------------------------------------------------------
// 值是否有異動? session 未設 或 值有異動時 session 都要重給
//--------------------------------------------------------------------------------------------
$_SESSION['bmonth'] = $_SESSION['byear'] =
$_SESSION['bday'] = $_SESSION['btime'] =
$_SESSION['eyear'] = $_SESSION['emonth'] = $_SESSION['eday'] = $_SESSION['abroad'] = "";

	if ($_SESSION["byear"]==""  || ($_SESSION['byear'] != ""  and  $_POST["byear"] != ""))
	    $_SESSION["byear"]=$byear;
	if ($_SESSION["bmonth"]==""  || ($_SESSION['bmonth'] != ""  and  $_POST["bmonth"] != ""))
	    $_SESSION["bmonth"]=$bmonth;
	if ($_SESSION["bday"]==""  || ($_SESSION['bday'] != ""  and  $_POST["bday"] != ""))
	    $_SESSION["bday"]=$bday;

	if ($_SESSION["btime"]=="" || ($_SESSION['btime'] != "" and  $_POST["btime"] != ""))
        $_SESSION["btime"]=@$btime;

	if ($_SESSION["eyear"]==""  || ($_SESSION['eyear'] != ""  and  $_POST["eyear"] != ""))
	    $_SESSION["eyear"]=$eyear;
	if ($_SESSION["emonth"]==""  || ($_SESSION['emonth'] != ""  and  $_POST["emonth"] != ""))
	    $_SESSION["emonth"]=$emonth;
	if ($_SESSION["eday"]==""  || ($_SESSION['eday'] != ""  and  $_POST["eday"] != ""))
	    $_SESSION["eday"]=$eday;

	if ($_SESSION["etime"]== "" || ($_SESSION['etime'] != "" and  $_POST["etime"] != ""))
	    $_SESSION["etime"]=@$etime;

	if ($_SESSION["abroad"]=="" || ($_SESSION['abroad'] != "" and  $_POST["abroad"] != ""))
	    $_SESSION["abroad"]=@$abroad;//是否出國

	//------------------------------------------------------------------
?>
<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <div class="row">
                            <form id="holiday" class="form-horizontal" name="holiday_form" action="" method="POST">
															<fieldset>
																<div class="form-group">
																	<div class="col-lg-12">
			                                <div id="message">
			                                </div>
			                                <div class="panel panel-primary">
			                                    <div class="panel-heading">
			                                        國立彰化師範大學 教職員請假/出差作業
			                                    </div>
			                                    <div class="panel-body">
			                                        <table class="table table-condensed table-hover table-bordered">
			                                  					<tr>
			                                  						<td class="td1">員工編號</td>
			                                              <td><input type="hidden" name="userid">0000676</td>
			                                              <td class="td1">姓名</td>
									                                  <td><input type='hidden' name='name'>李_朗</td>
			                                  					</tr>
			                                  					<tr>
			                                  						<td class="td1">請選擇單位</td>
			                                              <td>
			                                                <select class="selectpicker" data-style="btn-default" name='depart' onChange='document.holiday.submit();'>
			                                                  <option value=''>請選擇</option>
			                                                  <option value=MQ5 selected>圖書與資訊處系統開發組</option>
			                                                </select>
			                                              </td>
			                                  						<td class="td1">職稱</td>
			                                  						<td><input type='hidden' name='title' value='$title'>技正</td>
			                                  					</tr>
			                                  					<tr>
			                                  						<td class="td1">假別</td>
			                                  						<td>
			                                  							<select class="selectpicker" data-style="btn-default" id="leave">
			                                                  <option value=** selected>假別種類</option>
			                                                  <option value=01>出差</option>
			                                                  <option value=02>公假(受訓或研習)</option>
			                                                  <option value=03>公假(非受訓或研習)</option>
			                                                  <option value=04>事假</option>
			                                                  <option value=05>病假</option>
			                                                  <option value=06>休假(教職員)</option>
			                                                  <option value=07>婚假</option>
			                                                  <option value=08>娩假</option>
			                                                  <option value=09>喪假</option>
			                                                  <option value=10>天災假</option>
			                                                  <option value=11>加班補休</option>
			                                                  <option value=12>公傷</option>
			                                                  <option value=14>陪產假</option>
			                                                  <option value=15>流產假</option>
			                                                  <option value=16>產前假</option>
			                                                  <option value=17>延長病假</option>
			                                                  <option value=21>暑休</option>
			                                                  <option value=22>寒休</option>
			                                                  <option value=23>特休(勞基法)</option>
			                                                  <option value=24>勞動節(勞基法)</option>
			                                                  <option value=25>寒假(未兼行政教師出國旅遊)</option>
			                                                  <option value=26>暑假(未兼行政教師出國旅遊)</option>
			                                                  <option value=27>公假(監考、試務工作等)</option>
			                                                  <option value=28>出國(進修研究、休假研究)</option>
			                                                  <option value=29>例假日出國</option>
			                                                  <option value=30>家庭照顧假</option>
			                                                  <option value=31>事假(因故無法參加學校慶典)</option>
			                                                  <option value=32>生理假</option>
			                                                  <option value=33>原住民歲時祭放假</option>
			                                                  <option value=34>其他(留職停薪..)</option>
			                                                  <option value=35>產檢假(勞基法)</option>
			                                                  <option value=36>公出(短程公務外出)</option>
			                                  							</select>
			                                  						</td>
			                                  						<td class="td1">職務代理人</td>
			                                              <td>
			                                                <select class="selectpicker" data-style="btn-default" name='agentno' onChange='document.holiday.submit();'>
			                                                  <option value='' selected="">請選擇</option>
			                                                  <option value='0000929'>陳_德</option>
			                                                  <option value='0001083'>林_銘</option>
			                                                  <option value='0000845'>韋_忠</option>
			                                                  <option value='0001060'>施_男</option>
			                                                  <option value='0001077'>何_叡</option>
			                                                  <option value='5000852'>洪_賢</option>
			                                                  <option value='7000200'>許_維</option>
			                                                  <option value='7000279'>胡_菁</option>
			                                                  <option value='0000000'>其它單位</option>
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
