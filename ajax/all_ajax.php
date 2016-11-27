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
    else if($_POST['oper']=="qry_type")
    {
        $sql2 = "SELECT code_field,code_chn_item
                FROM   psqcode
                where  code_kind='0302'
                and     code_field !='**'
                order by code_field ";
                // and code_field in ('03','04','01','05','07','08','09','23')";
        $data = $db -> query_array($sql2);

        echo json_encode($data);
        exit;
    }

    if($_POST['oper'] == 0)
    {
        if ( $_POST['dpt'] == 'null' || $_POST['dpt'] == "請選擇單位" )
            if ( $_POST['type'] == 'null' || $_POST['type'] == "請選擇假別" )
                $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,
                            h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD, h.AGENTNO,h.serialno,h.CURENTSTATUS ,
                            h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                            substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate
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
                            h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD, h.AGENTNO,h.serialno,h.CURENTSTATUS ,
                            h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                            substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate
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
                            h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD, h.AGENTNO,h.serialno, h.CURENTSTATUS,
                            h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                            substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate
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
                        h.POVDATEE,h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD, h.AGENTNO,h.serialno, h.CURENTSTATUS,
                        h.agentsignd,h.onesignd,h.twosignd,h.THREESIGND,h.depart,h.secone_signd,h.perone_signd,h.pertwo_signd,
                        substr(DEPT_SHORT_NAME,1,14) dept_short_name,h.appdate
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
                $row['DEPT_SHORT_NAME'][$i],
                $row['EMPL_CHN_NAME'][$i],
                $row['CODE_CHN_ITEM'][$i],
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
        //exit;
    }

?>
