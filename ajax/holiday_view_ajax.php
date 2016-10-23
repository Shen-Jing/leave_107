<?php
include '../inc/connect.php';

$today  = getdate();
$year    = $today["year"] - 1911;

//Get_login_id
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

$userid = $db -> query_first_row($sql)[0];


$sql="select empl_id_no 
      from psfempl
      where empl_no='$userid'";
$id_no = $db -> query_first_row($sql)[0];



// DatableTable data
$sqlStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,
            h.POVDATEB,h.POVDATEE,
			h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,
            h.agentsignd,h.onesignd,h.twosignd
			FROM psfempl p,holidayform h,psqcode pc
			where  p.empl_id_no='$id_no'
			and  p.empl_no=h.pocard
			and h.CONDITION='1'
			and substr(povdateb,1,3)= '$year'
			and pc.CODE_KIND='0302'
			and pc.CODE_FIELD=h.POVTYPE";
$data = $db -> query_array ($sqlStr);
$a['data']="";

for ($i=0; $i < sizeof($data['EMPL_CHN_NAME']); $i++) {      
           $a['data'][] = array(
               $data['EMPL_CHN_NAME'][$i],
               $data['CODE_CHN_ITEM'][$i],
               $data['POVDATEB'][$i],
               $data['POVDATEE'][$i],
               $data['POVDAYS'][$i]."天".$data['POVHOURS'][$i]."時",
               $data['POVTIMEB'][$i],
               $data['POVTIMEE'][$i],
               $data['AGENTSIGND'][$i],
               $data['ONESIGND'][$i],
               $data['TWOSIGND'][$i]);
     }

echo json_encode($a);
?>