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

  if ($_POST['oper']=="qry_dept")//查詢系所
  {
        $sql = "select * from department where campus_id='" . $_POST['campus_id'] ."' and school_id='$school_id' and year='$year' order by id";
        $data = $db -> query_array ($sql);        
        echo json_encode($data);
        exit;
  }


  if ($_POST['oper']==0)//query
	{
        $sql = "select * from subject where school_id='$school_id' and year='$year' and substr(id,1,3)='".$_POST['dept_id']."' order by section,id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }
	if ($_POST['oper']==1)//insert
  {
        $sql = "insert into subject(id,section,name,rate,qualified,compare,school_id,year) values(".$_POST['id'].", '".$_POST['section']."', '".$_POST['name']."', '".$_POST['rate']."', '".$_POST['qualified']."', '".$_POST['compare']."','$school_id','$year')";
    
        $data = $db -> query($sql);
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);
        exit;
  }
  if ($_POST['oper']==2) //update
  {     
        $sql = "select subject_id from persub where school_id='$school_id' and year='$year' and subject_id='".$_POST['old_id'] ."'";
        $data = $db -> query_array($sql); 
        if(sizeof($data['SUBJECT_ID'])>0){
          $message=array("error_code"=>sizeof($data['SUBJECT_ID']),"error_message"=>"目前本項招生已有考生資料，無法刪除!","sql"=>$sql);
          echo json_encode($message);  
          exit;
        }

        $sql = "update subject set id = '".$_POST['id']."',name = '".$_POST['name']."',section = '".$_POST['section']."',rate = '".$_POST['rate']."',qualified = '".$_POST['qualified']."',compare = '".$_POST['compare']."' where id = ".$_POST['old_id']  ." and  school_id='$school_id' and year='$year'";   
        $data = $db -> query($sql);               
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);  
        exit;    
  }
  
  if ($_POST['oper']==3)//delete
  {
        $sql = "select subject_id from persub where school_id='$school_id' and year='$year' and subject_id='".$_POST['old_id'] ."'";
        $data = $db -> query_array($sql); 
        if(sizeof($data['SUBJECT_ID'])>0){
          $message=array("error_code"=>sizeof($data['SUBJECT_ID']),"error_message"=>"目前本項招生已有考生資料，無法刪除!","sql"=>$sql);
          echo json_encode($message);  
          exit;
        }
        $sql = "delete from subject where id = ".$_POST['old_id']  ." and school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);                   
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message); 
        exit;     
  }
?>
