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
                                        <div class="col-lg-12">
                                            <!-- 年月份 -->
                                            <div class="form-group">
                                                <input type='text' class="form-control" id='qry_ymd' name="qry_ymd" readonly="">
                                            </div>
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
                                        刷卡異常記錄
                                    </div>
                                    <div class="panel-body" id="container">
                                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>人員代號</th>
                                                    <th>姓名</th>
                                                    <th>日期</th>
                                                    <th>時間</th>
                                                    <th>異常原因</th>
                                                    <th>回覆原因</th>
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
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
