<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <?
            @$_POST['department'] = $_GET['dept'];

            if (@$_POST['department'] == '' and $_SESSION['depart'] == '')
                $_SESSION['dept'] = 'M80';
            else if (@$_POST['department'] != '' and $_SESSION['depart'] != '' )
                $_SESSION['dept'] = @$_POST['department'];
            ?>
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
                                        <select id="qry_dept" class="form-control">
                                        </select>
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
                                        加班資料
                                    </div>
                                    <div class="panel-body" id="container">
                                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>人員代號</th>
                                                    <th>姓名</th>
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
                                    <!--<div class="panel-footer">
                                    </div>-->
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row (Modal) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Modal -->
                                <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">&times;</span>
                                                    <span class="sr-only">Close</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">加班記錄</h4>
                                            </div>
                                            <!-- Modal Body -->
                                            <div class="modal-body">
                                            </div>
                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    關閉
                                                </button>
                                                <!-- <button id="btn-save" type="button" class="btn btn-primary">
                                                    儲存
                                                </button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (Modal)-->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
