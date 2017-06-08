<?php
    session_start();
    include '../inc/connect.php';
    $empl_no = $_SESSION['empl_no'];

    $today = getdate();
    $year = $today["year"] - 1911;
    $month = $today["mon"];


    if ($_POST['oper']=="qry_year")
    {
        $data = array('year' => $year, 'month' => $month );
        echo json_encode($data);
        exit;
    }
    else if ($_POST['oper']=="qry_dpt")
    {
        $sql2 = "select min(dept_no) dept_no,min(dept_full_name) dept_full_name
            from stfdept
            where use_flag is null
            group by substr(dept_no,1,2)";

        $data = $db -> query_array($sql2);

        echo json_encode($data);
        exit;
    }
    else if ($_POST["oper"] == "qry_dpt_empl" )
    {
        if($_POST["dpt"] != 'null' && $_POST["dpt"] != "請選擇單位")
        {
            $deptt = substr($_POST["dpt"],0,2);
            $sql = "select empl_no,empl_chn_name from psfempl,psfcrjb where empl_no=crjb_empl_no and crjb_quit_date is null and substr(empl_no,1,1) in ('0','5','7','3','4') and substr(crjb_depart,0,2)='$deptt' ";

            $data = $db -> query_array($sql);

            echo json_encode($data);
        }

        exit;

    }
    else if($_POST['oper']=="qry_type")
    {
        $sql2 = "SELECT code_field,code_chn_item
                FROM   psqcode
                where  code_kind='0302'
                and     code_field !='**'
                order by code_field ";
        $data = $db -> query_array($sql2);

        echo json_encode($data);
        exit;
    }
    else if($_POST["oper"] == "voc_detail")
    {

        $permit_commt = 0;
        $eplace = 0;
        $trip = 0;
        $nouse = 0;
        $budget = 0;
        $data = array();

        for($i = 0 ; $i < count($_POST["voc_type"]) ; $i++)
        {
            if(strcmp($_POST["voc_type"][$i], "permit_commt") == 0)
                $permit_commt = 1;
            if(strcmp($_POST["voc_type"][$i], "eplace") == 0)
                $eplace = 1;
            if(strcmp($_POST["voc_type"][$i], "trip") == 0)
                $trip = 1;
            if(strcmp($_POST["voc_type"][$i], "nouse") == 0)
                $nouse = 1;
            if(strcmp($_POST["voc_type"][$i], "budget") == 0)
                $budget = 1;
            if(strcmp($_POST["voc_type"][$i], "class") == 0)
                $class = 1;
        }

        $j = count($_POST["voc_type"]);


        if($permit_commt)
        {
            $SQLStr ="SELECT h.permit_commt, h.containsat FROM psfempl p,holidayform h,psqcode pc,stfdept
                where substr(lpad(povdateb,7,'0'),1,3) =    lpad('$_POST[p_year]',3,'0')
                and   substr(lpad(povdateb,7,'0'),4,2)  =   lpad('$_POST[p_month]',2,'0')
                and   dept_no=depart
                and   substr(depart,1,2)=substr('$_POST[dept_no]',1,2)
                and   condition in ('0','1')
                and   p.empl_no=h.pocard
                and   pc.CODE_KIND='0302'
                and   pc.CODE_FIELD=h.POVTYPE
                and   h.povtype= '$_POST[typeval]'
                AND   h.POVDATEB = $_POST[POVDATEB]
                AND   h.POVDATEE = $_POST[POVDATEE]
                AND   p.empl_chn_name = '$_POST[EMPL_CHN_NAME]'
                order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";

                $permit_arr = $db -> query_array($SQLStr);
                $data["permit"] = $permit_arr;

        }
        if($eplace)
        {
            $SQLStr ="SELECT h.eplace FROM psfempl p,holidayform h,psqcode pc,stfdept
                where substr(lpad(povdateb,7,'0'),1,3) =    lpad('$_POST[p_year]',3,'0')
                and   substr(lpad(povdateb,7,'0'),4,2)  =   lpad('$_POST[p_month]',2,'0')
                and   dept_no=depart
                and   substr(depart,1,2)=substr('$_POST[dept_no]',1,2)
                and   condition in ('0','1')
                and   p.empl_no=h.pocard
                and   pc.CODE_KIND='0302'
                and   pc.CODE_FIELD=h.POVTYPE
                and   h.povtype= '$_POST[typeval]'
                AND   h.POVDATEB = $_POST[POVDATEB]
                AND   h.POVDATEE = $_POST[POVDATEE]
                AND   p.empl_chn_name = '$_POST[EMPL_CHN_NAME]'
                order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";

                $eplace_arr = $db -> query_array($SQLStr);
                $data["eplace"] = $eplace_arr;
        }
        if($trip)
        {
            $SQLStr ="SELECT h.trip FROM psfempl p,holidayform h,psqcode pc,stfdept
                where substr(lpad(povdateb,7,'0'),1,3) =    lpad('$_POST[p_year]',3,'0')
                and   substr(lpad(povdateb,7,'0'),4,2)  =   lpad('$_POST[p_month]',2,'0')
                and   dept_no=depart
                and   substr(depart,1,2)=substr('$_POST[dept_no]',1,2)
                and   condition in ('0','1')
                and   p.empl_no=h.pocard
                and   pc.CODE_KIND='0302'
                and   pc.CODE_FIELD=h.POVTYPE
                and   h.povtype= '$_POST[typeval]'
                AND   h.POVDATEB = $_POST[POVDATEB]
                AND   h.POVDATEE = $_POST[POVDATEE]
                AND   p.empl_chn_name = '$_POST[EMPL_CHN_NAME]'
                order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";

                $trip_arr = $db -> query_array($SQLStr);
                $data["trip"] = $trip_arr;
        }
        if($budget)
        {
            $SQLStr ="SELECT h.budget FROM psfempl p,holidayform h,psqcode pc,stfdept
                where substr(lpad(povdateb,7,'0'),1,3) =    lpad('$_POST[p_year]',3,'0')
                and   substr(lpad(povdateb,7,'0'),4,2)  =   lpad('$_POST[p_month]',2,'0')
                and   dept_no=depart
                and   substr(depart,1,2)=substr('$_POST[dept_no]',1,2)
                and   condition in ('0','1')
                and   p.empl_no=h.pocard
                and   pc.CODE_KIND='0302'
                and   pc.CODE_FIELD=h.POVTYPE
                and   h.povtype= '$_POST[typeval]'
                AND   h.POVDATEB = $_POST[POVDATEB]
                AND   h.POVDATEE = $_POST[POVDATEE]
                AND   p.empl_chn_name = '$_POST[EMPL_CHN_NAME]'
                order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";

                $budget_arr = $db -> query_array($SQLStr);
                $data["budget"] = $budget_arr;
        }
        $SQLStr ="SELECT h.class,h.abroad FROM psfempl p,holidayform h,psqcode pc,stfdept
                where substr(lpad(povdateb,7,'0'),1,3) =    lpad('$_POST[p_year]',3,'0')
                and   substr(lpad(povdateb,7,'0'),4,2)  =   lpad('$_POST[p_month]',2,'0')
                and   dept_no=depart
                and   substr(depart,1,2)=substr('$_POST[dept_no]',1,2)
                and   condition in ('0','1')
                and   p.empl_no=h.pocard
                and   pc.CODE_KIND='0302'
                and   pc.CODE_FIELD=h.POVTYPE
                and   h.povtype= '$_POST[typeval]'
                AND   h.POVDATEB = $_POST[POVDATEB]
                AND   h.POVDATEE = $_POST[POVDATEE]
                AND   p.empl_chn_name = '$_POST[EMPL_CHN_NAME]'
                order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";

        $class_arr = $db -> query_array($SQLStr);
        $data["class_abroad"] = $class_arr;



        echo json_encode($data);
        exit;

    }
    else
    {
        if($_POST["empl_no"] == 'null' || $_POST['empl_no'] == "請選擇人員")
        {
            if ( $_POST['dpt'] == 'null' || $_POST['dpt'] == "請選擇單位" )
                if ( $_POST['type'] == 'null' || $_POST['type'] == "請選擇假別" )
                    $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,
                                h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.POVTYPE,h.ABROAD, h.AGENTNO,h.serialno,h.CURENTSTATUS ,
                                h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                                substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate,dept_no
                                FROM psfempl p,holidayform h,psqcode pc,stfdept
                                where substr(lpad(povdateb,7,'0'),1,3) =  lpad('$_POST[p_year]',3,'0')
                                and   substr(lpad(povdateb,7,'0'),4,2)  =  lpad('$_POST[p_month]',2,'0')
                                and   dept_no=depart
                                and   condition in ('0','1')
                                and   p.empl_no=h.pocard
                                and   pc.CODE_KIND='0302'
                                and   pc.CODE_FIELD=h.POVTYPE
                                order by depart,h.POVDATEB desc ,h.POVHOURS desc";
                else
                    $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,
                                h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.POVTYPE,h.ABROAD, h.AGENTNO,h.serialno,h.CURENTSTATUS ,
                                h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                                substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate,dept_no
                                FROM psfempl p,holidayform h,psqcode pc,stfdept
                                where substr(lpad(povdateb,7,'0'),1,3) =  lpad('$_POST[p_year]',3,'0')
                                and   substr(lpad(povdateb,7,'0'),4,2)  =  lpad('$_POST[p_month]',2,'0')
                                and   dept_no=depart
                                and   condition in ('0','1')
                                and   p.empl_no=h.pocard
                                and   pc.CODE_KIND='0302'
                                and   pc.CODE_FIELD=h.POVTYPE
                                and   h.povtype= '$_POST[type]'
                                order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";
            else
                if ($_POST['type'] == 'null' || $_POST['type'] == "請選擇假別")
                    $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,
                                h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.POVTYPE,h.ABROAD, h.AGENTNO,h.serialno, h.CURENTSTATUS,
                                h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                                substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate,dept_no
                                FROM psfempl p,holidayform h,psqcode pc,stfdept
                                where substr(lpad(povdateb,7,'0'),1,3) =    lpad('$_POST[p_year]',3,'0')
                                and   substr(lpad(povdateb,7,'0'),4,2)  =   lpad('$_POST[p_month]',2,'0')
                                and   dept_no=depart
                                and   substr(depart,1,2)=substr('$_POST[dpt]',1,2)
                                and   condition in ('0','1')
                                and   p.empl_no=h.pocard
                                and   pc.CODE_KIND='0302'
                                and   pc.CODE_FIELD=h.POVTYPE
                                order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";
                else
                    $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,
                        h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.POVTYPE,h.ABROAD, h.AGENTNO,h.serialno, h.CURENTSTATUS,
                        h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                        substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate,dept_no
                        FROM psfempl p,holidayform h,psqcode pc,stfdept
                        where substr(lpad(povdateb,7,'0'),1,3) =    lpad('$_POST[p_year]',3,'0')
                        and   substr(lpad(povdateb,7,'0'),4,2)  =   lpad('$_POST[p_month]',2,'0')
                        and   dept_no=depart
                        and   substr(depart,1,2)=substr('$_POST[dpt]',1,2)
                        and   condition in ('0','1')
                        and   p.empl_no=h.pocard
                        and   pc.CODE_KIND='0302'
                        and   pc.CODE_FIELD=h.POVTYPE
                        and   h.povtype= '$_POST[type]'
                        order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";
        }
        else
        {
            if ($_POST['type'] == 'null' || $_POST['type'] == "請選擇假別")
                $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,
                            h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.POVTYPE,h.ABROAD, h.AGENTNO,h.serialno, h.CURENTSTATUS,
                            h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                            substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate,dept_no
                            FROM psfempl p,holidayform h,psqcode pc,stfdept
                            where substr(lpad(povdateb,7,'0'),1,3) =    lpad('$_POST[p_year]',3,'0')
                            and   substr(lpad(povdateb,7,'0'),4,2)  =   lpad('$_POST[p_month]',2,'0')
                            and   dept_no=depart
                            and   substr(depart,1,2)=substr('$_POST[dpt]',1,2)
                            and   condition in ('0','1')
                            and   p.empl_no=h.pocard
                            and   pc.CODE_KIND='0302'
                            and   pc.CODE_FIELD=h.POVTYPE
                            and   h.pocard = $_POST[empl_no]
                            order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";
            else
                $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,
                    h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.POVTYPE,h.ABROAD, h.AGENTNO,h.serialno, h.CURENTSTATUS,
                    h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                    substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate,dept_no
                    FROM psfempl p,holidayform h,psqcode pc,stfdept
                    where substr(lpad(povdateb,7,'0'),1,3) =    lpad('$_POST[p_year]',3,'0')
                    and   substr(lpad(povdateb,7,'0'),4,2)  =   lpad('$_POST[p_month]',2,'0')
                    and   dept_no=depart
                    and   substr(depart,1,2)=substr('$_POST[dpt]',1,2)
                    and   condition in ('0','1')
                    and   p.empl_no=h.pocard
                    and   pc.CODE_KIND='0302'
                    and   pc.CODE_FIELD=h.POVTYPE
                    and   h.pocard = $_POST[empl_no]
                    and   h.povtype= '$_POST[type]'
                    order by depart,h.POVDATEB desc ,h.POVHOURS desc,depart";
        }



        $row = $db -> query_array($SQLStr);

        $a['data']="";

        for($i = 0; $i < count($row['EMPL_CHN_NAME']) ; $i++)
        {

            if ($row['TWOSIGND'][$i] == '')
                $row['TWOSIGND'][$i] = '-';
            if ($row['PERONE_SIGND'][$i] == '')
                $row['PERONE_SIGND'][$i] = '-';
            if ($row['PERTWO_SIGND'][$i] == '')
                $row['PERTWO_SIGND'][$i] = '-';
            if ($row['SECONE_SIGND'][$i] == '')
                $row['SECONE_SIGND'][$i] = '-';

            if (strlen( $row['POVTIMEB'][$i]) > 2)
                 $row['POVTIMEB'][$i] = substr( $row['POVTIMEB'][$i],0,2) . ":" . substr( $row['POVTIMEB'][$i],2,2);

            if (strlen($row['POVTIMEE'][$i]) > 2)
                $row['POVTIMEE'][$i] = substr($row['POVTIMEE'][$i],0,2) . ":" . substr($row['POVTIMEE'][$i],2,2);

            $a['data'][] = array(
                $row['DEPT_SHORT_NAME'][$i] . "<div style = 'display : none; class = 'dept_no'>/" . $row['DEPT_NO'][$i] . "</div>" ,
                $row['EMPL_CHN_NAME'][$i],
                $row['CODE_CHN_ITEM'][$i] . "<div style = 'display : none; class = 'povtype'>/" . $row['POVTYPE'][$i] . "</div>",
                $row['POVDATEB'][$i],
                $row['POVDATEE'][$i],
                $row['POVTIMEB'][$i],
                $row['POVDAYS'][$i] . "/" . $row['POVHOURS'][$i],
                $row['TWOSIGND'][$i],
                $row['PERONE_SIGND'][$i],
                $row['PERTWO_SIGND'][$i],
                $row['SECONE_SIGND'][$i],
                $row['APPDATE'][$i]
            );

        }
        echo json_encode($a);

    }

?>
