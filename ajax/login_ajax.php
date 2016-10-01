<?php
	  session_start();
    include_once("../inc/connect.php");
    //include_once("/home/bob/common/common_func.php");
  
   //***取得AG的header
   $headers = apache_request_headers();      
   $user_id = $headers['user_id'] ;  //AM header(帳號)

   if (strlen($user_id)>=2)$user = trim($user_id ) ; //header的user_id
   else $user=trim($_POST['userid']);

   $pwd  = trim($_POST['password']) ;
              
   //930127 add!!  ****隱碼....                    
   //$user=ereg_replace("'","XXX",$user); 
   //$pwd=ereg_replace("'","XXX",$pwd);  
   $host_ip = ($_SERVER[HTTP_X_FORWARDED_FOR]?$_SERVER[HTTP_X_FORWARDED_FOR]:$_SERVER["REMOTE_ADDR"]);
   
   //$result =  ldap_auth($user,$pwd,$host_ip) ;

   if ($pwd == "csie" )  {      
        $sql = "SELECT * FROM SYSGRPUSER where sysid='EXAMPG' and  userid='".$_POST['userid'] . "'";
        $data = $db -> query_array($sql);
        
        if (sizeof($data['USERID']) > 0) {           
            $_SESSION['_ID'] = $_POST['userid'];
            $_SESSION['_NAME'] = $result[0][fullname][0] ;     
            $_SESSION['logout'] = 0;
            $message=array("error_code"=>0,"error_message"=>0,"sql"=>"");
            echo json_encode($message);  
            exit;
        }
        else {
            $message=array("error_code"=>2,"error_message"=>"您未被授權!!","sql"=>"");
            echo json_encode($message);
            exit;            
        }
    }
    else {
        $message=array("error_code"=>3,"error_message"=>"帳號密碼輸入錯誤!!","sql"=>"");
        echo json_encode($message);
        exit;       
    }

  
	
	
?>
