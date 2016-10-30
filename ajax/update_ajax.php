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
$userid = $db -> query_first_row($sql)[0];;
//"0000848";

//fetch COUNT
$sql = "SELECT count(*) count
		FROM   holidayform 
		WHERE  POCARD='$userid' 
		AND    condition in ('0','2')";
$count = $db -> query_first_row($sql)[0];

switch(@$_POST['oper'])
{
    case "edit":
        goto EDIT_DATA;
    case "del":
        goto DELETE_DATA;
    default:
        break;
}

LOAD_DATA:
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

$a['rows']="";

for ($i=0; $i < sizeof($data['EMPL_CHN_NAME']); $i++) {      
           $a['rows'][] = [
			   'SERIALNO' => $data['SERIALNO'][$i],
               'EMPL_CHN_NAME' => $data['EMPL_CHN_NAME'][$i],
               'CODE_CHN_ITEM' => $data['CODE_CHN_ITEM'][$i],
               'POVDATEB' => $data['POVDATEB'][$i],
               'POVDATEE' => $data['POVDATEE'][$i],
               'POVTIMEB' => $data['POVTIMEB'][$i],
               'POVTIMEE' => $data['POVTIMEE'][$i],
			   'POVHOURS' => $data['POVHOURS'][$i],
			   'AGENTNO' => $data['AGENTNO'][$i]
			   ];
}
echo json_encode($a); 
exit;




EDIT_DATA:

$sql ="UPDATE holidayform
       SET    POVDATEB='".$_POST['POVDATEB']."',POVDATEE='".$_POST['POVDATEE']."',
			  POVHOURS='".$_POST['POVHOURS']."', POVTIMEB='".$_POST['POVTIMEB']."',POVTIMEE='".$_POST['POVTIMEE']."',AGENTNO='".$_POST['AGENTNO']."'
       WHERE  SERIALNO='".$_POST['SERIALNO']."'
       ";
$data = $db -> query($sql);               
$message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
echo json_encode($message);  
exit;  


DELETE_DATA:

$sql ="DELETE holidayform
       WHERE  SERIALNO='".$_POST['SERIALNO']."'
       AND    EMPL_CHN_NAME='".$_POST['EMPL_CHN_NAME']."'
       ";
$data = $db -> query($sql);               
$message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
echo json_encode($message);

?>