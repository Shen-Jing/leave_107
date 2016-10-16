<?php
	  session_start();
		include_once("../inc/connect.php");
    //include_once("/home/bob/common/common_func.php");


	 $user = trim($_POST['userid']);

   $pwd = trim($_POST['password']);

   //930127 add!!  ****隱碼....
   //$user=ereg_replace("'","XXX",$user);
   //$pwd=ereg_replace("'","XXX",$pwd);
  //  $host_ip = ($_SERVER[HTTP_X_FORWARDED_FOR]?$_SERVER[HTTP_X_FORWARDED_FOR]:$_SERVER["REMOTE_ADDR"]);

   //$result =  ldap_auth($user,$pwd,$host_ip) ;

   if ($pwd == "test123" )  {
		 		// 根據輸入的帳號查找此人
				$sql = "SELECT dept_full_name, empl_chn_name, empl_no, CRJB_DEPART, CRJB_TITLE
		          FROM  psfempl, psfcrjb, stfdept
				  WHERE empl_no = crjb_empl_no
				  AND crjb_depart = dept_no
				  AND crjb_seq = '1'
				  AND substr(empl_no, 1, 1) !='A'
				  AND crjb_quit_date IS NULL
				  AND psfempl.email = '" . $user . "@cc.ncue.edu.tw'";
        $data = $db -> query_array($sql);

				// 若有符合則給定各項$_SESSION值
        if (count($data['DEPT_FULL_NAME']) > 0) {
						$_SESSION['_ID'] = $_POST['userid'];
						$_SESSION['dept_name'] = $data['DEPT_FULL_NAME'];
						$_SESSION['empl_name'] = $data['EMPL_CHN_NAME'];
						$_SESSION['empl_no'] = $data['EMPL_NO'];
						$_SESSION['depart'] = $data['CRJB_DEPART'];
						$_SESSION['class_depart'] = $data['CRJB_DEPART'];
						$_SESSION['title'] = $data['CRJB_TITLE'];
						$_SESSION['logout'] = 0;

            $message = array("error_code"=>0,"error_message"=>0,"sql"=>"");
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
