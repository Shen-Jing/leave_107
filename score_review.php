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
                                        <div class="col-lg-12 text-center">
                                            <input type="text" id="qry_student_id" class="form-inline text-center" size="20" placeholder="請輸入准考證號">
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
                                                        <th>科目名稱</th>
                                                        <th>分數</th>
                                                        <th>試卷袋碼</th>
                                                        <th>考卷流水碼</th>
                                                        <th>試場</th>
                                                        <th>試卷袋流水號</th>
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
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->
                </div>
                <!-- /#page-wrapper -->
                <? include("inc/footer.php"); ?>
