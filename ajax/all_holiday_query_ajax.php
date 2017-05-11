<?php
session_start();
include '../inc/connect.php';
$empl_no = $_SESSION['empl_no'];

$today = getdate();
$year = $today["year"] - 1911;
$month = $today["mon"];
$day = $today["mday"];
if ($_POST['oper']=="qry_year")
{
    $data = array('year' => $year, 'month' => $month,'day'=>$day );
    echo json_encode($data);
    exit;
}else if($_POST['oper'] == 0)
{
  if($_POST['flag']==0)//正式職員
  {
    $SQLStr="select   crjb_empl_no, empl_chn_name, dept_short_name,code_chn_item
						from     psfcrjb,psfempl,stfdept,psqcode
						where   crjb_empl_no=empl_no
						and      crjb_seq='1'
						and       substr(crjb_title,1,1) !='B'
						and       substr(crjb_empl_no,1,1)='0'
						and       crjb_quit_date is null
						and       crjb_depart=dept_no
						and       code_kind='0202'
						and       code_field=crjb_title
						order by  crjb_depart,crjb_empl_no";
  }else if($_POST['flag']==1)//專任教師
  {
    $SQLStr="select   crjb_empl_no, empl_chn_name, dept_short_name,code_chn_item
                        from     psfcrjb,psfempl,stfdept,psqcode
                        where   crjb_empl_no=empl_no
                        and      crjb_seq='1'
                        and       substr(crjb_title,1,1) in('B','C')
                        and       substr(crjb_empl_no,1,1) in('0','4')
                        and       crjb_quit_date is null
                        and       crjb_depart=dept_no
                        and       code_kind='0202'
                        and       code_field=crjb_title
                        order by  crjb_depart,crjb_empl_no";
  }else if($_POST['flag']==3)
  {
    $SQLStr="select   crjb_empl_no, empl_chn_name, dept_short_name,code_chn_item
						from     psfcrjb,psfempl,stfdept,psqcode
						where   crjb_empl_no=empl_no
						and      crjb_seq='1'
						and       substr(crjb_empl_no,1,1)='3'
						and       crjb_quit_date is null
						and       crjb_depart=dept_no
						and       code_kind='0202'
						and       code_field=crjb_title
						order by  crjb_depart,crjb_empl_no";
  }else if($_POST['flag']==5)
  {
    $SQLStr="select   crjb_empl_no, empl_chn_name, dept_short_name,code_chn_item
						from     psfcrjb,psfempl,stfdept,psqcode
						where   crjb_empl_no=empl_no
						and      crjb_seq='1'
						and       substr(crjb_empl_no,1,1)='5'  and crjb_title<>'Z83'
						and       crjb_quit_date is null
						and       crjb_depart=dept_no
						and       code_kind='0202'
						and       code_field=crjb_title
						order by  crjb_depart,crjb_empl_no";
  }else if($_POST['flag']==6)
  {
    $SQLStr="select   crjb_empl_no, empl_chn_name, dept_short_name,code_chn_item
						from     psfcrjb,psfempl,stfdept,psqcode
						where   crjb_empl_no=empl_no
						and      crjb_seq='1'
						and       substr(crjb_empl_no,1,1)='5'    and crjb_title='Z83'
						and       crjb_quit_date is null
						and       crjb_depart=dept_no
						and       code_kind='0202'
						and       code_field=crjb_title
						order by  crjb_depart,crjb_empl_no";
  }else if($_POST['flag']==7)
  {
    $SQLStr="select   crjb_empl_no, empl_chn_name, dept_short_name,code_chn_item
						from     psfcrjb,psfempl,stfdept,psqcode
						where   crjb_empl_no=empl_no
						and      crjb_seq='1'
						and       substr(crjb_empl_no,1,1)='7'
						and       crjb_quit_date is null
						and       crjb_depart=dept_no
						and       code_kind='0202'
						and       code_field=crjb_title
						order by  crjb_depart,crjb_empl_no";
  }else if($_POST['flag']==9)
  {
    $SQLStr="select   crjb_empl_no, empl_chn_name, dept_short_name,code_chn_item
						from     psfcrjb,psfempl,stfdept,psqcode
						where   crjb_empl_no=empl_no
					    and       substrb(crjb_depart,3,1) = '0'
						and       substrb(crjb_title,1,2) in ('A17','A24','A0','A1','A2')
						and       crjb_title != 'A01'
						and       crjb_quit_date is null
						and       crjb_depart=dept_no
						and       code_kind='0202'
						and       code_field=crjb_title
						order by  crjb_depart,crjb_empl_no";
  }
  $row = $db -> query_array($SQLStr);

  $tyear    =$_POST["tyearval"];
	$tmonth =$_POST["tmonthval"];
	$tday     =$_POST["tdayval"];
	$syear    =$_POST["syearval"];
	$smonth =$_POST["smonthval"];
	$sday     =$_POST["sdayval"];

  if (strlen($tmonth)<2)
		$tmonth='0'.$tmonth;
	if (strlen($smonth)<2)
		$smonth='0'.$smonth;
	if (strlen($tday)<2)
		$tday='0'.$tday;
	if (strlen($sday)<2)
		$sday='0'.$sday;

	$begin_date	=$tyear.$tmonth.$tday;
	$end_date   =$syear.$smonth.$sday;
  //echo $end_date;

$a['data']="";
  //echo sizeof($row['EMPL_CHN_NAME']).'<br>';
  for($i = 0; $i < sizeof($row['EMPL_CHN_NAME']); ++$i)
  {
    //echo 'i='.$i.'<br>';
     $empl_no      = $row['CRJB_EMPL_NO'][$i];
		 $empl_name = $row['EMPL_CHN_NAME'][$i];
		 $dept_name  = $row['DEPT_SHORT_NAME'][$i];
		 $title_name   = $row['CODE_CHN_ITEM'][$i];
     $user_no="";

     $vtype =array('01','02','03','32','30','17','04','05','06','07','08','09','11','21','22','23','34');
     /*    DAY12,  DAY13,  DAY14,	 DAY15,  DAY16, DAY17, DAY18 */
     $x[17]="";
     //echo count($vtype).'<br>';
     //echo $empl_no.'<br>';
     for($j=0;$j<count($vtype);$j++){//各假別處理
          $pohdaye=0;
					$pohoure=0;
					//-------------------------------------------
					//1.請假總天數及總時數，在選擇的範圍內
					//-------------------------------------------
          //echo $empl_no.'<br>'.$user_no.'<br>'.'<br>';
					$SQLStr = "SELECT sum(nvl(POVDAYS,0)) POHDAYE,sum(nvl(POVHOURS,0))    POHOURE
										 from   holidayform
										 where povtype='$vtype[$j]'
										 and     povdateb>='$begin_date'
										 and     povdatee<='$end_date'
										 and     pocard in ('$empl_no','$user_no')
										 and    condition in ('1','0')";
          $Arr = $db -> query_array($SQLStr);

          if(sizeof($Arr)>0)
          {
            $pohdaye=$Arr['POHDAYE'][0];
            $pohoure=$Arr['POHOURE'][0];
          }

          //echo 'Part1:'.$pohdaye.'<br>'.$pohoure;
          //----------------------------------------------------------------------------------
					//2.跨月請假--上月月底至本月月初
					//----------------------------------------------------------------------------------
					//97.01.04  POVDATEB<='$begin_date' 改為POVDATEB<'$begin_date'
					$SQLStr = "SELECT POVDATEE,POVTIMEE ,CONTAINSAT,CONTAINSUN
										 FROM holidayform
										 where  povtype='$vtype[$j]'
										 and      POVDATEB<'$begin_date'
										 and      POVDATEE>='$begin_date'
										 and      pocard in ('$empl_no','$user_no')
										 and      condition in ('1','0')";
          $Arr = $db -> query_array($SQLStr);
          //echo "size:". sizeof($Arr['POVDATEE']).'<br>';
          if(sizeof($Arr['POVDATEE'])>0)
          {
            $edate=$Arr['POVDATEE'][0];
            $etime=$Arr['POVTIMEE'][0];

            $saturday=$Arr['CONTAINSAT'][0];
            $bdate=$begin_date;
            $btime='8';
            if (substr($etime,2,2)=='30')
							 $etime=substr($etime,0,2);
            require "../calculate_time.php";
						$pohdaye += $tot_day;
						$pohoure += $tot_hour;
          }
          //echo 'Part2:'.$pohdaye.' '.$pohoure.'<br>';
          //----------------------------------------------------------------------------------
					//3.跨月請假--本月月底至下月月初
					//----------------------------------------------------------------------------------
          $SQLStr = "SELECT POVDATEB,POVTIMEB ,CONTAINSAT,CONTAINSUN
										 FROM holidayform
										 where povtype='$vtype[$j]'
										 and     POVDATEB<='$end_date'
										 and     POVDATEE>'$end_date'
										 and     pocard in ('$empl_no','$user_no')
										 and     condition in ('1','0')";
          $Arr = $db -> query_array($SQLStr);
          //$tmpint=0;
          if(sizeof($Arr['POVDATEB'])>0)
          {
            $bdate=$Arr['POVDATEB'][0];  //起始日期
						$btime=$Arr['POVTIMEB'][0];  //起始時間
						$saturday=$Arr['CONTAINSAT'][0];
						$edate=$end_date;
						$etime='17';
						//...........................................................
						//半小時的轉成整數  10201 add
						if (substr($btime,2,2)=='30')
							$btime=substr($btime,0,2);
						//...........................................................
						require "../calculate_time.php";
						$pohdaye += $tot_day;
						$pohoure += $tot_hour;
          }
          //echo 'Part3:'.$pohdaye.' '.$pohoure.'<br>';
          $temp_h = 0;
					if ($pohoure >= 8){
						$temp_h= $pohoure % 8;
						$pohdaye += floor($pohoure / 8 );
						$pohoure=$temp_h;
					}
					if ($pohdaye=='') $pohdaye=0;
					if ($pohoure=='') $pohoure=0;

          $x[$j]=$pohdaye."/".$pohoure;
          //echo $pohdaye."/".$pohoure.'<br>';
          //echo $x[$j].'<br>';
     }
     //echo 'out'.'<br>'.'<br>';
     //echo $i." ";
      $a['data'][] = array(
         $dept_name,
         $title_name,
         $empl_name,
         $x[0],
         $x[1],
         $x[2],
         $x[3],
         $x[4],
         $x[5],
         $x[6],
         $x[7],
         $x[8],
         $x[9],
         $x[10],
         $x[11],
         $x[12],
         $x[13],
         $x[14],
         $x[15],
         $x[16]);

         //echo $dept_name." ".$title_name." ".$empl_name."  ";
      /*$a['data'][] = array(
           $dept_name,
           $title_name,
           $empl_name);*/


  }
  //echo "OUT!";
echo json_encode($a);
}
?>
