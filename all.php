<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <form class="form-inline" role="form" name="form1" id="form1" action="" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="message">
                                </div>
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    單位
                                                    <select class="form-control" name="qry_dpt" id="qry_dpt" style='display: inline-block; width: auto;'></select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    假別
                                                    <select class="form-control" name="qry_type" id="qry_type" style='display: inline-block; width: auto;'></select>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading"></div>
                                    <div class="panel-body ">
                                            <!--<fieldset>-->
                                                <!--link rel="stylesheet" href="style.css"-->
                                                <div id="_content">
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
</body>
</html>