<?
  	include 'inc/connect.php';
	include("inc/check.php");
?>


<!--=============================================================================================-->
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
		<form class="form-horizontal" role="form" name="holiday" action="<?=$_SERVER['PHP_SELF'] ?>" method="post"   ENCTYPE="multipart/form-data">
		<center>

		<center>
		<div class="panel panel-primary">
			<div class="panel-heading" style="text-align:left">
			    加班申請作業
			</div>
			<div class="panel-body panel-height">
				<table class="table table-bordered" id="table1">
					<thead>
						<tr>
							<td class="col-md-2" align="center">員工編號</td>
							<td class="col-md-4" id="empl_no"></td>
							<td class="col-md-2" align="center">姓名</td>
							<td class="col-md-4" id="empl_name"></td>
						</tr>

						<tr>
							<td class="col-md-2" align="center"> 單位</td>
							<td class="col-md-4" id="dname"></td>
							<td class="col-md-2" align="center"> 職稱</td>
							<td class="col-md-4" id="tname"></td>
						</tr>
					</thead>

					<thead>
					<tr>
						<td class="col-md-2" align="center">加班原因</td>
						<td class="col-md-4" colspan=3>
							<div class='form-group'>
								<div class='col-md-12'>
									加班簽呈日期：<select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='uyear' name='uyear' onChange=''></select>年
									<select class='form-control' style='width:auto; display: inline-block;' id='umonth' name='umonth' id='qry_month' onChange=''></select>月
									<select class='form-control' style='width:auto; display: inline-block;' id='uday' name='uday' onChange=''></select>日
									<font size='2' color='darkred'>(學校統一加班無提簽日期者，請以加班日期代替) </font>
								</div>
							</div>

							<div class='form-group'>
								<div class='col-md-12'>
									加班簽呈文號：<input type="text" name="reason" value="" size="25" maxlength="30" required><font size='2' color='darkred'> (學校統一加班無提簽文號者，請說明加班原因)</font>

									<div style="font-size:15px">　<input type="radio" name="pay_type" value="1" checked style="border:none">6個月內補休
										<input type="radio" name="pay_type" value="2"  style="border:none" onclick="javascript:alert('因本校無該項經費及預算，請勾選加班補休。');holiday.pay_type[0].checked='true';">請領加班費
									</div>
								</div>
						 	</div>
						</td>
					</tr>
					</thead>

					<thead>
					<tr>
						<td align="center">加班日期</td>
						<td colspan="3"><font size="4">
							<select class='form-control' style='width:auto; display: inline-block;' id='byear' name='byear' onChange=''></select>年
							<select class='form-control' style='width:auto; display: inline-block;' id='bmonth' name='bmonth' onChange=''></select>月
							<select class='form-control' style='width:auto; display: inline-block;' id='bday' name='bday' onChange=''></select>日~
							<select class='form-control' style='width:auto; display: inline-block;' id='eyear'  name='eyear' onChange=''></select>年
							<select class='form-control' style='width:auto; display: inline-block;' id='emonth' name='emonth' onChange=''></select>月
							<select class='form-control' style='width:auto; display: inline-block;' id='eday' name='eday' onChange=''></select>日
						</td>
					</tr>
					</thead>

					<thead>
					<tr>
						<td align="center">加班刷卡時間</td>
						<td class="col-md-5">
							<div class='form-group'>
								<div class='col-md-6'>
									<font color='darkred' size='2'>開始加班刷卡資料
									<select class='form-control' style='width:auto; display: inline-block;' id="btime" name='btime' onChange=''></select>
									<br>
									<font color='red' size='2'>1.例假日請選實際刷進時間。
								                             <br>2.上班日請選「得下班時間」
															 <br>例如：8:11上班則選17:11
								</div>
							</div>
						</td>

						<td colspan="2">
							<div class='form-group'>
								<div class='col-md-6'>
									<font color='darkred' size='2'>結束加班刷卡資料
									<select class='form-control' style='width:auto; display: inline-block;' id='etime' name='etime' onChange=''></select>
									<br>
									<font color='red' size='2'>1.請選實際刷退時間<br>
																2.畢業典禮「等」不必刷退請選17:00
								</div>
							</div>
					  </td>
					</tr>
					<tr>
						<td colspan="4" align="center">
							<button class="btn btn-primary"  name="check" onclick='timesum();''>送出計算</button>
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
					</thead>

				</table>
			</div>
		</form>
		</center>
		</div>

	</div>
		</div>
	    <!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php"); ?>
