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
$userid = $db -> fetch_cell($sql);



switch(@$_POST['oper'])
{
    case "load":
        goto LOAD_DATA;
    case "del":
        goto DELETE_DATA;
    default:
        exit;
}


LOAD_DATA:
$sql = "SELECT substr(to_char(sysdate,'yyyymmdd'),1,4)-'1911' end_year,substr(to_char(sysdate,'yyyymmdd'),5,2) end_month 
		FROM dual
		";

$this_year = $db -> fetch_cell($sql, 'end_year');
$this_month = $db -> fetch_cell($sql, 'end_month');


$sql = "SELECT 	empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB, 
				h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,serialno, h.depart,substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate,h.condition,h.curentstatus
		FROM 	psfempl p,holidayform h,psqcode pc,stfdept
		WHERE 	substr(lpad(povdateb,7,'0'),1,3)  =  lpad('$this_year',3,'0') 
		AND     substr(lpad(povdateb,7,'0'),4,2)  <=  lpad('$this_month',2,'0')
		AND     dept_no=depart
		AND     condition in ('0','2')
		AND     p.empl_no=h.pocard
		AND     pc.CODE_KIND='0302'
		AND     pc.CODE_FIELD=h.POVTYPE
		";

$aoData['data'] = $db -> query_array($sql, true);


echo json_encode($aoData); 
exit;



DELETE_DATA:

$sql ="DELETE FROM holidayform
       WHERE  SERIALNO='".$_POST['SERIALNO']."'
       ";
$data = $db -> query($sql);
$message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
echo json_encode($message);

?>