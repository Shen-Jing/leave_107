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
                                        <font STYLE="font-family:微軟正黑體">請選擇查詢年度</font>
                                    </div>
                                    <div class="panel-body">
                                        <select class="form-control" name="p_menu" id="qry_year" data-width="auto"></select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        本年度加班記錄
                                    </div>
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
            <!-- /#page-wra
            <? include("inc/footer.php"); ?>
