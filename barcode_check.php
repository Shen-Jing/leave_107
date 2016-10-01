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
                                            <select id="qry_campus" class="form-control"></select>
                                        </div>
                                        <div class="col-lg-6">
                                            <select id="qry_dept" class="form-control"></select>
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
                                            <div class="col-lg-12 text-center">
                                                試卷條碼號碼：
                                                <input id="Binput" type="text" class="form-inline">
                                            </div>
                                            <table id="Btable" class="table table-condensed table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>製卷碼</th>
                                                        <th>准考證號碼</th>
                                                        <th>科目代碼</th>
                                                        <th>科目名稱</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="_content">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="loading" class="text-center" style="display:none">
                                            <img src="images/loading.gif">
                                        </div>
                                    </div>
                                    <!--<div class="panel-footer">
                                    </div>-->
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
