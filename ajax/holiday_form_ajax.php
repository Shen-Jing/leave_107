<?php
  include_once("../inc/check.php");

  // 查詢欄位
  if ($_POST['oper'] == "qry_item") {
    $data = array();
    $empl_no = $_POST['empl_no'];
    $depart = $_SESSION['depart'][0];

    // 單位
    $sql = "SELECT dept_no, dept_full_name
            FROM  psfcrjb, stfdept
            WHERE crjb_empl_no = '$empl_no'
            AND   crjb_quit_date IS NULL
            AND   crjb_depart = dept_no
            ORDER BY crjb_seq DESC";
    $tmp_data = $db -> query_array($sql);
    $data['qry_dept'] = $tmp_data;

    // 職稱
    $sql = "SELECT code_chn_item, code_field
						FROM  psfcrjb, psqcode
						WHERE  crjb_empl_no = '$empl_no'
						AND    crjb_quit_date IS NULL
						AND    crjb_depart = '$depart'
						AND    code_kind = '0202'
						AND    code_field = crjb_title";
    $tmp_data = $db -> query_array($sql);
    $data['qry_title'] = $tmp_data;

    $sql = "SELECT code_field, code_chn_item
						FROM psqcode
						WHERE code_kind = '0302'
						ORDER BY code_field";
    $tmp_data = $db -> query_array($sql);
    $data['qry_vtype'] = $tmp_data;

    echo json_encode($data);
    exit;
  }

?>
