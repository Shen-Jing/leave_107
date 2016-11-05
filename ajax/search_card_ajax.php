<?php
  session_start();
  include '../inc/connect.php';
  $empl_no = $_SESSION['empl_no'];

  $today  = getdate();
  $year    = $today["year"] - 1911;


  if ($_POST['oper']=="qry_year"){
      $sql="select substr(to_char(sysdate,'yyyymmdd'),1,4)-'1911' end_year,substr(to_char(sysdate,'yyyymmdd'),5,2) end_month from dual";

      $data = $db -> query_array($sql);


      echo json_encode($data);
      exit;
  }

  else if($_POST['oper']==0)
  {
      $p_year=$_POST['p_year'];
      $p_month=$_POST['p_month'];

      $sql = "select  substr(lpad(do_dat,7,'0'),6,2) day, memo,
            substr(do_time,1,2)||':'||substr(do_time,3,2) do_time
            from ps_card_data p
            where  empl_no='$empl_no'
            and  substr(lpad(do_dat,7,'0'),1,3)=lpad('$p_year',3,'0')
            and  substr(lpad(do_dat,7,'0'),4,2)=lpad('$p_month',2,'0')
            order by day desc,do_time  ";


      $data = $db -> query_array($sql);

      echo json_encode($data);
      exit;

  }


?>
