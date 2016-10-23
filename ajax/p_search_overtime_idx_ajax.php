<?php
	session_start();
  include '../inc/connect.php';
  // $school_id = $_SESSION['school_id'];
  // $year = $_SESSION['year'];

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

  $dept_id = $_POST['dept_id'];
  if ($_POST['oper'] == 0){
    // 查詢單位加班資料
    $sql = "SELECT   empl_no, empl_chn_name, crjb_title
					 from    psfempl, psfcrjb
					 where   empl_no = crjb_empl_no
					 and     substr(empl_no, 1, 1) in ('0', '5', '7')
					 and     crjb_seq = '1'
					 and     crjb_quit_date is null
					 and     substr(crjb_title, 1, 1) != 'B'
					 and     substr(crjb_depart, 1, 2) = substr('$dept_id', 1, 2)";
     $data = $db -> query_array ($sql);
     echo json_encode($data);
     exit;
  }
?>
