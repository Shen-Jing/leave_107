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
                                          <!-- 單位 -->
                                          <select id="qry_dept" name="qry_year" class="form-control">
                                          </select>
                                      </div>
                                      <div class="col-lg-6">
                                          <!-- 人員 -->
                                          <select id="qry_empl" name="qry_empl" class="form-control">
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
                                        差假明細狀況
                                    </div>
                                    <div class="panel-body" id="container">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div id="modal-message">
                                                </div>
                                                <div class="panel panel-success">
                                                    <div class="panel-heading">
                                                        查詢條件
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-lg-12">
                                                            <!-- 年月 -->
                                                            <div class="form-group">
                                                                <input type='text' class="form-control" id='start_ym' name="start_ym" placeholder="開始年月" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <input type='text' class="form-control" id='end_ym' name="end_ym" placeholder="結束年月" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col-lg-12 -->
                                        </div>
                                        <!-- /.row -->
                                        <div class="table-responsive">
                                            <table id="detail_table" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                              <thead>
                                                  <tr>
                                                      <th>假別</th>
                                                      <th>開始日期</th>
                                                      <th>結束日期</th>
                                                      <th>開始時間</th>
                                                      <th>結束時間</th>
                                                      <th>地點</th>
                                                      <th>理由</th>
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
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- <div id="message">
                                </div> -->
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        差假統計狀況
                                    </div>
                                    <div class="panel-body" id="container">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div id="modal-message-2">
                                                </div>
                                                <div class="panel panel-warning">
                                                    <div class="panel-heading">
                                                        查詢條件
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-lg-12">
                                                            <!-- 年月 -->
                                                            <div class="form-group">
                                                                <input type='text' class="form-control" id='qry_year' name="qry_year" placeholder="年份" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col-lg-12 -->
                                        </div>
                                        <!-- /.row -->
                                        <div class="table-responsive">
                                            <h4><b>已簽核完成</b></h4>
                                            <table id="tot_ed_table" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                              <thead>
                                                  <tr>
                                                      <th>假別</th>
                                                      <th>總天數</th>
                                                      <th>總時數</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              </tbody>
                                            </table>
                                            <div id="modal-loading-2" class="text-center" style="display:none">
                                                <img src="images/loading.gif">
                                            </div>
                                            <h4><b>簽核中</b></h4>
                                            <table id="tot_ing_table" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                              <thead>
                                                  <tr>
                                                      <th>假別</th>
                                                      <th>總天數</th>
                                                      <th>總時數</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              </tbody>
                                            </table>
                                            <div id="modal-loading-3" class="text-center" style="display:none">
                                                <img src="images/loading.gif">
                                            </div>
                                        </div>
                                        <!-- div table -->
                                        <!-- <div class="panel-footer">
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
