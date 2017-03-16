<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php");?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                      <form class="form-horizontal" role="form" name="form1" id="form1" action="" method="post" target="right">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        調補課申請單填寫
                                    </div>
                                    <div class="panel-body">
                                      <div class="panel panel-success">
                                      <div class="panel-heading">
                                          <font STYLE="font-family:微軟正黑體">請選擇查詢時間</font>
                                      </div>
                                      <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    年份
                                                    <select class="form-control" name="qry_year" id="qry_year" style='display: inline-block; width: auto;'></select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    月份
                                                    <select class="form-control" name="qry_month" id="qry_month" style='display: inline-block; width: auto;'></select>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="panel panel-primary">
                                      <div class="panel-heading">
                                          <font STYLE="font-family:微軟正黑體">資料查詢結果</font>
                                      </div>
                                      <div class="table-responsive">

                                        <table class="table table-striped table-bordered dt-responsive nowrap" >




                                          <div class="form-group">
                                              <!--link rel="stylesheet" href="style.css"-->
                                              <div id="_content">
                                              </div>
                                          </div>

                                      </table>
                                      </div>

                                      </div>
                                      </div>


                                    <!--<div class="panel-footer">
                                    </div>-->
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                      </form>
                      <div class="row">
                          <div class="col-lg-12">
                              <!-- Modal -->

                              <!--修改頁面2-->
                              <div class="modal fade" id="ChangeModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                      <div class="modal-content">
                                          <!-- Modal Header -->
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">
                                                  <span aria-hidden="true">&times;</span>
                                                  <span class="sr-only">Close</span>
                                              </button>
                                              <h4 class="modal-title" id="ModalLabel2">資料填補</h4>
                                          </div>

                                          <!-- Modal Body -->
                                          <div class="modal-body">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        請選擇目前學年度:
                                                        <select class="form-control" name="qry_class_year" id="qry_class_year" style='display: inline-block; width: auto;'></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        學期:
                                                        <select class="form-control" name="qry_acadm" id="qry_acadm" style='display: inline-block; width: auto;'></select>
                                                    </div>
                                                </div>
                                                <div  id="class-modal">
                                                </div>


                                          </div>


                                          <!-- Modal Body -->
                                          <div class="modal-body">
                                            <div class="panel-body">
                                              <div class="panel panel-primary">
                                              <div class="panel-heading">
                                                  <font STYLE="font-family:微軟正黑體">教師本次差假以填寫之紀錄</font>
                                              </div>
                                              <div class="table-responsive">

                                                <table class="table table-striped table-bordered dt-responsive nowrap" >




                                                  <div class="form-group">
                                                      <!--link rel="stylesheet" href="style.css"-->
                                                      <div id="class_content">
                                                      </div>
                                                  </div>

                                              </table>
                                              </div>

                                              </div>
                                              </div>


                                              <div class="panel-body" id="data_modify">
                                                <div class="panel panel-primary">
                                                  <div class="panel-heading" style="text-align:left">
                                            			    加班申請作業
                                            			</div>
                                            			<div class="panel-body panel-height">
                                            				<table class="table table-bordered" >
                                                      <thead>
                                            					<tr>
                                            						<td class="td1" align="center">請假期間</td>
                                            						<td colspan="3" id="holiday_time"><font size="4">

                                            						</td>
                                            					</tr>
                                            					</thead>

                                                      <thead>
                                            					<tr>
                                            						<td class="td1" align="center">請假事由</td>
                                            						<td colspan="3" id="holidy_mark"><font size="4">

                                            						</td>
                                            					</tr>
                                            					</thead>

                                            					<thead>
                                            					<tr>
                                            						<td class="col-md-2 td1">科目名稱</td>
                                            						<td class="col-md-4">


                                            									<select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='subject-name' name='subject-name' onChange=''></select>

                                            									<font size='2' color='darkred'>(如果沒有選項，表示您學年與學期選錯了，請返回上一步) </font>


                                            						</td>
                                                        <td class="col-md-2 td1" align="center">上課班別</td>
                                            						<td class="col-md-4" id='class-name'>

                                            						</td>
                                            					</tr>
                                            					</thead>

                                                      <thead>
                                            					<tr>
                                            						<td class="col-md-2 td1" align="center">原上課日期</td>
                                            						<td class="col-md-4">
                                            							<div class='form-group'>
                                                            <div class='col-md-12'>
                                            									<select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='ocyear' name='ocyear' onChange=''></select>年
                                            									<select class='form-control' style='width:auto; display: inline-block;' id='ocmonth' name='ocmonth' onChange=''></select>月
                                            									<select class='form-control' style='width:auto; display: inline-block;' id='ocday' name='ocday' onChange=''></select>日
                                            								</div>
                                            							</div>
                                            						</td>

                                                        <td class="col-md-2 td1" align="center">原上課節次等</td>
                                          							<td class="col-md-4" id="scr_period"></td>
                                            					</tr>
                                            					</thead>

                                                      <thead>
                                            					<tr>
                                            						<td class="col-md-2 td1" align="center">調補課日期</td>
                                            						<td class="col-md-4">
                                            							<div class='form-group'>
                                                            <div class='col-md-12'>
                                            									<select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='ccyear' name='ccyear' onChange=''></select>年
                                            									<select class='form-control' style='width:auto; display: inline-block;' name='ccmonth' id='ccmonth' onChange=''></select>月
                                            									<select class='form-control' style='width:auto; display: inline-block;' id='ccday' name='ccday' onChange=''></select>日
                                            								</div>
                                            							</div>
                                            						</td>

                                                        <td class="col-md-2 td1" align="center">補課節次</td>
                                          							<td class="col-md-4" >
                                                          <div class='form-group'>
                                                            <div class='col-md-12'>
                                            									第<select class='form-control' style='width:auto; display: inline-block;' name='class_section21' id='class_section21' onChange=''></select>節~<br>
                                            									第<select class='form-control' style='width:auto; display: inline-block;' id='class_section22' name='class_section22' onChange=''></select>節
                                            								</div>
                                            							</div>
                                                        </td>
                                            					</tr>
                                            					</thead>

                                                      <thead>
                                            					<tr>
                                            						<td class="td1" align="center">補課教室</td>
                                            						<td colspan="3"><input type="text" class="form-control" name="class_room" id="class_room" value="" size="25" maxlength="30" required><font size="4">

                                            						</td>
                                            					</tr>
                                            					</thead>
                                                      <thead>
                                            					<tr>
                                            						<td class="td1" align="center">備註</td>
                                            						<td colspan="3"><input type="text" class="form-control" name="class_memo" id="class_memo" value="" size="25" maxlength="30" required><font size="4">

                                            						</td>
                                            					</tr>
                                                      <tr>
                                            						<td colspan="4" align="center">
                                            							<button class="btn btn-primary" name="close" onclick='closeM()'>離開或被退重送</button>
                                                          <button class="btn btn-primary" name="check" onclick='CheckData(9487)'>本班資料儲存</button>
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
                                          <!-- Modal Footer -->
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-default" data-dismiss="modal">
                                                  關閉
                                              </button>
                                              <!-- <button id="btn-save" type="button" class="btn btn-primary">
                                                  儲存
                                              </button> -->
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>


                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
