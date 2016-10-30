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

?>
