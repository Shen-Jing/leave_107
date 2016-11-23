<?
include '../inc/connect.php';

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['url']) && isset($_POST['type']) && isset($_POST['img']))
{
  $id = $_POST['id'];
  $name = $_POST['name'];
  $url = $_POST['url'];
  $type = $_POST['type'];
  $img = $_POST['img'];
  $level = ord($_POST['id']) - ord('A');
  $parent = substr($id, 0, -2);
  
  $sql = "INSERT INTO SYSPGM (PGMID,SYSID,PGMNAME,PGMLEVEL,PGMSORT,PGMURL,PGMTYPE,PARENT_FOLDER,FOLDER_IMG) VALUES ('$id','LEAVE','$name','$level','','$url','$type','$parent','$img')";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
