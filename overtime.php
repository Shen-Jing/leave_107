<?
  	include 'inc/connect.php';
	include("inc/check.php");
?>

<?php
//include ("inc/c_oracle.php");
//header('Content-type: text/html; charset=big5');
	//加班作業  1000429
	//**********************************************************
    //require("check.php");
    $_SESSION["empl_no"] = '0000676';
    $_SESSION["empl_name"] = '李_朗';
    //require "c_oracle.php";  
	//------------------------------------------------------------
	//抓職稱名稱
	//------------------------------------------------------------
	@$SQLStr = "SELECT code_chn_item
		FROM  psqcode
		where code_kind = '0202'
		and code_field = '$_SESSION[title]'";
	$data = $db -> query_array($SQLStr);
	$tname = '技正';
	//$stmt = ociparse($conn,$SQLStr); 
	//ociexecute($stmt,OCI_DEFAULT);
	//if (OCIFETCH($stmt))
		//$tname = ociresult($stmt,"CODE_CHN_ITEM");
		//$tname = '技正';
	//------------------------------------------------------------
	//抓單位名稱
	//------------------------------------------------------------
	@$sql = "select dept_short_name
			from   stfdept
			where  dept_no = '$_SESSION[depart]'";
	$data1 = $db -> query_array($sql);
	$dname = '系統開發組';
	//$stmt = OCIPARSE($conn,$sql);
	//ociexecute($stmt,OCI_DEFAULT); 
	//if (ocifetch($stmt))   
		//$dname = ociresult($stmt,"DEPT_SHORT_NAME");
		//$dname = '系統開發組';
	//------------------------------------------------------------
    //今日日期
	//------------------------------------------------------------
	$today = getdate();
	$year  = $today["year"] - 1911;
	$month = $today["mon"];
	$day   = $today["mday"];

	//------------------------------------------------------------
    // session 設定
	//------------------------------------------------------------
	/**session_register('byear');	session_register('bmonth');  session_register('bday');  
	session_register('eyear');	session_register('emonth');  session_register('eday');  
	session_register('uyear');	session_register('umonth');  session_register('uday');//提簽日期  
	session_register('btime'); 	session_register('etime'); //下班刷卡時間分及時 
	session_register('tot');**/
	
	$_SESSION["tot"] = 0;

	//------------------------------------------------------------
	// 設定初值或給值
	//------------------------------------------------------------
	@(!$_POST["byear"])  ? $byear = $year    : $byear = $_POST["byear"];
	@(!$_POST["bmonth"]) ? $bmonth = $month  : $bmonth = $_POST["bmonth"];
	@(!$_POST["bday"])   ? $bday   = $day    : $bday  = $_POST["bday"];

	@(!$_POST["eyear"])  ? $eyear =$year   : $eyear =$_POST["eyear"];
	@(!$_POST["emonth"]) ? $emonth=$month  : $emonth=$_POST["emonth"];
	@(!$_POST["eday"])   ? $eday  =$day    : $eday  =$_POST["eday"]; 

	@(!$_POST["uyear"])  ? $uyear =$year   : $uyear =$_POST["uyear"];
	@(!$_POST["umonth"]) ? $umonth=$month  : $umonth=$_POST["umonth"];
	@(!$_POST["uday"])   ? $uday  =$day    : $uday  =$_POST["uday"]; 

    //上班刷卡時間
 	@(!$_POST["btime"])  ? $btime=''      : $btime=$_POST["btime"];
	//下班刷卡時間
	@(!$_POST["etime"])  ? $etime=''      : $etime=$_POST["etime"];  

	@(!$_POST["reason"])  ? $reason=''      : $reason=$_POST["reason"];  

	//------------------------------------------------------------
	// 值是否有異動? session 未設 或 值有異動時 session 都要重給
	//------------------------------------------------------------
	if (@$_SESSION["byear"]==""  || (@$_SESSION['byear'] != ""  and  @$_POST["byear"] != ""))  
	    $_SESSION["byear"]=$byear;

	if (@$_SESSION["bmonth"]==""  || (@$_SESSION['bmonth'] != ""  and  @$_POST["bmonth"] != "")) 
	    $_SESSION["bmonth"]=$bmonth;

	if (@$_SESSION["bday"]==""  || (@$_SESSION['bday'] != ""  and  @$_POST["bday"] != "")) 
	    $_SESSION["bday"]=$bday;
