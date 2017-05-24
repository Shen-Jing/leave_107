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
                                            <!-- 年月 -->
                                            <div class="form-group">
                                                <input type='text' class="form-control" id='qry_ym' name="qry_ym" placeholder="開始年月" readonly>
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
                                        <h3 class="panel-title"><span class="clickable"><i class="glyphicon glyphicon-chevron-up"></i></span> 有課老師名單</h3>
                                    </div>
                                    <div class="panel-body" id="container">
                                        <div class="table-responsive">
                                            <table id="Btable" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                              <thead>
                                                  <tr>
                                                    <th>姓名</th>
                                                    <th>假別</th>
                                                    <th>出國否</th>
                                                    <th>起始日</th>
                                                    <th>終止日</th>
                                                    <th>起始時</th>
                                                    <th>終止時</th>
                                                    <th>天數</th>
                                                    <th>職務代理人</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              </tbody>
                                            </table>
                                            <div id="modal-loading" class="text-center" style="display:none">
                                                <img src="images/loading.gif">
                                            </div>
                                        </div>
                                        <!-- div table -->
                                        <!-- <div class="panel-footer panel-red">
                                        </div> -->
                                    </div>
                                    <!-- div panel body -->
                                </div>
                                <!-- div panel primary -->
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
