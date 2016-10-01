<?php	
  session_start();
  include '../inc/connect.php';
  $school_id=$_SESSION['school_id'];
  $year=$_SESSION['year'];

  if ($_POST['oper']=="saveall")//saveall
  {
        $sql = "select id from signupdata where school_id='$school_id' and year='$year'";
        $data = $db -> query_array($sql); 
        if(sizeof($data['ID'])>0){
          $message=array("error_code"=>sizeof($data['ID']),"error_message"=>"目前已有學生資料，無法刪除!","sql"=>$sql);
          echo json_encode($message);  
          exit;
        }
        $sql = "delete from campus where school_id='$school_id' and year='$year'";    
        $data = $db -> query($sql);
        for($i=0;$i<=9;$i++)
        {
          if($_POST["id$school_id$i"]!="")//ex.id21
          {
            $sql = "insert into campus values(".$_POST["id$school_id$i"].", '".$_POST["name$school_id$i"]."','$school_id','$year')";    
            $data = $db -> query($sql);
          }
        }
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message); 
        exit;     
  }

  if ($_POST['oper']==0)//query
	{
        $sql = "select * from campus where school_id='$school_id' and year='$year' order by id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }
	if ($_POST['oper']==1)//insert
  {
        $sql = "insert into campus values(".$_POST['id'].", '".$_POST['name']."','$school_id','$year')";
    
        $data = $db -> query($sql);
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);
        exit;
  }

  

	if ($_POST['oper']==2) //update
	{ 	  
        $sql = "update campus set id = '".$_POST['id']."',name = '".$_POST['name']."' where id = ".$_POST['old_id']  ." and  school_id='$school_id' and year='$year'";	  
        $data = $db -> query($sql);               
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);  
        exit;    
  }
	
	if ($_POST['oper']==3)//delete
	{
        $sql = "select id from signupdata where school_id='$school_id' and year='$year'";
        $data = $db -> query_array($sql); 
        if(sizeof($data['ID'])>0){
          $message=array("error_code"=>sizeof($data['ID']),"error_message"=>"目前已有學生資料，無法刪除!","sql"=>$sql);
          echo json_encode($message);  
          exit;
        }
        $sql = "delete from campus where id = ".$_POST['old_id']  ." and school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);          
        $sql = "delete from department where id like '".$_POST['old_id']  ."%' and school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);
        $sql = "delete from organize where id like '".$_POST['old_id']  ."%' and school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);
        $sql = "delete from orastatus where id like '".$_POST['old_id']  ."%' and school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);  
        $sql = "delete from subject where id like '".$_POST['old_id']  ."%' and school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);       
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message); 
        exit;  		
  }

	
?>
