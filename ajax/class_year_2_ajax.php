<?php
//二個來源:老師請假且有課時由 process.php來的，另為補填調補課申請單時。


//***************************************************
//老師請假且有課時由 process.php來的
//***************************************************
   session_start();
//***************************************************
//非請假補填調補課申請單  class_add.php  來的
//***************************************************
   include '../inc/connect.php';
   $today = getdate();
   $year  = $today["year"] - 1911;
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


   //***************************************************
   //填調補課申請單學年度及學期  class_year_2.php  來的
   //***************************************************

   $class_year   = @$_POST['class_year']; //學年度
   $class_acadm = @$_POST['class_acadm']; //學期
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


   if($_POST['oper'] == "qry_year")
   {

     echo json_encode($year);
     exit;

   }

   if($_POST['oper'] == "query")
   {
      $month = $today["mon"];
      $day   = $today["mday"];

      if (strlen($month)<2)
        $month ='0'.$month;

      if (strlen($day)<2)
         $day = '0'.$day;

      if ( @$_SESSION['this_serialno']=='')  //從未休假修改申請單
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
      $SQLStr = "select * from haveclass
                where class_serialno='$serialno'";
      $query2_ck = 0;

      if (@$query2 !=2)   //來自 class_apply.php
         $query2_ck = 1;

      $rec_query = $db -> query_array($SQLStr);
      $rec_data = array();
      for($i = 0 ; $i < count($rec_query) ; $i++)
      {
         $class_name = $rec_query['CLASS_NAME'];
         $class_subject = $rec_query['CLASS_SUBJECT'];
         $class_date = $rec_query['CLASS_DATE'];
         $class_date2 = $rec_query['CLASS_DATE2'];
         $class_room = $rec_query['CLASS_ROOM'];
         $class_section = $rec_query['CLASS_SECTION2'];
         $class_no = $rec_query['CLASS_NO'];

         $rec_data["CLASS_NAME"][$i] = $class_name;
         $rec_data["CLASS_SUBJECT"][$i] = $class_subject;
         $rec_data["CLASS_DATE"][$i] = $class_date;
         $rec_data["CLASS_DATE2"][$i] = $class_date2;
         $rec_data["CLASS_ROOM"][$i] = $class_room;
         $rec_data["CLASS_SECTION2"][$i] = $class_section;
         if (@$query2 !=2)
         {
            $rec_data["EDIT"][$i] = $class_no;
            $rec_data["SERIALNO"][$i] = $serialno;
         }
      }

      echo json_encode($rec_data);
      exit;

   }

   if($_POST['oper'] == "new")
   {
      $userid = $_SESSION['empl_no'];
      $update_str='狀態:新增資料';

      //************************************
      //從 class_index_2 來的 補請調補課
      //************************************
      $class_year   =$_SESSION['class_year'];
      $class_acadm=$_SESSION['class_acadm'];

      //**************************************************
      //來自 來自 class_query.php 之修改功能，按了修改鈕
      //*************************************************
      $class_no  ='';  $update='';
      $serialno   = @$_GET["serialno"];
      $class_no  = @$_GET["class_no"];
      $update    = @$_GET["update"];

      @$_SESSION["class_no"]       = $class_no;
      @$_SESSION["class_update"] = $update;

      if ($serialno<>'')
         @$_SESSION['this_serialno']   = $serialno;
      /* else  保留來自 class_add_2.php 的  $_SESSION['this_serialno']  值(於 class_year_2.php 被存入)，不作存入  */

      if ($update=='u')
      {
         $update_str='狀態:修改資料';
         $SQLStr ="select * from haveclass
                 where class_serialno='$serialno'
                 and   class_no='$class_no'";

         $row = $db ->query_array($SQLStr);
         if (count($count))
         {

            $class_date    = $row['CLASS_DATE'];
            $class_date2   = $row['CLASS_DATE2'];

            @$_SESSION["class_subject"]  = $row['CLASS_SELCODE'];
            @$_SESSION["class_room"]     = $row['CLASS_ROOM'];
            @$_SESSION["class_section2"] = $row['CLASS_SECTION2'];
            $temp_memo                  = $row['CLASS_MEMO'];

            @$_SESSION["cyear"]   = substr($class_date,0,3);
            @$_SESSION["cmonth"] = substr($class_date,3,2);
            @$_SESSION["cday"]    = substr($class_date,5,2);

            @$_SESSION["dyear"]   = substr($class_date2,0,3);
            @$_SESSION["dmonth"] = substr($class_date2,3,2);
            @$_SESSION["dday"]    = substr($class_date2,5,2);

          }
      }
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

        //按本班資料儲存
      if($_POST["send"])
      {
        echo "<script>location.href='store_2.php'</script>";
        exit;
      }

      $cls_name='-';$scr_period='-';
      $SQLStr2="select  d.cls_id||d.cls_name cls_name , a.SCR_PERIOD
                  from  dean.s32_smscourse@schlink.us.oracle.com a ,
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
                  and    c.emp_id = '$userid'
                  and    a.scr_selcode='$_SESSION[class_subject]'";

      $class_sub = $db -> query_array($SQLStr2);
      if(count($class_sub))
      {
        $cls_name     = $class_sub['CLS_NAME'];
        $scr_period   = $class_sub['SCR_PERIOD'];
      }
      $data_new = array();
      $data_new['CLS_NAME'] = $cls_name;
      $data_new['SCR_PERIOD'] = $scr_period;
      @$_SESSION["class_name"]= $cls_name;    //班別代碼+上課班別名稱
      @$_SESSION["scr_period"]= $scr_period;   //原上課節次等

      echo json_encode($data_new);
      exit;

   }

?>