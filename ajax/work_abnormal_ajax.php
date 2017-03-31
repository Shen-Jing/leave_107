<?php
  session_start();
  include '../inc/connect.php';

	// 根據年份、月份、系所查詢差假狀況
  if ($_POST['oper'] == 0){
    // ex: 2017/03/29
    list($p_menu, $m_menu, $d_menu) = explode("/", $_POST['select_date']);
    $p_menu -= 1911;
    $sql ="SELECT *
            FROM abnormal
            WHERE substr(lpad(do_dat, 7, '0'), 1, 3) = lpad('$p_menu', 3, '0')
            AND   substr(lpad(do_dat, 7, '0'), 4, 2) = lpad('$m_menu', 2, '0')
            AND   substr(lpad(do_dat, 7, '0'), 6, 2) = lpad('$d_menu', 2, '0')";
    $data = $db -> query_array($sql);

    echo json_encode($data);
    exit;
  }

?>
