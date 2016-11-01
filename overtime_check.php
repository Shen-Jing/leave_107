<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        查詢條件
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-6">
                                            <!-- 年份 -->
                                            <select id="qry_year" name="qry_year" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <!-- 單位 -->
                                            <select id="qry_dept" name="qry_dept" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        加班資料
                                    </div>
                                    <div class="panel-body" id="container">
                                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>單位</th>
                                                    <th>姓名</th>
                                                    <th>提簽日期</th>
                                                    <th>文號</th>
                                                    <th>加班日</th>
                                                    <th>開始時間</th>
                                                    <th>結束時間</th>
                                                    <th>時數</th>
                                                    <th>功能</th>
                                                </tr>
                                            </thead>
                                            <tbody id="_content">
                                            </tbody>
                                        </table>
                                        <div id="loading" class="text-center" style="display:none">
                                            <img src="images/loading.gif">
                                        </div>
                                    </div>
                                    <!--<div class="panel-footer">
                                    </div>-->
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row (Modal) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Modal -->
                                <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">&times;</span>
                                                    <span class="sr-only">Close</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">刷卡記錄</h4>
                                            </div>
                                            <!-- Modal Body -->
                                            <div class="modal-body">
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
                        <!-- /.row (Modal)-->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