//---------
	if (@$_SESSION["eyear"]==""  || (@$_SESSION['eyear'] != ""  and  @$_POST["eyear"] != ""))  
	    $_SESSION["eyear"]=$eyear;

	if (@$_SESSION["emonth"]==""  || (@$_SESSION['emonth'] != ""  and  @$_POST["emonth"] != "")) 
	    $_SESSION["emonth"]=$emonth;

	if (@$_SESSION["eday"]==""  || (@$_SESSION['eday'] != ""  and  @$_POST["eday"] != "")) 
	    $_SESSION["eday"]=$eday;
//---------
	if (@$_SESSION["uyear"]==""  || (@$_SESSION['uyear'] != ""  and  @$_POST["uyear"] != ""))  
	    $_SESSION["uyear"]=$uyear;

	if (@$_SESSION["umonth"]==""  || (@$_SESSION['umonth'] != ""  and  @$_POST["umonth"] != "")) 
	    $_SESSION["umonth"]=$umonth;

	if (@$_SESSION["uday"]==""  || (@$_SESSION['uday'] != ""  and  @$_POST["uday"] != "")) 
	    $_SESSION["uday"]=$uday;
//---------
	if (@$_SESSION["btime"]=="" || (@$_SESSION['btime'] != "" and  @$_POST["btime"] != ""))
        $_SESSION["btime"]=$btime;

	if (@$_SESSION["etime"]== "" || (@$_SESSION['etime'] != "" and  @$_POST["etime"] != "")) 
	    $_SESSION["etime"]=$etime;  

	//------------------------------------------------------------
	// 選擇的日期
	//------------------------------------------------------------
	if(strlen($_SESSION["bmonth"])<2)
		$_SESSION["bmonth"]='0'.$_SESSION["bmonth"];
	if(strlen($_SESSION["bday"])<2)
		$_SESSION["bday"]='0'.$_SESSION["bday"];

	if(strlen($_SESSION["emonth"])<2)
		$_SESSION["emonth"]='0'.$_SESSION["emonth"];
	if(strlen($_SESSION["eday"])<2)
		$_SESSION["eday"]='0'.$_SESSION["eday"];

	if(strlen($_SESSION["umonth"])<2)
		$_SESSION["umonth"]='0'.$_SESSION["umonth"];
	if(strlen($_SESSION["uday"])<2)
		$_SESSION["uday"]='0'.$_SESSION["uday"];

	 $over_date =$_SESSION["byear"].$_SESSION["bmonth"].$_SESSION["bday"];
	 $over_date2=$_SESSION["eyear"].$_SESSION["emonth"].$_SESSION["eday"];//加班跨隔日	 
	 $draw_date =$_SESSION["uyear"].$_SESSION["umonth"].$_SESSION["uday"];//提簽日期
//----------------------------------------------
//   1000629 add  判斷是否為寒暑假期間
//----------------------------------------------

$SQLStr2 = "select  count(*) count
			from    t_card_time
			where   '$over_date' between afternoon_s and afternoon_e";
