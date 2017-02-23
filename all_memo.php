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
                                        <div class="col-lg-4">
                                            <!-- 年份 -->
                                            <select id="qry_year" name="qry_year" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <!-- 月份 -->
                                            <select id="qry_month" name="qry_month" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <!-- 單位 -->
                                            <select id="qry_dept" name="qry_dept" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        差假狀況
                                    </div>
                                    <div class="panel-body" id="container">
                                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>單位</th>
                                                    <th>姓名</th>
                                                    <th>假別</th>
                                                    <th>起始日</th>
                                                    <th>終止日</th>
                                                    <th>起始</th>
                                                    <th>天/時</th>
                                                    <th>差假事由</th>
                                                </tr>
                                            </thead>
                                            <tbody id="_content">
                                            </tbody>
                                        </table>
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
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
