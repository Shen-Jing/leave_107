<?
include("inc/header.php");
include("inc/navi.php");
include("inc/sidebar.php");
$sql ="select distinct pgmid,pgmname,pgmlevel,pgmsort,pgmurl,pgmtype ,parent_folder,folder_img
    from syspgm
    where sysid='LEAVE'
    order by pgmid,pgmsort";
$d = $db -> query_array($sql);
?>
<!-- Page Content -->
<div id="page-wrapper">
  <div class="container-fluid">
    <? include ("inc/page-header.php"); ?>
    <ol class="root vertical"><!-- content -->
      <?
        $parent_previous = "";
        for ($i=0; $i < sizeof($d['PGMNAME']); $i++) {
          $pgmid = $d['PGMID'][$i] ;
          $pgmurl = $d['PGMURL'][$i] ;
          $pgmname =  $d['PGMNAME'][$i] ;
          $parent_folder = $d['PARENT_FOLDER'][$i] ; //父節點
          $folder_img = $d['FOLDER_IMG'][$i] ; //節點圖示

          if($i>0 && $parent_folder<>$parent_previous)//換根節點
          {
            $len = strlen($parent_previous) - strlen($parent_folder) ;
            if ($len==5)//ex. A07 -- >"" 相差2層
              echo "</ol></li>\r\n</ol></li>\r\n</li>";
            if ($len==3)//ex. A07 -- >"" 相差2層
              echo "</ol></li>\r\n</ol></li>\r\n";
            else //ex. A07-->A
              echo "</ol></li>\r\n";
          }

          echo "<li id='$pgmid'>\r\n<span>$pgmname</span><span class='right'><button class='edit-opener btn btn-info btn-sm'>編輯</button> <button class='delete btn btn-danger btn-sm'>刪除</button></span>";
          if ($pgmurl == "")
            echo "<ol>\r\n";
          else
            echo "</li>\r\n";

          $parent_previous = $parent_folder  ;
          if($pgmurl=="") $parent_previous = $pgmid ; //目前的根節點
        }
      ?>
    </ol>
    </ol>
    </ol>
    <button id="add-opener" class="btn btn-success">新增</button>

    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">新增節點</h4>
              </div>
              <!-- Modal Body -->
              <div class="modal-body">
                程式 ID：
                <input id="add-id" class="form-control"></input><br>
                程式名稱：
                <input id="add-name" class="form-control"></input><br>
                程式連結：
                <input id="add-url" class="form-control"></input><br>
                程式是否開新分頁：
                <input id="add-type" type="checkbox" class="form-control"></input><br>
                程式圖示：
                <input id="add-img" class="form-control"></input>
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
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">編輯節點</h4>
              </div>
              <!-- Modal Body -->
              <div class="modal-body">
                <input id="edit-id" type="hidden"></input>
                程式名稱：
                <input id="edit-name" class="form-control"></input><br>
                程式連結：
                <input id="edit-url" class="form-control"></input><br>
                程式是否開新分頁：
                <input id="edit-type" type="checkbox" class="form-control"></input><br>
                程式圖示：
                <input id="edit-img" class="form-control"></input>
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
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php");