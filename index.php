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
                                        頁面名稱
                                    </div>
                                    <div class="panel-body">
                                        <div class="alert alert-warning">
                                            <i class="fa fa-warning" style="float:left"></i>
                                            <ul>
                                                <li>
                                                注意！您的簽核方式可選單筆連續簽核，或以勾選方式再批次送出一次簽核完成
                                                </li>
                                            </ul>
                                        </div>
                                        勾選簽核方式：
                                        <button type="button" class="btn btn-default">全部勾選</button>
                                        <button type="button" class="btn btn-default">反向勾選</button>
                                        <button type="button" class="btn btn-default">全部取消</button>
                                        <button type="button" class="btn btn-default">勾選確認送出（同意請假）</button>
                                        <div id="loading" class="text-center" style="display:none">
                                            <img src="images/loading.gif">
                                        </div>
                                        <?
                                        // $_SESSION['title_name']==""
                                            if(True){
                                        ?>
                                        <div class="alert alert-danger">
                                            <i class="fa fa-times" style="float:left"></i>
                                            <ul>
                                                <li>
                                                您目前沒有待簽核假單！
                                                </li>
                                            </ul>
                                        </div>
                                        <? }?>
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
