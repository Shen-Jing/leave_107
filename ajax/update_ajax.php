<?php
include '../inc/connect.php';

//Get_login_id
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
//"0000848";

//fetch COUNT
$sql = "SELECT count(*) count
		FROM   holidayform 
		WHERE  POCARD='$userid' 
		AND    condition in ('0','2')";
$count = $db -> query_first_row($sql)[0];

if ($count==0){
    echo "已完成簽核假單無法再修改，請利用取消假單方式。";
    exit;
}

/*
$sql ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE,
			  h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
			  h.serialno,h.CURENTSTATUS
       FROM   psfempl p,holidayform h,psqcode pc
       WHERE  h.POCARD='$userid' 
       AND    condition IN ('0','2')
       AND    p.empl_no=h.pocard
       AND    pc.CODE_KIND='0302'
       AND    pc.CODE_FIELD=h.POVTYPE
       ORDER BY h.POVDATEB DESC ,h.POVHOURS";

$data = $db -> query_array($sql);
echo json_encode($data); 
*/


?>