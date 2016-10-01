<?php
    session_start();
    include '../inc/connect.php';
	$school_id=$_SESSION['school_id'];
	$year=$_SESSION['year'];


    if ($_POST['info']) {
        $str = "select is_input one,is_secondinput two,bag_no bno,bagid,score,firstscore,
        (select name from  department where  school_id='$school_id' and year='$year' and id=substr(a.bagid,1,3)) dname,
        (select name from  subject where  school_id='$school_id' and year='$year' and id=substr(a.bagid,1,7)) sname
        from persub a 
        where school_id='$school_id' and year='$year' and bagid='".$_POST['bag']."'  order by is_input asc, is_secondinput asc";

        $value = $db -> query_array($str);
        echo json_encode($value);
    }

    if ($_POST['check_exist']) {
        $str = "select on_off_exam, makenumber, ordernumber, firstscore from persub 
            where  school_id='$school_id' and year='$year' and makenumber = ".$_POST['barcode']." and bagid = ".$_POST['bag']." and is_input = 1";
        $value = $db -> query_array($str);
        if ($value['MAKENUMBER'][0] == '') {
            $value['MAKENUMBER'][0] = -1;
        }
        echo json_encode($value);
    }
    if ($_POST['store']) {
        for ($i=1; $i < $_POST['size']; $i++) {
            $sql = "update persub set is_secondinput = 1, secondorder = ".$i.", score = ".$_POST['score'][$i].", result = ".$_POST['score'][$i]." where  school_id='$school_id' and year='$year'  and bagid = ".$_POST['bag']." and makenumber = ".$_POST['makenum'][$i];
            $db -> query($sql);
        }

        $str = "select score, makenumber, is_secondinput from persub where  school_id='$school_id' and year='$year'  and bag_no = ".$_POST['bag_serial']." and bagid = ".$_POST['bag']." order by secondorder";
        $value = $db -> query_array($str);
        echo json_encode($value);
    }
?>
