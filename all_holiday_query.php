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
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        差假統計報表
                                    </div>
                                    <div class="panel-body">
                                      <div class="panel panel-success">
                                      <div class="panel-heading">
                                          <font STYLE="font-family:微軟正黑體">請選擇查詢條件</font>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  請輸入身分:
                                                  <select class="form-control" name="id_flag" id="id_flag" style='display: inline-block; width: auto;'></select>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  日期區間:
                                                  <select class="form-control" name="tyear" id="tyear" style='display: inline-block; width: auto;'></select>
                                                  年
                                                  <select class="form-control" name="tmonth" id="tmonth" style='display: inline-block; width: auto;'></select>
                                                  月
                                                  <select class="form-control" name="tday" id="tday" style='display: inline-block; width: auto;'></select>
                                                  日
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  至
                                                  <select class="form-control" name="syear" id="syear" style='display: inline-block; width: auto;'></select>
                                                  年
                                                  <select class="form-control" name="smonth" id="smonth" style='display: inline-block; width: auto;'></select>
                                                  月
                                                  <select class="form-control" name="sday" id="sday" style='display: inline-block; width: auto;'></select>
                                                  日
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                    </div>

                                    <div class="panel-body">
                                      <div class="table-responsive">
                                        <table id="Btable" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr style="font-weight:bold">
                                            <th>單位</th>
                                            <th>職稱</th>
                                            <th>姓名</th>
                                            <th>出差</th>
                                            <th>公假(02)</th>
                                            <th>公假(03)</th>
                                            <th>生理假</th>
                                            <th>家庭照顧假</th>
                                            <th>延長病假</th>
                                            <th>事假</th>
                                            <th>病假</th>
                                            <th>休假(公)</th>
                                            <th>婚假</th>
                                            <th>婉假</th>
                                            <th>喪假</th>
                                            <th>加班補休</th>
                                            <th>暑休</th>
                                            <th>寒休</th>
                                            <th>特休(勞)</th>
                                            <th>其他</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                    </tbody>
                                  </table>
                                      </div>

                                    </div>
                                    <!--<div class="panel-footer">
                                    </div>-->
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
