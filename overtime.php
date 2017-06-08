<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
<style>
	h3{
		font-family: "微軟正黑體";
	}
	li{
		font-family: "微軟正黑體";
	}
	table{
		font-family: "微軟正黑體";
	}

</style>
<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid" >
		<? include ("inc/page-header.php"); ?>

		<div class="panel panel-primary">
			<div class="panel-heading" style="text-align:left">
			    加班申請作業
			</div>
			<div class="panel-body panel-height">
				<form data-toggle="validator" class="form-horizontal" role="form" name="holiday" id="holiday" action="<?=$_SERVER['PHP_SELF'] ?>" method="post"   ENCTYPE="multipart/form-data">
				<table class="table table-bordered" id="table1">
						<tr>
							<div class="row">
								<td class="col-xs-2 col-md-1 td1" align="center">員工編號</td>
								<td class="col-xs-2 col-md-2" id="empl_no"></td>
								<td class="col-xs-1 col-md-1 td1" align="center">姓名</td>
								<td class="col-xs-2 col-md-2" id="empl_name"></td>
							</div>
						</tr>

						<tr>
							<div class="row">
								<td class="col-xs-2 col-md-1 td1" align="center"> 單位</td>
								<td class="col-xs-2 col-md-2" id="dname"></td>
								<td class="col-xs-1 col-md-1 td1" align="center"> 職稱</td>
								<td class="col-xs-2 col-md-2" id="tname"></td>
							</div>
						</tr>

					<tr>
						<div class="row">
							<td class="col-md-1 td1" align="center">加班原因</td>
							<td class="col-md-1" colspan=3>
								<div class='form-group'>
									<div class="row">
										<div class="col-md-12">
											<div class="col-xs-3 col-md-2">
												<span>加班簽呈日期：</span>
											</div>
											<div class="col-xs-8 col-md-2">
												<input type='text' class="form-control" id='signed_date' name="signed_date" readonly="true">
											</div>
											<div class="col-xs-5 col-md-5">
												<font size='2' color='darkred'>(學校統一加班無提簽日期者，請以加班日期代替) </font>
											</div>
										</div>
									</div>
								</div>

								<div class='form-group has-feedback'>
									<div class="row">
										<div class="col-md-12">
											<div class="col-xs-3 col-md-2">
												<span>加班簽呈文號：</span>
											</div>
											<div class="col-xs-8 col-md-2">
												<input type="text" class="form-control" name="reason" id="reason" value="" size="25" maxlength="30" >
											</div>
											<div class="col-xs-5 col-md-5">
												<font size='2' color='darkred' > (學校統一加班無提簽文號者，請說明加班原因)</font>
											</div>
										</div>
									</div>
								</div>
								<div style="font-size:15px">　<input type="radio" name="pay_type" value="1" checked style="border:none">6個月內補休
										<input type="radio" name="pay_type" value="2"  style="border:none" onclick="javascript:alert('因本校無該項經費及預算，請勾選加班補休。');
										holiday.pay_type[0].checked='true';">請領加班費
								</div>
							</td>

						</div>
					</tr>

					<tr>
						<td class="col-md-1 td1" align="center">加班日期</td>
						<td colspan="3" style="position: relative">
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<font>從</font>
											<input type='text' class="form-control" id='begin_time' name="begin_time" readonly="true" style='width:auto; display: inline-block;'>
										</div>

										<div class="col-md-6">
											<font>到</font>
											<input type='text' class="form-control" id='end_time' name="end_time" readonly="true" style='width:auto; display: inline-block;'>
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>

					<tr>
						<td class="col-md-1 td1" align="center">加班刷卡時間</td>
						<td class="col-md-3">
							<div class='form-group'>
								<div class="row">
									<div class='col-md-6'>
										<font color='darkred' size='2'>開始加班刷卡資料</font>
										<select class='form-control' style='width:auto; display: inline-block;' id="btime" name='btime'></select>
									</div>
									<div class="col-md-6">
										<font color='red' size='2'>1.例假日請選實際刷進時間。<br>
									                             2.上班日請選「得下班時間」<br>
																 例如：8:11上班則選17:11</font>
									</div>
								</div>
							</div>
						</td>

						<td class="col-md-2" colspan="2">
							<div class='form-group'>
								<div class="row">
									<div class='col-md-6'>
										<font color='darkred' size='2'>結束加班刷卡資料</font>
										<select class='form-control' style='width:auto; display: inline-block;' id='etime' name='etime' ></select>
									</div>
									<div class="col-md-6">
										<font color='red' size='2'>1.請選實際刷退時間<br>
																	2.畢業典禮「等」不必刷退請選17:00</font>
									</div>
								</div>
							</div>
					  </td>
					</tr>
					<tr>
						<td colspan="4" align="center">
							<button type="submit" class="btn btn-primary" name="check" >送出計算</button>
							<!-- onclick='timesum();' -->
						</td>
					</tr>
					<tr>
						<td colspan="4">

						    <div class="alert alert-warning">
						    	<i class="fa fa-warning">
						    		注意!
						    		<ol>
						    			<li>
						    			    加班應事先以書面專案簽准。
						    			</li>
						    			<li>
						    			    請於加班刷卡紀錄寫入差假系統之"上下班之刷卡資料"後再進行加班申請作業。
						    			</li>
						    			<li>
						    			    加班申請需人事室審核通過後方能申請補休。
						    			</li>
						    			<li>
						    			    加班時數應由六個月內補休完畢，並以"時"為計算單位。
						    			</li>
						    			<li>
						    			    除加班過凌晨可跨日外，其餘均以一天為單位分次申請。
						    			</li>
						    		</ol>
						    	</i>
						    </div>

						</td>
					</tr>
					<!-- </thead> -->

				</table>
			</div>
		</form>
		</div>

	</div>
		</div>
	    <!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php"); ?>
