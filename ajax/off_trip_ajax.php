<?php
include'../inc/connect.php';
$account="0ob@cc.ncue.edu.tw";
$sql = "select empl_no
        from  psfempl, psfcrjb, stfdept
   where empl_no=crjb_empl_no
   and crjb_depart=dept_no
   and crjb_seq='1'
   and substr(empl_no,1,1) !='A'
   and crjb_quit_date is null
   and psfempl.email='".$account."'
        ";
  $userid =$db -> query_first_row($sql)[0];


  $sql = "SELECT count(*) count
        FROM holidayform
        where POCARD='$userid'
        and  trip=2
        and condition <> -1";
  $data = $db -> query_array($sql);



// DatableTable data
$count=sizeof($data);
if ($count==0)
echo $data[0];
else{
$SQLStr ="SELECT empl_chn_name,h.POCARD,pc.CODE_CHN_ITEM,h.POVDATEB,h.POVDATEE,
h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
h.serialno,h.CURENTSTATUS
FROM psfempl p,holidayform h,psqcode pc
where h.POCARD='$userid'
and condition <> -1
and  trip=2
and  p.empl_no=h.pocard
and  pc.CODE_KIND='0302'
and  pc.CODE_FIELD=h.POVTYPE
order by h.POCARD,h.POVDATEB,h.POVHOURS";
$row = $db -> query_array($SQLStr);
$a['data']="";

for($i = 0; $i < sizeof($row['EMPL_CHN_NAME']); ++$i){
  $a['data'][] = array(
    $row['EMPL_CHN_NAME'][$i],
    $row['CODE_CHN_ITEM'][$i],
    $row['POVDATEB'][$i],
    $row['POVDATEE'][$i],
    $row['POVTIMEE'][$i],
    $row['POVTIMEB'][$i],
    $row['POVDAYS'][$i]."天".$row['POVHOURS'][$i]."時",
    "",
    "<button type=\"button\" class=\"btn btn-default\">取消</button>");

}

echo json_encode($a);
}
?>