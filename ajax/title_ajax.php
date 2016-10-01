<?php	
  session_start();
  include '../inc/connect.php';
  $school_id=$_SESSION['school_id'];
  $year=$_SESSION['year'];


  if ($_POST['oper']==0)//query
	{
        $sql = "select '1' id,title_name,year from title where school_id='$school_id' and year='$year' order by school_id";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }
	if ($_POST['oper']==1)//insert
  {
        include("../inc/connect_pdo_bob.php");
        try {  
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $dbh->beginTransaction();
          $sql="insert into title values('".$_POST['title_name']."','$school_id','".$_POST['year']."')";
          $sql = mb_convert_encoding($sql, "BIG5", "UTF-8");
          $dbh->exec($sql);
          
          if($_POST['import']==1){ //匯入目前招生資料
              $dbh->exec("insert into campus 
                        select id, name, school_id, ".$_POST['year'] ." from campus where  school_id='$school_id' and year='$year'");
              $dbh->exec("insert into department 
                        select name, campus_id, devide, id, prime,flag_of_status, test_type, 
                         create_subject, part, oral_flag,union_flag, short, location, school_id, ".$_POST['year'] ." from department where  school_id='$school_id' and year='$year'");
              $dbh->exec("insert into organize 
                        select id, name, dept_id, name2, oral_flag,union_flag, school_id, ".$_POST['year'] ." from organize where  school_id='$school_id' and year='$year'");
              $dbh->exec("insert into orastatus 
                        select id, allperson, resultperson, resultscore, secondperson, secondscore, organize_id, name, enrollperson, remark, school_id, ".$_POST['year'] ." from orastatus where  school_id='$school_id' and year='$year'");
              $dbh->exec("insert into subject 
                        select id, name, rate, orastatus_id, section, compare, qualified, standard, base_stan, top_stan, front_stan, beyond_stan, union_flag, school_id, ".$_POST['year'] ." from subject where  school_id='$school_id' and year='$year'");
              $dbh->exec("insert into test_area 
                        select test_area_id, test_area_name, address, school_id, ".$_POST['year'] ." from test_area where  school_id='$school_id' and year='$year'");
              $dbh->exec("insert into building 
                        select building_id, building_name, test_area_id, class_count, is_order,   school_id, ".$_POST['year'] ." from building where  school_id='$school_id' and year='$year'");
              $dbh->exec("insert into classroom 
                        select classroom_id, seat_number, building_id, is_across, comments,   school_id, ".$_POST['year'] ." from classroom where  school_id='$school_id' and year='$year'");
              $dbh->exec("insert into scoreremark 
                        select remark,   school_id, ".$_POST['year'] ." from scoreremark where  school_id='$school_id' and year='$year'");
          }
          $dbh->commit();
          $_SESSION['title_name']=$_POST['title_name'];
          $_SESSION['year']=$_POST['year'];
          $message=array("error_code"=>"","error_message"=>"","sql"=>"");          
        } catch (Exception $e) {
          $dbh->rollBack();
          $message=array("error_code"=>"009","error_message"=>$e->getMessage(),"sql"=>$sql);          
        }
        echo json_encode($message);
        exit;
  }
	if ($_POST['oper']==2) //update
	{ 	  
        $sql = "update title set title_name = '".$_POST['title_name']."' where school_id='$school_id' and year='$year'";	  
        $data = $db -> query($sql);     
        if($data['code']==""){
          $_SESSION['title_name']=$_POST['title_name'];          
        }          
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);  
        exit;    
  }
	
	if ($_POST['oper']==3)//delete
	{
        //改用利用foreign-key限制刪除
        //check if exist any student data!!!
        $sql = "select id from signupdata where school_id='$school_id' and year='$year'";
        $data = $db -> query_array($sql); 
        if(sizeof($data['ID'])>0){
          $message=array("error_code"=>sizeof($data['ID']),"error_message"=>"目前本項招生已有考生資料，無法刪除!","sql"=>$sql);
          echo json_encode($message);  
          exit;
        }

        $sql = "delete from title where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        $sql = "delete from campus where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        $sql = "delete from department where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        $sql = "delete from organize where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        $sql = "delete from orastatus where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        $sql = "delete from subject where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        $sql = "delete from test_area where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        $sql = "delete from building where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        $sql = "delete from classroom where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        $sql = "delete from scoreremark where school_id='$school_id' and year='$year'";
        $data = $db -> query($sql); 
        //if($data['code'] !="") $rollback_flag = 1;

        // $sql = "delete from test_area where school_id='$school_id' and year='$year'";
        // $data = $db -> query($sql,"OCI_NO_AUTO_COMMIT"); 
        
        // $result = $db -> commit();   //Trancation commit!

        if($data['code']==""){ //success,重新查詢目前招生名稱
          $sql="select title_name,school_id,year from title t where school_id='$school_id' order by year desc "; 
          $d = $db -> query_array($sql);                  
          
          $_SESSION['school_id'] = $d['SCHOOL_ID'][0];
          $_SESSION['title_name'] = $d['TITLE_NAME'][0];
          $_SESSION['year'] = $d['YEAR'][0];
        }
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message); 
        exit;  		
  }

	
?>
