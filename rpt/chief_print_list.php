<?php
  //include_once("../inc/check.php");
  session_start();
  include_once("../inc/connect.php");
  // 舊頁面似乎沒使用到裏頭的ntos func
  // include("date_func.php");
  define('FPDF_FONTPATH',"./font");
  require('chinese-unicode.php');
  class myPDF extends PDF_Unicode
  {
  	//Page header
  	function Header()
  	{
  		global $title ;
  		// $this -> SetFont('font1', 'B', 12);
  		// $this -> Cell(0, 8, $title, "BT", 1, 'L');
  	}
  }
  $serialno = $_GET['serialno'];

  $today = getdate();
  $year = $today["year"] - 1911;
  $mon = $today["mon"];
  $mday = $today["mday"];

  $sql = "SELECT * FROM holidayform WHERE serialno = $serialno";
  $data = $db -> query_array($sql);
  if (count($data['POVTYPE']) != 0){
    $pocard = $data['POCARD'][0];
    // 限本人或祕書室承辦人(列印給主秘及校長看)
    if ($_SESSION['empl_no'] != "7000193" && $pocard != $_SESSION['empl_no'])
      exit;

    $vtype = $data['POVTYPE'][0];
    $agentno  = $data['AGENTNO'][0];
    $agentno2  = $data['AGENTNO'][0];
	  $poremark = $data['POREMARK'][0];
		$on_duty = $data['ON_DUTY'][0]; //1030119 add 擔任職位
		if (strlen($on_duty) > 1)
      $poremark = $poremark ."( " .  $on_duty . ")" ;
    $povdateb = $data['POVDATEB'][0];
    $povdatee = $data['POVDATEE'][0];
    $povtimeb = $data['POVTIMEB'][0];
    $povtimee = $data['POVTIMEE'][0];
    $povdays  = $data['POVDAYS'][0];
    $povhours = $data['POVHOURS'][0];
    $depart  = $data['DEPART'][0];
    $agent_depart = $data['AGENT_DEPART'][0]; //971103 ADD
    //971226 add below
    $appdate  = $data['APPDATE'][0];
    $agentsignd  = $data['AGENTSIGND'][0];
    $bossone  = $data['BOSSONE'][0];
    $bosstwo  = $data['BOSSTWO'][0];
    $bossthree  = $data['BOSSTHREE'][0];
    $onesignd  = $data['ONESIGND'][0];
    $twosignd  = $data['TWOSIGND'][0];
    $threesignd  = $data['THREESIGND'][0];
    $perone  = $data['PERONE'][0];
    $perone_signd  = $data['PERONE_SIGND'][0];
    $pertwo  = $data['PERTWO'][0];
    $pertwo_signd  = $data['PERTWO_SIGND'][0];
    $secone_signd  = $data['SECONE_SIGND'][0];
    $eplace  = $data['EPLACE'][0];
    $permit  = $data['PERMIT_COMMT'][0];
    $budget  = $data['BUDGET'][0];
    $abroad  = $data['ABROAD'][0];
    $exit_date  = $data['EXIT_DATE'][0];
    $back_date  = $data['BACK_DATE'][0];
    $mdateb  = $data['MEETDATEB'][0]; // 會議(工作)期
    $mdatee  = $data['MEETDATEE'][0];
    $mtimeb  = $data['MEETTIMEB'][0]; // 會議(工作)間
    $mtimee  = $data['MEETTIMEE'][0];
    $curstus  = $data['CURENTSTATUS'][0];
  }
  else
    exit;

  if ($eplace !='')
    $eplace = '/' . $eplace;
  if ($permit == '')
    $permit = '';
  $byear  = substr($povdateb, 0, 3);
  $bmonth = substr($povdateb, 3, 2);
  $bday   = substr($povdateb, 5, 2);
  $btime  = $povtimeb;
  $eyear  = substr($povdatee, 0, 3);
  $emonth = substr($povdatee, 3, 2);
  $eday   = substr($povdatee, 5, 2);
  $etime  = $povtimee;
  $total  = $povdays."日".$povhours."時";

  if ($exit_date != ''){
    $exit_year  = substr($exit_date, 0, 3);
    $exit_month = substr($exit_date, 3, 2);
    $exit_day   = substr($exit_date, 5, 2);
    $back_year  = substr($back_date, 0, 3);
    $back_month = substr($back_date, 3, 2);
    $back_day   = substr($back_date, 5, 2);
  }

  if ($mdatee != ''){
    $meetb_y  = substr($mdateb, 0, 3);
    $meetb_m  = substr($mdateb, 3, 2);
    $meetb_d  = substr($mdateb, 5, 2);
    $meete_y  = substr($mdatee, 0, 3);
    $meete_m  = substr($mdatee, 3, 2);
    $meete_d  = substr($mdatee, 5, 2);
  }

  //...........................................................
  //10201 add  半小時轉換
  if (strlen($btime) > 2)
    $btime = substr($btime, 0, 2) . ":" . substr($btime, 2, 2);

  if (strlen($etime) > 2)
    $etime = substr($etime, 0, 2) . ":" . substr($etime, 2, 2);

  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  //本人 961231 add
  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  // 判斷是否為老師
  $sql = "SELECT count(*) count
      FROM   psfcrjb
      WHERE  crjb_empl_no = '$pocard'
      AND  crjb_seq = '1'
      AND  substr(crjb_title, 1, 1) = 'B'
      AND  crjb_quit_date IS NULL";
  $data = $db -> query_array($sql);
  if (count($data['COUNT'][0]) > 0)
    $cnt_i = $data['COUNT'][0];  //真的主管身份，不見得是一級主管，可能是組長

  $sql = "SELECT count(*) count
      FROM    psfcrjb
      WHERE  crjb_empl_no = '$pocard'
      AND  crjb_seq > '1'
      AND  crjb_quit_date IS NULL";
  $data = $db -> query_array($sql);
  if (count($data['COUNT'][0]) > 0)
    $cnt_j = $data['COUNT'][0];  //真的主管身份，不見得是一級主管，可能是組長

  if ($cnt_i > 0 and $cnt_j > 0){
    $sql = "SELECT  empl_chn_name,
            (SELECT dept_full_name
            FROM  stfdept WHERE dept_no=c.crjb_depart) dept_name,
            (SELECT code_chn_item
            FROM psqcode WHERE  code_kind='0202'
            AND  code_field=c.crjb_title) title_name
            FROM  psfempl p,psfcrjb  c
            WHERE empl_no='$pocard'
            AND   empl_no=crjb_empl_no
            AND   crjb_seq > '1'
            AND   crjb_quit_date IS NULL";
  }else {
    $sql = "SELECT  empl_chn_name,
        (SELECT dept_full_name
        FROM  stfdept WHERE dept_no=c.crjb_depart) dept_name,
        (SELECT code_chn_item
        FROM psqcode WHERE  code_kind='0202'
        AND  code_field=c.crjb_title) title_name
        FROM  psfempl p,psfcrjb  c
        WHERE empl_no='$pocard'
        AND   empl_no=crjb_empl_no
        AND   crjb_seq='1'
        AND   crjb_quit_date IS NULL";
  }
  $data = $db -> query_array($sql);
  if (count($data['EMPL_CHN_NAME']) > 0){
    $name   = $data['EMPL_CHN_NAME'][0];
    $dept_name = $data['DEPT_NAME'][0];
    $title_name  = $data['TITLE_NAME'][0];
  }

  $sql = "SELECT 	code_chn_item||crjb_saly_code   grade_name
        FROM    psfcrjb, psqcode
        WHERE crjb_empl_no = '$pocard'
        AND     crjb_seq='1'
        AND     crjb_quit_date IS NULL
        AND     code_kind='0201'
        AND     code_field=crjb_job_grade";
  $grade_name = "";
  $data = $db -> query_array($sql);
  if (count($data['GRADE_NAME']) > 0)
    $grade_name = $data['GRADE_NAME'][0];

  $sql = "SELECT code_field,code_chn_item
        FROM     psqcode
        WHERE    code_kind='0302'
        AND      code_field='$vtype'
        ORDER BY code_field ";
  $data = $db -> query_array($sql);
  $item = "";
  if (count($data['CODE_CHN_ITEM']) > 0)
    $item = $data['CODE_CHN_ITEM'][0];

  //-----------961129 ADD-------------------------------------
  //兼二個以上單位代理人處理，抓請假者請假時所選之單位當代理人
  //-------------------------------------------------------
  $sql = "SELECT count(*) count
        FROM  psfcrjb
        WHERE crjb_empl_no='$pocard'
        AND   crjb_seq>'1'
        AND   crjb_quit_date IS NULL";
  $data = $db -> query_array($sql);
  if (count($data['COUNT']) > 0)
    $cnt_a = $data['COUNT'][0];  //有幾個主管職務

  if ($cnt_a > 1){
    //正確作法應該抓 chief_agent (有加日期判斷)，detp_boss 會隨時被蓋掉
    $sql = "SELECT boss_agent,boss_agent_dept
        FROM dept_boss
        WHERE dept_no='$depart'";
    $data = $db -> query_array($sql);
    if (count($data['BOSS_AGENT']) > 0){
      $agentno = $data['BOSS_AGENT'][0];
      $agent_depart = $data['BOSS_AGENT_DEPT'][0];
    }
  }

  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  //代理人
  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  $sql = "SELECT dept_full_name,empl_chn_name,code_chn_item
        FROM  psfempl, psfcrjb, stfdept,psqcode
        WHERE empl_no=crjb_empl_no
        AND   crjb_depart=dept_no
        AND   crjb_quit_date IS NULL
        AND   code_kind='0202'
        AND   code_field=crjb_title
        AND   empl_no='$agentno'
        AND   crjb_depart='$agent_depart'";
  $data = $db -> query_array($sql);
  if (count($data['DEPT_FULL_NAME']) > 0){
    $agent_dept_name = $data['DEPT_FULL_NAME'][0];
    $agent_name = $data['EMPL_CHN_NAME'][0];
    $agent_title = $data['CODE_CHN_ITEM'][0];
  }
  else{
    $sql = "SELECT dept_full_name,empl_chn_name,code_chn_item
          FROM  psfempl, psfcrjb, stfdept,psqcode
          WHERE empl_no=crjb_empl_no
          AND   crjb_seq='1'
          AND   crjb_depart=dept_no
          AND   crjb_quit_date IS NULL
          AND   code_kind='0202'
          AND   code_field=crjb_title
          AND   empl_no='$agentno2'";

    $data = $db -> query_array($sql);
    if (count($data['DEPT_FULL_NAME']) > 0){
      $agent_dept_name = $data['DEPT_FULL_NAME'][0];
      $agent_name = $data['EMPL_CHN_NAME'][0];
      $agent_title = $data['CODE_CHN_ITEM'][0];
    }
  }
  $agent_text = '線上簽核';
  if ($agentno == 'none'){
    $agent_name = '免';
    $agent_text = '';
  }

  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  //現職檔主管 0990303 看簽核是否為主管本人，不是多代理字眼
  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  $crjb_bossone = '';
  $crjb_bosstwo = '';
  $crjb_bossthree = '';

  if (substr($depart, 0, 1) <= '9') //教學單位
    $crjb_seq = " AND crjb_seq > '1'";
  else  //行政單位
    $crjb_seq = " AND crjb_title IN (select code_field from psqcode where code_kind='0202' and type>'0')";

  $sql = "SELECT empl_no
          FROM  psfempl,psfcrjb
          WHERE empl_no=crjb_empl_no
          AND   crjb_depart = '$depart'" . $crjb_seq .
          "AND   crjb_quit_date IS NULL
          AND   substr(empl_no, 1, 1) = '0'";

  $data = $db -> query_array($sql);
  if (count($data['EMPL_NO']) > 0)
    $crjb_bossone = $data['EMPL_NO'][0];//二級主管

  $sql = "SELECT empl_no
          FROM  psfempl,psfcrjb
          WHERE empl_no=crjb_empl_no
          AND   substr(crjb_depart,1,2) = substr('$depart', 1, 2)
          AND   substr(crjb_depart,3,1) = '0'" . $crjb_seq .
          "AND   crjb_quit_date is null
          AND   substr(empl_no,1,1)='0'";

  $data = $db -> query_array($sql);
  if (count($data['EMPL_NO']) > 0)
    $crjb_bosstwo = $data['EMPL_NO'][0]; //一級主管

  $sql = "SELECT empl_no
          FROM  psfempl,psfcrjb
          WHERE empl_no=crjb_empl_no
          AND   substr(crjb_depart,1,1) = substr('$depart', 1, 1)
          AND   substr(crjb_depart,2,2) = '00'" . $crjb_seq .
          " AND   crjb_quit_date IS NULL
          AND   substr(empl_no,1,1) = '0'";
  $data = $db -> query_array($sql);
  // 院長
  if (count($data['EMPL_NO']) > 0)
    $crjb_bossthree = $data['EMPL_NO'][0];

  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  //簽核者
  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  $bossone_name = '';
  $bosstwo_name = '';
  $bossthree_name = '';
  $pertwo_name = '';

  $sql = "SELECT empl_chn_name
          FROM  psfempl
          WHERE empl_no='$bossone'";
  $data = $db -> query_array($sql);
  //二級主管姓名
  if (count($data['EMPL_CHN_NAME']) > 0){
    $bossone_name = $data['EMPL_CHN_NAME'][0];
  if ($crjb_bossone != $bossone) //0990303 add
    $bossone_name .= "代理";
  }

  $sql = "SELECT empl_chn_name
          FROM  psfempl
          WHERE empl_no='$bosstwo'";
  $data = $db -> query_array($sql);
  //一級主管姓名
  if (count($data['EMPL_CHN_NAME']) > 0){
    $bosstwo_name = $data['EMPL_CHN_NAME'][0];
  if ($crjb_bosstwo != $bosstwo)
    $bosstwo_name .= "代理";
  }

  $sql = "SELECT empl_chn_name
          FROM  psfempl
          WHERE empl_no='$bossthree'";
  $data = $db -> query_array($sql);
  //院長姓名
  if (count($data['EMPL_CHN_NAME']) > 0){
    $bossthree_name = $data['EMPL_CHN_NAME'][0];
  if ($crjb_bossthree != $bossthree)
    $bossthree_name .= "代理";
  }

  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  //人事簽核者
  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  $perone_name = '';
  $pertwo_name = '';
  $sql = "SELECT empl_chn_name
          FROM  psfempl
          WHERE empl_no='$perone'";
  $data = $db -> query_array($sql);
  if (count($data['EMPL_CHN_NAME']) > 0){
    $perone_name = $data['EMPL_CHN_NAME'][0];
  }

  $chief = '';
  if ($pertwo != ''){
    $sql = "SELECT empl_chn_name
            FROM  psfempl
            WHERE empl_no='$pertwo'";
    $data = $db -> query_array($sql);
    if (count($data['EMPL_CHN_NAME']) > 0)
      $pertwo_name = $data['EMPL_CHN_NAME'][0];

    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    //秘書室簽核者
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    $sql = "SELECT empl_chn_name
            FROM  psfempl,dept_boss
            WHERE empl_no=boss_no
            AND   dept_no='M70'
            AND   type='7'";
    $data = $db -> query_array($sql);
    if (count($data['EMPL_CHN_NAME']) > 0) {
      $sectwo_name = $data['EMPL_CHN_NAME'][0];
      $chief = '秘書室:' . $sectwo_name .
          "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
      $secone_signd;
    }
  }
  elseif ($secone_signd == ''){
    if ($curstus == '6') {
      $pertwo_signd = '一層決行';
      $chief = '一層決行';
    }
    else {
      $pertwo_signd = '二層決行';
      $chief = '二層決行';
    }
  }
  else{
    $sql = "SELECT empl_chn_name
            FROM  psfempl,dept_boss
            WHERE empl_no=boss_no
            AND   dept_no='M70'
            AND   type='7'";
    $data = $db -> query_array($sql);
    if (count($data['EMPL_CHN_NAME']) > 0) {
      $sectwo_name = $data['EMPL_CHN_NAME'][0];
      $chief = '秘書室:' . $sectwo_name .
          "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
      $secone_signd;
    }
  }

  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  //上課相關資料
  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  $sql = "SELECT * FROM haveclass
          WHERE class_serialno = '$serialno'";
  $data = $db -> query_array($sql);

  $class = 0;
  for (; $class < count($data['CLASS_NAME']); $class++){//liru update
    $class_name[$class] = $data['CLASS_NAME'][$class];
    $class++;
  }

  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  //研發處註記日期
  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  $sign_date = "免";
  $sql = "SELECT sign_date
          FROM  teacher
          WHERE teacher_no='$pocard'
          AND   begin_date='$povdateb'
          AND   end_date='$povdatee'";
  $data = $db -> query_array($sql);

  if (count($data['SIGN_DATE']) > 0){
    $sign_date = $data['SIGN_DATE'][0];
  }
  // echo "<script>alert('請先取消頁首頁尾設定，取消方式： 點選瀏覽器視窗「檔案」-->「設定列印格式」或者是「版面設定」-->「頁首和頁尾」-->「清空頁首及頁尾或是頁腳的內容。」'); </script>";
  $str_today = $year . "/" . $mon . "/" . $mday;
  $pdf = new myPDF();
  $pdf->AddPage();  //新的一頁
  // $pdf->SetMargins(10, 5);  //設定邊界(需在第一頁建立以前)
  //$pdf->AddUniCNShwFont('font1');
  $pdf->AddUniCNShwFont('font1','DFKaiShu-SB-Estd-BF');
  $pdf->SetFont('font1');
  $width = 32;
  $height = 7;

  $pdf->Cell(0, $height, "國立彰化師範大學公差假核准證明", 0, 0, "C");
  $pdf->Cell(0, $height, "申請日期：" . $str_today, 0, 1, "R");
  $pdf->Cell($width, $height, "項目別", 1, 0, "C");
  $pdf->Cell($width / 2, $height, "姓名", 1, 0, "C");
  $pdf->Cell($width * 2, $height, "所屬單位", 1, 0, "C");
  $pdf->Cell($width, $height, "職稱", 1, 0, "C");
  $pdf->Cell($width, $height, "職等", 1, 0, "C");
  $pdf->Cell($width - 10, $height, "蓋章", 1, 1, "C");

  $pdf->Cell($width, $height * 2, "請假人", 1, 0, "C");
  $pdf->Cell($width / 2, $height * 2, $name, 1, 0, "C");
  $pdf->Cell($width * 2, $height * 2, $dept_name, 1, 0, "C");
  $pdf->Cell($width, $height * 2, $title_name, 1, 0, "C");
  $pdf->Cell($width, $height * 2, $grade_name, 1, 0, "C");
  $pdf->MultiCell($width - 10, $height, $appdate . "\n線上申請", 1, 1, "C");

  $pdf->Cell($width, 7, "職務代理人", 1, 0, "C");
  $pdf->Cell($width / 2, 7, $agent_name, 1, 0, "C");
  $pdf->Cell($width * 2, 7, $agent_dept_name, 1, 0, "C");
  $pdf->Cell($width, 7, $agent_title, 1, 0, "C");
  $pdf->Cell($width, 7, "", 1, 0, "C");
  $pdf->Cell($width - 10, 7, $agentsignd . $agent_text, 1, 1, "C");

  $pdf->Cell($width, 7, "請假別/地點", 1, 0, "C");
  $pdf->Cell(166, 7, $item . $eplace, 1, 1, "C");

  $pdf->Cell($width, 7, "請假期間", 1, 0, "C");
  $str_cell = $byear . "年" . $bmonth . "月" . $bday . "日" . $btime . "時 至 " .
              $eyear . "年" . $emonth . "月" . $eday . "日" . $etime . "時";
  $pdf->Cell(166, 7, $str_cell, 1, 1, "C");

  if( ($vtype == '01' || $vtype == '02' || $vtype) == '03' && $abroad == '1'){
    $pdf->Cell($width, $height, "出入境期間", 1, 0, "C");
    $str_cell = $exit_year . "年" . $exit_month . "月" . $exit_day . "日 至 " .
                $back_year . "年" . $back_month . "" . $back_day . "日";
    $pdf->Cell(166, 7, $str_cell, 1, 1, "C");
  }
  if( ($vtype == '01' || $vtype == '02' || $vtype) == '03' && $abroad == '0'){
    $pdf->Cell($width, $height, "事由及會議起訖時間", 1, 0, "C");
    if ($mdatee !=''){
      $str_cell = $meetb_y . "年" . $meetb_m . "月" . $meetb_d . "日" . $mtimeb . "時 至 " .
                  $meete_y . "年" . $meete_m . "月" . $meete_d . "日" . $mtimee . "時";
      $pdf->Cell(166, $height, $str_cell, 1, 1, "C");
    }
    elseif ($mtimee != ''){
      $str_cell =  $mtimeb . "時 至" . $mtimee . "時";
      $pdf->Cell(0, $height, $str_cell, 1, 1, "C");
    }
  }

  $pdf->Cell($width, 7, "奉派文號或提簽日期", 1, 0, "C");
  $pdf->Cell(166, 7, $permit, 1, 1, "C");

  $pdf->Cell($width, 7, "經費來源", 1, 0, "C");
  $pdf->Cell(110, 7, $budget, 1, 0, "C");
  $pdf->Cell(56, 7, "系辦註記：" . $sign_date, 1, 1, "C");

  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $pdf->MultiCell(66, $height, "處室/中心/學院\n簽核日期及簽核者", 1, "C");
  $pdf->SetXY($x + 66, $y);
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $pdf->MultiCell(66, $height, "人事室\n簽核日期及簽核者", 1, "C");
  $pdf->SetXY($x + 66, $y);
  $pdf->MultiCell(66, $height * 2, "校長", 1, "C");

  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $str_cell = "";
  if ($bossone_name !='')
    $str_cell = "組長：$bossone_name / $onesignd";
  else
    $str_cell = "組長：免";

  if ($bosstwo_name != '')
    $str_cell .= "\n主任：$bosstwo_name / $twosignd";
  else
    $str_cell .=  "\n主任：免";

  if ($bossthree_name != '')
    $str_cell .=  "\n院長：$bossthree_name / $threesignd";
  else
    $str_cell .=  "\n院長：免";

  $pdf->MultiCell(66, $height * 2, $str_cell, 1, "C");
  $pdf->SetXY($x + 66, $y);
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $str_cell = "承辦員：$perone_name / $perone_signd\n" .
              "主任：$pertwo_name / $pertwo_signd";
  $pdf->MultiCell(66, $height * 3, $str_cell, 1, "C");
  $pdf->SetXY($x + 66, $y);
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $pdf->MultiCell(66, $height * 6, $chief, 1, "C");
  $pdf->SetXY($x + 66, $y);

  $pdf->Ln();
  $pdf->Output();
?>
