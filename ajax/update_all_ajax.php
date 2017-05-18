<?php
include '../inc/check.php';

if(!isset($_POST["oper"]))
	exit;

$userid = $_SESSION['empl_no'];



switch(@$_POST['oper'])
{
	case "qrydept":
		goto QRYDEPT;
	case "qryemps":
		goto QRYEMPS;
    case "load":
        goto LOAD;
    default:
        exit;
}

QRYDEPT:

$sql = "SELECT substr(min(dept_no), 0, 2) DEPT_NO, min(dept_full_name) DEPT_FULL_NAME
		FROM stfdept
		WHERE use_flag is null			
		GROUP BY substr(dept_no,1,2)
		";

$data = $db -> query_array($sql, true);

echo json_encode($data); 

exit;

QRYEMPS:

if(empty($_POST['dept']))
	exit;
$dept = $_POST['dept'];

$sql = "SELECT empl_chn_name
		FROM   psfempl,psfcrjb
		WHERE  empl_no=crjb_empl_no 
		AND    crjb_seq>='1'
		AND    crjb_quit_date is null
		AND    substr(empl_no,1,1) in ('0','5','7','3','4')
		AND    substr(crjb_depart,1,2)='$dept'
		";

$data = $db -> query_array($sql, true);

echo json_encode($data); 

exit;


LOAD:

$year = $_POST['year'];
$month = $_POST['month'];

$sqlsubstr_oper_all = ($_POST['unpassed'] == "true") ? "<=" : "=";

$sql = "SELECT 	substr(DEPT_SHORT_NAME,1,14) deptname, empl_chn_name AS poname, substr(pc.CODE_CHN_ITEM,1,2) povtype,
				h.POVDATEB, h.POVDATEE, h.POVTIMEB,
				h.POVDAYS || '天' || h.POVHOURS || '時' AS AGGRETIME,
				h.appdate,
				CASE (h.condition)
					WHEN '0' THEN '簽核中'
					WHEN '1' THEN '簽核完成'
					WHEN '2' THEN '退回'
					WHEN '-1' THEN '取消'
					ELSE h.condition
				END CONDITION,
				CASE (h.curentstatus)
					WHEN '0' THEN '代理人'
					WHEN '1' THEN '二級主管'
					WHEN '2' THEN '一級主管'
					WHEN '3' THEN '院長'
					WHEN '4' THEN '人事承辦'
					WHEN '5' THEN '人事主任'
					WHEN '6' THEN '秘書室承辦'
					WHEN '7' THEN '主秘'
					ELSE h.curentstatus
				END CURENTSTATUS,
				h.serialno, h.pocard, h.depart
		FROM 	psfempl p,holidayform h,psqcode pc,stfdept
		WHERE 	substr(lpad(povdateb,7,'0'),1,3)  =  lpad('$year',3,'0') 
		AND     substr(lpad(povdateb,7,'0'),4,2)".$sqlsubstr_oper_all."lpad('$month',2,'0')
		AND     dept_no=depart
		AND     condition in ('0','2')
		AND     p.empl_no=h.pocard
		AND     pc.CODE_KIND='0302'
		AND     pc.CODE_FIELD=h.POVTYPE
		";

$aoData['data'] = $db -> query_array($sql, true);

// method 2 : 利用php處理值映射
// $arr_condition = array(0=>'簽核中','簽核完成','退回',-1=>'取消');
// $arr_curentstatus = array(0=>'代理人','二級主管','一級主管','院長','人事承辦','人事主任','秘書室承辦','主秘');

// for ($i=0; $i < count($aoData['data']); $i++) { 
// 	$aoData['data'][$i]['CONDITION'] = $arr_condition[$aoData['data'][$i]['CONDITION']];
// 	$aoData['data'][$i]['CURENTSTATUS'] = $arr_curentstatus[$aoData['data'][$i]['CURENTSTATUS']];
// }


echo json_encode($aoData); 
exit;


?>