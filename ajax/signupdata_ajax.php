<?php
	session_start();
  include ("../inc/check_authentication.php");
  include '../inc/connect.php';
  $school_id=$_SESSION['school_id'];
  $year=$_SESSION['year'];

  if($_POST['oper']==9)//載入datatables原始資料
  {
      $sql = "select a.name,a.id,decode(sex,'0','女','1','男','') sex,b.name dept_name,decode(substr(organize_id,4,1),'0','不分組','1','甲組','2','乙組','3','丙組','4','丁組','5','戊組','9','選考不分組','') group_name,
        a.orastatus_id,to_char(to_number(to_char(birthday,'yyyy')) - 1911,'099')||to_char(birthday,'/MM/DD') birthday,email,zip,address,zip_o,address_o,tel_h,tel_o,tel_m,decode(cripple_type,'0','否','1','上肢肢障','2','視障','3','其它功能障礙','') cripple_type,decode(prove_type,'1','一般學歷','2','同等學力','') prove_type,signup_sn,subject_id,lock_up,ac_school_name,ac_dept_name,to_char(to_number(to_char(ac_date,'yyyy')) - 1911,'099')||to_char(ac_date,'/MM/DD') ac_date 
          from signupdata a,department b 
          where a.school_id='$school_id' and a.year='$year' and a.year=b.year and a.school_id=b.school_id and a.dept_id=b.id
          order by a.orastatus_id,a.id";
     $data = $db -> query_array ($sql);
     $a['data']=""; //necessary for null data
     for ($i=0; $i < sizeof($data['ID']); $i++) {      
           $a['data'][] = array($data['FUNCTIONS'][$i],$data['NAME'][$i],$data['ID'][$i],$data['SEX'][$i],$data['DEPT_NAME'][$i],$data['GROUP_NAME'][$i],$data['ORASTATUS_ID'][$i],$data['BIRTHDAY'][$i],$data['EMAIL'][$i],$data['ZIP'][$i],$data['ADDRESS'][$i],$data['ZIP_O'][$i],$data['ADDRESS_O'][$i],$data['TEL_H'][$i],$data['TEL_O'][$i],$data['TEL_M'][$i],$data['CRIPPLE_TYPE'][$i],$data['PROVE_TYPE'][$i],$data['SIGNUP_SN'][$i],$data['SUBJECT_ID'][$i],$data['LOCK_UP'][$i],$data['AC_SCHOOL_NAME'][$i],$data['AC_DEPT_NAME'][$i],$data['AC_DATE'][$i]);       
     }
    echo json_encode($a);
    exit;
  }

  if ($_POST['oper']==8)//考生報名基本資料
  {
      switch ($_SESSION['school_id']){
        case 1://博班
            $url="http://aps.ncue.edu.tw/exampg_d/getdata.php?ID=".$_POST['id'] ."&sn=" .$_POST['signup_sn'] ; 
            break;
        case 2://碩博推
            $url="http://aps.ncue.edu.tw/exampg/getdata.php?ID=".$_POST['id'] ."&sn=" .$_POST['signup_sn'] ; 
            break;
        case 3://碩班
            $url="http://aps.ncue.edu.tw/exampg_m/getdata.php?ID=".$_POST['id'] ."&sn=" .$_POST['signup_sn'] ; 
            break;
        case 4://暑轉
            $url="http://aps.ncue.edu.tw/exampg_t/getdata.php?ID=".$_POST['id'] ."&sn=" .$_POST['signup_sn'] ; 
            break;
        case 5://寒轉
            $url="http://aps.ncue.edu.tw/exampg_t/getdata.php?ID=".$_POST['id'] ."&sn=" .$_POST['signup_sn'] ; 
            break;
        case 6://在職專班
            $url="http://aps.ncue.edu.tw/exampg_n/getdata.php?ID=".$_POST['id'] ."&sn=" .$_POST['signup_sn'] ; 
            break;
      }
       
       $handle = fopen($url,"r"); 
       while(!feof($handle)) 
       { 
          $finaldata.= fgets($handle,512);    //考生報名資料931104 add!     
       }            
       fclose($handle);  
        
       $finaldata = mb_convert_encoding($finaldata, "UTF-8", "BIG5");
       $data=array("content"=>$finaldata,"url"=>$url);
       echo json_encode($data);
       exit;
  }


  if ($_POST['oper']==2) //update
  {     
        $sql = "update signupdata set name = '".$_POST['name']."',lock_up = '".$_POST['lock_up']."',address = '".$_POST['address']."',address_o = '".$_POST['address_o']."' ,tel_h = '".$_POST['tel_h']."' ,tel_o = '".$_POST['tel_o']."' ,tel_m = '".$_POST['tel_m']."' ,zip = '".$_POST['zip']."' ,zip_o = '".$_POST['zip_o']."' ,ac_school_name = '".$_POST['ac_school_name']."' ,ac_dept_name = '".$_POST['ac_dept_name']."' ,email = '".$_POST['email']."' where id ='".$_POST['id']  ."' and signup_sn='".$_POST['signup_sn'] . "' and  school_id='$school_id' and year='$year'";   
        $data = $db -> query($sql);               
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message);  
        exit;    
  }
  
  if ($_POST['oper']==3)//delete
  {
        $sql = "select id from person where school_id='$school_id' and year='$year' and id='".$_POST['id'] ."'";
        $data = $db -> query_array($sql); 
        if(sizeof($data['ID'])>0){
          $message=array("error_code"=>sizeof($data['ID']),"error_message"=>"此考生已存在准考證資料，無法刪除!","sql"=>$sql);
          echo json_encode($message);  
          exit;
        }
        $sql = "delete from signupdata where id ='".$_POST['id']  ."' and signup_sn='".$_POST['signup_sn'] . "' and school_id='$school_id' and year='$year'";
        $data = $db -> query($sql);                   
        $message=array("error_code"=>$data['code'],"error_message"=>$data['message'],"sql"=>$sql);
        echo json_encode($message); 
        exit;     
  }
?>
