<?
include '../inc/connect.php';

if (isset($_POST['id']))
{
  $id = $_POST['id'];

  $sql = "DELETE FROM SYSPGM WHERE SYSID='LEAVE' AND PGMID='$id'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
