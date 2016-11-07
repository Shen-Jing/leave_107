<?
include("inc/header.php");
$sql ="select distinct pgmid,pgmname,pgmlevel,pgmsort,pgmurl,pgmtype ,parent_folder,folder_img
    from syspgm
    where sysid='LEAVE'
    order by pgmid,pgmsort";
$d = $db -> query_array($sql);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <link rel="stylesheet" type="text/css" href="/css/editNode2.css" media="screen">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://www.internetke.com/jsEffects/2015080322/js/jquery-sortable-lists.min.js"></script>
    <script src="js/editNode2.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
  </head>
  <body>
    <div class="root">
      <ul class="sTree2 listsClass" id="sTree2">
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
            if ($len==3)//ex. A07 -- >"" 相差2層
              echo "</ul></li>\r\n</ul></li>\r\n";
            else //ex. A07-->A
              echo "</ul></li>\r\n";
          }

          echo "<li id='$pgmid'>\r\n<div>$pgmname<span class='right'><button class='edit-opener'>編輯</button></span></div>";
          if ($pgmurl == "")
            echo "<ul>\r\n";
          else
            echo "</li>\r\n";

          $parent_previous = $parent_folder  ;
          if($pgmurl=="") $parent_previous = $pgmid ; //目前的根節點
        }
        ?>
      </ul>
    </div>

    <button id="add-opener">新增</button>
    <button class='edit-opener'>編輯</button>
    <button id='delete'>刪除</button>

    <div id="add-dialog" title="新增介面">
      程式 ID：
      <input id="add-id"></input><br>
      程式名稱：
      <input id="add-name"></input><br>
      程式連結：
      <input id="add-url"></input><br>
      程式是否開新分頁：
      <input id="add-type" type="checkbox"></input><br>
      程式圖示：
      <input id="add-img"></input>
    </div>

    <div id="edit-dialog" title="編輯介面">
      程式 ID：
      <input id="edit-id"></input><br>
      程式名稱：
      <input id="edit-name"></input><br>
      程式連結：
      <input id="edit-url"></input><br>
      程式是否開新分頁：
      <input id="edit-type" type="checkbox"></input><br>
      程式圖示：
      <input id="edit-img"></input>
    </div>

  </body>
</html>
