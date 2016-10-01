<?php
	session_start();
  include '../inc/connect.php';
  $school_id=$_SESSION['school_id'];
  $year=$_SESSION['year'];

  if ($_POST['oper']=="qry_area")//查詢考區
  {
        $sql = "select * from test_area where school_id='$school_id' and year='$year' order by test_area_id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }


  if ($_POST['oper']==0)//query
	{
        $sql = "select * from building where school_id='$school_id' and year='$year' and test_area_id='".$_POST['test_area_id']."' order by building_id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }
	if ($_POST['oper']==1)//insert
  {
        $sql = "insert into building values(".$_POST['building_id'].", '".$_POST['building_name']."', '".$_POST['test_area_id']."', '".$_POST['class_count']."','0','$school_id','$year')";    
        $data = $db -> query($sql);

        create_classroom() ;

        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);
        exit;
  }
	if ($_POST['oper']==2) //update
	{ 	  
        $sql = "update building set building_id = '".$_POST['building_id']."',building_name = '".$_POST['building_name']. "',class_count = '".$_POST['class_count']."' where building_id = ".$_POST['old_id'] ." and school_id='$school_id' and year='$year'";	  
        $data = $db -> query($sql);   

        create_classroom() ;

        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message); 
        exit;    
  }
	
	if ($_POST['oper']==3)//delete
	{
        $sql = "delete from building where building_id = ".$_POST['old_id'] ." and school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);  

        create_classroom() ;         

        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);   
        exit;		
  }

  function create_classroom()
  {
     global $school_id , $year,$db;
     $sql = "delete from classroom where school_id='$school_id' and year='$year'";
     $data = $db -> query($sql); 
     $sql = "delete from backup where school_id='$school_id' and year='$year'";
     $data = $db -> query($sql); 
     
     $sql="select * from building where school_id='$school_id' and year='$year' order by test_area_id,building_id";
     $data = $db -> query_array ($sql);
     $classroom_id=0;
     for($i=0;$i<sizeof($data['CLASS_COUNT']);$i++)
     {        
        $class_count = $data['CLASS_COUNT'][$i];
        $building_id = $data['BUILDING_ID'][$i];
               
        for($k=0;$k<$class_count;$k++)
        {
          $classroom_id++;
          $sql = "insert into classroom values($classroom_id,42,$building_id,0,'','$school_id','$year')";
          $data = $db -> query($sql); 

          for($j=1;$j<=5;$j++)
          {
            if($classroom_id<=9)
              $back_number="00".($classroom_id)."10".$j;
            else 
              $back_number="0".($classroom_id)."10".$j;
            $sql = "insert into backup values('$back_number','0','0','0','$school_id','$year')";
            $data = $db -> query($sql);
          }
        }

     }
  }

	
?>