//echo $SQLStr2;
$data2 = $db -> query_array($SQLStr2);
//$stmt=ociparse($conn,$SQLStr2); 
//ociexecute($stmt,OCI_DEFAULT);
//if (OCIFETCH($stmt))
   //$cn=ociresult($stmt,"COUNT");

	 
	 //------------------------------------------------------------
    //確定送出處理求加班時數
	//------------------------------------------------------------
   if(@$_POST["check"] and $_SESSION["btime"]!='' and $_SESSION["etime"]!=''  and $reason!=''){
		 //------------
		 //計算加班時數
		 //------------
         if ($over_date == $over_date2){  // 加班在同一天
			if (substr($_SESSION["etime"],2,2) >= substr($_SESSION["btime"],2,2)) //下班分 > 上班分
				$tot= substr($_SESSION["etime"],0,2) - substr($_SESSION["btime"],0,2) ;
			else
				$tot= substr($_SESSION["etime"],0,2) - substr($_SESSION["btime"],0,2) - 1;  //借時

		 }
		 else{   //加班過淩晨，不同天
			if (substr($_SESSION["etime"],2,2) >= substr($_SESSION["btime"],2,2)) //下班分 > 上班分
				$tot= substr($_SESSION["etime"],0,2) + 24 - substr($_SESSION["btime"],0,2)  ;
			else
				$tot= substr($_SESSION["etime"],0,2) + 24 - substr($_SESSION["btime"],0,2) - 1;  //借時
         }

		 $_SESSION["tot"]=$tot; //採計到時

		 $time_1=$_SESSION["btime"];
		 $time_2=$_SESSION["etime"];

		 //-------------------------------------------------------------------
		 //求此加班日之六月內到期日為何(不排除例假日），請人事室將隔年的行事曆也產生並維護好
		 //-------------------------------------------------------------------
        /*
		select min(calendar_yymm||lpad(to_char(calendar_dd),2,'0'))  due_date
		from   ps_calendar
		where  calendar_status is null
		and    lpad(calendar_yymm||lpad(to_char(calendar_dd),2,'0'),7,'0') >='1000426'
		group  by lpad(calendar_yymm||lpad(to_char(calendar_dd),2,'0'),7,'0')
		having count(*) >= 60
		*/
/*
		$sql="select calendar_yymm||lpad(to_char(calendar_dd),2,'0')  due_date
				from   ps_calendar
				where  lpad(calendar_yymm||lpad(to_char(calendar_dd),2,'0'),7,'0') >='$over_date'";
		$stmt=ociparse($conn,$sql);         
		ociexecute($stmt,OCI_DEFAULT);
		$i=0;
		while (OCIFETCH($stmt)){
		  $due_date=ociresult($stmt,"DUE_DATE");
          if ($i++ ==180)  break;
        }
*/
        //104/08/26 update! 改成6個月
		if (substr($over_date,3,2)>'06')
			$due_date = substr($over_date,0,3)+1 . substr($over_date,3,2)-6 . substr($over_date,5,2);
		else
			$due_date = substr($over_date,0,3) . substr($over_date,3,2)+6 . substr($over_date,5,2);



       //寫入檔案
	  $SQLStr = "insert into overtime (EMPL_NO,OVER_DATE,DO_TIME_1,DO_TIME_2,NOUSE_TIME,PERSON_CHECK,DRAW_DATE,DUE_DATE,ALL_TIME,REASON)";
	  $SQLStr .= " values ('$_SESSION[empl_no]','$over_date','$time_1','$time_2',$tot,'0','$draw_date','$due_date',$tot,'$reason')";
      //echo $SQLStr;
	  $stmt = OCIPARSE($conn,$SQLStr);
	  $value= ociexecute($stmt,OCI_DEFAULT);
	  if ($value==false)
		  echo "<script> alert('資料重複申請或儲存有問題，請洽管理者')</script>";
	  else{
		  OCICommit($conn);
		  //通知人事室
          /*$mail_from     ="edoc@cc.ncue.edu.tw";
		  $mail_headers  = "From: $mail_from\r\n";
		  $mail_headers .= "Reply-To:lucy@cc.ncue.edu.tw\r\n";
		  $mail_headers .= "X-Mailer: PHP\r\n"; // mailer  
		  $mail_headers .= "Return-Path: edoc@cc2.ncue.edu.tw\r\n"; 
		  $mail_headers .= "Content-type: text/html; charset=big5\r\n";

		  $mail_body2 = $_SESSION['empl_name']."於". substr($over_date,0,3). "/". substr($over_date,3,2). "/". substr($over_date,5,2)."申請加班，請上線審核其資料。"; //本體

		  $mail_subject2 = "教職員加班申請通知";                    //主旨
		  $mail_subject2 = "=?big5?B?".base64_encode($mail_subject2)."?=";

		  @mail('lucy@cc.ncue.edu.tw', $mail_subject2, $mail_body2,$mail_headers);
		  //@mail($email, $mail_subject2, $mail_body2,$mail_headers);*/

		 unset($_SESSION['btime']);
		 unset($_SESSION['etime']);
		  echo "<script> alert('資料儲存完畢。')</script>";
		  }
   } //check   
	else if (@$_POST["check"] and $reason =='' )
	   echo "<script>alert('請輸入加班原因!'); </script>";	
	else if (@$_POST["check"] and ($_SESSION["btime"] =='' || $_SESSION["etime"]==''))
	   echo "<script>alert('請選擇刷卡時間!'); </script>";
