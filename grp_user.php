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
$sql = "SELECT dept_no, dept_short_name, empl_chn_name, psfempl.email
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
          echo "<li id='$grpid' class='grp-li' style='display:none;'>$grpname
          <ol class='grp'>";
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
      篩選器： <input id="filter" class="form-control" type="text" value="">
      <br/>
      <ol class="root user vertical"><!-- content -->
        <?
        for ($i=0; $i < sizeof($u['EMAIL']); $i++) {
          $userid = substr($u['EMAIL'][$i], 0, -15) ;
          $empl_chn_name = $u['EMPL_CHN_NAME'][$i];
          $dept_short_name = $u['DEPT_SHORT_NAME'][$i];
          $dept_no = $u['DEPT_NO'][$i];
          if ($i == 0)
          {
            echo "<li class='disable'>$dept_no - $dept_short_name<ol style='display: none'>";
            $tmp = $dept_no;
          }
          else if (strcmp($dept_no, $tmp) != 0)
          {
            echo "</ol></li>";
            echo "<li class='disable'>$dept_no - $dept_short_name<ol style='display: none'>";
            $tmp = $dept_no;
          }
          echo "<li id='$userid' class='disable'>$userid - $empl_chn_name</li>";
        }
        ?>
      </ol>
    </div>
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php");
