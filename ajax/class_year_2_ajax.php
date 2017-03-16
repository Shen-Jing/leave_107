<?php
//二個來源:老師請假且有課時由 process.php來的，另為補填調補課申請單時。


//***************************************************
//老師請假且有課時由 process.php來的
//***************************************************
   session_start();
   include '../inc/connect.php';
   $today = getdate();
   $year  = $today["year"] - 1911;
   $month = $today["mon"];
   $day     = $today["mday"];

   if (strlen($month)<2)
     $month ='0'.$month;

   if (strlen($day)<2)
      $day = '0'.$day;

   $userid = $_SESSION['empl_no'];

   //**************************************************
   //來自 來自 class_query_2.php 之修改功能，按了修改鈕
   //**************************************************
   $class_no  ='';  $update='';
   $serialno   = @$_POST["serialno"];
   $class_no  = @$_POST["class_no"];
   $update    = @$_POST["update"];

   //-----------------------------------------------------------------------------------------------------
   //*session_register('class_subject'); //科目名稱
   (!@$_POST["class_subject"])  ? $class_subject = '' : $class_subject = @$_POST["class_subject"];
   if (@$_SESSION["class_subject"] == '' || (@$_SESSION['class_subject'] !='' and  @$_POST["class_subject"] !=''))
      @$_SESSION["class_subject"] = $class_subject;
   //-----------------------------------------------------------------------------------------------------
   //*session_register('cyear'); session_register('cmonth');  session_register('cday');  //原上課時間

   (!@$_POST["cyear"])    ? $cyear=$year        : $cyear   =@$_POST["cyear"];
   (!@$_POST["cmonth"]) ? $cmonth=$month  : $cmonth=@$_POST["cmonth"];
   (!@$_POST["cday"])     ? $cday=$day          : $cday    =@$_POST["cday"]; //$cday-->temp

   if (@$_SESSION["cyear"]==""  || (@$_SESSION['cyear'] != ""  and  @$_POST["cyear"] != ""))
      @$_SESSION["cyear"]=$cyear;
   if (@$_SESSION["cmonth"]==""  || (@$_SESSION['cmonth'] != ""  and  @$_POST["cmonth"] != ""))
      @$_SESSION["cmonth"]=$cmonth;
   if (@$_SESSION["cday"]==""  || (@$_SESSION['cday'] != ""  and  @$_POST["cday"] != ""))
      @$_SESSION["cday"]=$cday;
   //-----------------------------------------------------------------------------------------------------
   //*session_register('dyear');  session_register('dmonth');  session_register('dday');  //補課時間
   (!@$_POST["dyear"])    ? $dyear =$year       : $dyear   =@$_POST["dyear"];
   (!@$_POST["dmonth"]) ? $dmonth=$month  : $dmonth=@$_POST["dmonth"];
   (!@$_POST["dday"])     ? $dday  =$day        : $dday    =@$_POST["dday"];

   if (@$_SESSION["dyear"]==""  || (@$_SESSION['dyear'] != ""  and  @$_POST["dyear"] != ""))
      @$_SESSION["dyear"]=$dyear;
   if (@$_SESSION["dmonth"]==""  || (@$_SESSION['dmonth'] != ""  and  @$_POST["dmonth"] != ""))
      @$_SESSION["dmonth"]=$dmonth;
   if (@$_SESSION["dday"]==""  || (@$_SESSION['dday'] != ""  and  @$_POST["dday"] != ""))
      @$_SESSION["dday"]=$dday;
   //-----------------------------------------------------------------------------------------------------
   //*session_register('class_section2');  //補課節次
   (!@$_POST["class_section2"])  ? $class_section2=''  : $class_section2=@$_POST["class_section2"];
   if (@$_SESSION["class_section2"]=='' || (@$_SESSION['class_section2'] !='' and  @$_POST["class_section2"] !=''))
      @$_SESSION["class_section2"]=$class_section2;
   //-----------------------------------------------------------------------------------------------------
   //*session_register('class_room');  //補課教室
   (!@$_POST["class_room"])  ? $class_room=''  : $class_room=@$_POST["class_room"];
   if (@$_SESSION["class_room"]=='' || (@$_SESSION['class_room'] !='' and  @$_POST["class_room"] !=''))
      @$_SESSION["class_room"]=$class_room;
   //-----------------------------------------------------------------------------------------------------
   //*session_register('class_memo');  //備註
   //*session_register('memo_f');//控制備註清空
   if ($update=='u')
   {
      if (@$_POST["class_memo"]==''  and  @$_SESSION['class_memo'] =='')
      {//第一次進來
         $memo=$temp_memo;
         @$_SESSION["memo_f"]='1';
      }
      else
         $memo = @$_POST["class_memo"];

      if (@$_SESSION["memo_f"]=="1")//只要有異動，不管是否清空
         @$_SESSION["class_memo"]=$memo;
   }
   else
   {
      (!@$_POST["class_memo"])  ? $class_memo=''  : $class_memo=@$_POST["class_memo"];
      if (@$_SESSION["class_memo"]=='' || (@$_SESSION['class_memo'] !='' and  @$_POST["class_memo"] !=''))
         @$_SESSION["class_memo"]=$class_memo;
   }

   //-----------------------------------------------------------------------------------------------------

