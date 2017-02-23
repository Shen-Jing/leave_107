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
                            <div class="panel-heading">修改假單</div>
                            <div class="panel-body">
                                <table cellspacing="0" class="dt-Table table table-striped table-bordered" id="update_table" width="100%">
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
                                            <th>修改</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
<? include("inc/footer.php"); ?>