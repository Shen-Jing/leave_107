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
                                            <div class="row">
                                                <div class="col-lg-7">
                                                    <label for="bag">試卷袋號碼：</label>
                                                    <input id="bag" type="text" size="10" class="form-inline">
                                                    <label for="bag_serial">試卷袋流水號：</label>
                                                    <input id="bag_serial" type="text" size="10" class="form-inline">
                                                </div>
                                                <div class="col-lg-5">
                                                    <label for="makenumber">製卷碼：</label>
                                                    <input id="makenumber" type="text" class="form-inline">
                                                </div>
                                            </div>
                                            <div class="row" id="dept_subject">
                                                <div class="col-lg-6">
                                                    <div id="dept" class="bg-danger" style="line-height:20px;font-size:16px;margin:7px;padding:7px"></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div id="subject" class="bg-danger" style="line-height:20px;font-size:16px;margin:7px;padding:7px"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <table id="Btable" class="table table-condensed table-hover text-center">
                                                        <thead>
                                                            <tr align="center" style="font-weight:bold">
                                                                <td>第一次流水號</td>
                                                                <td>第二次流水號</td>
                                                                <td>製卷碼</td>
                                                                <td>第一次成績</td>
                                                                <td>第二次成績</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="_content">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div id="loading" class="text-center" style="display:none">
                                                <img src="images/loading.gif">
                                            </div>
                                        </div>
                                        <div class="panel-footer text-center" id="store">
                                            <button type="button" class="btn btn-primary" onclick="store()"> <i class="fa fa-save"></i> 儲存 </button>
                                        </div>
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
