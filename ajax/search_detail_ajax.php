<?
session_start();
include '../inc/connect.php';
$account="0ob@cc.ncue.edu.tw";
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
  //$name = $db -> query_first_row($sql)[1];
  //echo $userid;




  $today  = getdate();
  $year   = $today["year"] - 1911;




              /* $begin_date=$_POST['p_menu'].'0101';
                $end_date=$_POST['p_menu'].'1231';*/

                 $name=$_SESSION['empl_name'][0];
                 $empl_no="";


                 //echo $year;

                 if($_POST['oper'] == "p_menu")
                 {

                     $data = array("year" => array("$year"));
                     $begin_date=$_POST['year'].'0101';
                     $end_date=$_POST['year'].'1231';
                     //echo "ppp success!!!";
                 	echo json_encode($data);
                     exit;

                 }






  ?>
