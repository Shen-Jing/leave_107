<?php
	session_start();
  include '../inc/connect.php';
  $school_id = $_SESSION['school_id'];
  $year = $_SESSION['year'];

  if ($_POST['oper']==0)//query
	{
        $sql = "select person_student_id id ,substr(subject_id,6,1) section,disobey,remark,score,result from persub where school_id='$school_id' and year='$year' and disobey >0 order by person_student_id,section";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }
	if ($_POST['oper']==1)//新增
  {
        $id = $_POST['id'];
        $section = $_POST['section'];
        $disobey = $_POST['disobey'];
        $remark = $_POST['remark'];
        $sql = "update persub set disobey=$disobey,remark='$remark',result=(score-$disobey) where school_id='$school_id' and year='$year' and person_student_id='$id' and substr(subject_id,6,1)='$section' ";  
        $data = $db -> query($sql);
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);
        exit;
  }
	if ($_POST['oper']==2) //update
	{ 	  
        $id = $_POST['id'];
        $section = $_POST['section'];
        $disobey = $_POST['disobey'];
        $remark = $_POST['remark'];
        $sql = "update persub set disobey=$disobey,remark='$remark',result=(score-$disobey) where school_id='$school_id' and year='$year' and person_student_id='$id' and substr(subject_id,6,1)='$section' ";  
        $data = $db -> query($sql);               
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);   
        exit;   
  }
	
	if ($_POST['oper']==3)//delete
	{
        $id = $_POST['id'];
        $section = $_POST['section'];
        $sql = "update persub set disobey=0,remark='',result=score where school_id='$school_id' and year='$year' and person_student_id='$id' and substr(subject_id,6,1)='$section' ";
        $data = $db -> query($sql);                   
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);   	
        exit;	
  }

	
?>
