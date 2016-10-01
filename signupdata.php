<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <?= $_SESSION["pgmname"]?>
                                    </div>
                                    <div class="panel-body">
                                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>功能</th>
                                                    <th>姓名</th>
                                                    <th>身分證字號</th>
                                                    <th>性別</th>
                                                    <th>系所</th>
                                                    <th>組別</th>
                                                    <th>代碼</th>
                                                    <th>生日</th>
                                                    <th>E-mail</th>
                                                    <th>郵遞區號</th>
                                                    <th>通訊地址</th>
                                                    <th>郵遞區號</th>
                                                    <th>戶籍地址</th>
                                                    <th>電話(住家)</th>
                                                    <th>電話(公司)</th>
                                                    <th>電話(手機)</th>
                                                    <th>身障</th>
                                                    <th>學歷證明</th>
                                                    <th>報名序號</th>
                                                    <th>考科</th>
                                                    <th>確認</th>
                                                    <th>畢業學校</th>
                                                    <th>畢業系所</th>
                                                    <th>畢業日期</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div id="loading" class="text-center" style="display:none">
                                            <img src="images/loading.gif">
                                        </div>
                                        <!--<div class="alert alert-warning">
                                                <i class="fa fa-warning" style="float:left"></i>
                                                <ul>
                                                    <li></li>
                                                </ul>
                                            </div>-->
                                    </div>
                                    <!--<div class="panel-footer">
                                    </div>-->
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row (Modal)-->
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Modal -->
                                <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">&times;</span>
                                                    <span class="sr-only">Close</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">考生基本資料維護</h4>
                                            </div>
                                            <!-- Modal Body -->
                                            <div class="modal-body">
                                                <form id="add-form" class="form-horizontal">
                                                    <input type="hidden" id="oper" name="oper" value="2" />
                                                    <table class="table-bordered" width="100%" align="center">
                                                        <tr>
                                                            <td class="td_head">姓名</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="name" name="name" />
                                                            </td>
                                                            <td class="td_head">身分證字號</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="id" name="id" />
                                                            </td>
                                                            <td class="td_head">性別</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="sex" name="sex" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td_head">系所</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="dept_name" name="dept_name" />
                                                            </td>
                                                            <td class="td_head">組別</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="group_name" name="group_name" />
                                                            </td>
                                                            <td class="td_head">報名序號</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="signup_sn" name="signup_sn" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td_head">生日</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="birthday" name="birthday" />
                                                            </td>
                                                            <td class="td_head">E-mail</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="email" name="email" />
                                                            </td>
                                                            <td class="td_head">是否已確認</td>
                                                            <td>
                                                                <input type="radio" id="lock_up" name="lock_up" value="0" /> 否
                                                                <input type="radio" id="lock_up" name="lock_up" value="1" /> 是
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td_head">電話(住家)</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="tel_h" name="tel_h" />
                                                            </td>
                                                            <td class="td_head">電話(公司)</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="tel_o" name="tel_o" />
                                                            </td>
                                                            <td class="td_head">電話(手機)</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="tel_m" name="tel_m" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td_head">郵遞區號</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="zip" name="zip" />
                                                            </td>
                                                            <td class="td_head">通訊地址</td>
                                                            <td colspan="4">
                                                                <input type="text" class="form-control input-group" id="address" name="address" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td_head">郵遞區號</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="zip_o" name="zip_o" />
                                                            </td>
                                                            <td class="td_head">戶籍地址</td>
                                                            <td colspan="4">
                                                                <input type="text" class="form-control input-group" id="address_o" name="address_o" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td_head">畢業學校</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="ac_school_name" name="ac_school_name" />
                                                            </td>
                                                            <td class="td_head">畢業系所</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="ac_dept_name" name="ac_dept_name" />
                                                            </td>
                                                            <td class="td_head">畢業日期</td>
                                                            <td>
                                                                <input type="text" class="form-control input-group" id="ac_date" name="ac_date" />
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </div>
                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    關閉
                                                </button>
                                                <button id="btn-save" type="button" class="btn btn-primary">
                                                    儲存
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (Modal)-->
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
                                                <h4 class="modal-title" id="myModalLabel">考生報名基本資料</h4>
                                            </div>
                                            <!-- Modal Body -->
                                            <div class="modal-body">                                                
                                            </div>
                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    關閉
                                                </button>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.container-fluid -->
                </div>
                <!-- /#page-wrapper -->
                <? include("inc/footer.php"); ?>
