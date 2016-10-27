<?php
  include_once("../inc/check.php");
  $empl_no = $_POST['empl_no'];
  $empl_name = $_POST['empl_name'];

  $sql = "SELECT lpad(to_char(sysdate, 'yyyymmdd') - '19110000', 7, '0') ndate,
							   lpad(to_char(sysdate, 'yyyy') - '1911', 3, '0') year
			    FROM   dual";
  $data = $db -> query_array($sql);
  // 系統日期
  $ndate = $data['NDATE'][0];
  $year = $data['YEAR'][0];

  if (@$_POST['yy'] == null){
    $end_year = $year;
    @$_POST['yy'] = $year;
  }
  else {
    $end_year = @$_POST['yy'];
    @$_POST['p_menu'] = @$_POST['yval'];
  }

  if (@$_POST['p_menu'] == '')
		@$_POST['p_menu'] = $year;
	if (strlen(@$_POST['p_menu']) < 3)
	  @$_POST['p_menu'] = "0" . @$_POST['p_menu'];

  // 依據選單所選年分，查詢加班記錄
  $sql = "SELECT *
					FROM overtime
					WHERE empl_no = '$empl_no'
					AND   substr(over_date, 1, 3) = '$_POST[p_menu]'
					ORDER BY over_date";
  $data = $db -> query_array($sql);
?>
<form name="overtime" id="overtime-record" method="post">
    <div class="row">
        <div class="col-lg-12">
            <div id="modal-message">
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">
                    查詢條件
                </div>
                <div class="panel-body">
                    <select name="p_menu" id="qry_year" class="form-control" onChange='document.overtime.submit();'>
                        <?
                          for ($y = 99; $y <= $end_year + 1; $y++){
                            if ($y == @$_POST['p_menu'])
                              echo "<option value=$y selected>$y</option>";
                            else
                              echo "<option value=$y>$y</option>";
                          }
                        ?>
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
                    加班資料修改
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
                            <?
                                echo "<tr>";
                                if (count($data['EMPL_NO']) == 0){
                                  echo "<td colspan='6'>目前尚無加班資料</td>";
                                }
                                else {
                                  for ($i = 0; $i < count($data['EMPL_NO']); $i++){
                                    // 加班日期
                                    $over_date = $data['OVER_DATE'][$i];
                                    // 加班起始時間
                                		$time_1 = $data['DO_TIME_1'][$i];
                                    // 加班結束時間
                                		$time_2 = $data['DO_TIME_2'][$i];
                                    // 目前剩餘時數
                                		$nouse  = $data['NOUSE_TIME'][$i];
                                    // 到期日期
                                		$due_date = $data['DUE_DATE'][$i];
                                    // ??
                                		$p_check = $data['PERSON_CHECK'][$i];

                                    echo "<td>$over_date</td>";
                                    echo "<td>$time_1</td>";
                                    echo "<td>$time_2</td>";
                                    echo "<td><input value=$nouse type='text' class='form-control'></td>";
                                    echo "<td><input value=$due_date type='text' class='form-control'></td>";
                                  }
                                }
                                echo "</tr>";
                            ?>
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
</form>

<script>
  $('div').on('click', function(){
    alert("test");
  });
</script>
