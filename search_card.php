<? include("inc/check.php");?>
<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <form class="form-horizontal" role="form" name="form1" id="form1" action="" method="post" target="right">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <font STYLE="font-family:微軟正黑體">查詢條件</font>
                                    </div>
                                    <div class="panel-body">
                                    <div class="row">
                                    	請選擇年份
                                    	<select class="form-control" name="qry_year" id="qry_year" style='display: inline-block;'></select>

                                    	請選擇月份
                                    	<select class="form-control" name="qry_month" id="qry_month" style='display: inline-block;'></select>

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
                                                <div class="form-group">
                                                    <div>
                                                    </div>
                                                    <!--link rel="stylesheet" href="style.css"-->
                                                    <div id="_content">

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
</body>
</html>