//***************************************************
//非請假補填調補課申請單  class_add_2.php  來的              =>    目前未處理部分
//***************************************************
   $query2 = @$_POST['query2'];  //來自 class_apply.php

   $serialno = @$_GET[serialno];
   @$_SESSION['this_serialno'] =  $serialno;//未請假修改申請單
   if ($serialno != '')
   {
      @$_SESSION['check'] = 'cl';
      $sql = "select substr(pocard,1,3) byear,substr(pocard,4,2) bmonth,substr(pocard,6,2) bday, acadm_return,night_return
				 from no_holidayform
				 where pocard='$serialno'";

      $stmt = ociparse($conn,$sql);
      ociexecute($stmt,OCI_DEFAULT);
      if (OCIFETCH($stmt))
      {
		   @$_SESSION['byear']   = ociresult($stmt,"BYEAR");
         @$_SESSION['bmonth'] = ociresult($stmt,"BMONTH");
		   @$_SESSION['bday']    = ociresult($stmt,"BDAY");
		   @$_SESSION['acadm_return']  = ociresult($stmt,"ACADM_RETURN");  //是否被退 null:正常核准, 0:被退重送簽核完成, 1:被退中
		   @$_SESSION['night_return']    = ociresult($stmt,"NIGHT_RETURN");
	   }
   }




   if($_POST['oper'] == "qry_year")
   {

     echo json_encode($year);
     exit;

   }

   if($_POST['oper'] == "qry_record")
   {
      //***************************************************
      //填調補課申請單學年度及學期  class_year_2.php  來的     =>    目前處理部分
      //***************************************************

      $class_year   = $_POST['class_year']; //學年度
      $class_acadm = $_POST['class_acadm']; //學期
      @$_SESSION['class_year'] = $class_year;
      @$_SESSION['class_acadm'] = $class_acadm;

      if (@$_POST['class_year'] == '')
      {  //來自 store
         $class_year   = @$_GET['year']; //學年度
         $class_acadm = @$_GET['acadm']; //學期
      }

      if (@$_SESSION['class_year'] == '' || @$_SESSION['class_year'] != $class_year || @$_SESSION['class_acadm'] != $class_acadm)
      {
         @$_SESSION['class_year'] = $class_year;
         @$_SESSION['class_acadm'] = $class_acadm;
      }


      if ( @$_SESSION['this_serialno'] == '')  //從未休假修改申請單
            $serialno= $year.$month.$day.$_SESSION["empl_no"];
      else
            $serialno= @$_SESSION['this_serialno']; //來自非請假調補課修改  class_add_2.php


      $name='';
      if (@$_GET['serialno'] !='')
      {          //來自 class_apply.php
         @$_SESSION['this_serialno'] = @$_GET['serialno'];
         $serialno= @$_GET['serialno'];
         $query2 = @$_GET['query'];     //來自 class_apply.php
         $sql="select   empl_chn_name
               from  psfempl
               where  empl_no=".$_SESSION["empl_no"];
         $data = $db -> query_array($sql);
         $name = $data["EMPL_CHN_NAME"][0];
      }

      $SQLStr = "SELECT * FROM haveclass WHERE class_serialno='$serialno'";

      // echo json_encode($serialno);
      // exit;

      // echo json_encode($query2 . " " . $query2_ck);
      // exit;

      $rec_query = $db -> query_array($SQLStr);

      // echo json_encode($rec_query);
      // exit;

      $rec_data = array();
      for($i = 0 ; $i < count($rec_query["CLASS_NAME"]) ; $i++)
      {
         $class_name = $rec_query['CLASS_NAME'][$i];
         $class_subject = $rec_query['CLASS_SUBJECT'][$i];
         $class_date = $rec_query['CLASS_DATE'][$i];
         $class_date2 = $rec_query['CLASS_DATE2'][$i];
         $class_room = $rec_query['CLASS_ROOM'][$i];
         $class_section = $rec_query['CLASS_SECTION2'][$i];
         $class_no = $rec_query['CLASS_NO'][$i];

         $rec_data["CLASS_NAME"][$i] = $class_name;
         $rec_data["CLASS_SUBJECT"][$i] = $class_subject;
         $rec_data["CLASS_DATE"][$i] = $class_date;
         $rec_data["CLASS_DATE2"][$i] = $class_date2;
         $rec_data["CLASS_ROOM"][$i] = $class_room;
         $rec_data["CLASS_SECTION2"][$i] = $class_section;
         $rec_data['CLASS_NO'][$i] = $class_no;
         if ($query2 !=2)
         {
            $rec_data["SERIALNO"][$i] = $serialno;
         }
      }

      echo json_encode($rec_data);
      exit;

   }

   if($_POST['oper'] == "qry_subject")
   {
      $class_year   = $_POST['class_year']; //學年度
      $class_acadm = $_POST['class_acadm']; //學期

      // $SQLStr2 = "select a.scr_selcode, b.sub_name , b.sub_id from      =>    未有TABLE，暫以假資料代替
      //             dean.s32_smscourse@schlink.us.oracle.com a ,
      //             dean.s90_subject@schlink.us.oracle.com   b ,
      //             dean.s32_teacher@schlink.us.oracle.com   c ,
      //             dean.s90_class@schlink.us.oracle.com     d ,
      //             dean.s10_employee@schlink.us.oracle.com  e
      //             where  a.yms_year = c.yms_year
      //             and    a.yms_smester = c.yms_smester
      //             and    a.cls_id = c.cls_id
      //             and    a.sub_id = c.sub_id
      //             and    a.scr_dup = c.scr_dup
      //             and    a.scr_selcode = c.scr_selcode
      //             and    a.cls_id = d.cls_id
      //             and    a.sub_id = b.sub_id
      //             and    substr(a.cls_id,1,1)='N'
      //             and    c.emp_id  = e.emp_id
      //             and    a.yms_year='$class_year'
      //             and    a.yms_smester='$class_acadm'
      //             and    c.emp_id = '$_SESSION[empl_no]' ";

      //$subject = $db -> query_array($SQLStr2);

      $subject_data = array();
      // for($i = 0 ; $i < count($subject['SCR_SELCODE']) ; $i++)
      // {
      //    $subject_data["SCR_SELCODE"][$i] = $subject['SCR_SELCODE'][$i];    //選課代碼
      //    $subject_data["SUB_NAME"][$i] = $subject['SUB_NAME'][$i];       //開課科目中文名稱
      //    $subject_data["SUB_ID"][$i] = $subject['SUB_ID'][$i];         //開課科目代碼  haveclass 中的  class_id
      // }


      $subject_data["SCR_SELCODE"][0] = '001';    //選課代碼
      $subject_data["SUB_NAME"][0] = '線性代數';       //開課科目中文名稱
      $subject_data["SUB_ID"][0] = '5201';         //開課科目代碼  haveclass 中的  class_id

      $subject_data["SCR_SELCODE"][1] = '002';
      $subject_data["SUB_NAME"][1] = '電腦網路';
      $subject_data["SUB_ID"][1] = '5202';

      $subject_data["SCR_SELCODE"][2] = '003';
      $subject_data["SUB_NAME"][2] = '資料結構';
      $subject_data["SUB_ID"][2] = '5203';

      echo json_encode($subject_data);
      exit;

   }

   if($_POST['oper'] == "qry_class_id")
   {
      $cls_name = '-';
      $scr_period = '-';

      // $SQLStr2 = "select  d.cls_id||d.cls_name cls_name , a.SCR_PERIOD from      =>    未有TABLE，暫以假資料代替
      //             dean.s32_smscourse@schlink.us.oracle.com a ,
      //             dean.s90_subject@schlink.us.oracle.com   b ,
      //             dean.s32_teacher@schlink.us.oracle.com   c ,
      //             dean.s90_class@schlink.us.oracle.com     d ,
      //             dean.s10_employee@schlink.us.oracle.com  e
      //             where  a.yms_year = c.yms_year
      //             and    a.yms_smester = c.yms_smester
      //             and    a.cls_id = c.cls_id
      //             and    a.sub_id = c.sub_id
      //             and    a.scr_dup = c.scr_dup
      //             and    a.scr_selcode = c.scr_selcode
      //             and    a.cls_id = d.cls_id
      //             and    a.sub_id = b.sub_id
      //             and    c.emp_id  = e.emp_id
      //             and    a.yms_year='$class_year'
      //             and    a.yms_smester='$class_acadm'
      //             and    c.emp_id = '$userid'
      //             and    a.scr_selcode='$_SESSION[class_subject]'";

      //$class = $db -> query_array($SQLStr2);

      echo json_encode("00454000資工二");
      exit;

      // $_SESSION["class_name"]= $cls_name;    //班別代碼+上課班別名稱
      // $_SESSION["scr_period"]= $scr_period;   //原上課節次等
   }

   if($_POST['oper'] == 'qry_dates')
   {
      $date = array();
      $date["cyear"] = $_SESSION['cyear'] ;
      $date["cmonth"] = $_SESSION['cmonth'] ;
      $date["cday"] = $_SESSION['cday'] ;

      $date["dyear"] = $_SESSION['dyear'] ;
      $date["dmonth"] = $_SESSION['dmonth'] ;
      $date["dday"] = $_SESSION['dday'] ;

      echo json_encode($date);
      exit;
   }

   if($_POST['oper'] == "del")
   {
      $serialno  = $_POST["serialno"];
      $class_no = $_POST["class_no"];

      $sql="delete from haveclass
           where class_serialno='$serialno'
           and   class_no='$class_no'";

      $value = $db -> query($sql);

      if ( !empty($value["message"])  )
         $data = "資料刪除有問題。";
      else
         $data = "資料刪除完畢。";

      echo json_encode($data);
      exit;

   }

   if($_POST['oper'] == "edit")
   {

   }

   if($_POST['oper'] == "new")
   {
      $userid           =$_SESSION["empl_no"];
      $class_year    =$_POST['class_year'];
      $class_acadm=$_POST['class_acadm'];

      if ($_SESSION['this_serialno'] =='')
      {
           //新增時的序號產生方式
         $serialno = $year.$month.$day.$_SESSION["empl_no"];
         $_SESSION['this_serialno']=$serialno;
      }
      else
          $serialno = $_SESSION['this_serialno'] ;  // 非請假調補課修改

      //-----------------------------------------------------------------
      $selcode  = $_POST["class_subject"];             //選課代碼
      $class_name = substr($_POST["class_name"],8);   //上課班別
      $class_code = substr($_POST['class_name'],0,8);   //上課班別代碼
      //$class_no = $_POST['class_no'];                      //補課科目之序號，系統編的當 key值

      $class_section2 = $_POST['class_section2'];    //補課節次
      $class_room     = $_POST['class_room'];        //補課教室
      $class_memo     = $_POST['class_memo'];        //傋註

      $byear  = $_POST['cyear'];
      $bmonth = $_POST['cmonth'];
      $bday   = $_POST['cday'];

      $eyear  = $_POST['dyear'];
      $emonth = $_POST['dmonth'];
      $eday   = $_POST['dday'];

      //-----------------------------------------------------------------


      if ($class_section2 == '' || $class_room=='')
      {
         echo json_encode("請輸入補課節次及補課教室");
         exit;
      }
      else
      {  //資料齊全，可以處理
         //求本職單位，請假老師的單位
         $sql="select crjb_depart from psfcrjb
              where crjb_seq='1'
              and   crjb_empl_no='$_SESSION[empl_no]'";
         $data = $db -> query_array($sql);
         $depart = $data["CRJB_DEPART"][0];


         //由資料庫抓取
           $SQLStr2="select  b.sub_name , b.sub_id
                from    dean.s32_smscourse@schlink.us.oracle.com a ,
                      dean.s90_subject@schlink.us.oracle.com   b ,
                      dean.s32_teacher@schlink.us.oracle.com   c ,
                      dean.s90_class@schlink.us.oracle.com     d ,
                      dean.s10_employee@schlink.us.oracle.com  e
               where  a.yms_year = c.yms_year
               and    a.yms_smester = c.yms_smester
               and    a.cls_id = c.cls_id
               and    a.sub_id = c.sub_id
               and    a.scr_dup = c.scr_dup
               and    a.scr_selcode = c.scr_selcode
               and    a.cls_id = d.cls_id
               and    a.sub_id = b.sub_id
               and    c.emp_id  = e.emp_id
               and    a.yms_year='$class_year'
               and    a.yms_smester='$class_acadm'
               and    c.emp_id = '$_SESSION[empl_no]'
               and    a.scr_selcode='$selcode'";

            $res2 =  $db -> query_array($SQLStr2);
            if(count($res2['SUB_NAME']) > 0)
            {
               $class_subject    = $res2['SUB_NAME'];       //開課科目中文名稱
               $class_id         = $res2['SUB_ID'];         //開課科目代碼  haveclass 中的  class_id
            }

         //--------------------------------------------------------------------
         if ($byear < 100)
            $class_date='0'.$byear;
         else
            $class_date=$byear;

         if ($bmonth < 10)
            $class_date = $class_date.'0'.$bmonth;
         else
            $class_date = $class_date.$bmonth;

         $class_yymm = $class_date;

         if ($bday < 10)
            $class_date = $class_date.'0'.$bday;
         else
            $class_date = $class_date.$bday;

         /*$class_week    = substr($_SESSION["scr_period"],1,2);   //原上課時間是星期幾？*/
         $class_section = substr($_SESSION["scr_period"],5,5);     //原上課節次，暫不比對教務處課表 */

         //求$class_week 上課時間是星期幾？有的上課時間有二個時段   100.11.03
         $sql="select decode(calendar_week,'1','日','2','一','3','二','4','三','5','四', '6','五','7','六','')  week
              from   ps_calendar
              where  calendar_yymm='$class_yymm' and   calendar_dd='$bday'";

         $data =$db -> query_array($sql);

         if(count($data["WEEK"]) > 0)
            $class_week = $data["WEEK"][0];

         //-------------------------------------------------------------------------
         if ($eyear < 100)
            $class_date2 = '0'.$eyear;
         else
            $class_date2 = $eyear;

         if ($emonth < 10)
            $class_date2 = $class_date2.'0'.$emonth;
         else
            $class_date2 = $class_date2.$emonth;

         $class_yymm2 = $class_date2;

         if ($eday < 10)
            $class_date2 = $class_date2.'0'.$eday;
         else
            $class_date2 = $class_date2.$eday;

         //求$class_week2 補課時間是星期幾？
         $sql="select decode(calendar_week,'1','日','2','一','3','二','4','三','5','四', '6','五','7','六','')  week
              from   ps_calendar
              where  calendar_yymm='$class_yymm2' and   calendar_dd='$eday'";
         $data2 = $db -> query_array($sql);

         if (count($data2["WEEK"]) > 0)
            $class_week2 = $data2["WEEK"][0];

         //新增
         //求此假單補課單號碼到那裡
         $sql="select MAX(CLASS_NO) class_no from haveclass where class_serialno=$serialno ";
         $new_data = $db -> query_array($sql);

         $class_no = 0;
         if (count($new_data["CLASS_NO"]) > 0)
         {
               $class_no = $new_data["CLASS_NO"][0];
               $class_no++;
         }

         // 儲入資料庫
         $insert =
         "insert into haveclass(CLASS_SERIALNO,CLASS_NO,CLASS_DEPART,CLASS_NAME, CLASS_CODE, CLASS_SUBJECT,CLASS_ID,CLASS_DATE, CLASS_WEEK , CLASS_SECTION,  CLASS_DATE2, CLASS_WEEK2 ,CLASS_SECTION2 ,CLASS_ROOM,CLASS_MEMO,CLASS_SELCODE,CLASS_YEAR,CLASS_ACADM)
         values ('$serialno',$class_no,'$depart','$class_name','$class_code','$class_subject','$class_id',
         '$class_date','$class_week','$class_section','$class_date2','$class_week2',  '$class_section2','$class_room','$class_memo', '$selcode','$class_year','$class_acadm')";

         if($result = $db -> query_trsac($insert))//失敗需rollback
         {
            if( !empty($result["message"]) )
            {
               echo json_encode("更新資料庫失敗，請聯絡程式設計師 1523");
               exit;
            }
            else
            {
               // unset($_SESSION['class_section2']);
               // unset($_SESSION['class_room']);
               $db -> end_trsac();
               echo json_encode("更新資料成功!");
               exit;
            }
         }
      } // 資料齊全 else
   }
?>