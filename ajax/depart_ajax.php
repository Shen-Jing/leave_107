<?php
  session_start();
  include '../inc/connect.php';

	$year = $_POST['year'];
  $month = $_POST['month'];
	$dept = $_POST['dept'];
	$sql = "SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,4) code_chn_item,h.POVDATEB,
									h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,
									h.AGENTNO,h.serialno,h.CURENTSTATUS,h.agentsignd,h.onesignd,h.twosignd,h.condition,
									h.THREESIGND,h.depart,h.serialno
					FROM psfempl p,holidayform h,psqcode pc
					where substr(lpad(povdateb,7,'0'),1,3)  = lpad('$year',3,'0')
					and   substr(lpad(povdateb,7,'0'),4,2)  = lpad('$month',2,'0')
					and  pocard in (select crjb_empl_no
									from  psfcrjb
									where substr(crjb_depart,1,2)=substr('$dept',1,2)
									and   substr(crjb_empl_no,1,1) <>'A'
									and   crjb_quit_date is null)
					and CONDITION<>'-1'
					and p.empl_no=h.pocard
					and pc.CODE_KIND='0302'
					and pc.CODE_FIELD=h.POVTYPE
					order by POVDATEB desc ";
  $data = $db -> query_array($sql);
  $data['DEPT_SHORT_NAME'] = array();
  $data['AGENT_NAME'] = array();
  for ($i=0; $i < count($data['AGENTNO']); $i++) {
    $depart = $data['DEPART'][$i];
    $sql = "SELECT substr(DEPT_SHORT_NAME,1,14) dept_short_name
			FROM stfdept
			where dept_no='$depart'";
    $tmp = $db -> query_array($sql);
    $data['DEPT_SHORT_NAME'][$i] = $tmp['DEPT_SHORT_NAME'][0];

    $agentno = $data['AGENTNO'][$i];
    $sql = "SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO='$agentno'";
    $tmp = $db -> query_array($sql);
    $data['AGENT_NAME'][$i] = $tmp['EMPL_CHN_NAME'][0];
  }
  echo json_encode($data);
  exit;
?>
