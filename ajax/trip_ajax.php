<?php
session_start();
include '../inc/connect.php';
$account="0ob@cc.ncue.edu.tw";

$sql = "SELECT empl_no
        FROM   psfempl, psfcrjb, stfdept
		WHERE  empl_no=crjb_empl_no
		AND    crjb_depart=dept_no
		AND    crjb_seq='1'
		AND    substr(empl_no,1,1) !='A'
		AND    crjb_quit_date is null
		AND    psfempl.email='".$account."'
        ";

$userid = $db -> query_first_row($sql)[0];


$sql="SELECT empl_id_no
      FROM 	 psfempl
      WHERE  empl_no='$userid'";
$id_no = $db -> query_first_row($sql)[0];
if($_POST['oper'] == "dealing")
{
$SQLStr ="SELECT empl_chn_name,h.POCARD,pc.CODE_CHN_ITEM,h.POVDATEB,h.POVDATEE,
          h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
          h.serialno,h.CURENTSTATUS,h.DEPART, substr(DEPT_SHORT_NAME,1,14), dept_short_name,
            (select   code_chn_item   from psfcrjb,psqcode
            where crjb_empl_no=h.pocard
            and  crjb_seq='1'
            and  crjb_quit_date is null
            and  code_kind='0202'
            and code_field=crjb_title) crjb_title
          FROM psfempl p,holidayform h,psqcode pc,stfdept s
          where trip=1 and condition <> -1
          and  p.empl_no=h.pocard
          and  pc.CODE_KIND='0302'
          and  pc.CODE_FIELD=h.POVTYPE
          and  s.dept_no=h.depart
          order by h.POCARD,h.POVDATEB,h.POVHOURS";

          $data = $db -> query_array($SQLStr);

            echo json_encode($data);
          //echo $data["EMPL_CHN_NAME"][0];



          exit;
        }else if($_POST['oper']=='canceled')
      {
        $SQLStr ="SELECT empl_chn_name,h.POCARD,pc.CODE_CHN_ITEM,h.POVDATEB,h.POVDATEE,
      	h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
          h.serialno,h.CURENTSTATUS,h.DEPART
      	FROM psfempl p,holidayform h,psqcode pc
      	where trip=-1 and condition <> -1
      	and  p.empl_no=h.pocard
      	and  pc.CODE_KIND='0302'
      	and  pc.CODE_FIELD=h.POVTYPE
      	order by h.POCARD,h.POVDATEB,h.POVHOURS";
        $data = $db -> query_array($SQLStr);
        echo json_encode($data);
      }else if($_POST['oper']=='canceledClick')
      {
        $serialno=$_POST['serialnoVar'];
        $SQLStr=  "update holidayform set trip=2  where serialno = $serialno ";
        $db -> query($SQLStr);
      }

?>
