<? include("inc/header.php"); ?>

<? include("inc/navi.php"); ?>

<? include("inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <? include ("inc/page-header.php"); ?>
            <div class="panel panel-success">
                <div class="panel-heading">請選擇查詢條件</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                年份
                                <select class="form-control" id="qry_year"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                月份
                                <select class="form-control" id="qry_month"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="abc-checkbox abc-checkbox-primary">
                                    <input id="unpassed" type="checkbox" class="styled">
                                    <label for="unpassed">僅列出未完成假單</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">所有差假狀況</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <table class="dt-Table table table-hover table-striped table-bordered" id="oTable" width="100%">
                                    <thead>
                                        <tr>
                                            <th>單位</th>
                                            <th>姓名</th>
                                            <th>假別</th>
                                            <th>起始日</th>
                                            <th>終止日</th>
                                            <th>起始</th>
                                            <th>天/時</th>
                                            <th>填寫日期</th>
                                            <th>簽核結果</th>
                                            <th>目前位置</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<!-- Modal -->
<div class="modal fade" id="fullscrModal" tabindex="-1" role="dialog" aria-labelledby="fullscrModalLabel" aria-hidden="true">
    <div class="modal-dialog fullscr-iframe">
        <div class="modal-content fullscr-iframe">
            <div class="modal-body fullscr-iframe">
                <iframe class="NewPage-IFrame"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<div id="Modalsearch" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"></button>
                <h4 class="modal-title">請選擇人員</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-lg-12">
                        <div class="form-group">
                            部門
                            <select class="form-control" id="qry_dept">
                                <option value="">請選擇部門</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-12">
                        <div class="form-group">
                            人員
                            <select class="form-control" id="qry_emps"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<? include("inc/footer.php"); ?>
