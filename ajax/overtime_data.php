<?php
  include_once("../inc/connect.php");
  // from p_search_overtime_idx_ajax.js
  $empl_no = $_POST['empl_no'];
  $empl_name = $_POST['empl_name'];


?>
<div class="row">
    <div class="col-lg-12">
        <div id="modal-message">
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">
                查詢條件
            </div>
            <div class="panel-body">
                <select name="p_menu" id="qry_year" class="form-control">
                </select>
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
                <span id="empl_name"><?=$empl_name ?></span>
                <span id="empl_no"><?=$empl_no ?></span>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>加班日期</th>
                            <th>加班起始時間</th>
                            <th>加班結束時間</th>
                            <th>目前剩餘時數</th>
                            <th>到期日期</th>
                            <th>功能</th>
                        </tr>
                    </thead>
                    <tbody id="_overtime-data">
                    </tbody>
                </table>
                <div id="modal-loading" class="text-center" style="display:none">
                    <img src="images/loading.gif">
                </div>
            </div>
            <!--<div class="panel-footer">
            </div>-->
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>

<script src="js/<?=basename($_SERVER['PHP_SELF'], ".php")?>.js"></script>
