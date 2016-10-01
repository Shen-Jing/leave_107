<?php
	session_start();
  include '../inc/connect.php';
  $school_id=$_SESSION['school_id'];
  $year=$_SESSION['year'];


  if ($_POST['oper']==0)//query
	{
        $sql = "select * from test_area where  school_id='$school_id' and year='$year' order by test_area_id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }
	if ($_POST['oper']==1)//insert
  {
        $sql = "insert into test_area values(".$_POST['test_area_id'].", '".$_POST['test_area_name']."', '".$_POST['address']."','$school_id','$year')";
    
        $data = $db -> query($sql);
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);
        exit;
  }
	if ($_POST['oper']==2) //update
	{ 	  
        $sql = "update test_area set test_area_id = '".$_POST['test_area_id']."',test_area_name = '".$_POST['test_area_name']. "',address = '".$_POST['address']."' where test_area_id = ".$_POST['old_id']  ." and school_id='$school_id' and year='$year'";	  
        $data = $db -> query($sql);               
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);   
        exit;   
  }
	
	if ($_POST['oper']==3)//delete
	{
        $sql = "delete from test_area where test_area_id = ".$_POST['old_id']  ." and  school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);                   
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);   	
        exit;	
  }

	
?>
