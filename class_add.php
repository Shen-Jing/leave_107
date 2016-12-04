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
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
