<?php
  session_start();
  include '../inc/connect.php';
  $empl_no = $_SESSION['empl_no'][0];

  $today  = getdate();
  $year    = $today["year"] - 1911;


  if ($_POST['oper']=="qry_year"){
      $sql="select lpad(to_char(sysdate,'yyyymmdd')-'19110000',7,'0') ndate,
                  lpad(to_char(sysdate,'yyyy')-'1911',3,'0') year
                  from dual";

      $data = $db -> query_array($sql);

      /*if (!IsSet($_SESSION['yy'])){
                $end_year = $year;
                $_SESSION['yy']=$year;
                $sel_y=$year;
      }
      else{ //page updated then restore,because $end_year must be reflashed ;
            $end_year=$_SESSION['yy'];
            @$_POST['p_menu']=$_GET['yval'];
      } */

      if (@$_POST['p_menu']=='')
            $_POST['p_menu']=$year;

      if(strlen($_POST['p_menu'])<3)
            $_POST['p_menu']="0".$_POST['p_menu'];

      echo json_encode($data);
      exit;
  }

  else if($_POST['oper']==0)
  {

      $p_menu=$_POST['p_year'];
      $sql = "SELECT  count(*) count
              FROM  overtime
              WHERE empl_no='$empl_no'
              AND   substr(over_date,1,3)='$p_menu'";

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
                    where empl_no='$empl_no'
                    and   substr(over_date,1,3)='$p_menu'
                    order by over_date";

          $data = $db -> query_array($SQLStr);

          echo json_encode($data);
          exit;
      }

  }
  else if($_POST['oper']==3)
  {
      $sql=  "delete from overtime
              where empl_no ='$empl_no'
              and over_date='$_POST[old_id]'";

      $data = $db -> query($sql);

      $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);

      echo json_encode($message);
      exit;
  }


?>
