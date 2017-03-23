<?
session_start();
include '../inc/connect.php';
$empl_no = $_SESSION['empl_no'];
$account="0ob@cc.ncue.edu.tw";
$today = getdate();
$year = $today["year"] - 1911;
$month = $today["mon"];
$sql = "select empl_no
        from  psfempl, psfcrjb, stfdept
   where empl_no=crjb_empl_no
   and crjb_depart=dept_no
   and crjb_seq='1'
   and substr(empl_no,1,1) !='A'
   and crjb_quit_date is null
   and psfempl.email='".$account."'
        ";
  $userid =$db -> query_first_row($sql)[0];

                 if($_POST['oper'] == "qry_year")
                 {

                   $data = array('year' => $year, 'month' => $month );
                   echo json_encode($data);
                   exit;

                 }
                 if($_POST['oper'] == "qry_class")
                 {

                   $data = array('year' => $year);
                   echo json_encode($data);
                   exit;

                 }

                  if($_POST['oper'] == "new_data_class")
                  {
                    $classno=0;
                    $serialno=$_POST['serialno'];

                    $classno=0;
                    $SQLStr="select MAX(CLASS_NO) class_no from haveclass where class_serialno='$serialno' ";
                    $row = $db -> query_array($SQLStr);
                    $classno=$row['CLASS_NO'][0];
                    $classno++;

                    /*$data = array('classno' => $classno);
                    echo json_encode($data);
                    exit;*/
                    $data = array('classno' => $classno);
                    echo json_encode($data);
                    exit;
                  }
                 if($_POST['oper'] == "edit_class")
                 {
                   $serialno=$_POST[serialnoVar];
                   $SQLStr= "select substr(povdateb,1,3) byear,substr(povdateb,4,2) bmonth,substr(povdateb,6,2) bday,substr(povdatee,1,3) eyear, substr(povdatee,4,2) emonth,substr(povdatee,6,2) eday,poremark, acadm_return,night_return
	                         from holidayform where serialno='$serialno'";
                   $row = $db -> query_array($SQLStr);
                   $byear  = $row["BYEAR"];
                   $bmonth = $row["BMONTH"];
                   $bday   = $row["BDAY"];
                   $eyear  = $row["EYEAR"];
                   $emonth = $row["EMONTH"];
                   $eday   = $row["EDAY"];
                   $holidaymark   = $row["POREMARK"];
                   /*if(strlen($byear)<3)
	                    $byear='0'.$byear;
                   if(strlen($eyear)<3)
	                    $eyear='0'.$eyear;
                   if(strlen($bmonth)<2)
	                    $bmonth='0'.$bmonth;
                   if(strlen($bday)<2)
	                    $bday='0'.$bday;
                   if(strlen($emonth)<2)
	                    $emonth='0'.$emonth;
                   if(strlen($eday)<2)
	                    $eday='0'.$eday;*/
                      if(strlen($byear[0])<3)
   	                    $byear[0]='0'.$byear[0];
                      if(strlen($eyear[0])<3)
   	                    $eyear[0]='0'.$eyear[0];
                      if(strlen($bmonth[0])<2)
   	                    $bmonth[0]='0'.$bmonth[0];
                      if(strlen($bday[0])<2)
   	                    $bday[0]='0'.$bday[0];
                      if(strlen($emonth[0])<2)
   	                    $emonth[0]='0'.$emonth[0];
                      if(strlen($eday[0])<2)
   	                    $eday[0]='0'.$eday[0];
                   $bdate = $byear.$bmonth.$bday;
                   $edate = $eyear.$emonth.$eday;
                   $class_year =$_POST['class_year'];
                   $class_acadm=$_POST['class_acadm'];
                   $class_no=$_POST['class_no'];
                   $SQLStr ="select * from haveclass
			                       where class_serialno='$serialno'
			                       and   class_no='$class_no'";
                   $row = $db -> query_array($SQLStr);
                   $class_date    = $row['CLASS_DATE'];
		               $class_date2   = $row['CLASS_DATE2'];
                   $class_subject  = $row['CLASS_SELCODE'];
		               $class_room     = $row['CLASS_ROOM'];
		               $class_section2 = $row['CLASS_SECTION2'];


                   $class_acadm=$_POST['class_acadm'];
                   /*$SQLStr="select a.scr_selcode, b.sub_name , b.sub_id
				                     from    dean.s32_smscourse@schlink.us.oracle.com a ,
						                 dean.s90_subject@schlink.us.oracle.com   b ,
						                 dean.s32_teacher@schlink.us.oracle.com   c ,
						                 dean.s90_class@schlink.us.oracle.com     d ,
						                 dean.s10_employee@schlink.us.oracle.com  e
				                     where  a.yms_year = c.yms_year
				                     and    a.yms_smester = c.yms_smester
				                     and    a.cls_id = c.cls_id
				                     and    a.sub_id = c.sub_id
				                     and    a.scr_dup = c.scr_dup
				                     and    a.scr_selcode = c.scr_selcode
				                     and    a.cls_id = d.cls_id
				                     and    a.sub_id = b.sub_id
				                     and    c.emp_id  = e.emp_id
				                     and    a.yms_year='$class_year'
				                     and    a.yms_smester='$class_acadm'
				                     and    c.emp_id = '$userid' ";
                   $row = $db -> query_array($SQLStr);
                   $selcode     = $row1['SCR_SELCODE'];
				           $sub_name    = $row1['SUB_NAME'];*/

                   $data = array('byear' => $byear,'bmonth'=>$bmonth,'bday'=>$bday,'eyear' => $eyear,'emonth'=>$emonth,'eday'=>$eday,'holidaymark'=>$holidaymark,'year' => $year,'class_no'=>$class_no,'class_room'=>$class_room);
                   echo json_encode($data);

                   exit;

                 }
                 if($_POST['oper']=="send")
                 {

                   $serialno=$_POST['serialno'];
                   $class_no=$_POST['class_no'];
                   /*$sql="select crjb_depart from psfcrjb
			                   where crjb_seq='1'
			                   and   crjb_empl_no='$empl_no'";
                   $depart=$db->query($sql);*/
                   //$depart=0;
                   $class_subject=$_POST['classsubject'];
                   $byear=$_POST['byear'];
                   $bmonth=$_POST['bmonth'];
                   $bday=$_POST['bday'];
                   if ($byear<100)
	                   $class_date='0'.$byear;
	                 else
	                   $class_date=$byear;

	                if ($bmonth <10)
	                   $class_date=$class_date.'0'.$bmonth;
	                else
	                   $class_date=$class_date.$bmonth;


                  $class_yymm=$class_date;

	                if ($bday <10)
	                   $class_date=$class_date.'0'.$bday;
	                else
	                   $class_date=$class_date.$bday;
                  $sql="SELECT decode(calendar_week,'1','日','2','一','3','二','4','三','5','四',	'6','五','7','六','')  week
		                   FROM   ps_calendar
		                   WHERE  calendar_yymm='$class_yymm' AND   calendar_dd='$bday'";
                   $class_week=$db->query($sql);
                   //$class_week=0;


                   $eyear=$_POST['eyear'];
                   $emonth=$_POST['emonth'];
                   $eday=$_POST['eday'];
                   if ($eyear<100)
	                  $class_date2='0'.$eyear;
	                 else
	                  $class_date2=$eyear;
	                 if ($emonth <10)
	                  $class_date2=$class_date2.'0'.$emonth;
	                 else
	                  $class_date2=$class_date2.$emonth;

	                  $class_yymm2=$class_date2;

	                 if ($eday <10)
	                  $class_date2=$class_date2.'0'.$eday;
	                 else
	                  $class_date2=$class_date2.$eday;
                  /*$sql="select decode(calendar_week,'1','日','2','一','3','二','4','三','5','四',	'6','五','7','六','')  week
		                   from   ps_calendar
		                   where  calendar_yymm='$class_yymm2' and   calendar_dd='$eday'";
                   $class_week2=$db->query($sql);*/
                   $class_week2=87;

                   $class_section21=$_POST['classsection21'];
                   $class_section22=$_POST['classsection22'];
                   $class_section2=$class_section21.'-'.$class_section22;
                   $class_room=$_POST['class_room'];
                   $class_memo=$_POST['class_memo'];
                   $class_year=$_POST['class_year'];
                   $class_acadm=$_POST['class_acadm'];
                  /* $insert =
	                     "INSERT INTO HAVECLASS(CLASS_SERIALNO,CLASS_NO,CLASS_DEPART,CLASS_NAME, CLASS_CODE, CLASS_SUBJECT,CLASS_ID,CLASS_DATE, CLASS_WEEK , CLASS_SECTION,  CLASS_DATE2, CLASS_WEEK2 ,CLASS_SECTION2 ,CLASS_ROOM ,CLASS_MEMO, CLASS_SELCODE, CLASS_YEAR,CLASS_ACADM)
	                     VALUES ('9481',$class_no,'','','','$class_subject','',
				               '$class_date','$class_week','$class_section','$class_date2','$class_week2',  '$class_section2','$class_room','$class_memo', '','$class_year','$class_acadm')";
                       $db->query($insert);*/
                       if($_POST['State']==4)//4為修改 5為新增
                       {//修改的方式為先刪除再加入...What?
                         $sql="DELETE from haveclass
			                         WHERE class_serialno='$serialno'
			                         AND   class_no='$class_no'";
                         $db->query($sql);
                       }
                       $insert =
                           "INSERT INTO HAVECLASS(CLASS_SERIALNO,CLASS_NO,CLASS_SUBJECT,CLASS_DATE,CLASS_DATE2,CLASS_SECTION2,CLASS_ROOM,CLASS_MEMO, CLASS_YEAR,CLASS_ACADM)
                           VALUES ('$serialno','$class_no','$class_subject','$class_date','$class_date2',  '$class_section2','$class_room','$class_memo','$class_year','$class_acadm')";
                            $db->query($insert);

                      //下面這是原本的
                      /* $insert =
    	                     "insert into haveclass(CLASS_SERIALNO,CLASS_NO,CLASS_DEPART,CLASS_NAME, CLASS_CODE, CLASS_SUBJECT,CLASS_ID,CLASS_DATE, CLASS_WEEK , CLASS_SECTION,  CLASS_DATE2, CLASS_WEEK2 ,CLASS_SECTION2 ,CLASS_ROOM ,CLASS_MEMO, CLASS_SELCODE, CLASS_YEAR,CLASS_ACADM)
    	                     values ('$serialno',$class_no,'$depart','$class_name','$class_code','$class_subject','$class_id',
    				               '$class_date','$class_week','$class_section','$class_date2','$class_week2',  '$class_section2','$class_room','$class_memo', '$selcode','$class_year','$class_acadm')";*/
                 }

                 if($_POST['oper']==1)//修改畫面
                 {
                   $serialno=$_POST['serialnoVar'];
                   $SQLStr ="SELECT * FROM HAVECLASS
                 			    	WHERE class_serialno='$serialno'";
                   $row = $db -> query_array($SQLStr);
                   echo json_encode($row);
                   exit;

                 }
                 if($_POST['oper']=="Delete_Data")//刪除資料
                 {
                   $serialno=$_POST['serialno'];
                   $class_no=$_POST['class_no'];
                   $sql="DELETE from haveclass
                         WHERE class_serialno='$serialno'
                         AND   class_no='$class_no'";
                   $db->query($sql);
                 }
                 if($_POST['oper']==0)
                 {
                   $sql = "SELECT count(*) count
                 		FROM holidayform
                 		where substr(lpad(povdateb,7,'0'),1,3)=	lpad('$_POST[p_year]',3,'0')
                 		and   substr(lpad(povdateb,7,'0'),4,2)=	lpad('$_POST[p_month]',2,'0')
                 		and POCARD='$userid'
                 		and (condition =0 or  condition =1 or condition=2)";
                    $row = $db -> query_array($sql);
                    if($row['COUNT'][0]==0)
                    {
                      echo json_encode($row);
                      exit;
                    }else {
                      $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE,
                    	h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
                        h.serialno,h.CURENTSTATUS,h.depart
                    	FROM psfempl p,holidayform h,psqcode pc
                    	where substr(lpad(povdateb,7,'0'),1,3)=  lpad('$_POST[p_year]',3,'0')
                    	and   substr(lpad(povdateb,7,'0'),4,2)=	 lpad('$_POST[p_month]',2,'0')
                    	and POCARD='$userid'
                    	and (condition =0 or  condition =1 or condition=2)
                    	and p.empl_no=h.pocard
                    	and pc.CODE_KIND='0302'
                    	and pc.CODE_FIELD=h.POVTYPE
                    	order by h.POVDATEB desc ,h.POVHOURS desc";
                      $row = $db -> query_array($SQLStr);
                      echo json_encode($row);
                      exit;
                    }
                 }








  ?>
