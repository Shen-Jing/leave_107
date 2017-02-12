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


    if($_POST['oper'] == 0)
    {
        if ( $_POST['dpt'] == 'null' || $_POST['dpt'] == "請選擇單位" )
                $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE, h.POVDAYS,h.serialno ,h.depart,h.eplace,h.poremark,h.travel_date
                        FROM psfempl p,holidayform h,psqcode pc
                        where substr(lpad(povdateb,7,'0'),1,3)=        lpad('$_POST[p_year]',3,'0')
                            and   substr(lpad(povdateb,7,'0'),4,2)=    lpad('$_POST[p_month]',2,'0')
                            and povtype in ('01','02')
                            and travel='1'
                        and p.empl_no=h.pocard
                        and pc.CODE_KIND='0302'
                        and pc.CODE_FIELD=h.POVTYPE
                        order by h.depart,h.POVDATEB desc ,h.POVHOURS desc";
        else
            $SQLStr ="SELECT empl_chn_name,h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVDATEE, h.POVDAYS,h.serialno ,h.depart,h.eplace,h.poremark,h.travel_date
                    FROM psfempl p,holidayform h,psqcode pc
                    where substr(lpad(povdateb,7,'0'),1,3)=       lpad('$_POST[p_year]',3,'0')
                        and   substr(lpad(povdateb,7,'0'),4,2)=   lpad('$_POST[p_month]',2,'0')
                        and   substr(depart,1,2)=substr('$_POST[dpt]',1,2)
                        and povtype in ('01','02')
                        and travel='1'
                    and p.empl_no=h.pocard
                    and pc.CODE_KIND='0302'
                    and pc.CODE_FIELD=h.POVTYPE
                    order by h.depart,h.POVDATEB desc ,h.POVHOURS desc";

        $row = $db -> query_array($SQLStr);

        $a['data']="";

        for($i = 0 ; $i < count($row['EMPL_CHN_NAME']) ; $i++)
        {
            $depart = $row['DEPART'][$i];
            $SQLStr2 = "SELECT substr(DEPT_SHORT_NAME,1,14) dept_short_name
                                FROM stfdept
                                where dept_no='$depart'";

            $row2 = $db -> query_array($SQLStr2);
            $deptname = $row2["DEPT_SHORT_NAME"][0];

            $a['data'][] = array(
                $deptname,
                $row['EMPL_CHN_NAME'][$i],
                $row['POVDATEB'][$i],
                $row['POVDATEE'][$i],
                $row['POVDAYS'][$i],
                $row['POREMARK'][$i],
                $row['TRAVEL_DATE'][$i],
            );

        }
        echo json_encode($a);
        //exit;
    }

?>
