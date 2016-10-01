<?php
	session_start();
  include '../inc/connect.php';
  $school_id=$_SESSION['school_id'];
  $year=$_SESSION['year'];


  if ($_POST['oper']=="qry_campus")//查詢學院
  {
        $sql = "select * from campus where school_id='$school_id' and year='$year' order by id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }

  if ($_POST['oper']=="qry_scoreremark")//查詢成績單備註說明
  {
        $sql = "select remark from scoreremark where school_id='$school_id' and year='$year' ";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }

  if ($_POST['oper']=="save_scoreremark")//儲存成績單備註說明
  {
        $remark = $_POST['remark'];
        $sql = "update scoreremark set remark ='$remark' where school_id='$school_id' and year='$year' ";
        $data = $db -> query($sql);
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);
        exit;
  }

  if ($_POST['oper']=="qry_dept")//查詢系所
  {
        $sql = "select * from department where campus_id='" . $_POST['campus_id'] ."' and school_id='$school_id' and year='$year' order by id";
        $data = $db -> query_array ($sql);        
        echo json_encode($data);
        exit;
  }


  
?>
