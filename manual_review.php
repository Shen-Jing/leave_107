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
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        查詢條件
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-6">
                                            <select id="qry_dept" class="form-control"></select>
                                        </div>
                                        <div class="col-lg-6">
                                            <select id="qry_subject" class="form-control"></select>
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
                                            <table class="table table-condensed table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>序號</th>
                                                        <th>准考證號碼</th>
                                                        <th>姓名</th>
                                                        <th>成績</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="_content">
                                                </tbody>
                                            </table>
                                            <div id="loading" class="text-center" style="display:none">
                                                <img src="images/loading.gif">
                                            </div>
                                        </div>
                                        <div class="panel-footer text-center" id="store">
                                            <button type="button" class="btn btn-primary" onclick="store()"> <i class="fa fa-save"></i> 儲存 </button>
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
