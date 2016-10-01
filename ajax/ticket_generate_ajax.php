<?php
	session_start();
  include '../inc/connect.php';
  $school_id=$_SESSION['school_id'];
  $year=$_SESSION['year'];


  if ($_POST['oper']=="oper")
  {
        //若已輸入成績則不允許....
        $str = "select * from persub where  school_id='$school_id' and year='$year' and score>0 ";
        $data = $db -> query_array($str);
        // if (sizeof($data['SCORE']>0)){
        //   $message=array("error_code"=>"001","error_message"=>"已輸入成績，不允許重新產生准考證!!","sql"=>$sql);
        //   echo json_encode($message); 
        //   exit; 
        // }

        $str ="delete from person where school_id='$school_id' and year='$year'";
        $db -> query($str);
        $str ="delete from persub where school_id='$school_id' and year='$year'";
        $db -> query($str);
// $message=array("error_code"=>"001","error_message"=>"已輸入成績，不允許重新產生准考證!!","sql"=>$sql);
//           echo json_encode($message); 
//           exit; 
        $str = "select id, orastatus_id, subject_id from signupdata 
         where  school_id='$school_id' and year='$year' order by  orastatus_id,e_place,subject_id";
        $signupdata = $db -> query_array($str);

        $last = $signupdata['ORASTATUS_ID'][0];
        $serial = 1 ;
        for ($i = 0 ; $i < sizeof($signupdata['ORASTATUS_ID']);$i++) {
            $serial = ($last != $signupdata['ORASTATUS_ID'][$i]) ? 1 : $serial;
            $last = $signupdata['ORASTATUS_ID'][$i];

            $ticket = $signupdata['ORASTATUS_ID'][$i] * 1000 + $serial; // 准考證號碼

            $to_person_sql = "insert into person(id,student_id,school_id,year) values('".$signupdata['ID'][$i]."', '$ticket','$school_id','$year')";
            $db -> query($to_person_sql);

            $str = "select id from subject where  school_id='$school_id' and year='$year' and orastatus_id = '".$signupdata['ORASTATUS_ID'][$i] ."' order by id";
            $subject_id = $db -> query_array($str);
            for ($j = 0; $j < sizeof($subject_id['ID']); ++$j) {
                //ex.3110120  || 3110131(選考) || 3470101(口試)
                if ($subject_id['ID'][$j] % 10 == 0 || $subject_id['ID'][$j] % 10 == $signupdata['SUBJECT_ID'][$i] || substr($subject_id['ID'][$j],5,2)=="01") { // persub makenumber 須調整為可null
                    //ex.31101004 + 3110120 ==> 3110100420
                    $make_num = $ticket * 100 + $subject_id['ID'][$j] % 100;
                    $to_persub_sql = "insert into persub(person_student_id,subject_id,makenumber,school_id,year) values('$ticket', '".$subject_id['ID'][$j]."', '$make_num','$school_id','$year')";
                    $db -> query($to_persub_sql);
                }
            }
            $serial++;
        }

        $message=array("error_code"=>"","error_message"=>"准考證產生完畢!","sql"=>"");
        echo json_encode($message);
        exit;
        
  }

  
?>
