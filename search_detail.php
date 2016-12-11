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
                                        個人差假資料明細
                                    </div>
                                    <div class="panel-body">
                                      <div class="panel panel-success">
                                      <div class="panel-heading">
                                          <font STYLE="font-family:微軟正黑體">請選擇查詢年度</font>
                                      </div>
                                      <div class="panel-body">
                                        <select class="form-control" name="p_menu" id="p_menu" onChange=''></select>
                                      </div>
                                    </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="panel panel-primary">
                                      <div class="panel-heading">
                                          <font STYLE="font-family:微軟正黑體">差假資料查詢</font>
                                      </div>
                                      <div class="form-group">
                                        <table class="table table-striped table-bordered dt-responsive nowrap" >

                                        <tr style="font-weight:bold">
                                            <td id="start_end"></td>
                                        </tr>
                                    </table>
                                        <table class="table table-striped table-bordered dt-responsive nowrap" >

                                    <thead>

                                        <tr style="font-weight:bold">
                                            <td>姓名</td>
                                            <td id="empl_name"></td>
                                            <td>員工編號</td>
                                            <td id="empl_no"></td>
                                        </tr>

                                    </thead>


                                      <table id="Table_Detail" class="table table-striped table-bordered dt-responsive nowrap" width="100%" ></table>

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
