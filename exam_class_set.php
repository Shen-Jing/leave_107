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
                                            <form name="form1" id="form1">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-3">
                                                            <select id="qry_area"></select>
                                                            <select id="qry_building"></select>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <input type="radio" name="identity" value="1" checked> 一般生
                                                            <input type="radio" name="identity" value="1"> 身障生
                                                        </div>
                                                        <div class="col-lg-1">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <select id="qry_campus" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-3 text-center">
                                                            <div class="alert alert-success" role="alert" style="padding-top: 3px;padding-bottom: 3px;">試場總座位： <b><span id="seat_count"></span></b> 個</div>
                                                        </div>
                                                        <div class="col-lg-4 text-center">
                                                            <div class="alert alert-info" role="alert" style="padding-top: 3px;padding-bottom: 3px;">欲排列系組： <b><span id="arrange_count"></span></b> 人</div>
                                                        </div>
                                                        <div class="col-lg-1 text-center">
                                                        </div>
                                                        <div class="col-lg-4 text-center">
                                                            <div class="alert alert-warning" role="alert" style="padding-top: 3px;padding-bottom: 3px;">可排列系組</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-3">
                                                            <table class="table table-condensed table-hover table-bordered text-center" id="classroom">
                                                                <tr align='center'>
                                                                    <td>編號</td>
                                                                    <td>人數</td>
                                                                    <td>註解</td>
                                                                </tr>
                                                                <tbody id="_content">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-lg-4 text-center">
                                                            <select multiple size="15" id="list2" name="list2" class="form-control" style="font-size:10px;color:blue;  overflow-x: scroll">
                                                            </select>
                                                            <input type="hidden" id="str_arrange" name="str_arrange">
                                                            <br/>
                                                            <button id="arrange" type="button" class="btn btn-primary" title="送出排列"><i class="glyphicon glyphicon-sort"></i> 送出排列 </button>
                                                            <button  id="re_arrange" type="button" class="btn btn-danger" title="全部清除重排"><i class="glyphicon glyphicon-refresh"></i> 全部重排 </button>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <br/>
                                                            <button type="button" class="btn btn-default" onclick="move(document.form1.list1,document.form1.list2)" title="移至欲排列系組"><i class="fa fa-arrow-left"></i></button>
                                                            <br/>
                                                            <br/>
                                                            <button type="button" class="btn btn-default" onclick="move(document.form1.list2,document.form1.list1);list2_count()" title="移至可排列系組"><i class="fa fa-arrow-right"></i></button>
                                                            <br/>
                                                            <br/>
                                                            <button type="button" class="btn btn-primary" onclick="move_v('-')"><i class="fa fa-arrow-up" title="往上移"></i></button>
                                                            <br/>
                                                            <br/>
                                                            <button type="button" class="btn btn-primary" onclick="move_v('+')" title="往下移"><i class="fa fa-arrow-down"></i></button>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <select multiple size="15" id="list1" name="list1" class="form-control" style="font-size:6px;overflow-x: scroll"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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
