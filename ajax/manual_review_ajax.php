<?php
    session_start();
    include '../inc/connect.php';
	$school_id=$_SESSION['school_id'];
    $year=$_SESSION['year'];


    if ($_POST['oper']=="qry_dept") { //有非筆試科目之系所
        $str = "select name, id from department 
        where  school_id='$school_id' and year='$year'  and id in 
        (select distinct substr(id,1,3) from subject where  school_id='$school_id' and year='$year'  and substr(id,6,2) in('00','01','60','70','80','90') )
        order by id ";
        $value = $db -> query_array($str);
        echo json_encode($value);
    }

    if ($_POST['oper']=="qry_subject") {
        $str = "select id, name from subject 
        where  school_id='$school_id' and year='$year' and substr(id, 1, 3) = ".$_POST['dept']." and substr(id, 6, 2) in ('00','01','60','70','80','90') order by id";
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
        $str = "select p.person_student_id psid, s.name ,p.firstscore
        from persub p, signupdata s, person p1 
        where  p.school_id='$school_id' and p.year='$year' 
        and p.subject_id = ".$_POST['subject']." and p.school_id=p1.school_id and p.year=p1.year 
        and p.person_student_id = p1.student_id and p1.school_id=s.school_id and p1.year=s.year and p1.id = s.id and s.orastatus_id = substr(p1.student_id,1,5)
        order by p.person_student_id";
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
            $sql = "update persub set on_off_exam = ".$on_off.", is_input = 1, ordernumber = ".$i.", firstscore = ".$_POST['score'][$i]." ,bagid='$bagid' where school_id='$school_id' and year='$year' and person_student_id = ".$_POST['psid'][$i]." and subject_id = ".$_POST['subject'];
            $db -> query($sql);
        }

        $str = "select on_off_exam, firstscore, is_input, person_student_id psid from persub 
        where school_id='$school_id' and year='$year' and subject_id = ".$_POST['subject']." order by ordernumber";
        $value = $db -> query_array($str);
        echo json_encode($value);
    }
?>
