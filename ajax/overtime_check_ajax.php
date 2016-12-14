<?php
  include '../inc/connect.php';

  // 查詢年份與系所
  if ($_POST['oper'] == "qry_year_and_dept"){
    $data = array();

    // 查詢年份
    $sql = "SELECT substr(to_char(sysdate, 'yyyymmdd'), 1, 4) - '1911' end_year,
          substr(to_char(sysdate, 'yyyymmdd'), 5, 2) end_month
          FROM dual";
    $tmp_data = $db -> query_array($sql);
    $data['qry_year'] = $tmp_data;

    // 查詢系所
    $sql = "SELECT min(dept_no) dept_no, min(dept_full_name) dept_full_name
  					FROM stfdept
  					WHERE use_flag IS NULL
  					GROUP BY substr(dept_no, 1, 2)";
    $tmp_data = $db -> query_array($sql);
    $data['qry_dept'] = $tmp_data;

    echo json_encode($data);
    exit;
  }

	// 根據年份、系所查詢加班名單
  if ($_POST['oper'] == 0){
		$year = $_POST['year'];
		$dept = $_POST['dept'];

		if ($dept == ""){
			$sql = "SELECT   dept_short_name depart, empl_chn_name, f.email, over_date, do_time_1,
						   do_time_2, nouse_time, draw_date, o.empl_no, o.reason
							FROM   overtime o, psfempl f, psfcrjb c, stfdept t
							WHERE  f.empl_no = o.empl_no
							AND    c.crjb_empl_no = o.empl_no
							AND    c.crjb_depart = t.dept_no
							AND 	 c.crjb_quit_date IS NULL
							AND    substr(over_date, 1, 3) = '$year'
							AND    person_check = '0'";
		}
		else {
			$sql = "SELECT   dept_short_name depart, empl_chn_name, f.email, over_date, do_time_1,
					   do_time_2, nouse_time, draw_date, o.empl_no,o.reason
							FROM   overtime o,psfempl f,psfcrjb c,stfdept t
							WHERE  f.empl_no = o.empl_no
							AND    c.crjb_empl_no = o.empl_no
							AND    c.crjb_depart = t.dept_no AND c.crjb_quit_date IS NULL
							AND    substr(over_date, 1, 3) = '$year'
							AND    substr(crjb_depart, 1, 2) = substr('$dept', 1, 2)
							AND    person_check = '0'";
		}
    $data = $db -> query_array($sql);

    echo json_encode($data);
    exit;
  }

	// 修改時數
	if ($_POST['oper'] == 2){
		$sql = "SELECT lpad(to_char(sysdate, 'yyyymmdd') - '19110000', 7, '0') ndate
				  FROM dual";
		$data = $db -> query_array($sql);
		// 誰審核通過的
		$ndate = $_SESSION['empl_no'] . $data['NDATE'][0];
		// 欲修改成的時數
    $nouse = $_POST['nouse_time'];
    $empl_no = $_POST['empl_no'];
    // 加班日期
    $over_date = $_POST['over_date'];

		$sql = "UPDATE overtime
				 SET nouse_time = '$nouse', time_date = '$ndate'
				 WHERE empl_no = '$empl_no'
				 AND over_date = '$over_date'";

		$data = $db -> query($sql);

		$message = array("error_code" => $data['code'], "error_message" => $data['message'], "sql" => $sql);
		echo json_encode($message);
	}

	// 審核加班
	if ($_POST['oper'] == 4){
		$empl_no = @$_POST['empl_no'];
	  $over_date = @$_POST['over_date'];

		$sql = "SELECT lpad(to_char(sysdate, 'yyyymmdd') - '19110000', 7, '0') ndate
				  FROM dual";
		$data = $db -> query_array($sql);

		// 誰審核通過的 + 系統日期
		$ndate = $_SESSION['empl_no'] . $data['NDATE'][0];
		$sql = "UPDATE overtime
						SET person_check = '1', check_date = '$ndate'
						WHERE empl_no = '$empl_no'
						AND   over_date = '$over_date'";
		$data = $db -> query($sql);
		// $message = array("error_code" => $data['code'], "error_message" => $data['message'], "sql" => $sql);
    echo json_encode($data);
    exit;
	}


?>
