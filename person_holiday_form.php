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
		<form class="form-inline" role="form" name="holiday" method="post"  ENCTYPE="multipart/form-data">

		<div class="panel panel-primary">
			<div class="panel-heading" style="text-align:left">
			    人事室<font color="#f93e61">差假補登作業</font>
			</div>
			<div class="panel-body panel-height">
				<table class="table table-bordered" id="table1">
					<thead>
						<tr>
							<td class="col-md-2 td1" align="center">請假者單位</td>
							<td class="col-md-4">
								<div class='form-group'>
									<div class='col-md-12'>
										<select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='dpt' name='dpt' onChange=''></select>
									</div>
								</div>
							</td>
							<td class="col-md-2 td1" align="center">姓名</td>
							<td class="col-md-4">
								<div class='form-group'>
									<div class='col-md-12'>
										<select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='user' name='user' onChange=''><option value=''>請選擇</option></select>
									</div>
								</div>
							</td>
						</tr>
					</thead>
					<thead>
						<tr>
							<td class="col-md-2 td1" align="center"> 員工編號</td>
							<td class="col-md-4" align="center" id="empl_no"></td>
							<td class="col-md-2 td1" align="center"> 職稱</td>
							<td class="col-md-4" align="center" id="tname"></td>
						</tr>
					</thead>

					<thead>
					<tr>
						<td class="col-md-2 td1" align="center">假別</td>
						<td class="col-md-4">
							<div class='form-group'>
								<div class='col-md-12'>
									<select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='type' name='type' onChange=''></select>
								</div>
							</div>
						</td>
						<td class="col-md-2 td1" align="center">請假日數</td>
						<td class="col-md-4">
							<div class='form-group'>
								<input type="text" class="form-control" name="hday" id="hday" value="" required >
								日
								<input type="text" class="form-control" name="hour" id="hour" value="" required >
								時
							</div>
						</td>
					</tr>
					</thead>

					<thead>
					<tr>
						<td class="col-md-2 td1" align="center">開始日期</td>
						<td class="col-md-4">
							<div class='form-group'>
								<div class='col-md-12'>
									<input type="text" class="form-control" name="btime" id="btime" value="" required >
									例：0990701
								</div>
							</div>
						</td>
						<td class="col-md-2 td1" align="center">結束日期</td>
						<td class="col-md-4">
							<div class='form-group'>
								<div class='col-md-12'>
									<input type="text" class="form-control" name="etime" id="etime" value="" required >
									例：0990701
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="4" align="center">
							<button class="btn btn-default" type="reset">取消</button>
							<button type="button" class="btn btn-primary" name="check" onclick='holidaycheck();'>確定</button>
						</td>
					</tr>
					<tr>
						<td colspan="4">

						    <div class="alert alert-warning">
						    	<i class="fa fa-warning">
						    		注意!
						    		<ol>
						    			<li style="color:red">
						    			    請假日期不要有跨年的情況。
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
		</div>

	</div>
		</div>
	    <!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php"); ?>
