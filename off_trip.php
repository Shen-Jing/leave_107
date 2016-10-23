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
                                        取消國民旅遊
                                    </div>

                                    <div class="panel-body">
                                      <div class="table-responsive">
                                        <table id="Btable" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr style="font-weight:bold">
                                            <th>姓名</th>
                                            <th>假別</th>
                                            <th>起始日期</th>
                                            <th>中止日期</th>
                                            <th>起始時間</th>
                                            <th>中止時間</th>
                                            <th>總時間</th>
                                            <th>職務代理人</th>
                                            <th>不刷旅遊卡了</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                    </tbody>
                                  </table>
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
            <script>
            $(document).ready(function()
            {
                //table <thead> is necessary.
                $('#Btable').DataTable({
                    "scrollCollapse": true,
                    "displayLength": 10,
                    "paginate": true,
                    "lengthChange": true,
                    "processing": false,
                    "serverSide": false,
                    "ajax": {
                        url: 'ajax/off_trip_ajax.php',
                        type: 'POST',
                        dataType: 'json'
                    },
                    "columns": [
                        { "name": "EMPL_CHN_NAME" },
                        { "name": "CODE_CHN_ITEM" },
                        { "name": "POVDATEB" },
                        { "name": "POVDATEE" },
                        { "name": "POVTIMEB" },
                        { "name": "POVTIMEE" },
                        { "name": "POVHOURSDAYS" },
                        { "name": "AGENTNAME" },
                        { "name": "BUTTON1" }
                    ]

                });
            });
            </script>
