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

   $Shaveclas="0";
   $Sabroa="0";
   $Ssunday="0";
   $Ssaturday="0";
   $Strip="0";
   $Ssure='1';  //取消表示已選好職務代理人
   $Sstr="";
   $Sacadm_return = "";
   $Snight_return = "";

//***************************************************
//非請假補填調補課申請單  class_add_2.php  來的              =>    目前未處理部分
//***************************************************
   $query2 = @$_POST['query2'];  //來自 class_apply.php

   $serialno = $_POST["serialno"];
   $Sthis_serialno =  $serialno;//未請假修改申請單
   if ($serialno != '')
   {
      @$_SESSION['check'] = 'cl';
      $sql = "select substr(pocard,1,3) byear,substr(pocard,4,2) bmonth,substr(pocard,6,2) bday, acadm_return,night_return
             from no_holidayform
             where pocard='$serialno'";
             // echo json_encode($sql);
             // exit;

      $data = $db -> query_array($sql);
      if (empty($data["message"]))
      {
         $Sbyear   = $data["BYEAR"][0];
         $Sbmonth = $data["BMONTH"][0];
         $Sbday   = $data["BDAY"][0];
         $Sacadm_return = $data["ACADM_RETURN"][0];  //是否被退 null:正常核准, 0:被退重送簽核完成, 1:被退中
         $Snight_return = $data["NIGHT_RETURN"][0];
      }
   }


   if($_POST['oper'] == "qry_record")
   {
      //***************************************************
      //填調補課申請單學年度及學期  class_year_2.php  來的     =>    目前處理部分
      //***************************************************

      $class_year   = $_POST['class_year']; //學年度
      $class_acadm = $_POST['class_acadm']; //學期
      // @$_SESSION['class_year'] = $class_year;
      // @$_SESSION['class_acadm'] = $class_acadm;

      // if (@$_POST['class_year'] == '')
      // {  //來自 store
      //    $class_year   = @$_GET['year']; //學年度
      //    $class_acadm = @$_GET['acadm']; //學期
      // }

      // if (@$_SESSION['class_year'] == '' || @$_SESSION['class_year'] != $class_year || @$_SESSION['class_acadm'] != $class_acadm)
      // {
      //    @$_SESSION['class_year'] = $class_year;
      //    @$_SESSION['class_acadm'] = $class_acadm;
      // }


      if ( $_POST['serialno'] == '')  //從未休假修改申請單
         $serialno= $year.$month.$day.$_SESSION["empl_no"];
      else
      {
         $name='';
         $serialno= $_POST['serialno']; //來自非請假調補課修改  class_add_2.php
         $Sthis_serialno = $_POST['serialno'];
         $query2 = @$_GET['query'];     //來自 class_apply.php
         $sql="select empl_chn_name from psfempl where empl_no='$_SESSION[empl_no]'";

         // echo json_encode($sql);
         // exit;

         $data = $db -> query_array($sql);
         $name = $data["EMPL_CHN_NAME"][0];
      }

      $SQLStr = "SELECT * FROM haveclass WHERE class_serialno='$serialno'";

      // echo json_encode($SQLStr);
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
      $scr_period = '04-06';

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

      $data["class_name"] = "00454000資工二";
      $data["scr_period"] = $scr_period;

      echo json_encode($data);
      exit;

      // $_SESSION["class_name"]= $cls_name;    //班別代碼+上課班別名稱
      // $_SESSION["scr_period"]= $scr_period;   //原上課節次等
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

   else if($_POST['oper'] == "edit_fill")
   {
      $serialno = $_POST['serialno'];
      $class_no = $_POST['class_no'];

      $sql = "SELECT * FROM haveclass WHERE CLASS_SERIALNO = '$serialno' AND CLASS_NO = '$class_no' ";

      $data = $db -> query_array($sql);

      echo json_encode($data);
      exit;

   }

   else if($_POST['oper'] == "new")
   {
      $class_year    =$_POST['class_year'];
      $class_acadm=$_POST['class_acadm'];

      if ($Sthis_serialno =='')
      {
           //新增時的序號產生方式
         $serialno = $year.$month.$day.$_SESSION["empl_no"];
         $Sthis_serialno = $serialno;
      }
      else
          $serialno = $Sthis_serialno ;  // 非請假調補課修改

      //-----------------------------------------------------------------
      $selcode  = $_POST["class_subject"];             //選課代碼
      $class_name = substr($_POST["class_name"],8);   //上課班別
      $class_code = substr($_POST['class_name'],0,8);   //上課班別代碼
      //$class_no = $_POST['class_no'];                      //補課科目之序號，系統編的當 key值

      $class_section2 = $_POST['class_section2_1']."-".$_POST['class_section2_2'];    //補課節次

      $class_room     = $_POST['class_room'];        //補課教室
      $class_memo     = $_POST['class_memo'];        //傋註

      $origin_time = $_POST['origin_time'];
      $change_time = $_POST['change_time'];

      $origin_time_sec = explode("/",$origin_time);
      $change_time_sec = explode("/",$change_time);

      $byear = (int)($origin_time_sec[0])-1911;
      $bmonth = (int)($origin_time_sec[1]);
      $bday = (int)($origin_time_sec[2]);

      $eyear = (int)($change_time_sec[0])-1911;
      $emonth = (int)($change_time_sec[1]);
      $eday = (int)($change_time_sec[2]);

      //-----------------------------------------------------------------
      // $userid
      // $selcode


      if ($class_section2 == '' || $class_room=='')
      {
         echo json_encode("請輸入補課節次及補課教室");
         exit;
      }
      else
      {  //資料齊全，可以處理
         //求本職單位，請假老師的單位
         $sql="SELECT crjb_depart FROM psfcrjb WHERE crjb_seq='1' AND crjb_empl_no='$_SESSION[empl_no]'";
         $data = $db -> query_array($sql);
         $depart = $data["CRJB_DEPART"][0];

         // echo json_encode($sql);
         // exit;

         //由資料庫抓取
         //目前無資料庫
         // $SQLStr2 = "SELECT b.sub_name , b.sub_id FROM dean.s32_smscourse@schlink.us.oracle.com a ,dean.s90_subject@schlink.us.oracle.com b ,dean.s32_teacher@schlink.us.oracle.com c ,dean.s90_class@schlink.us.oracle.com d ,dean.s10_employee@schlink.us.oracle.com e WHERE a.yms_year = c.yms_year AND a.yms_smester = c.yms_smester AND a.cls_id = c.cls_id AND a.sub_id = c.sub_id AND a.scr_dup = c.scr_dup AND a.scr_selcode = c.scr_selcode AND a.cls_id = d.cls_id AND a.sub_id = b.sub_id AND c.emp_id  = e.emp_id AND a.yms_year='$class_year' AND a.yms_smester='$class_acadm' AND c.emp_id = '$_SESSION[empl_no]' AND a.scr_selcode='$selcode'";

         // $res2 =  $db -> query_array($SQLStr2);
         // if(count($res2['SUB_NAME']) > 0)
         // {
         //    $class_subject    = $res2['SUB_NAME'];       //開課科目中文名稱
         //    $class_id         = $res2['SUB_ID'];         //開課科目代碼  haveclass 中的  class_id
         // }
         // echo json_encode($SQLStr2);
         // exit;
         if($selcode == "001")
         {
            $class_subject = "線性代數";
            $class_id = "CSIE2001";
         }
         else if($selcode == "002")
         {
            $class_subject = "電腦網路";
            $class_id = "CSIE2002";
         }
         else if($selcode == "003")
         {
            $class_subject = "資料結構";
            $class_id = "CSIE2003";
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
         // $class_section = substr($_POST["scr_period"],5,5);     //原上課節次，暫不比對教務處課表 */
         $class_section = $_POST["scr_period"];
         //求$class_week 上課時間是星期幾？有的上課時間有二個時段   100.11.03
         $sql="SELECT decode(calendar_week,'1','日','2','一','3','二','4','三','5','四', '6','五','7','六','') week FROM ps_calendar WHERE calendar_yymm='$class_yymm' AND calendar_dd='$bday'";


         $data =$db -> query_array($sql);

         if(count($data["WEEK"]) > 0)
            $class_week = $data["WEEK"][0];
         // echo json_encode($sql);
         // exit;
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
         $sql="SELECT decode(calendar_week,'1','日','2','一','3','二','4','三','5','四', '6','五','7','六','') week FROM ps_calendar WHERE calendar_yymm='$class_yymm2' AND calendar_dd='$eday'";
         $data2 = $db -> query_array($sql);

         if (count($data2["WEEK"]) > 0)
            $class_week2 = $data2["WEEK"][0];
         // echo json_encode($sql);
         // exit;

         //新增
         //求此假單補課單號碼到那裡
         $sql="SELECT MAX(CLASS_NO) class_no FROM haveclass WHERE class_serialno = $serialno ";
         $new_data = $db -> query_array($sql);

         $class_no = 0;
         if (count($new_data["CLASS_NO"]) > 0)
         {
               $class_no = $new_data["CLASS_NO"][0];
               $class_no++;
         }

         // 儲入資料庫
         $insert = "insert into haveclass(CLASS_SERIALNO,CLASS_NO,CLASS_DEPART,CLASS_NAME, CLASS_CODE, CLASS_SUBJECT,CLASS_ID,CLASS_DATE, CLASS_WEEK , CLASS_SECTION,  CLASS_DATE2, CLASS_WEEK2 ,CLASS_SECTION2 ,CLASS_ROOM,CLASS_MEMO,CLASS_SELCODE,CLASS_YEAR,CLASS_ACADM) values ('$serialno',$class_no,'$depart','$class_name','$class_code','$class_subject','$class_id','$class_date','$class_week','$class_section','$class_date2','$class_week2',  '$class_section2','$class_room','$class_memo', '$selcode','$class_year','$class_acadm')";

         // echo json_encode($insert);
         // exit;

         // echo json_encode("= ". $serialno . "= " . $class_no . "= " . $depart . "= " . $class_name . "= " . $class_code . "= " . $class_subject . "= " . $class_id . "= " . $class_date . "= " . $class_week . "= " . $class_section . "= " . $class_date2 . "= " . $class_week2 . "= " . $class_section2 . "= " . $class_room . "= " . $class_memo . "= " . $selcode . "= " . $class_year . "= " . $class_acadm);
         // exit;
         // echo json_encode($insert);
         // exit;
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

   else if($_POST["oper"] == "store")
   {
      $acadm_return = $Sacadm_return;//是否被退 null:正常核准, 0:被退重送簽核完成, 1:被退中
      $night_return = $Snight_return;

      $serialno = $Sthis_serialno;

      if ($acadm_return=='1'){//教務處重送
      $update ="update no_holidayform set ACADM_DATE =null, ACADM2_DATE=null, ACADM3_DATE =null, ACADM_SIGN=null,acadm_return='2' where pocard='$serialno'";

      $result = $db -> query_trsac($update);
      if(!$result)
      {
         echo json_encode($result["message"]);
         exit;
      }

   }

   if ($night_return=='1'){//進修部重送
      $update ="update no_holidayform set night_DATE =null, night2_DATE=null, night3_DATE =null, night_SIGN=null ,night_return='2' where pocard='$serialno'";

      // echo $update;

      $result = $db -> query_trsac($update);
      if(!$result)
      {
         echo json_encode($result["message"]);
         exit;
      }

    }

     //****************************************************************
     //調補課申請單填完離開(新增)
     // 無請假調補課申請單號碼，寫到另一個資料庫 no_holidayform 供簽核用
     //****************************************************************
      $sql =" select count(*)  count from no_holidayform where  pocard='$serialno'";
      //echo $sql;
      $data = $db -> query_array($sql);

      if (empty($data["message"]))
         $count = $data["COUNT"][0];

         if ($count ==0){
            $update =" insert into no_holidayform(pocard)  values('$serialno')";
            //echo "<br>".$update;
            $result = $db -> query_trsac($update);
            if(!$result)
            {
               echo json_encode($result["message"]);
               exit;
            }

            $mail_from     ="edoc@cc.ncue.edu.tw";
            $mail_headers  = "From: $mail_from\r\n";
            $mail_headers .= "Reply-To:lucy@cc.ncue.edu.tw\r\n";
            $mail_headers .= "X-Mailer: PHP\r\n"; // mailer
            $mail_headers .= "Return-Path: edoc@cc2.ncue.edu.tw\r\n";
            $mail_headers .= "Content-type: text/html; charset=big5\r\n";

            $mail_subject="非請假調補課申請";
            $mail_subject = "=?big5?B?".base64_encode($mail_subject)."?=";
            $mail_body=$update;
            @mail('bob@cc.ncue.edu.tw', $mail_subject, $mail_body, $mail_headers);
         }
    //-----------------------------------------------------------------
      $Shaveclass="0";
      $Sabroad="0";
      $Ssunday="0";
      $Ssaturday="0";
      $Strip="0";
      $Ssure='1';  //取消表示已選好職務代理人
      $str=$Sstr;

      $db -> end_trsac();

      echo json_encode("簽核完成!");
      exit;
   }

   else if($_POST['oper'] == "update")
   {
      $serialno = $_POST['serial_no'];
      $class_no = $_POST['class_no'];
      $selcode = $_POST["class_subject"];

      $edit_origin_time = $_POST['edit_origin_time'];
      $edit_change_time = $_POST['edit_change_time'];

      $edit_origin_time_sec = explode("/",$edit_origin_time);
      $edit_change_time_sec = explode("/",$edit_change_time);

      $byear = (int)($edit_origin_time_sec[0])-1911;
      $bmonth = (int)($edit_origin_time_sec[1]);
      $bday = (int)($edit_origin_time_sec[2]);

      $eyear = (int)($edit_change_time_sec[0])-1911;
      $emonth = (int)($edit_change_time_sec[1]);
      $eday = (int)($edit_change_time_sec[2]);

      $selcode  = $_POST["class_subject"];             //選課代碼
      $class_name = substr($_POST["class_name"],8);   //上課班別
      $class_code = substr($_POST['class_name'],0,8);   //上課班別代碼

      if($selcode == "001")
      {
         $class_subject = "線性代數";
         $class_id = "CSIE2001";
      }
      else if($selcode == "002")
      {
         $class_subject = "電腦網路";
         $class_id = "CSIE2002";
      }
      else if($selcode == "003")
      {
         $class_subject = "資料結構";
         $class_id = "CSIE2003";
      }


      $sql="SELECT crjb_depart FROM psfcrjb WHERE crjb_seq='1' AND crjb_empl_no='$_SESSION[empl_no]'";
      $data = $db -> query_array($sql);
      $depart = $data["CRJB_DEPART"][0];

      if ($Sthis_serialno =='')
      {
           //新增時的序號產生方式
         $this_serialno = $year.$month.$day.$_SESSION["empl_no"];
         $Sthis_serialno = $this_serialno;
      }
      else
          $this_serialno = $Sthis_serialno ;

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
      $class_section = substr($_POST["scr_period"],5,5);     //原上課節次，暫不比對教務處課表 */

      //求$class_week 上課時間是星期幾？有的上課時間有二個時段   100.11.03
      $sql="SELECT decode(calendar_week,'1','日','2','一','3','二','4','三','5','四', '6','五','7','六','') week FROM ps_calendar WHERE calendar_yymm='$class_yymm' AND calendar_dd='$bday'";

      $data =$db -> query_array($sql);

      if(count($data["WEEK"]) > 0)
         $class_week = $data["WEEK"][0];
      // echo json_encode($sql);
      // exit;
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
      $sql="SELECT decode(calendar_week,'1','日','2','一','3','二','4','三','5','四', '6','五','7','六','') week FROM ps_calendar WHERE calendar_yymm='$class_yymm2' AND calendar_dd='$eday'";
      $data2 = $db -> query_array($sql);

      if (count($data2["WEEK"]) > 0)
         $class_week2 = $data2["WEEK"][0];

      $class_section2 = $_POST['class_section2_1']."-".$_POST['class_section2_2'];

      $update = "UPDATE haveclass SET CLASS_SERIALNO ='$this_serialno', CLASS_NO = '$class_no', CLASS_DEPART = '$depart', CLASS_NAME = '$class_name', CLASS_CODE = '$class_code', CLASS_SUBJECT = '$class_subject', CLASS_ID = '$class_id', CLASS_DATE = '$class_date', CLASS_WEEK = '$class_week' , CLASS_SECTION = '$class_section', CLASS_DATE2 = '$class_date2', CLASS_WEEK2 ='$class_week2' ,CLASS_SECTION2 = '$class_section2', CLASS_ROOM = '$_POST[class_room]', CLASS_MEMO = '$_POST[class_memo]', CLASS_SELCODE = '$selcode', CLASS_YEAR = '$_POST[class_year]', CLASS_ACADM = '$_POST[class_acadm]' WHERE CLASS_SERIALNO = '$serialno' AND CLASS_NO = '$class_no' ";
      // echo json_encode($update);
      // exit;

      $result = $db -> query_trsac($update);
      // echo json_encode($result);
      // exit;
      if($result)//失敗需rollback
      {
         if( !empty($result["message"]) )
         {
            // echo json_encode($update);
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

   }

?>