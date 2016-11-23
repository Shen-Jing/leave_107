<?
include '../inc/connect.php';

if (isset($_POST['id']))
{
  $id = $_POST['id'];
  $parent = substr($id, 0, -2);

  $sql = "DELETE FROM SYSPGM WHERE SYSID='LEAVE' AND PGMID='$id'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
  $sql = "UPDATE syspgm SET pgmid=(substr(pgmid,0,length(pgmid)-2)||LPAD(TO_CHAR(TO_NUMBER(substr(pgmid,-2))-1),2,'0')) WHERE sysid='LEAVE' AND pgmid>='$id' AND parent_folder='$parent'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
