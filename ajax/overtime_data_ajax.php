<?php
  include_once("../inc/check.php");

  // 查詢加班記錄年份
  if ($_POST['oper'] == "qry_year") {
    // 系統日期
    $sql = "SELECT lpad(to_char(sysdate, 'yyyymmdd') - '19110000', 7, '0') ndate,
  							   lpad(to_char(sysdate, 'yyyy') - '1911', 3, '0') year
  			    FROM   dual";
    $data = $db -> query_array($sql);
    echo json_encode($data);
    exit;
  }

  // 查詢詳細加班記錄
  if ($_POST['oper'] == 0) {
    $empl_no = $_POST['empl_no'];
    $empl_name = $_POST['empl_name'];
    $qry_year = $_POST['qry_year'];
    // 依據選單所選年分，查詢加班記錄
    $sql = "SELECT *
  					FROM overtime
  					WHERE empl_no = '$empl_no'
  					AND   substr(over_date, 1, 3) = '$qry_year'
  					ORDER BY over_date";
    $data = $db -> query_array($sql);
    echo json_encode($data);
    exit;
  }

  // 修改剩餘時數
  if ($_POST['oper'] == 2){
    // 欲修改成的剩餘時數
    $nouse = $_POST['nouse_time'];
    $empl_no = $_POST['empl_no'];
    // 加班日期
    $over_date = $_POST['over_date'];
    // 到期日期
    $due_date = $_POST['due_date'];

    // 系統日期
    $sql = "SELECT lpad(to_char(sysdate, 'yyyymmdd') - '19110000', 7, '0') ndate
			     FROM dual";
    $data = $db -> query_array($sql);
    $ndate = $data['NDATE'][0];
    // 誰審核通過的
    $ndate = $empl_no . $ndate;

    $sql = "UPDATE overtime
  				 SET nouse_time = '$nouse', time_date = '$ndate'
  				 WHERE  empl_no = '$empl_no'
  				 AND    over_date = '$over_date'" ;
    $data = $db -> query($sql);

    $sql = "UPDATE overtime
  				 SET due_date = '$due_date', due2_date ='$ndate'
  				 WHERE empl_no = '$empl_no'
  				 AND over_date = '$over_date'";
    $data = $db -> query($sql);

    $message = array("error_code" => $data['code'], "error_message" => $data['message'], "sql" => $sql);
    echo json_encode($message);
    exit;
  }

?>
