<?
// 前置引入
include("inc/header.php");
include("inc/navi.php");
include("inc/sidebar.php");
// 取得 LEAVE 中所有 grp 的 id 和 name 並存入 $g
$sql ="select distinct grpid, grpname
    from  sysgrp
    where sysid='LEAVE'
    order by grpid";
$g = $db -> query_array($sql);
?>
<!-- Page Content -->
<!-- 編輯群組的視窗 -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">編輯群組</h4>
          </div>
          <!-- Modal Body -->
          <div class="modal-body">
            <input id="edit-id" type="hidden"></input>
            群組名稱：
            <input id="edit-name" class="form-control"></input><br>
          </div>
          <!-- Modal Footer -->
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                  關閉
              </button>
              <button id="edit-btn" type="button" class="btn btn-primary">
                  確定編輯
              </button>
          </div>
      </div>
  </div>
</div>

<!-- 新增群組的視窗 -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">新增群組</h4>
          </div>
          <!-- Modal Body -->
          <div class="modal-body">
            群組 ID：
            <input id="add-id" class="form-control"></input><br>
            群組名稱：
            <input id="add-name" class="form-control"></input><br>
          </div>
          <!-- Modal Footer -->
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                  關閉
              </button>
              <button id="add-btn" type="button" class="btn btn-primary">
                  確定新增
              </button>
          </div>
      </div>
  </div>
</div>

<div id="page-wrapper">
  <div class="container-fluid">
    <? include ("inc/page-header.php"); ?>
    <button class="add-opener btn btn-success">新增群組</button><br><br>
    <ol class="root vertical"><!-- content -->
      <?
      // 將資料庫的 grp 化為 li 條列出來
      for ($i=0; $i < sizeof($g['GRPID']); $i++) {
        $grpid = $g['GRPID'][$i];
        $grpname = $g['GRPNAME'][$i];
        echo "<li id='$grpid'><span class='left'>$grpname</span><span class='right'><button class='edit-opener btn btn-info btn-xs'>編輯</button> <button class='delete btn btn-danger btn-xs'>刪除</button></span>
        </li>";
      }
      ?>
    </ol>
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php");
