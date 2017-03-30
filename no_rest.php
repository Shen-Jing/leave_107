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
			    不休假獎金計算作業
			</div>
			<div class="panel-body panel-height">
				<form data-toggle="validator" class="form-horizontal" role="form" name="no_rest" id="no_rest" action="<?=$_SERVER['PHP_SELF'] ?>" method="post">
				<table class="table table-bordered" id="table1">
						<tr>
							<div class="row">
								<td class="col-xs-2 col-md-6 td1" align="center">請勾選欲計算對象</td>
								<td class="col-xs-2 col-md-6">
									<input name="id" type="radio" value='1'><font size='4'>正式職員</font>
									<input name="id" type="radio" value='2'><font size='4'>教師兼主管</font>
								</td>
							</div>
						</tr>

						<tr>
							<div class="row">
								<td class="col-xs-2 col-md-6 td1" align="center">請勾選欲進行作業</td>
								<td class="col-xs-2 col-md-6">
									<input name="do_process" type="radio" value='1'>
									<font size='4'>計算不休假獎金 -- 以</font><select name="base_month">
									<option value="0">預設月份(註)</option>
									<option value="1">1月</option>
									<option value="2">2月</option>
									<option value="3">3月</option>
									<option value="4">4月</option>
									<option value="5">5月</option>
									<option value="6">6月</option>
									<option value="7">7月</option>
									<option value="8">8月</option>
									<option value="9">9月</option>
									<option value="10">10月</option>
									<option value="11">11月</option>
									<option value="12">12月</option>
									</select> 薪資做為計算基準 (<span style="font-size:13px;color:red">註:正式職員:12月 , 教師兼主管:7月</span>)
									<br><input name="do_process" type="radio" value='2'><font size='4'>發mail通知
									<br><input name="do_process" type="radio" value='3'><font size='4'>列印清冊
									<br><input name="do_process" type="radio" value='4'><font size='4'>發通知給本人
								</td>
							</div>
						</tr>

					<tr>
						<td colspan="4" align="center">
							<button type="submit" class="btn btn-primary" name="action" >送出計算</button>
							<!-- onclick='timesum();' -->
						</td>
					</tr>
				</table>
			</div>

			<!--列印頁面-->
			<div class="modal fade" id="ChangeModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			    <div class="modal-dialog modal-lg">
			        <div class="modal-content">
			            <!-- Modal Header -->
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal">
			                    <span aria-hidden="true">&times;</span>
			                    <span class="sr-only">Close</span>
			                </button>
			                <h4 class="modal-title" id="ModalLabel2">不休假獎金清冊</h4>
			            </div>

			            <!-- Modal Body -->
			            <div class="modal-body">
			                <div  id="class-modal">
			                </div>
			            </div>


			            <!-- Modal Body -->
			            <div class="modal-body">
			                <div class="panel-body" id="data_modify">
			                    <!--<fieldset>-->
			                        <div id="_content">
			                        	<div class='table-responsive'>
			                        		<table class='table table-bordered table-striped' id="Btable" cellspacing="0">
			                        			<thead>
			                        				<tr style="font-weight:bold">
			                        					<th style='text-align:center;'>單位</th>
			                        					<th style='text-align:center;'>職稱</th>
			                        					<th style='text-align:center;'>姓名</th>
			                        					<th style='text-align:center;'>休假年資</th>
			                        					<th style='text-align:center;'>每月俸給</th>
			                        					<th style='text-align:center;'>可休日數</th>
			                        					<th style='text-align:center;'>已休日數</th>
			                        					<th style='text-align:center;'>改發加班費日數</th>
			                        					<th style='text-align:center;'>改發加班費金額</th>
			                        					<th style='text-align:center;'>超過14天補助</th>
			                        					<th style='text-align:center;'>合計</th>
			                        				</tr>
			                        			</thead>
			                        			<tbody>
			                        			</tbody>
			                        		</table>
			                        	</div>
			                        </div>
			                    <!--</fieldset>-->
			                </div>
			            </div>
			            <!-- Modal Footer -->
			            <div class="modal-footer">
			                <button type="button" class="btn btn-default" data-dismiss="modal">
			                    關閉
			                </button>
			            </div>
			        </div>
			    </div>
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
