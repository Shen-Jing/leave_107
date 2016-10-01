<?php
    session_start();
    include '../inc/connect.php';
    $school_id=$_SESSION['school_id'];
    $year=$_SESSION['year'];

    //**把person 內的issort值設為1(可排名) (**再確認!) 
    $sql = "update person set issort=1 where school_id='3' and year='105'";
    $db -> query($sql);
    //**把persub的note值設為0(可排名)   
    $sql = "update persub set note = 0 where school_id='3' and year='105'";
    $db -> query($sql);
   
    //**各科加權計算
    $sql = "update persub set ratescore = score * (select rate from subject where  school_id='3' and year='105' and id = subject_id) / 100 where school_id='3' and year='105' ";
    $db -> query($sql);
    //**原始及加權分數加總
    $sql = "update person set 
    allscore = (select sum(score) from persub where school_id='3' and year='105' and person_student_id = student_id group by person_student_id) ,
    allratescore = (select sum(ratescore) from persub where  school_id='3' and year='105' and person_student_id = student_id group by person_student_id)
    where  school_id='3' and year='105'";
    $db -> query($sql);

    //**計算標準
    //有哪些科目要算標準
    $sql = "select id,qualified from subject where school_id='3' and year='105' and qualified!=0";
    $subject_rows = $db -> query_array($sql);
    for($j=0 ; $j<sizeof($subject_rows['ID']);$j++)
    {
        $subject_id = $subject_rows['ID'][$j];
        $qualified = $subject_rows['QUALIFIED'][$j];
        //取出該科所有應考生成績
        $sql = "select subject_id,person_student_id,score  from persub where  school_id='3' and year='105' and subject_id='$subject_id' and on_off_exam=0 order by score desc";
        $persub_rows = $db -> query_array($sql);

        $r = sizeof($persub_rows['SUBJECT_ID']) ; //總筆數
        // 計算頂標
        $score = 0;
        $count = ceil($r / 4); //取前 1/4
        $k = 0;
        for ($i = 0; $i < $count; $i++) {
            $score += $persub_rows['SCORE'][$i];
            $k++;
        }
        $top_stan = round(($score / $count) * 100) / 100;

        // 計算前標,奇數時多取1個
        $score = 0;
        $count = ceil($r / 2);
        $k = 0;
        for ($i = 0; $i < $count; $i++) {
            $score += $persub_rows['SCORE'][$i];
            $k++;
        }
        $front_stan = round(($score / $count) * 100) / 100;

        // 計算均標
        $score = 0;
        $count = $r;
        $k = 0;
        for ($i = 0; $i < $count; $i++) {
            $score += $persub_rows['SCORE'][$i];
            $k++;
        }
        $standard = round(($score / $count) * 100) / 100;

        // 計算後標,奇數時少取1個
        $score = 0;
        $count = floor($r / 2);
        $k = 0;
        for ($i = $count; $i < $r; $i++) // $i=index
        {
            $score += $persub_rows['SCORE'][$i];
            $k++;
        }
        $beyond_stan = round(($score / $k) * 100) / 100;

        // 計算底標
        $score = 0;
        $count = $r - floor($r / 4);
        $k = 0;
        for ($i = $r - 1; $i >= $count; $i--) {
            $score += $persub_rows['SCORE'][$i];
            $k++;
        }
        $base_stan = round(($score / $k) * 100) / 100;

        //將各個標準分數寫入DB
        $sql = "update subject set standard = $standard,base_stan = $base_stan,top_stan =$top_stan,front_stan =$front_stan,beyond_stan =$beyond_stan where  school_id='3' and year='105' and id ='$subject_id'";
        $db -> query($sql);

        if($qualified==1) $stan_score = $top_stan ;//1:頂標
        if($qualified==2) $stan_score = $front_stan ;//2:前標
        if($qualified==3) $stan_score = $standard ;//3:均標
        if($qualified==4) $stan_score = $beyond_stan ;//4:後標
        if($qualified==5) $stan_score = $base_stan ;//5:底標                        

        //將不符標準的note 設為 1
        $sql = "update persub set note = 1 where  school_id='3' and year='105' and subject_id ='$subject_id' and score<$stan_score";
        $db -> query($sql);
    }

    //如果考生有未達標準的科目或總分為0,不予排名
    $sql = "update  person set issort=0,allnumber=0 
         where allscore=0 or student_id in
        (select distinct person_student_id from persub where school_id='3' and year='105' and note='1')";
    $db -> query($sql);


    //排名
    $sql = "select id from orastatus where school_id='3' and year='105' order by id";
    $orastatus_rows = $db -> query_array($sql);
    for($j=0 ; $j<sizeof($orastatus_rows['ID']);$j++)
    {
        $orastatus_id=$orastatus_rows['ID'][$j];
        $sql="select student_id,allratescore,
            (select ratescore from persub a,subject b 
            where a.school_id='3' and a.year='105' and a.person_student_id=c.student_id and a.school_id=b.school_id and a.year=b.year 
            and a.subject_id=b.id and compare='1') c1,
            (select ratescore from persub a,subject b 
            where a.school_id='3' and a.year='105' and a.person_student_id=c.student_id and a.school_id=b.school_id and a.year=b.year 
            and a.subject_id=b.id and compare='2') c2,
            (select ratescore from persub a,subject b 
            where a.school_id='3' and a.year='105' and a.person_student_id=c.student_id and a.school_id=b.school_id and a.year=b.year 
            and a.subject_id=b.id and compare='3') c3,
            (select ratescore from persub a,subject b 
            where a.school_id='3' and a.year='105' and a.person_student_id=c.student_id and a.school_id=b.school_id and a.year=b.year 
            and a.subject_id=b.id and compare='4') c4,
            (select ratescore from persub a,subject b 
            where a.school_id='3' and a.year='105' and a.person_student_id=c.student_id and a.school_id=b.school_id and a.year=b.year 
            and a.subject_id=b.id and compare='5') c5,
            (select ratescore from persub a,subject b 
            where a.school_id='3' and a.year='105' and a.person_student_id=c.student_id and a.school_id=b.school_id and a.year=b.year 
            and a.subject_id=b.id and compare='6') c6,
            (select ratescore from persub a,subject b 
            where a.school_id='3' and a.year='105' and a.person_student_id=c.student_id and a.school_id=b.school_id and a.year=b.year 
            and a.subject_id=b.id and compare='7') c7
            from person c where school_id='3' and year='105' and substr(student_id,1,5)='$orastatus_id' and issort='1'
            order by allratescore desc,c1 desc,c2 desc,c3 desc,c4 desc,c5 desc,c6 desc,c7 desc"; 
        $comp_rows = $db -> query_array($sql);
        $rank = 0 ;//名次
        $str_compare_tmp="";
        for($x=0 ; $x<sizeof($comp_rows['STUDENT_ID']);$x++)
        {
            $student_id=$comp_rows['STUDENT_ID'][$x];
            //用來比較成績是否和前一筆完全相同
            $str_compare = $comp_rows['ALLRATESCORE'][$x].$comp_rows['C1'][$x].$comp_rows['C2'][$x].$comp_rows['C3'][$x].$comp_rows['C4'][$x].$comp_rows['C5'][$x].$comp_rows['C6'][$x].$comp_rows['C7'][$x];

            if ($str_compare != $str_compare_tmp) //成績小於前一筆
                $rank = $x + 1;

            $str_compare_tmp = $str_compare ;
                
            //寫入名次
            $sql = "update person set allnumber=$rank  where  school_id='3' and year='105' and student_id='$student_id'";
            $db -> query($sql);
        }
    }

    $message=array("error_code"=>0,"error_message"=>"");
    echo json_encode($message);

?>
