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

  if ($_POST['oper']=="qry_dept_org")//查詢可排系所組別人數
  {
        // $sql = "select distinct b.id,a.name,b.name as ora_name,d.count from department a,organize b,signupdata c,
        // (select b.id,count(b.id) as count 
        // from department a,organize b,signupdata c where a.campus_id='".$_POST['campus_id']."' and a.id=b.dept_id and c.organize_id=b.id group by b.id) d 
        // where a.campus_id='".$_POST['campus_id']."' and a.id=b.dept_id and c.organize_id=b.id and b.id = d.id";
    $sql ="select a.id,a.name ora_name,
          (select name from department where id=substr(a.id,1,3) and year='$year' ) name ,
          (select count(*) from person where year='$year' and student_id like a.id||'%') count
          from organize a where id like '".$_POST['campus_id']."%' and year='$year' and id not in 
          (select distinct substr(student_id,1,4)  from seat where school_id='$school_id' and year='$year')
           order by a.id";
        $data = $db -> query_array ($sql);        
        echo json_encode($data);
        exit;
  }

  if ($_POST['oper']=="qry_area")//查詢考區
  {
        $sql = "select * from test_area where school_id='$school_id' and year='$year' order by test_area_id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }

  if ($_POST['oper']=="qry_building")//查詢系館
  {
        $sql = "select * from building where school_id='$school_id' and year='$year' and test_area_id='" . $_POST['test_area_id'] ."' order by building_id";
        $data = $db -> query_array ($sql);        
        echo json_encode($data);
        exit;
  }
  
  if ($_POST['oper']=="qry_classroom")//查詢某一系館教室及人數
  {
        $sql = "select classroom_id,seat_number,comments from classroom where school_id='$school_id' and year='$year' and building_id='".$_POST['building_id'] ."' order by classroom_id";
        $data = $db -> query_array ($sql);        
        echo json_encode($data);
        exit;
  }

  if ($_POST['oper']=="arrange")//送出排列
  {
        $seat_number = $_POST['seat_number9'] ;
        $str_arrange = $_POST['str_arrange'] ;
        $message = array("seat_number"=>$seat_number,"str_arrange"=>$str_arrange);
        echo json_encode($message);
        // $sql = "select CLASSROOM_ID,SEAT_NUMBER,COMMENTS from CLASSROOM where BUILDING_ID='".$_POST['building_id'] ."'";
        // $data = $db -> query_array ($sql);        
        // echo json_encode($data);
        exit;
  }

  if ($_POST['oper']=="re_arrange")//全部重排
  {//not yet!!
        $seat_number = $_POST['seat_number9'] ;
        $str_arrange = $_POST['str_arrange'] ;
        $message = array("seat_number"=>$seat_number,"str_arrange"=>$str_arrange);
        echo json_encode($message);
        // $sql = "select CLASSROOM_ID,SEAT_NUMBER,COMMENTS from CLASSROOM where BUILDING_ID='".$_POST['building_id'] ."'";
        // $data = $db -> query_array ($sql);        
        // echo json_encode($data);
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
        $sql = "update subject set id = '".$_POST['id']."',name = '".$_POST['name']."',section = '".$_POST['section']."',rate = '".$_POST['rate']."',qualified = '".$_POST['qualified']."',compare = '".$_POST['compare']."' where id = ".$_POST['old_id']  ." and  school_id='$school_id' and year='$year'";   
        $data = $db -> query($sql);               
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);  
        exit;    
  }
  
  if ($_POST['oper']==3)//delete
  {
        $sql = "delete from subject where id = ".$_POST['old_id']  ." and school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);                   
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message); 
        exit;     
  }
?>
