 <?php
  include_once("../inc/connect.php");
   // from over_time.js
  $empl_no = @$_POST['empl_no'];
  $over_date = @$_POST['over_date'];

  $sql = "SELECT do_dat, memo, substr(do_time, 1, 2) || ':' || substr(do_time, 3, 2) do_time
           FROM ps_card_data p
           WHERE empl_no = lpad('$empl_no', 7, '0')
           AND do_dat = '$over_date'
           ORDER BY do_time";
  $card_data = $db -> query_array($sql);

  $sql = "SELECT  code_chn_item, povdateb, povdatee, povdays, povhours
      		FROM    holidayform, psqcode
      		WHERE   pocard = '$empl_no'
      		AND     '$over_date' BETWEEN povdateb AND povdatee
      		AND     condition <> -1
      		AND     povtype = code_field
      		AND     code_kind = '0302'";
  $holiday_data = $db -> query_array($sql);

 ?>
 <div class="row">
     <div class="col-lg-12">
         <div class="panel panel-primary">
             <div class="panel-heading">
                刷卡記錄
             </div>
             <div class="panel-body">
                 <table class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                     <thead>
                         <tr>
                             <th>日期</th>
                             <th>刷卡時間</th>
                             <th>附註</th>
                         </tr>
                     </thead>
                     <tbody id="_card-data">
                          <?
                            $len = count($card_data['DO_DAT']);
                            if ($len == 0) {
                              echo "<tr><td colspan='5'>目前尚無資料</td></tr>";
                            }
                            else {
                              for ($i = 0; $i < $len; $i++) {
                                echo "<tr>";
                                echo "  <td>" . $card_data['DO_DAT'][$i] . "</td>";
                                echo "  <td>" . $card_data['MEMO'][$i] . "</td>";
                                echo "  <td>" . $card_data['DO_TIME'][$i] . "</td>";
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
             <!--<div class="panel-footer">
             </div>-->
         </div>
     </div>
     <!-- /.col-lg-12 -->
 </div>
 <!-- /.row -->
 <div class="row">
     <div class="col-lg-12">
         <div class="panel panel-primary">
             <div class="panel-heading">
                請假記錄
             </div>
             <div class="panel-body">
                 <table class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                     <thead>
                         <tr>
                             <th>假別</th>
                             <th>請假日期</th>
                             <th>請假日期(?)</th>
                             <th>天數</th>
                             <th>時數</th>
                         </tr>
                     </thead>
                     <tbody id="_holiday-data">
                          <?
                            $len = count($holiday_data['CODE_CHN_ITEM']);
                            if ($len == 0) {
                              echo "<tr><td colspan='5'>目前尚無資料</td></tr>";
                            }
                            else {
                              for ($i = 0; $i < $len; $i++) {
                                echo "<tr>";
                                echo "  <td>" . $holiday_data['CODE_CHN_ITEM'][$i] . "</td>";
                                echo "  <td>" . $holiday_data['POVDATEB'][$i] . "</td>";
                                echo "  <td>" . $holiday_data['POVDATEE'][$i] . "</td>";
                                echo "  <td>" . $holiday_data['POVDAYS'][$i] . "</td>";
                                echo "  <td>" . $holiday_data['POVHOURS'][$i] . "</td>";
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
             <!--<div class="panel-footer">
             </div>-->
         </div>
     </div>
     <!-- /.col-lg-12 -->
 </div>
