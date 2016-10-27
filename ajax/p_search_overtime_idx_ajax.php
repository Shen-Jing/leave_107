<?php
	session_start();
  include '../inc/connect.php';
  // $school_id = $_SESSION['school_id'];
  // $year = $_SESSION['year'];

  // 查詢單位
  if ($_POST['oper'] == "qry_dept"){
    //查詢系所
    $sql = "SELECT min(dept_no) dept_no, min(dept_full_name) dept_full_name
					FROM stfdept
					WHERE use_flag IS NULL
					GROUP BY substr(dept_no, 1, 2)";
    $data = $db -> query_array ($sql);
    echo json_encode($data);
    exit;
  }

  // query
  if ($_POST['oper'] == 0){
    // 根據單位id查詢
    $dept_id = @$_POST['dept_id'];
    // 查詢單位加班資料
    $sql = "SELECT   empl_no, empl_chn_name, crjb_title
					 FROM    psfempl, psfcrjb
					 WHERE   empl_no = crjb_empl_no
					 AND     substr(empl_no, 1, 1) IN ('0', '5', '7')
					 AND     crjb_seq = '1'
					 AND     crjb_quit_date IS NULL
					 AND     substr(crjb_title, 1, 1) != 'B'
					 AND     substr(crjb_depart, 1, 2) = substr('$dept_id', 1, 2)";
     $data = $db -> query_array ($sql);
     echo json_encode($data);
     exit;
  }

  // insert
  if ($_POST['oper'] == 1) {

  }

  // update
  if ($_POST['oper'] == 2) {

  }

  // delete
  if ($_POST['oper'] == 3) {

  }

  // show 加班記錄
  if ($_POST['oper'] == 4) {
    
  }
?>
