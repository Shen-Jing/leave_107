<?php
    session_start();
    include '../inc/connect.php';

if($_POST['oper'] == "qry_dpt")
{
    $sql = "select dept_no,dept_full_name
			from   stfdept
			where  use_flag is null
			order  by dept_no";
	$data = $db -> query_array($sql);

	echo json_encode($data);
	exit;
}

if($_POST['oper'] == "qry_type")
{
	$sql = "SELECT code_field,code_chn_item FROM psqcode where code_kind='0302'  order by code_field ";
	$data = $db -> query_array($sql);

	echo json_encode($data);
	exit;
}

if($_POST['oper'] == "qry_name")
{
	$sql = "select empl_no,empl_chn_name
			from   psfempl,psfcrjb
			where  empl_no=crjb_empl_no
			and    crjb_seq='1'
			and    crjb_quit_date is null
			and    substr(empl_no,1,1) in ('0','5','7')
			and    crjb_depart='$_POST[dpt]'";

	$data = $db -> query_array($sql);

	echo json_encode($data);
	exit;
}

if($_POST['oper'] == "fill_data")
{
	$SQLStr =	"SELECT code_chn_item
				FROM  psfcrjb,psqcode
				where  crjb_empl_no='$_POST[empl_no]'
				and    crjb_depart='$_POST[dpt]'
				and    code_kind='0202'
				and    code_field=crjb_title";

	$data = $db -> query_array($SQLStr);

	echo json_encode($data);
	exit;
}

if($_POST['oper'] == "check")
{
	if($_POST['dpt'] == '')
		$message = "請選擇單位!";
	else if($_POST['type'] == '')
		$message = "請選擇假別!";
	else if(empty($_POST['empl_no']))
		$message = "請選擇姓名!";
	else
	{
		$SQLStr2 = "insert into holidayform(pocard,povtype,povdateb,povdatee,povhours,povdays,depart,condition)
			            values('$_POST[empl_no]','$_POST[type]',lpad($_POST[btime],7,'0'),lpad($_POST[etime],7,'0'),
						$_POST[hour],$_POST[hday],'$_POST[dpt]','1')";

	   	$message = $db -> query($SQLStr2);

   		if( !empty($message["message"]) )
    		$message = "儲存有問題!";
    	else
			$message = "儲存完畢!";
	}

	echo json_encode($message);
	exit;
}
?>