<?
	session_start();
  	include '../inc/connect.php';
  	$today = getdate();
   	$year  = $today["year"] - 1911;
   	$month = $today["mon"];
   	$day     = $today["mday"];

   	if(strlen($month)<2)
     	$month ='0'.$month;

   	if(strlen($day)<2)
      	$day = '0'.$day;

  	$userid = $_SESSION['empl_no'];

  	if($_POST["oper"] == "view")
  	{
  		$serialno = $_POST["serialno"];

  		$sql = "select * from haveclass where class_serialno='$serialno' order by class_no ";


  		$row = $db -> query_array($sql);

  		$data = "";
  		for($i = 0; $i < count($row['CLASS_NAME']) ; $i++)
  		{
  			$class_memo ='';
  			$class_name    = $row['CLASS_NAME'][$i];
  			$class_subject  = $row['CLASS_SUBJECT'][$i];
  			$class_date      = $row['CLASS_DATE'][$i];
  			$class_date2    = $row['CLASS_DATE2'][$i];
  			$class_room     = $row['CLASS_ROOM'][$i];
  			$class_section  = $row['CLASS_SECTION2'][$i];
  			$class_code     = $row['CLASS_CODE'][$i];
  			$class_week     = $row['CLASS_WEEK'][$i];
  			$class_week2   = $row['CLASS_WEEK2'][$i];
  			$class_memo    = $row['CLASS_MEMO'][$i];
  			$class_selcode  = $row['CLASS_SELCODE'][$i];
  			$class_year       = $row['CLASS_YEAR'][$i];
  			$class_acadm   = $row['CLASS_ACADM'][$i];

  			$data = $data . "<tr><td align=\"center\">" ;
  			$data = $data . $class_name ;
  			$data = $data . "</td><td>" ;
  			$data = $data . $class_selcode ;
  			$data = $data . "</td><td>" ;
  			$data = $data . $class_subject ;
  			$data = $data . "</td><td>" ;
  			$data = $data . $class_date."(".$class_week.")";
  			$data = $data . "</td><td>" ;
  			$data = $data . $class_date2."(".$class_week2.")";	 ;
  			$data = $data . "</td><td>" ;
  			$data = $data . $class_room ;
  			$data = $data . "</td><td>" ;
  			$data = $data . $class_section ;
  			$data = $data . "</td><td>" ;
  			if ($class_memo =='')
  				$data = $data . "--" ;
  			else
  				$data = $data . $class_memo;

  			$data = $data . "</td></tr>";
  		}

  		if(empty($row["message"]))
  			echo json_encode($data);
  		else
  			echo json_encode("發生錯誤!");

  		exit;
  	}
  	else if($_POST["oper"] == "edit")
  	{

  	}
  	else if($_POST["oper"] == 0)
  	{
  		$sql = "SELECT class_serialno, substr(class_serialno,1,3)||'/'||substr(class_serialno,4,2)||'/'||substr(class_serialno,6,2) day FROM haveclass where substr(class_serialno,1,3) = lpad('$_POST[p_year]',3,'0') and substr(class_serialno,8,7) = '$userid' group  by class_serialno";

  		$row = $db -> query_array($sql);

  		$a['data']="";

  		for($i = 0; $i < count($row['CLASS_SERIALNO']) ; $i++)
  		{
  			$serialno = $row["CLASS_SERIALNO"][$i];
  			$day = $row["DAY"][$i];

  		    $a['data'][] = array(
  		        $serialno,
  		        $day,
  		        "<button type='button' class='btn-primary' name='view' id='view' onclick='View($serialno)' title='查看紀錄'>查看紀錄</button>",
  		        "<a role='button' class='btn-warning' name='edit' id='edit' href=\"class_year_2.php?serialno=$serialno\" title='修改申請單'>修改申請單</button>"
  		    );

  		}
  		echo json_encode($a);

  	}

?>