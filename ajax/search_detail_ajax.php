<?
session_start();
include '../inc/check.php';
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
  echo $userid;




  $today  = getdate();
  $year   = $today["year"] - 1911;


            /*if (!IsSet($_SESSION['yy'])){
              $end_year = $year;
              $_SESSION['yy']=$year;
            }
            else{ //page updated then restore,because $end_year must be reflashed ;
              $end_year=$_SESSION['yy'];
              $_POST['p_menu']=$_GET['yval'];
            }

             if ($_POST['p_menu']=='') $_POST['p_menu']=$year;

              /*if($_POST['p_menu']<100)
               $_POST['p_menu']="0".$_POST['p_menu'];*/

               $begin_date=$_POST['p_menu'].'0101';
                $end_date=$_POST['p_menu'].'1231';

                //echo $_POST['p_menu'];
                 //$vtype =array('01','03','04','05','06','07','08','09','21');



                 //$userid =$_SESSION['empl_no'];
                 $name=$_SESSION['empl_name'][0];
                 $empl_no="";


                 echo "p_menu=". $_POST['p_menu'];


        echo json_encode($year);



  ?>
