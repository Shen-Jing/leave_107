<?
include '../inc/connect.php';

if (isset($_POST['o_id']) && isset($_POST['n_id']))
{
  $o_id = $_POST['o_id'];
  $n_id = $_POST['n_id'];

  $o_parent = null;
  if (strlen($o_id) > 2)
    $o_parent = substr($o_id, 0, -2);
  $n_parent = null;
  if (strlen($n_id) > 2)
    $n_parent = substr($n_id, 0, -2);

  $level = ord($n_id) - ord('A');

  $sql = "UPDATE syspgm SET pgmid=(substr(pgmid,0,length(pgmid)-2)||LPAD(TO_CHAR(TO_NUMBER(substr(pgmid,-2))+1),2,'0')) WHERE sysid='LEAVE' AND pgmid>='$n_id' AND parent_folder='$n_parent'";
  $db -> query_array ($sql);

  $sql = "UPDATE SYSPGM SET PGMID='$n_id',PARENT_FOLDER='$n_parent',PGMLEVEL='$level' WHERE SYSID='LEAVE' AND PGMID='$o_id'";
  $db -> query_array ($sql);

  $sql = "UPDATE syspgm SET pgmid=(substr(pgmid,0,length(pgmid)-2)||LPAD(TO_CHAR(TO_NUMBER(substr(pgmid,-2))-1),2,'0')) WHERE sysid='LEAVE' AND pgmid>'$o_id' AND parent_folder='$o_parent'";
  $db -> query_array ($sql);
  exit;
}

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['url']) && isset($_POST['type']) && isset($_POST['img']))
{
  $id = $_POST['id'];
  $name = $_POST['name'];
  $url = $_POST['url'];
  $type = $_POST['type'];
  $img = $_POST['img'];
  $sql = "UPDATE SYSPGM SET PGMNAME='$name',PGMURL='$url',PGMTYPE='$type',FOLDER_IMG='$img' WHERE SYSID='LEAVE' AND PGMID='$id'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}

/*
// 遞迴更改子節點
function rec($old, $new){
  $rec_level = ;
  $sql = "UPDATE SYSPGM SET PGMID='$tmp_id',PARENT_FOLDER='$new',PGMLEVEL='$rec_level' WHERE SYSID='LEAVE' AND PARENT_FOLDER='$old'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
*/
?>
