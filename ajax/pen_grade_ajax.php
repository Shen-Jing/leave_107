<?php
    session_start();
    include '../inc/connect.php';
	$school_id=$_SESSION['school_id'];
	$year=$_SESSION['year'];


    if ($_POST['info']) {
        $str = "select d.name dname, s.name sname, p.makenumber, p.bag_no finished 
            from department d, persub p, subject s, bag b, seat s1 
            where b.school_id='$school_id' and b.year='$year' and b.bag_id ='".$_POST['bag']."' and b.school_id=s1.school_id and b.year=s1.year and b.classroom_id = s1.classroom_id and p.school_id=s1.school_id and p.year= s1.year and s1.student_id = p.person_student_id and s.school_id=b.school_id and s.year=b.year and s.id = substr(b.bag_id, 1, 7) and d.school_id=b.school_id and d.year=b.year and d.id = substr(b.bag_id, 1, 3) and substr(b.bag_id, 1, 5) = substr(p.makenumber, 1, 5) and substr(b.bag_id, 6, 2) = substr(p.makenumber, 9, 2) order by p.bag_no";
        $value = $db -> query_array($str);        
        echo json_encode($value);
    }
    if ($_POST['check_exist']) {
        $str = "select p.makenumber from persub p, bag b, seat s1 
                where p.school_id='$school_id' and p.year='$year' and  p.makenumber = '".$_POST['barcode']."' and b.school_id=p.school_id and b.year=p.year and b.bag_id =' ".$_POST['bag']."' and s1.school_id=b.school_id and s1.year=b.year and b.classroom_id = s1.classroom_id and s1.student_id = p.person_student_id and substr(b.bag_id, 1, 5) = substr(p.makenumber, 1, 5) and substr(b.bag_id, 6, 2) = substr(p.makenumber, 9, 2)";
        $value = $db -> query_array($str);
        if ($value['MAKENUMBER'][0] == '') {
            $value['MAKENUMBER'][0] = -1;
        }
        echo json_encode($value);
    }
    if ($_POST['store']) {
        for ($i=1; $i < $_POST['size']; $i++) {
            $on_off = 0;
            if ($_POST['score'][$i] == -1) {
                ++$on_off;//缺考
                $_POST['score'][$i] = 0;
            }
            $sql = "update persub set on_off_exam = ".$on_off.", is_input = 1, ordernumber = ".$i.", firstscore = ".$_POST['score'][$i].", bag_no ='".$_POST['bag_serial']."', bagid ='".$_POST['bag']."' 
                where  school_id='$school_id' and year='$year'  and makenumber ='".$_POST['makenum'][$i]."'";
            $db -> query($sql);
        }

        $str = "select on_off_exam, firstscore, makenumber, is_input from persub 
            where  school_id='$school_id' and year='$year' and bag_no ='".$_POST['bag_serial']."' and bagid ='".$_POST['bag']."' order by ordernumber";
        $value = $db -> query_array($str);
        echo json_encode($value);
    }
?>
