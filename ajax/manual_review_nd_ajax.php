<?php
    session_start();
    include '../inc/connect.php';
	$school_id=$_SESSION['school_id'];
	$year=$_SESSION['year'];


    if ($_POST['oper']=="qry_dept") { //有非筆試科目之系所
        $str = "SELECT NAME, ID FROM DEPARTMENT 
        WHERE  school_id='$school_id' and year='$year'  and id in 
        (select distinct substr(id,1,3) from subject where  school_id='$school_id' and year='$year'  and substr(id,6,2) in('00','01','60','70','80','90') )
        ORDER BY ID ";
        $value = $db -> query_array($str);
        echo json_encode($value);
    }

    if ($_POST['oper']=="qry_subject") {
        $str = "SELECT ID, NAME FROM SUBJECT 
        WHERE  school_id='$school_id' and year='$year' and SUBSTR(ID, 1, 3) = ".$_POST['dept']." AND SUBSTR(ID, 6, 2) IN ('00','01','60','70','80','90') ORDER BY ID";
        $tmp = $db -> query_array($str);
        // for ($i=0; $i < sizeof($tmp['ID']); $i++) {
        //     $str = "SELECT IS_INPUT FROM PERSUB 
        //     WHERE  school_id='$school_id' and year='$year' and SUBJECT_ID = ".$tmp['ID'][$i]." 
        //     ORDER BY PERSON_STUDENT_ID";
        //     $a = $db -> query_array($str);
        //     if ($a['IS_INPUT'][0] == 1) {
        //         $tmp['ID'][$i] = '';
        //         $tmp['NAME'][$i] = '';
        //     }
        // }
        echo json_encode($tmp);
    }

    if ($_POST['oper']=="info") {
        $str = "SELECT p.PERSON_STUDENT_ID PSID, s.NAME ,p.firstscore,p.score, p.ON_OFF_EXAM
        FROM PERSUB p, SIGNUPDATA s, PERSON p1 
        WHERE  p.school_id='$school_id' and p.year='$year' 
        and p.SUBJECT_ID = ".$_POST['subject']." AND p.school_id=p1.school_id and p.year=p1.year 
        and p.PERSON_STUDENT_ID = p1.STUDENT_ID AND p1.school_id=s.school_id and p1.year=s.year and p1.ID = s.ID and s.orastatus_id = substr(p1.student_id,1,5)
        ORDER BY p.PERSON_STUDENT_ID";
        $_SESSION['subject_id'] = $_POST['subject'] ; //方便列印報表
        $value = $db -> query_array($str);
        echo json_encode($value);
    }

    if ($_POST['oper']=="store") {
        for ($i=1; $i < $_POST['size']; $i++) {
            $on_off = 0;
            if ($_POST['score'][$i] == -1) {
                ++$on_off;
                $_POST['score'][$i] = 0;
            }
            $bagid = date("mdhis"); 
            $sql = "UPDATE PERSUB SET ON_OFF_EXAM = ".$on_off.", IS_SECONDINPUT = 1, SECONDORDER = ".$i.", SCORE = ".$_POST['score'][$i]." , RESULT = ".$_POST['score'][$i]." ,bagid='$bagid' WHERE school_id='$school_id' and year='$year' and PERSON_STUDENT_ID = ".$_POST['psid'][$i]." AND SUBJECT_ID = ".$_POST['subject'];
            $db -> query($sql);
            $_SESSION['subject_id'] = $_POST['subject'] ; //方便列印報表
        }

        $str = "SELECT ON_OFF_EXAM, SCORE, IS_SECONDINPUT, PERSON_STUDENT_ID PSID 
        FROM PERSUB 
        WHERE school_id='$school_id' and year='$year' and SUBJECT_ID = ".$_POST['subject']." ORDER BY SECONDORDER";
        $value = $db -> query_array($str);
        echo json_encode($value);
    }
?>
