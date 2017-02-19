<?php
  session_start();
  include_once("../inc/connect.php");
  // from staff_call_off.js
  $empl_no = $_SESSION['empl_no']; // 950620 liru change 登錄此系統者
  $serialno = @$_POST['serialno'];

  $sql = "SELECT lpad(to_char(SYSDATE,'yyyymmdd')-'19110000',7,'0') ndate
          FROM dual";
  $data = $db -> query_array($sql);
  $ndate = $data['NDATE'][0];

  // 查詢請假開始/結束的時間/時刻
  $sql = "SELECT pocard,lpad(povdateb,7,'0') povdateb,lpad(povdatee,7,'0') povdatee,
          povtimeb,povtimee,depart
          FROM holidayform
          WHERE serialno=$serialno";
  $data = $db -> query_array($sql);
  $pocard = $data['POCARD'][0];
  $povdateb = $data['POVDATEB'][0];
  $povdatee = $data['POVDATEE'][0];
  $povtimeb = $data['POVTIMEB'][0];
  $povtimee = $data['POVTIMEE'][0];
  $depart = $data['DEPART'][0];

  // 查詢職位、人名
  $sql = "SELECT  empl_chn_name,
        (SELECT code_chn_item
          FROM psqcode WHERE  code_kind='0202'
          AND  code_field=c.crjb_title) title_name
          FROM  psfempl p,psfcrjb  c
          WHERE empl_no='$pocard'
          AND   empl_no=crjb_empl_no
          AND   crjb_seq>'1'
          AND   crjb_depart='$depart'
          AND   crjb_quit_date IS NULL";
  $data = $db -> query_array($sql);
  if (count($data['EMPL_CHN_NAME']) != 0){
    $name   = $data['EMPL_CHN_NAME'][0];
    $title_name  = $data['TITLE_NAME'][0];
  }
  else {
    $sql = "SELECT empl_chn_name,
            (SELECT code_chn_item
            FROM psqcode WHERE  code_kind='0202'
            AND  code_field=c.crjb_title) title_name
            FROM  psfempl p,psfcrjb  c
            WHERE empl_no='$pocard'
            AND   empl_no=crjb_empl_no
            AND   crjb_seq='1'
            AND   crjb_depart='$depart'
            AND   crjb_quit_date IS NULL";
    $data = $db -> query_array($sql);
    $name   = $data['EMPL_CHN_NAME'][0];
    $title_name  = $data['TITLE_NAME'][0];
  }

  // 查詢刷卡記錄
  $sql = "SELECT do_dat,decode(do_time,'0000','未刷卡',do_time) do_time,memo
      FROM  ps_card_data
      WHERE empl_no='$pocard'
      AND lpad(do_dat,7,'0') BETWEEN '$povdateb' AND '$povdatee'
      ORDER BY do_dat,do_time";
  $card_data = $db -> query_array($sql);

?>
<div class="row">
    <div class="col-lg-12">
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
                        <?
                          $len = count($card_data['DO_DAT']);
                          if ($len == 0) {
                            echo "<tr><td colspan='3'>目前尚無資料</td></tr>";
                          }
                          else {
                            for ($i = 0; $i < $len; $i++) {
                              echo "<tr>";
                              echo "  <td>" . $card_data['DO_DAT'][$i] . "</td>";
                              echo "  <td>" . $card_data['DO_TIME'][$i] . "</td>";
                              echo "  <td>" . $card_data['MEMO'][$i] . "</td>";
                              echo "</tr>";
                            }
                          }
                        ?>
                    </tbody>
                </table>
                <div id="modal-loading" class="text-center" style="display:none">
                    <img src="images/loading.gif">
                </div>
            </div>
            <!-- div panel-body -->
            <div class="panel-footer">
                <div class="alert alert-danger">
                    <i class="fa fa-danger" style="float:left"></i>
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
    </div>
    <!-- /.col-lg-12 -->
</div>
