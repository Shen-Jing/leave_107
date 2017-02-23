<?php
include '../inc/check.php';

$userid = $_SESSION['empl_no'];

$sql = "SELECT empl_chn_name, substr(pc.CODE_CHN_ITEM,1,2) code_chn_item,
                h.POVDATEB, h.POVDATEE, h.POVTIMEB, h.POVTIMEE,
                h.POVDAYS || '天' || h.POVHOURS || '時' AS AGGRETIME,
                NVL( (SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO=h.AGENTNO), '無' ) AGENTNAME,
				h.serialno
		FROM psfempl p,holidayform h,psqcode pc
		where h.POCARD='$userid' 
		and   condition in ('0','2')
		and  p.empl_no=h.pocard
		and  pc.CODE_KIND='0302'
		and  pc.CODE_FIELD=h.POVTYPE";

$data['data'] = $db -> query_array ($sql, true);

echo json_encode($data);
?>