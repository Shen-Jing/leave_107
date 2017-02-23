<?php
  // from staff_call_off.js
  // 講此值存在html欄位讓view_call_off_ajax.php可取得
  $serialno = @$_POST['serialno'];
 ?>
<div class="row">
    <div class="col-lg-12">
        <div id="message">
            <span id="hide-serial" style="display:none"><?=$serialno ?></span>
            <!-- 請假資訊 -->
            <div class="alert alert-info">
                <i class="fa fa-info" style="float:left"></i>
                  <ul>
                  </ul>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
              假單刷卡記錄
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>日期</th>
                            <th>刷卡時間</th>
                            <th>假別</th>
                        </tr>
                    </thead>
                    <tbody id="_card-data">
                    </tbody>
                </table>
                <div id="modal-loading" class="text-center" style="display:none">
                    <img src="images/loading.gif">
                </div>
            </div>
            <!-- div panel-body -->
            <div class="panel-footer">
                <div class="alert alert-danger">
                    <i class="fa fa-bolt" style="float:left"></i>
                    <ul>
                        <li>
                        注意事項
                          <ol>
                            <li>已放過假是不能再取消的！</li>
                          </ol>
                        </li>
                    </ul>
                </div>
                <div class="alert alert-warning">
                    <i class="fa fa-warning" style="float:left"></i>
                    <ul>
                        <li>
                        假單取消條件
                          <ol>
                            <li>時間未到</li>
                            <li>刷卡記錄二筆都有刷卡時間（請一天）</li>
                            <li>一筆有刷卡時間（請半天）</li>
                            <li>教師</li>
                          </ol>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- div panel-footer -->
        </div>
        <!-- div panel-primary -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<script src="js/<?=basename($_SERVER['PHP_SELF'], ".php")?>.js"></script>
