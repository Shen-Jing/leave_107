<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <form id="form1">
                                    <input type="hidden" name="oper" id="oper">
                                    <div id="message">
                                    </div>
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            條件
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-sm-6">
                                                <!-- 年份 -->
                                                <select id="qry_year" name="qry_year" class="form-control">
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <!-- 月份 -->
                                                <select id="qry_month" name="qry_mon" class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.col-sm-12 -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <?= $_SESSION["pgmname"] ?>
                                    </div>
                                    <div class="panel-body" id="container">
                                        <div id="loading" class="text-center" style="display:none">
                                            <img src="images/loading.gif">
                                            <h5 class="text-danger">執行中，請勿重新整理或重覆執行...</h5>
                                        </div>
                                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>姓名</th>
                                                    <th>假別</th>
                                                    <th>起始日期</th>
                                                    <th>終止日期</th>
                                                    <th>總時數</th>
                                                    <th>差假事由</th>
                                                    <th>補印</th>
                                                </tr>
                                            </thead>
                                            <tbody id="_content">
                                            </tbody>
                                        </table>
                                        <div id="loading-data" class="text-center" style="display:none">
                                            <img src="images/loading.gif">
                                        </div>
                                    </div>
                                    <!-- panel-body -->
                                </div>
                            </div>
                            <!-- /.col-sm-12 -->
                        </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
