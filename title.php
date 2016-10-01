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
                                        <table class="table table-condensed table-hover">
                                            <thead>
                                                <tr>
                                                    <th>目前考試全銜</th>
                                                    <th>目前招生年度</th>
                                                    <th>功能</th>
                                                </tr>
                                            </thead>
                                            <tbody id="_content">
                                            </tbody>
                                        </table>
                                        <div id="loading" class="text-center" style="display:none">
                                            <img src="images/loading.gif">
                                        </div>
                                        <div class="alert alert-warning">
                                            <i class="fa fa-warning" style="float:left"></i>
                                            <ul>
                                                <li>刪除考試全銜將一併刪除相關基本資料(含學院、系所組別、考科、考區、試場等)，若已存在考生資料則不允許刪除。</li>
                                                <li>「匯入前一次招生資料」選項會自動匯入上一次招生的基本資料(含學院、系所組別、考科、考區、試場等)。</li>
                                            </ul>
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
