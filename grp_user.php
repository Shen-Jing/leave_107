<?
include("inc/header.php");
include("inc/navi.php");
include("inc/sidebar.php");
$sql ="select distinct grpid, grpname
    from  sysgrp
    where sysid='LEAVE'
    order by grpid";
$g = $db -> query_array($sql);
$sql ="select distinct grpid, userid
    from  sysgrpuser
    where sysid='LEAVE'
    order by grpid";
$gu = $db -> query_array($sql);
$sql = "SELECT dept_no, dept_short_name, empl_chn_name, psfempl.email, empl_no
	      FROM  psfempl, psfcrjb, stfdept
			  WHERE empl_no = crjb_empl_no
			  AND crjb_depart = dept_no
			  AND crjb_seq = '1'
			  AND substr(empl_no, 1, 1) != 'A'
			  AND crjb_quit_date IS NULL
			  AND psfempl.email LIKE '%@cc.ncue.edu.tw'
        ORDER BY dept_no";
$u = $db -> query_array($sql);
?>
<!-- Page Content -->
<div id="page-wrapper">
  <div class="container-fluid">
    <? include ("inc/page-header.php"); ?>
    <div id="left">
      <select class="form-control" id="grp">
        <option selected disabled class="text-hide">請選擇群組</option>
        <option value='all-display'>全部顯示</option>
        <?
        for ($i=0; $i < sizeof($g['GRPID']); $i++) {
          $grpid = $g['GRPID'][$i];
          $grpname = $g['GRPNAME'][$i];
          echo "<option value='$grpid'>$grpname</option>";
        }
        ?>
      </select>
      <br>
      <ol class="root vertical"><!-- content -->
        <?
        for ($i=0, $j=0; $i < sizeof($g['GRPID']); $i++) {
          $grpid = $g['GRPID'][$i];
          $grpname = $g['GRPNAME'][$i];
          echo "<li id='$grpid' class='grp-li' style='display:none;'>$grpname <i class='fa fa-caret-square-o-up'></i>
          <ol class='grp' style='display: none'>";
          for (; $j < sizeof($gu['GRPID']); $j++)
          {
            if ($grpid != $gu['GRPID'][$j])
              break;
            $userid = $gu['USERID'][$j];
            echo "<li id='$userid'>$userid
              <span class='right'>
                <button class='delete btn btn-danger btn-xs'>移除</button>
              </span>
            </li>";
          }
          echo "</ol>
            </li>";
        }
        ?>
      </ol>
    </div>
    <div id="right">
      <input id="filter" class="form-control" type="text" value="" placeholder="這是篩選器，請輸入欲篩選的內容">
      <br/>
      <select multiple class="form-control" id="empl">
        <option value='0'>0</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        <option value='6'>6</option>
        <option value='7'>7</option>
        <option value='8'>8</option>
        <option value='9'>9</option>
        <option value='A'>A</option>
        <option value='J'>J</option>
        <option value='N'>N</option>
        <option value='T'>T</option>
      </select>
      <br/>
      <ol class="root user vertical"><!-- content -->
        <?
        for ($i=0, $flag=false; $i < sizeof($u['EMAIL']); $i++) {
          $userid = substr($u['EMAIL'][$i], 0, -15) ;
          $empl_chn_name = $u['EMPL_CHN_NAME'][$i];
          $dept_short_name = $u['DEPT_SHORT_NAME'][$i];
          $dept_no = $u['DEPT_NO'][$i];
          $empl_no = $u['EMPL_NO'][$i];
          if ($i == 0)
          {
            echo "<li id='$dept_no' class='$dept_short_name disable'>$dept_no - $dept_short_name <i class='fa fa-caret-square-o-up'></i><ol style='display: none'>";
            $tmp = $dept_no;
          }
          else if (strcmp($dept_no, $tmp) != 0)
          {
            if ($flag == true)
              echo "</ol></li>";
            if (substr($dept_no, -1) == '0')
            {
              echo "</ol></li>";
              $flag = false;
            }
            else
              $flag = true;
            echo "<li id='$dept_no' class='$dept_short_name disable'>$dept_no - $dept_short_name <i class='fa fa-caret-square-o-up'></i><ol style='display: none'>";
            $tmp = $dept_no;
          }
          echo "<li id='$userid' class='$empl_chn_name disable' name='$empl_no'>$userid - $empl_chn_name</li>";
        }
        ?>
      </ol>
    </div>
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php");
