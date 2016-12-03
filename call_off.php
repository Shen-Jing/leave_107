<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <form class="form-horizontal" role="form" name="form1" id="form1" action="" method="post" target="right">
                        <div class="panel panel-success">
                                    <div class="panel-heading">
                                        查詢條件
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

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        未簽核完成--取消假單
                                    </div>
                                    <div class="panel-body ">

                                            <!--<fieldset>-->
                                                <div class="form-group">
                                                    <div>
                                                    </div>
                                                    <!--link rel="stylesheet" href="style.css"-->
                                                    <div id="_content1">
                                                    </div>
                                                    <div id="loading1" class="text-center" style="display:none">
                                                        <img src="images/loading.gif">
                                                    </div>
                                                </div>
                                            <!--</fieldset>-->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--分隔-->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        已簽核完成--取消假單
                                    </div>
                                    <div class="panel-body ">

                                            <!--<fieldset>-->
                                                <div class="form-group">
                                                    <div>
                                                    </div>
                                                    <!--link rel="stylesheet" href="style.css"-->
                                                    <div id="_content2">
                                                    </div>
                                                    <div id="loading2" class="text-center" style="display:none">
                                                        <img src="images/loading.gif">
                                                    </div>
                                                </div>
                                            <!--</fieldset>-->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>