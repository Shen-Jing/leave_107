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

    if ($_POST['oper']=="qry_barcode") {
        $str = "SELECT P.MAKENUMBER, P.PERSON_STUDENT_ID, P.SUBJECT_ID, S.NAME FROM PERSUB P, SUBJECT S WHERE SUBSTR(P.MAKENUMBER, 1, 3) = ".$_POST['id']." AND P.SUBJECT_ID = S.ID ORDER BY P.SUBJECT_ID ASC, P.PERSON_STUDENT_ID ASC";
        $barcode = $db -> query_array ($str);
        echo json_encode($barcode);
    }
?>
