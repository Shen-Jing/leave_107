<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <? $depart = $_SESSION['depart']; ?>
            <style type="text/css">
                #Btable tr {
                cursor: pointer;
            }
            </style>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <form class="form-inline" role="form" name="form1" id="form1" action="" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="message">
                                </div>
                                <div style="display: none;" id="depart"><?=$depart ?></div>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        查詢條件
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="row">
                                                        年份<select class="form-control" name="qry_year" id="qry_year" style='display: inline-block; width: auto;'></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="row">
                                                        月份<select class="form-control" name="qry_month" id="qry_month" style='display: inline-block; width:   auto;'></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="row">
                                                        單位<select class="form-control" name="qry_dpt" id="qry_dpt" style='display: inline-block; width: auto;'></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="row">
                                                        姓名<select class="form-control" name="qry_dpt_empl" id="qry_dpt_empl" style='display: inline-block;    width: auto;'></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="row">
                                                        假別<select class="form-control" name="qry_type" id="qry_type" style='display: inline-block; width: auto;'></select>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading"></div>
                                        <div class="panel-body ">
                                            <!--<fieldset>-->
                                                <div id="_content">
                                                	<div class='table-responsive'>
                                                		<table class="table table-striped table-condensed table-hover table-bordered nowrap" id="Btable" cellspacing="0">
                                                			<thead>
                                                				<tr style="font-weight:bold">
                                                					<th style='text-align:center;'>單位</th>
                                                					<th style='text-align:center;'>姓名</th>
                                                					<th style='text-align:center;'>假別</th>
                                                					<th style='text-align:center;'>起始日</th>
                                                					<th style='text-align:center;'>終止日</th>
                                                					<th style='text-align:center;'>起始</th>
                                                					<th style='text-align:center;'>天/時</th>
                                                					<th style='text-align:center;'>單位簽</th>
                                                					<th style='text-align:center;'>人事承辦</th>
                                                					<th style='text-align:center;'>人事主任</th>
                                                					<th style='text-align:center;'>秘書簽 </th>
                                                					<th style='text-align:center;'>填寫日期</th>
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
                            </div>
                        </div>
                    </form>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">詳細資料</h4>
                                </div>
                                <div class="modal-body">
                                    <div align="center" id="loadingIMG">
                                        <img src="images/loading.gif">
                                    </div>
                                    <div id="data">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">差假基本資料</div>
                                                <div class="panel-body ">
                                                    <table class="table table-bordered" id="detail">
                                                        <tr>
                                                            <td class="td1">單位</td>
                                                            <td id="DEPT_SHORT_NAME"></td>
                                                            <td class="td1">姓名</td>
                                                            <td id="EMPL_CHN_NAME"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td1">假別</td>
                                                            <td id="CODE_CHN_ITEM"></td>
                                                            <td class="td1">填寫日期</td>
                                                            <td id="APPDATE"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td1">起始日</td>
                                                            <td id="POVDATEB"></td>
                                                            <td class="td1">終止日</td>
                                                            <td id="POVDATEE"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td1">起始/天/時</td>
                                                            <td id="POVTIMEB"></td>
                                                            <td class="td1">人事承辦/人事主任</td>
                                                            <td id="PERONE_SIGND"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                        </div>

                                        <div class="panel panel-primary">
                                            <div class="panel-heading">差假簽核資料</div>
                                                <div class="panel-body ">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td class="td1">秘書簽</td>
                                                            <td id="SECONE_SIGND"></td>
                                                            <td class="td1">單位簽</td>
                                                            <td id="TWOSIGND"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-2 td1">是否有課</td>
                                                            <td class="col-md-2" id="haveclass"></td>
                                                            <td class="col-md-2 td1">是否出國</td>
                                                            <td class="col-md-2" id="abroad"></td>
                                                        </tr>
                                                        <tr style="display: none;" id="permit-row">
                                                            <td class="col-md-2 td1">文號或簽呈日期</td>
                                                            <td class="col-md-2" id="permit_commt"></td>
                                                            <td class="col-md-2 td1">是否含例假日</td>
                                                            <td class="col-md-2" id="containsat"></td>
                                                        </tr>
                                                        <tr style="display: none;" id="eplace_row">
                                                            <td class="col-md-2 td1">出差地點</td>
                                                            <td class="col-md-2" id="eplace"></td>
                                                        </tr>
                                                        <tr style="display: none;" id="trip-row">
                                                            <td class="col-md-2 td1">是否刷國民旅遊卡</td>
                                                            <td class="col-md-2" id="trip"></td>
                                                        </tr>
                                                        <tr style="display: none;" id="nouse-row">
                                                            <td class="col-md-2 td1"></td>
                                                            <td class="col-md-2" id="nouse"></td>
                                                        </tr>
                                                        <tr style="display: none;" id="nouse-row">
                                                            <td class="col-md-2 td1">
                                                            <td class="col-md-2" id="nouse"></td>
                                                        </tr>
                                                        <tr style="display: none;" id="budget-row">
                                                            <td class="col-md-2 td1">經費來源</td>
                                                            <td class="col-md-2" id="budget"></td>
                                                        </tr>
                                                    </table>

                                                </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
</body>
</html>