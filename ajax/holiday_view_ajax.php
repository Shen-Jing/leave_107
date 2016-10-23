<?php
include '../inc/connect.php';

$year    = $_POST['year'];

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


$sql="SELECT empl_id_no 
      FROM 	 psfempl
      WHERE  empl_no='$userid'";
$id_no = $db -> query_first_row($sql)[0];

//################################################

switch($_POST['tbid']){
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
$sqlStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,
                 h.POVDATEB,h.POVDATEE,h.poremark,
			     h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,
                 h.agentsignd,h.onesignd,h.twosignd
		  FROM   psfempl p,holidayform h,psqcode pc
		  WHERE  p.empl_id_no='$id_no'
		  AND    p.empl_no=h.pocard
		  AND    h.CONDITION='1'
		  AND    substr(povdateb,1,3)= '$year'
		  AND    pc.CODE_KIND='0302'
		  AND    pc.CODE_FIELD=h.POVTYPE";
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
               $data['TWOSIGND'][$i],
               $data['POREMARK'][$i]);
}

echo json_encode($a);
exit;



HOILDAY_LIST_CANCELED:
$sqlStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE,
		         h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
		         h.serialno,h.CURENTSTATUS,h.THREESIGND,h.poremark
		  FROM   psfempl p,holidayform h,psqcode pc
          WHERE  h.POCARD IN ('$userid','$id_no') 
          AND    substr(povdateb,1,3)= '$year'
          AND    p.empl_no=h.pocard
          AND    h.CONDITION='-1'
          AND    pc.CODE_KIND='0302'
          AND    pc.CODE_FIELD=h.POVTYPE
          ORDER BY h.POVDATEB DESC,h.POVHOURS";

$data = $db -> query_array ($sqlStr);
$a['data']="";


for ($i=0; $i < sizeof($data['EMPL_CHN_NAME']); $i++) {
    
    $agentno = $data['AGENTNO'][$i];
    $sqlStr2 = " SELECT EMPL_CHN_NAME 
                 FROM   PSFEMPL 
                 WHERE  EMPL_NO='$agentno' ";
    $agentname = $db -> query_array($sqlStr2)['EMPL_CHN_NAME'][0];

    $a['data'][] = array(
        $data['EMPL_CHN_NAME'][$i],
        $data['CODE_CHN_ITEM'][$i],
        $data['POVDATEB'][$i],
        $data['POVDATEE'][$i],
        $data['POVTIMEB'][$i],
        $data['POVTIMEE'][$i],
        $data['POVDAYS'][$i]."天".$data['POVHOURS'][$i]."時",
        $agentname,
        $data['THREESIGND'][$i]
    );
}

echo json_encode($a);
exit;


HOILDAY_LIST_DEALING:

$sqlStr = "SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE,
		          h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,h.serialno,h.CURENTSTATUS,h.agentsignd,h.onesignd,h.twosignd, h.THREESIGND,h.poremark
		   FROM   psfempl p,holidayform h,psqcode pc
		   WHERE  h.POCARD IN ('$userid','$id_no') 
           AND    p.empl_no=h.pocard
           AND    h.CONDITION='0'
           AND    substr(povdateb,1,3)='$year'	
           AND    pc.CODE_KIND='0302'
           AND    pc.CODE_FIELD=h.POVTYPE
           ORDER BY h.POVDATEB DESC,h.POVHOURS";

$data = $db -> query_array ($sqlStr);
$a['data']="";


for ($i=0; $i < sizeof($data['EMPL_CHN_NAME']); $i++) {
    $a['data'][] = array(
        $data['EMPL_CHN_NAME'][$i],
        $data['CODE_CHN_ITEM'][$i],
        $data['POVDATEB'][$i],
        $data['POVDATEE'][$i],
        $data['POVTIMEB'][$i],
        $data['POVTIMEE'][$i],
        $data['POVDAYS'][$i]."天".$data['POVHOURS'][$i]."時",
        $data['AGENTSIGND'][$i],
        $data['ONESIGND'][$i],
        $data['TWOSIGND'][$i],
        $data['POREMARK'][$i]
    );
}
echo json_encode($a);
exit;

HOILDAY_LIST_REJECTED:

$sqlStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE,h.poremark,
		         h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
		         h.serialno,h.CURENTSTATUS,h.onecomt,h.twocomt,h.threecomt,
		         h.perone_commt,h.pertwo_commt,h.secone_commt 
		  FROM psfempl p,holidayform h,psqcode pc
		  WHERE h.POCARD IN ('$userid','$id_no')
		  AND substr(povdateb,1,3)= '$year'
		  AND  p.empl_no=h.pocard
		  AND h.CONDITION='2'
		  AND pc.CODE_KIND='0302'
		  AND pc.CODE_FIELD=h.POVTYPE
		  ORDER BY h.POVDATEB DESC,h.POVHOURS";

$data = $db -> query_array ($sqlStr);
$a['data']="";

for ($i=0; $i < sizeof($data['EMPL_CHN_NAME']); $i++) {
    $a['data'][] = array(
        $data['EMPL_CHN_NAME'][$i],
        $data['CODE_CHN_ITEM'][$i],
        $data['POVDATEB'][$i],
        $data['POVDATEE'][$i],
        $data['POVTIMEB'][$i],
        $data['POVTIMEE'][$i],
        $data['TWOCOMT'][$i],
        $data['PERTWO_COMMT'][$i],
        $data['SECONE_COMMT'][$i]
    );
}

echo json_encode($a);
exit;
?>