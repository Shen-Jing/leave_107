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
                                        <?= $_SESSION["pgmname"]?>
                                    </div>
                                    <div class="panel-body">
                                        <div id="loading" class="text-center" style="display:none">
                                            <img src="images/loading.gif">
                                            <h5 class="text-danger">成績處理作業執行中，請勿重新整理或重覆執行...</h5>
                                        </div>
                                    </div>
                                    <div class="panel-footer text-center">
                                        <button type="button" class="btn btn-primary" id="execute"> <i class="fa  fa-caret-right"></i> 開始執行違規處理作業 </button>
                                    </div>
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