?>
<!--=============================================================================================-->
<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
<style>
	h3{
		font-family: "微軟正黑體";
	}
	li{
		font-family: "微軟正黑體";
	}
	table{
		font-family: "微軟正黑體";
	}

</style>
<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid" >
		<? include ("inc/page-header.php"); ?>
		<form name="holiday" action="overtime.php" method="post"   ENCTYPE="multipart/form-data">
		<center>
		<div class="container text-left">
	        <div class="alert alert-warning">
	        	<i class="fa fa-warning">
	        		注意!
	        		<ol>
	        			<li>
	        			    加班應事先以書面專案簽准。
	        			</li>
	        			<li>
	        			    請於加班刷卡紀錄寫入差假系統之"上下班之刷卡資料"後再進行加班申請作業。
	        			</li>
	        			<li>
	        			    加班申請需人事室審核通過後方能申請補休。
	        			</li>
	        			<li>
	        			    加班時數應由六個月內補休完畢，並以"時"為計算單位。
	        			</li>
	        			<li>
	        			    除加班過凌晨可跨日外，其餘均以一天為單位分次申請。
	        			</li>
	        		</ol>
	        	</i>
	        </div>
	    </div>
		<center>
		<div class="panel panel-primary">
			<div class="panel-heading" style="text-align:left">
			    加班申請作業
			</div>
			<div class="panel-body panel-height">
				<table class="table table-bordered" id="table1">
					<thead>
						<tr>
							<td class="col-md-2">員工編號</td>
							<td class="col-md-4"><?=$_SESSION['empl_no']?></td>
							<td class="col-md-2">姓名</td>
							<td class="col-md-4"><?php echo $_SESSION['empl_name']?></td>
						</tr>

						<tr> 
							<td class="col-md-2"> 單位</td>
							<td class="col-md-4"><?php echo $dname ?></td>
							<td class="col-md-2"> 職稱</td>
							<td class="col-md-4"><?php echo $tname?></td>
						</tr>
					</thead>

					<thead>
					<tr>
						<td class="col-md-2">加班原因</td>
						<td class="col-md-4" style="text-align:left" colspan=3><font size="4">
							<?php
							echo "加班簽呈日期：<select class='selectpicker' data-width='auto' data-style= 'btn-default' name='uyear' onChange='document.holiday.submit();'>";
							for ($i=$year-1;$i<=($year+1);$i++)
							   echo "<option value='".$i."'".(($_SESSION["uyear"]==$i)?'selected':'').">" . $i . "</option>";
							echo "</select>";
							echo "年";
							//--------------------------
							echo "<select class='selectpicker' data-width='auto' name='umonth' onChange='document.holiday.submit();'>";
							   for ($i=1;$i<=12;$i++)
								  echo "<option value='".$i."'".(($_SESSION["umonth"]==$i)?'selected':'').">" . $i . "</option>";
							echo "</select>";
							echo "月";
						    //判斷閏年-----------------------------
							$monthday = array("31","28","31","30","31","30","31","31","30","31","30","31");
							$bmd = $monthday[$_SESSION["umonth"]-1];	//開始那個月的日數
							if($_SESSION["umonth"]==2 && ($_SESSION["uyear"]+1911)%4==0)//閏年且為二月
								$bmd = $bmd+1;


							echo "<select class='selectpicker' data-width='auto' name='uday' onChange='document.holiday.submit();'>";
							   for ($i=0;$i<=$bmd;$i++)  //liru i=0
								  echo "<option value='".$i."'".(($_SESSION["uday"]==$i)?'selected':'').">" . $i . "</option>";
							echo "</select>";
							echo "日";
						?><font size='2' color='darkred'>(學校統一加班無提簽日期者，請以加班日期代替) </font>
						 <div>加班簽呈文號：<input type="text" name="reason" value="<?=$reason?>" size="25" maxlength="30" required><font size='2' color='darkred'> (學校統一加班無提簽文號者，請說明加班原因)</font></div>

						 <div style="font-size:15px">　<input type="radio" name="pay_type" value="1" checked style="border:none">6個月內補休 　　<input type="radio" name="pay_type" value="2"  style="border:none" onclick="javascript:alert('因本校無該項經費及預算，請勾選加班補休。');holiday.pay_type[0].checked='true';">請領加班費</div>
						</td>
					</tr>
					</thead>

					<thead>
					<tr>
						<td align="center">加班日期</td>
						<td align="center"  colspan="3"><font size="4">
							<?php
							echo "<select class='selectpicker' data-width='auto' name='byear' onChange='document.holiday.submit();'>";
							for ($i=$year-1;$i<=($year+1);$i++)
							   echo "<option value='".$i."'".(($_SESSION["byear"]==$i)?'selected':'').">" . $i . "</option>";
							echo "</select>";
							echo "年";
							//--------------------------
							echo "<select class='selectpicker' data-width='auto' name='bmonth' onChange='document.holiday.submit();'>";
							   for ($i=1;$i<=12;$i++)
								  echo "<option value='".$i."'".(($_SESSION["bmonth"]==$i)?'selected':'').">" . $i . "</option>";
							echo "</select>";
							echo "月";
						    //判斷閏年-----------------------------
							$monthday = array("31","28","31","30","31","30","31","31","30","31","30","31");
							$bmd = $monthday[$_SESSION["bmonth"]-1];	//開始那個月的日數
							if($_SESSION["bmonth"]==2 && ($_SESSION["byear"]+1911)%4==0)//閏年且為二月
								$bmd = $bmd+1;


							echo "<select class='selectpicker' data-width='auto' name='bday' onChange='document.holiday.submit();'>";
							   for ($i=0;$i<=$bmd;$i++)  //liru i=0
								  echo "<option value='".$i."'".(($_SESSION["bday"]==$i)?'selected':'').">" . $i . "</option>";
							echo "</select>";
							echo "日∼";
				            //     至 ^^^^^^^^^^^^^^^^^^
							echo "<select class='selectpicker' data-width='auto' name='eyear' onChange='document.holiday.submit();'>";
							for ($i=$year-1;$i<=($year+1);$i++)
							   echo "<option value='".$i."'".(($_SESSION["eyear"]==$i)?'selected':'').">" . $i . "</option>";
							echo "</select>";
							echo "年";
							//--------------------------
							echo "<select class='selectpicker' data-width='auto' name='emonth' onChange='document.holiday.submit();'>";
							   for ($i=1;$i<=12;$i++)
								  echo "<option value='".$i."'".(($_SESSION["emonth"]==$i)?'selected':'').">" . $i . "</option>";
							echo "</select>";
							echo "月";
						  //判斷閏年-----------------------------
							$monthday = array("31","28","31","30","31","30","31","31","30","31","30","31");
							$bmd = $monthday[$_SESSION["emonth"]-1];	//開始那個月的日數
							if($_SESSION["emonth"]==2 && ($_SESSION["eyear"]+1911)%4==0)//閏年且為二月
								$bmd = $bmd+1;

							echo "<select class='selectpicker' data-width='auto' name='eday' onChange='document.holiday.submit();'>";
							   for ($i=0;$i<=$bmd;$i++)  //liru i=0
								  echo "<option value='".$i."'".(($_SESSION["eday"]==$i)?'selected':'').">" . $i . "</option>";
							echo "</select>";
							echo "日<font color='darkred' size='2'>(加班過凌晨請跨日)</font>";
						?></td>
					</tr>
					</thead>

					<thead>
					<tr>
						<td align="center">加班刷卡時間</td>
						<td align="center"><font color='darkred' size='2'>開始加班刷卡資料
							<?php	
						     echo "<select class='selectpicker' data-width='auto' name='btime'>";
								 //抓取刷卡記錄
								 //------------
								 //尋找加班上班刷卡記錄
							 $sql=" select  substr(do_time,1,2)||':'||substr(do_time,3,2) do_time2,do_time
									 from   ps_card_data p
									 where  empl_no='$_SESSION[empl_no]' 
									 and    do_dat='$over_date'
									 order  by do_time";

							 $stmt=OCIPARSE($conn,$sql);
						 	 ociexecute($stmt,OCI_DEFAULT); 
							 echo "<option value=''>請選擇</option>"; 
							 echo "<option value='0800'>08:00</option>"; 
							 while (ocifetch($stmt)){        
								$do_time2=ociresult($stmt,"DO_TIME2");
								$do_time=ociresult($stmt,"DO_TIME");

								if ($do_time == $_SESSION["btime"])   
								  echo "<option value=".$do_time." selected>".$do_time2."</option>";   
								else
								  echo "<option value=".$do_time.">".$do_time2."</option>";  
							 } 
							 echo "<option value='1300'>13:00</option>"; 
				 		     echo "<option value='1700'>17:00</option>";					  
							 for ($i=1;$i<=30;$i++) {
							    if ($i < 10)
									 if ($cn > 0)
									    echo "<option value='160".$i."'>16:0".$i."</option>"; 
									 else{
										echo "<option value='170".$i."'>17:0".$i."</option>"; 
				                     } 
				                else
									 if ($cn > 0)
										 echo "<option value='16".$i."'>16:".$i."</option>"; 
									 else
							            echo "<option value='17".$i."'>17:".$i."</option>"; 
				             }

							 echo "</select>";
							?><br><font color='red' size='2'>1.例假日請選實際刷進時間。
							                             <br>2.上班日請選「得下班時間」
														 <br>例如：8:11上班則選17:11
						</td>

						<td align="left" colspan="2"><font color='darkred' size='2'>結束加班刷卡資料
							<?php	
							  echo "<select class='selectpicker' data-width='auto' name='etime'>";
								 //-------------------
								 //尋找加班上班刷卡記錄
							 $sql=" select  substr(do_time,1,2)||':'||substr(do_time,3,2) do_time2,do_time 
									 from   ps_card_data p
									 where  empl_no='$_SESSION[empl_no]' 
									 and    do_dat='$over_date2'
									 order  by do_time";

							 $stmt=OCIPARSE($conn,$sql);
						 	 ociexecute($stmt,OCI_DEFAULT); 
							 echo "<option value=''>請選擇</option>"; 
							 echo "<option value='1200'>12:00</option>"; 
							 echo "<option value='1700'>17:00</option>"; 
							 while (ocifetch($stmt)){        
								$do_time2=ociresult($stmt,"DO_TIME2");
								$do_time=ociresult($stmt,"DO_TIME");

								if ($do_time == $_SESSION["etime"])   
								  echo "<option value=".$do_time." selected>".$do_time2."</option>";   
								else
								  echo "<option value=".$do_time.">".$do_time2."</option>";  
							 } 
							 echo "</select>";
							?><br><font color='red' size='2'>
							1.請選實際刷退時間&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
							2.畢業典禮「等」不必刷退請選17:00</font>
					  </td>
					</tr>
					</thead>
					<!--<tr>
						<td  align="center"  colspan="4">
						總計加班&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						<font color="red"><?php echo $_SESSION["tot"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 小時</font>(分不採計)</td>
					</tr>-->
				</table>
			</div>
			<button class="btn btn-primary" type="submit"  name="check">送出計算</button>

		</form>
		</center>
		</div>
	</div>
		</div>
	    <!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php"); ?>
