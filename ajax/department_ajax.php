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
        $sql = "select dept_no,dept_full_name from per.stfdept 
        where substr(dept_no,1,1)<='9' and substr(dept_no,2,1)<>'0' 
        and substr(dept_no,1,2)<>'72' and use_flag is null 
        order by dept_no";
        $data = $db -> query_array ($sql);
        echo json_encode($data);
        exit;
  }


  if ($_POST['oper']==0)//query
	{
        $sql = "select * from department where school_id='$school_id' and year='$year' and campus_id='".$_POST['campus_id']."' order by id";
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
          insert();
          $dbh->commit();          
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
        $sql = "select id from signupdata where school_id='$school_id' and year='$year'";
        $data = $db -> query_array($sql); 
        if(sizeof($data['ID'])>0){
          $message=array("error_code"=>sizeof($data['ID']),"error_message"=>"目前已有考生資料，無法異動!","sql"=>$sql);
          echo json_encode($message);  
          exit;
        }

        include("../inc/connect_pdo_bob.php");
        try {  
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $dbh->beginTransaction();

          $campus_id = $_POST['campus_id'] ;
          if($_POST['old_id']=='all')//全部儲存
          { 
            for($i=0;$i<=9;$i++)          
            {            
              if($_POST["id$campus_id$i"]!="")//ex.id312
              {  
                del("$campus_id$i");
                insert("$campus_id$i");
              }
            }   
          }
          else //單筆更新
          {
            del($_POST['old_id']);          
            insert($_POST['old_id']);
          }

          $dbh->commit();          
          $message=array("error_code"=>"","error_message"=>"","sql"=>"");          
        } catch (Exception $e) {
          $dbh->rollBack();
          $message=array("error_code"=>"009","error_message"=>$e->getMessage(),"sql"=>$sql);          
        }    
        echo json_encode($message); 
        exit;    
  }
	
	if ($_POST['oper']==3)//delete
	{
        $sql = "select id from signupdata where school_id='$school_id' and year='$year' and dept_id=".$_POST['old_id'];
        $data = $db -> query_array($sql); 
        if(sizeof($data['ID'])>0){
          $message=array("error_code"=>sizeof($data['ID']),"error_message"=>"目前此系所已有考生資料，無法刪除!","sql"=>$sql);
          echo json_encode($message);  
          exit;
        }
        include("../inc/connect_pdo_bob.php");
        try {  
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $dbh->beginTransaction();

          del($_POST['old_id']);

          $dbh->commit();          
          $message=array("error_code"=>"","error_message"=>"","sql"=>"");          
        } catch (Exception $e) {
          $dbh->rollBack();
          $message=array("error_code"=>"009","error_message"=>$e->getMessage(),"sql"=>$sql);          
        }    

        echo json_encode($message);   
        exit;		
  }

function insert($id=""){
          global $dbh,$sql,$school_id,$year;
          for($i=1;$i<=6;$i++){
            if(in_array($i,$_POST["part$id"]))
              $part .="1";
            else 
              $part .="0";
          }
          for($i=1;$i<=3;$i++){
            if(in_array($i,$_POST["flag_of_status$id"]))
              $flag_of_status .="1";
            else 
              $flag_of_status .="0";
          }
          //*****department*********
          $sql = "insert into department(id,name,campus_id,test_type,devide,part,prime,flag_of_status,location,school_id,year) values('".$_POST["id$id"]."', '".$_POST["name$id"]."', '".$_POST["campus_id"]."','" .$_POST["test_type$id"]."', '".$_POST["devide$id"]."','".$part."','".$_POST["prime$id"]."','".$flag_of_status ."','".$_POST["location$id"]."','$school_id','$year')";
          $sql = mb_convert_encoding($sql, "BIG5", "UTF-8");
          $dbh->exec($sql);

          //****organize**************
          $test_type = $_POST["test_type$id"] ;          
          if($test_type==0 || $test_type==2) //不分組及選考不分組
              $devide = 1;          
          else          
              $devide = $_POST["devide$id"] ;
          
          $arr_group = array(1=>"甲組","乙組","丙組","丁組","戊組","己組");
          for($i=1;$i<=$devide;$i++){
              if($test_type==0){
                  $organize_id = $_POST["id$id"]."0";//不分組
                  $group_name = "不分組";
              }
              else if($test_type==2){
                  $organize_id = $_POST["id$id"]."9";//選考不分組
                  $group_name = "選考不分組";
              } 
              else {
                  $organize_id = $_POST["id$id"].$i ;//分組
                  $group_name = $arr_group[$i];
              }
              $sql = "insert into organize(id,name,dept_id,school_id,year) values('".$organize_id."', '".$group_name."', '".$_POST["id$id"]."','$school_id','$year')";  
              $sql = mb_convert_encoding($sql, "BIG5", "UTF-8");
              $dbh->exec($sql); 

              //***********orastatus****************
              if(substr($flag_of_status,0,1)==1){//一般生
                  $sql = "insert into orastatus(id,organize_id,name,school_id,year)
                   values('".$organize_id."1', '$organize_id','一般生','$school_id','$year')";
                  $sql = mb_convert_encoding($sql, "BIG5", "UTF-8");
                  $dbh->exec($sql);
              }
              if(substr($flag_of_status,1,1)==1){//在職生,若轉學考->010(二年級)011(三年級)
                  if($_SESSION['school_id']==4 || $_SESSION['school_id']==5){//轉學考
                      if($flag_of_status=="010"){
                        $orastatus_id=$organize_id."2";
                        $orastatus_name="二年級";
                      }
                      if($flag_of_status=="011"){
                        $orastatus_id=$organize_id."3";
                        $orastatus_name="三年級";
                      }
                  }
                  else{
                      $orastatus_id=$organize_id."2";
                      $orastatus_name="在職生";
                  }
                  $sql = "insert into orastatus(id,organize_id,name,school_id,year)
                   values('$orastatus_id', '$organize_id','$orastatus_name','$school_id','$year')";
                  $sql = mb_convert_encoding($sql, "BIG5", "UTF-8");
                  $dbh->exec($sql);
              }//在職生
          }         
}

function del($id){     
  global $dbh,$school_id,$year;
  if($id="" || $school_id=="" || $year=="")
  {
      $message=array("error_code"=>"008","error_message"=>"異動資料發生錯誤!","sql"=>$sql);
      echo json_encode($message);  
      exit;
  }
  $sql = "delete from department where id like '".$id."%' and school_id='$school_id' and year='$year'";
  $dbh->exec($sql);
  $sql = "delete from organize where id like '".$id ."%' and school_id='$school_id' and year='$year'";         
  $dbh->exec($sql);
  $sql = "delete from orastatus where id like '".$id ."%' and school_id='$school_id' and year='$year'";         
  $dbh->exec($sql);
  $sql = "delete from subject where id like '".$id ."%' and school_id='$school_id' and year='$year'";         
  $dbh->exec($sql);
}
	
?>
