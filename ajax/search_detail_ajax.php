<?
session_start();
include '../inc/connect.php';
$account="0ob@cc.ncue.edu.tw";
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
  //$name = $db -> query_first_row($sql)[1];
  //echo $userid;




  $today  = getdate();
  $year   = $today["year"] - 1911;




              /* $begin_date=$_POST['p_menu'].'0101';
                $end_date=$_POST['p_menu'].'1231';*/

                 $name=$_SESSION['empl_name'][0];
                 $empl_no="";


                 //echo $year;

                 if($_POST['oper'] == "p_menu")
                 {

                     $data = array("year" => array("$year"),
                     "empl_no" => array("$userid"),
                     "empl_name" => array("$name"));

                     //echo "ppp success!!!";
                 	echo json_encode($data);
                     exit;

                 }

                 if($_POST['oper'] == 0)
                 {
                   $begin_date=$_POST['year'].'0101';
                   $end_date=$_POST['year'].'1231';
                   
                 $sql = "SELECT count(*) count
                     FROM holidayform
                     where POCARD  in ('$userid','$empl_no')
                     and CONDITION<>'-1' and condition<>'2'
                     and POVDATEB>='$begin_date'
                     and POVDATEE<='$end_date'";
                 $data = $db -> query_array($sql);
                 $count=sizeof($data);
                 if($count>0){
                 $SQLStr ="SELECT h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVHOURS,h.POVDAYS,h.CONDITION,
                 h.povtimeb,h.povtimee
                 FROM holidayform h,psqcode pc
                 where h.POCARD in ('$userid','$empl_no')
                 and h.CONDITION<>'-1' and condition<>'2'
                 and POVDATEB>='$begin_date'
                 and POVDATEE<='$end_date'
                 and pc.CODE_KIND='0302'
                 and pc.CODE_FIELD=h.POVTYPE
                   union
                   SELECT h.POCARD,pc.CODE_CHN_ITEM,h.POVDATEB,h.POVHOURS,h.POVDAYS,h.CONDITION,
                   h.povtimeb,h.povtimee
                 FROM holidayform h,psqcode pc
                 where h.POCARD='$userid'
                 and h.CONDITION<>'-1' and condition<>'2'
                 and POVDATEB<='$begin_date'
                 and POVDATEE>='$begin_date'
                 and pc.CODE_KIND='0302'
                 and pc.CODE_FIELD=h.POVTYPE
                   union
                   SELECT h.POCARD,pc.CODE_CHN_ITEM,h.POVDATEB,h.POVHOURS,h.POVDAYS,h.CONDITION,
                   h.povtimeb,h.povtimee
                 FROM holidayform h,psqcode pc
                 where h.POCARD='$userid'
                 and h.CONDITION<>'-1' and condition<>'2'
                 and POVDATEB<='$end_date'
                 and POVDATEE>='$end_date'
                 and pc.CODE_KIND='0302'
                 and pc.CODE_FIELD=h.POVTYPE
                 order by POCARD,POVDATEB";
                 $row = $db -> query_array($SQLStr);
                 echo json_encode($row);
                 exit;
                 }
}





  ?>
