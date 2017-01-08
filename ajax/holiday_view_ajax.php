<?php
include '../inc/check.php';

$year    = $_POST['year'];

$userid = $_SESSION['empl_no'];

$sql="SELECT empl_id_no 
      FROM 	 psfempl
      WHERE  empl_no='$userid'
      ";
$id_no = $db -> fetch_cell($sql);

//################################################

switch($_POST['tbid'])
{
    case 1:
        goto HOILDAY_LIST_PASSING;
    case 2:
        goto HOILDAY_LIST_CANCELED;
    case 3:
        goto HOILDAY_LIST_DEALING;
    case 4:
        goto HOILDAY_LIST_REJECTED;
}


HOILDAY_LIST_PASSING:
$sqlStr ="SELECT empl_chn_name,substr(pc.CODE_CHN_ITEM,1,2) code_chn_item,
                 h.POVDATEB,h.POVDATEE,h.POVTIMEB,h.POVTIMEE,
                 h.POVDAYS || '天' || h.POVHOURS || '時' AS POVTOTIME,
                 NVL(h.agentsignd, '-') AGENTSIGND,
                 NVL(h.onesignd, '-') ONESIGND,
                 NVL(h.twosignd, '-') TWOSIGND,
                 NVL(h.poremark, '-') POREMARK,
                 h.THREESIGND,h.perone_signd,h.pertwo_signd,h.secone_signd
		  FROM   psfempl p,holidayform h,psqcode pc
		  WHERE  p.empl_id_no='$id_no'
		  AND    p.empl_no=h.pocard
		  AND    h.CONDITION='1'
		  AND    substr(povdateb,1,3)= '$year'
		  AND    pc.CODE_KIND='0302'
		  AND    pc.CODE_FIELD=h.POVTYPE";
$data['data'] = $db -> query_array ($sqlStr, true);

echo json_encode($data);
exit;



HOILDAY_LIST_CANCELED:
$sqlStr ="SELECT empl_chn_name,substr(pc.CODE_CHN_ITEM,1,2) code_chn_item,
                 h.POVDATEB,h.POVDATEE,h.POVTIMEB,h.POVTIMEE,
                 h.POVDAYS || '天' || h.POVHOURS || '時' AS POVTOTIME,
                 (SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO=h.AGENTNO) AGENTNAME,
		         h.THREESIGND
		  FROM   psfempl p,holidayform h,psqcode pc
          WHERE  h.POCARD IN ('$userid','$id_no') 
          AND    substr(povdateb,1,3)= '$year'
          AND    p.empl_no=h.pocard
          AND    h.CONDITION='-1'
          AND    pc.CODE_KIND='0302'
          AND    pc.CODE_FIELD=h.POVTYPE";

$data['data'] = $db -> query_array ($sqlStr, true);

echo json_encode($data);
exit;


HOILDAY_LIST_DEALING:

$sqlStr = "SELECT empl_chn_name,substr(pc.CODE_CHN_ITEM,1,2) code_chn_item,
                  h.POVDATEB,h.POVDATEE,h.POVTIMEB,h.POVTIMEE,
                  h.POVDAYS || '天' || h.POVHOURS || '時' AS POVTOTIME,
                  NVL(h.agentsignd, '-') AGENTSIGND,
                  NVL(h.onesignd, '-') ONESIGND,
                  NVL(h.twosignd, '-') TWOSIGND,
                  NVL(h.poremark, '-') POREMARK,
                  h.THREESIGND,h.perone_signd,h.pertwo_signd,h.secone_signd
		   FROM   psfempl p,holidayform h,psqcode pc
		   WHERE  h.POCARD IN ('$userid','$id_no') 
           AND    p.empl_no=h.pocard
           AND    h.CONDITION='0'
           AND    substr(povdateb,1,3)='$year'	
           AND    pc.CODE_KIND='0302'
           AND    pc.CODE_FIELD=h.POVTYPE";

$data['data'] = $db -> query_array ($sqlStr, true);

echo json_encode($data);
exit;

HOILDAY_LIST_REJECTED:

$sqlStr ="SELECT empl_chn_name,substr(pc.CODE_CHN_ITEM,1,2) code_chn_item,
                 h.POVDATEB,h.POVDATEE,h.POVTIMEB,h.POVTIMEE,
                 h.onecomt||h.twocomt||h.threecomt AS DEPARTREASON,
                 h.perone_commt||h.pertwo_commt AS PERTWOSIGND,
                 NVL(h.secone_commt, '-') SECONE_COMMT
		  FROM psfempl p,holidayform h,psqcode pc
		  WHERE h.POCARD IN ('$userid','$id_no')
		  AND substr(povdateb,1,3)= '$year'
		  AND p.empl_no=h.pocard
		  AND h.CONDITION='2'
		  AND pc.CODE_KIND='0302'
		  AND pc.CODE_FIELD=h.POVTYPE";

$data['data'] = $db -> query_array ($sqlStr, true);


echo json_encode($data);
exit;
?>