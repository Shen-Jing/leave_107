<? include("inc/header.php"); ?>

<? include("inc/navi.php"); ?>

<? include("inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <? include ("inc/page-header.php"); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">請選擇查詢年度</div>
                        <div class="panel-body">
                            <select class="form-control" id="sel_years"></select>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">個人差假狀況(上)</div>
                        <div class="panel-body">
                            <div class="col-md-12 col-lg-12" id="hl_canceled">
                                <h3>已取消假單</h3>
                                <table class="dt-Table table table-striped table-bordered" id="Btable_canceled" width="100%">
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
                                            <th>取消日期</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <hr />
                            </div>
                            <div class="col-md-12 col-lg-12" id="hl_dealing">
                                <h3>處理中假單</h3>
                                <table class="dt-Table table table-striped table-bordered" id="Btable_dealing" width="100%">
                                    <thead>
                                        <tr style="font-weight:bold">
                                            <th>姓名</th>
                                            <th>假別</th>
                                            <th>起始日</th>
                                            <th>終止日</th>
                                            <th>起始</th>
                                            <th>終止</th>
                                            <th>總時數</th>
                                            <th>代理簽核</th>
                                            <th>直屬簽核</th>
                                            <th>單位簽核</th>
											<th>院長</th>
											<th>人事承辦員</th>
											<th>人事主任</th>
											<th>秘書室</th>
											<th>備註</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <hr />
                            </div>
                            <div class="col-md-12 col-lg-12" id="hl_rejected">
                                <h3>未通過假單</h3>
                                <table class="dt-Table table table-striped table-bordered" id="Btable_rejected" width="100%">
                                    <thead>
                                        <tr style="font-weight:bold">
                                            <th>姓名</th>
                                            <th>假別</th>
                                            <th>起始日</th>
                                            <th>終止日</th>
                                            <th>起始</th>
                                            <th>終止</th>
                                            <th>單位原因</th>
                                            <th>人事原因</th>
                                            <th>秘書室原因</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <hr />
                            </div>
                            <div class="col-md-12 col-lg-12" id="hl_passing">
                                <h3>已核准假單</h3>
                                <table class="dt-Table table table-striped table-bordered" id="Btable_passing" width="100%">
                                    <thead>
                                        <tr style="font-weight:bold">
                                            <th>姓名</th>
                                            <th>假別</th>
                                            <th>起始日</th>
                                            <th>終止日</th>
                                            <th>起始</th>
                                            <th>終止</th>
                                            <th>總時數</th>
                                            <th>代理簽核</th>
                                            <th>直屬簽核</th>
                                            <th>單位簽核</th>
											<th>院長</th>
											<th>人事承辦員</th>
											<th>人事主任</th>
											<th>秘書室</th>
											<th>備註</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
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

<? include("inc/footer.php"); ?>