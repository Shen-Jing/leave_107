<?php
  session_start();
  include '../inc/connect.php';
  $empl_no = $_SESSION['empl_no'];
  $userid=$_SESSION["empl_no"];

  $today  = getdate();
  $year    = $today["year"] - 1911;
  //$year=$_SESSION['yy'];


  if ($_POST['oper']=="qry_year"){
      $sql="select lpad(to_char(sysdate,'yyyymmdd')-'19110000',7,'0') ndate, 
                  lpad(to_char(sysdate,'yyyy')-'1911',3,'0') year
                  from dual";
      $data = $db -> query_array($sql);

            //$stmt=ociparse($conn,$sql); 
            
            //ociexecute($stmt,OCI_DEFAULT); 
            
            /*if (OCIFETCH($stmt)){
                @$ndate = OCIRESULT($stmt,NDATE);    //系統日期
                @$year  = OCIRESULT($stmt,YEAR);
                $sel_y=$year;      
            }*/
        
      if (!IsSet($_SESSION['yy'])){ 
                $end_year = $year; 
                $_SESSION['yy']=$year;
                $sel_y=$year;
      }
      else{ //page updated then restore,because $end_year must be reflashed ;
            $end_year=$_SESSION['yy'];
            @$_POST['p_menu']=$_GET['yval'];
      } 
        
      if (@$_POST['p_menu']=='')
            $_POST['p_menu']=$year;
        
      if(strlen($_POST['p_menu'])<3)
            $_POST['p_menu']="0".$_POST['p_menu'];

    
    
    echo json_encode($data);
    exit;
  }

  else if($_POST['oper']==0){
            $sql = "SELECT  count(*) count
                    FROM  overtime 
                    where empl_no='$userid'
                    and   substr(over_date,1,3)='$_POST[p_menu]'";
            $data = $db -> query_array($sql);
            if($data['COUNT'][0]==0)
            { 
              echo json_encode($data);
              exit;
            }
            else
            {
                  $SQLStr ="SELECT *
                            FROM overtime
                            where empl_no='$userid'
                            and   substr(over_date,1,3)='$_POST[p_menu]'
                            order by over_date";    
                  $data = $db -> query_array($SQLStr);

                  echo json_encode($data);
                  exit;
            }

  }
        
  
?>
