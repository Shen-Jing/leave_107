<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <div class="row">
                            <form id="form1" method='POST'>
                            <div class="col-lg-12">                            
                            <input type="hidden" name="oper" id="oper">
                                <div id="message">
                                </div>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        查詢條件
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-6">
                                            <select id="qry_campus" name="qry_campus" class="form-control"></select>
                                        </div>
                                        <div class="col-lg-6">
                                            <select id="qry_dept" name="qry_dept" class="form-control"></select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col-lg-12 -->                            
                            </div>
                            <!-- /.row -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <?= $_SESSION["pgmname"]?>
                                        </div>
                                        <div class="panel-body">
                                            <div>
                                            <label for="comment">成績單備註說明:</label>
                                            <textarea id="scoreremark" class="form-control" rows="5"></textarea>
                                            </div>
                                            <div id="loading" class="text-center" style="display:none">
                                                <img src="images/loading.gif">
                                                <h5 class="text-danger">執行中，請勿重新整理或重覆執行...</h5>
                                            </div>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button type="button" class="btn btn-danger" id="btn-save"> <i class="fa fa-save"></i> 儲存備註說明 </button>
                                            <button type="button" class="btn btn-primary" id="btn-report"> <i class="fa fa-print"></i> 成績單列印 </button>
                                        </div>
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
