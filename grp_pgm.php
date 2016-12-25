<?
include("inc/header.php");
include("inc/navi.php");
include("inc/sidebar.php");
$sql ="select distinct grpid, grpname
    from  sysgrp
    where sysid='LEAVE'
    order by grpid";
$g = $db -> query_array($sql);
$sql ="select distinct grpid, pgmid
    from  sysgrppgm
    where sysid='LEAVE'
    order by grpid";
$gp = $db -> query_array($sql);
$sql ="select distinct pgmid,pgmname,pgmurl,parent_folder
    from  syspgm
    where sysid='LEAVE'
    order by pgmid";
$p = $db -> query_array($sql);
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
          for (; $j < sizeof($gp['GRPID']); $j++)
          {
            if ($grpid != $gp['GRPID'][$j])
              break;
            $pgmid = $gp['PGMID'][$j];
            echo "<li id='$pgmid'>$pgmid
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
      <ol class="root pgm vertical"><!-- content -->
        <?
        $parent_previous = "";
        for ($i=0; $i < sizeof($p['PGMNAME']); $i++) {
          $pgmid = $p['PGMID'][$i] ;
          $pgmurl = $p['PGMURL'][$i] ;
          $pgmname =  $p['PGMNAME'][$i] ;
          $parent_folder = $p['PARENT_FOLDER'][$i] ; //父節點

          if($i>0 && $parent_folder<>$parent_previous)//換根節點
          {
            $len = strlen($parent_previous) - strlen($parent_folder) ;
            if ($len==5)//ex. A07 -- >"" 相差2層
              echo "</li></span>\r\n</li></span>\r\n</li></span>";
            if ($len==3)//ex. A07 -- >"" 相差2層
              echo "</li></span>\r\n</li></span>\r\n";
            else //ex. A07-->A
              echo "</li></span>\r\n";
          }

          echo "<li id='$pgmid' class='disable'>$pgmid - $pgmname</li>";

          if ($pgmurl == "")
            echo "<span class='tree'>\r\n";

          $parent_previous = $parent_folder;
          if($pgmurl == "")
            if (strcmp($p['PARENT_FOLDER'][$i+1], $pgmid) == 0)
              $parent_previous = $pgmid;
            else
              echo "</li></span>\r\n";
        }
        ?>
      </ol>
    </div>
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php");
