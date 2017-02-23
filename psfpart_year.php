<? include("inc/header.php"); ?>

<? include("inc/navi.php"); ?>

<? include("inc/sidebar.php"); ?>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <? include ("inc/page-header.php"); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">助理人員年資管理者維護作業</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            請選擇單位
                                            <select class="form-control" name="qry_dpt" id="qry_dpt" style='display: inline-block; width: auto;'></select>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-responsive table-hover table-bordered" id="jqGrid"></table>
                                <div id="jqGridPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->

<? include("inc/footer.php"); ?>