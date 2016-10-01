<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                    <form id="form1" method="POST"> 
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
                                            <select id="qry_campus" name="campus_id" class="form-control"></select>
                                        </div>
                                        <div class="col-lg-6">
                                            <select id="qry_dept" name="dept_id" class="form-control"></select>
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
                                                        <th>科目代碼</th>
                                                        <th>節次</th>
                                                        <th>科目名稱</th>
                                                        <th>計分比例</th>
                                                        <th>指定科目</th>
                                                        <th>備取同分順序</th>
                                                        <th>功能</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="_content">
                                                </tbody>
                                            </table>
                                            <div id="loading" class="text-center" style="display:none">
                                                <img src="images/loading.gif">
                                            </div>                                            
                                        </div>
                                        <div class="panel-footer text-center">
                                        <!--<button type="button" class="btn btn-primary" id="btn-saveall"> <i class="fa fa-save"></i> 全部儲存 </button>-->
                                    </div>
                                    </div>
                                </div>
                                <!-- /.col-lg-12 -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->
                    </form>
                </div>
                <!-- /#page-wrapper -->
                <? include("inc/footer.php"); ?>
