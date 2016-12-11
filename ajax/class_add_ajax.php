<?
session_start();
include '../inc/connect.php';
$empl_no = $_SESSION['empl_no'];
$account="0ob@cc.ncue.edu.tw";
$today = getdate();
$year = $today["year"] - 1911;
$month = $today["mon"];
$sql = "select empl_no
        from  psfempl, psfcrjb, stfdept
   where empl_no=crjb_empl_no
   and crjb_depart=dept_no
   and crjb_seq='1'
   and substr(empl_no,1,1) !='A'
   and crjb_quit_date is null
   and psfempl.email='".$account."'
        ";
  $userid =$db -> query_first_row($sql)[0];

                 if($_POST['oper'] == "qry_year")
                 {

                   $data = array('year' => $year, 'month' => $month );
                   echo json_encode($data);
                   exit;

                 }
                 if($_POST['oper'] == "qry_class")
                 {

                   $data = array('year' => $year);
                   echo json_encode($data);
                   exit;

                 }
                 if($_POST['oper']==1)//修改
                 {
                   $SQLStr ="select * from haveclass
                 			    	where class_serialno='$_POST[serialnoVar]'";
                   $row = $db -> query_array($SQLStr);
                   echo json_encode($row);
                   exit;

                 }
                 if($_POST['oper']==0)
                 {
                   $sql = "SELECT count(*) count
                 		FROM holidayform
                 		where substr(lpad(povdateb,7,'0'),1,3)=	lpad('$_POST[p_year]',3,'0')
                 		and   substr(lpad(povdateb,7,'0'),4,2)=	lpad('$_POST[p_month]',2,'0')
                 		and POCARD='$userid'
                 		and (condition =0 or  condition =1 or condition=2)";
                    $row = $db -> query_array($sql);
                    if($row['COUNT'][0]==0)
                    {
                      echo json_encode($row);
                      exit;
                    }else {
                      $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE,
                    	h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
                        h.serialno,h.CURENTSTATUS,h.depart
                    	FROM psfempl p,holidayform h,psqcode pc
                    	where substr(lpad(povdateb,7,'0'),1,3)=  lpad('$_POST[p_year]',3,'0')
                    	and   substr(lpad(povdateb,7,'0'),4,2)=	 lpad('$_POST[p_month]',2,'0')
                    	and POCARD='$userid'
                    	and (condition =0 or  condition =1 or condition=2)
                    	and p.empl_no=h.pocard
                    	and pc.CODE_KIND='0302'
                    	and pc.CODE_FIELD=h.POVTYPE
                    	order by h.POVDATEB desc ,h.POVHOURS desc";
                      $row = $db -> query_array($SQLStr);
                      echo json_encode($row);
                      exit;
                    }
                 }







  ?>
