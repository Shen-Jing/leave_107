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
                <div class="col-lg-12">
                    <!-- 年月 -->
                    <div class="form-group">
                        <input type='text' class="form-control" id='start_ym' name="start_ym" placeholder="開始年月" readonly>
                    </div>
                    <div class="form-group">
                        <input type='text' class="form-control" id='end_ym' name="end_ym" placeholder="結束年月" readonly>
                    </div>
                </div>
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
              <div class="table-responsive">
                  <table id="Btable" class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>假別</th>
                            <th>開始日期</th>
                            <th>結束日期</th>
                            <th>開始時間</th>
                            <th>結束時間</th>
                            <th>地點</th>
                            <th>理由</th>
                        </tr>
                    </thead>
                    <tbody>
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
