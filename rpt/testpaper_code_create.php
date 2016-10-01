<?php
  session_start();
  include './inc/connect.php';
  $file=date("YmdHis") . ".txt";//開啟檔案
  $fp=fopen("./rpt/$file","w");
  $dept_id = $_POST['qry_dept'] ;
  $school_id = $_SESSION['school_id'] ;
  $year = $_SESSION['year'] ;
  if ($_POST['oper']=="dept")//依系所條件產生試卷條碼
  {
        $sql = "select person_student_id,c.name dept_name,b.name subject_name,makenumber,b.id
          from persub a,subject b,department c 
          where a.school_id='$school_id' and a.year='$year'
           and substr(person_student_id,1,3)='$dept_id'  
          and  b.school_id=a.school_id and b.year=a.year and b.id=a.subject_id 
          and c.school_id=a.school_id and c.year=a.year and c.id=substr(person_student_id,1,3)
          order by b.id,a.makenumber";
          //echo "sql=" . $sql ;
        $persub_rows = $db -> query_array($sql);
        for($j=0 ; $j<sizeof($persub_rows['ID']);$j++)
        {
            $person_student_id = $persub_rows['PERSON_STUDENT_ID'][$j];
            $dept_name = $persub_rows['DEPT_NAME'][$j];
            $subject_name = $persub_rows['SUBJECT_NAME'][$j];
            $makenumber = $persub_rows['MAKENUMBER'][$j];
            fwrite($fp,$person_student_id.",".$dept_name.",".$subject_name.",".$makenumber."\r\n");
        }
  }

  if ($_POST['oper']=="spare")//備用試卷條碼(依試場)
  {
        $sql = "select classroom_id from classroom 
        where school_id='$school_id' and year='$year'
         order by classroom_id ";
        $classroom_rows = $db -> query_array($sql);
        for($j=0 ; $j<sizeof($classroom_rows['CLASSROOM_ID']);$j++)
        {
            $classroom_id = $classroom_rows['CLASSROOM_ID'][$j];
            if($classroom_id<=9)
              $classroom_id="00".($classroom_id);
            else 
              $classroom_id="0".($classroom_id);
            for($i=1;$i<=5;$i++){
              $code = $classroom_id . "10" . $i ; //ex.001105
              fwrite($fp,$code.",,,".$code."\r\n");
            }
        }
  }

fclose($fp);
//***************************************************************************
//以下為下載檔案(檔案儲存在主機的其他路徑下，避免使用者直接輸入網址就可取得檔案)
$attch_tmp="data_" . $dept_id .".txt";
$file_path = "./rpt/" . $file ;  //檔案來源：wbe server的絕對路徑
$file_size = filesize($file_path);
header('Pragma: public');
header('Expires: 0');
header('Last-Modified: ' . gmdate('D, d M Y H:i ') . ' GMT');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false);
header("Content-type: application/download");   
header('Content-Length: ' . $file_size);
header('Content-Disposition: attachment; filename="' . $attch_tmp . '";'); //要output的檔名(可自訂)
header('Content-Transfer-Encoding: binary');
readfile($file_path);
//**************************************************************************

?>
