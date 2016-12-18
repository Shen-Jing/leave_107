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
                   $bdate = $byear.$bmonth.$bday;
                   $edate = $eyear.$emonth.$eday;
                   $class_year =$_POST[class_year];
                   $class_acadm=$_POST[class_acadm];
                   $class_no=$_POST[class_no];
                   $SQLStr ="select * from haveclass
			                       where class_serialno='$serialno'
			                       and   class_no='$class_no'";
                   $row = $db -> query_array($SQLStr);
                   $class_date    = $row['CLASS_DATE'];
		               $class_date2   = $row['CLASS_DATE2'];
                   $class_subject  = $row['CLASS_SELCODE'];
		               $class_room     = $row['CLASS_ROOM'];
		               $class_section2 = $row['CLASS_SECTION2'];


                   $class_acadm=$_POST[class_acadm];
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

                   $data = array('byear' => $byear,'bmonth'=>$bmonth,'bday'=>$bday,'eyear' => $eyear,'emonth'=>$emonth,'eday'=>$eday,'holidaymark'=>$holidaymark);
                   echo json_encode($data);

                   exit;

                 }
                 if($_POST['oper']==1)//修改畫面
                 {
                   $SQLStr ="select * from haveclass
                 			    	where class_serialno='$_POST[serialnoVar]'";
                   $row = $db -> query_array($SQLStr);
                   echo json_encode($row);
                   exit;

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
