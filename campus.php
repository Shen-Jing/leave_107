<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <div class="row">
                            <form id="form1" method="POST">
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
                                                    <th>學院代碼</th>
                                                    <th>學院名稱</th>
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
                                        <button type="button" class="btn btn-primary" id="btn-saveall"> <i class="fa fa-save"></i> 全部儲存 </button>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
