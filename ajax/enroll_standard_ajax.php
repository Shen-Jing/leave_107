<?php
	session_start();
  include '../inc/connect.php';
  $school_id = $_SESSION['school_id'];
  $year = $_SESSION['year'];

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
        if(strlen($_POST['campus_id'])==2) $sql_cond = $_POST['campus_id'] ."%" ;//依學院查詢
        if(strlen($_POST['dept_id'])==3) $sql_cond = $_POST['dept_id'] . "%"; //依系所查詢
        
        $sql = "select a.id,a.allperson,a.enrollperson,a.resultperson,a.resultscore,a.secondperson,a.secondscore,a.name orastatus_name,b.name dept_name,c.name organize_name from orastatus a,department b,organize c
        where a.school_id='$school_id' and a.year='$year' and a.id like '$sql_cond'
        and b.school_id=a.school_id and b.year=a.year and b.id=substr(a.id,1,3)
        and c.school_id=a.school_id and c.year=a.year and c.id=substr(a.id,1,4)
        order by id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }
	
  if ($_POST['oper']==2) //update
  {     
        $sql = "update orastatus set allperson = ".$_POST['allperson'].",enrollperson = ".$_POST['enrollperson'].",resultperson = ".$_POST['resultperson'].",resultscore = ".$_POST['resultscore'].",secondperson = ".$_POST['secondperson'].",secondscore = ".$_POST['secondscore']." where id = ".$_POST['old_id']  ." and  school_id='$school_id' and year='$year'";   
        $data = $db -> query($sql);               
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);  
        exit;    
  }
  
  
?>
