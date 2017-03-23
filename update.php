<? include("inc/header.php"); ?>
<? include("inc/navi.php"); ?>
<? include("inc/sidebar.php"); ?>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <? include ("inc/page-header.php"); ?>
            <div class="panel-group" id="accordion-1">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion-1" href="#collapse-One">
                                未簽核完成 -- 修改/取消假單
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-One" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <table class="dt-Table table table-striped table-bordered" id="update_table" width="100%">
                                        <thead>
                                             <tr style="font-weight:bold">
                                                <th>姓名</th>
                                                <th>假別</th>
                                                <th>起始日</th>
                                                <th>終止日</th>
                                                <th>起始時間</th>
                                                <th>終止時間</th>
                                                <th>總時數</th>
                                                <th>職務代理人</th>
                                                <th>功能</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion-1" href="#collapse-Two">
                                已簽核完成 -- 取消假單
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-Two" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <table class="dt-Table table table-striped table-bordered" id="cancel_table" width="100%">
                                        <thead>
                                             <tr style="font-weight:bold">
                                                <th>姓名</th>
                                                <th>假別</th>
                                                <th>起始日</th>
                                                <th>終止日</th>
                                                <th>起始時間</th>
                                                <th>終止時間</th>
                                                <th>總時數</th>
                                                <th>職務代理人</th>
                                                <th>功能</th>
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

<? include("inc/footer.php"); ?>