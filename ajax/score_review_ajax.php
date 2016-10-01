<?php
	session_start();
  include '../inc/connect.php';
  $school_id = $_SESSION['school_id'];
  $year = $_SESSION['year'];
  $id = $_POST['id'];
  
  if ($_POST['oper']=='query')//query
	{
        $sql = "select subject_id,result,bagid,secondorder,substr(bagid,8,3) classroom_id,bag_no ,
          (select name from subject where school_id='$school_id' and year='$year' and id=a.subject_id) subject_name 
          from persub a 
          where school_id='$school_id' and year='$year' 
          and person_student_id='$id' and bagid is not null and substr(subject_id,6,1)<5 
          order by subject_id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }
	
?>
