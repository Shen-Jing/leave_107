<?php	
  session_start();
  include '../inc/connect.php';

  if ($_POST['oper']=="qry_cond1")//查詢目前招生資料
  {
        $sql = "select id,name,(select title_name from title 
                where school_id=a.id and year=(select max(year) from title where school_id=a.id)) title_name,(select max(year) from title where school_id=a.id) year 
                from school a order by id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }
	
?>
