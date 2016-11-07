<?
include '../inc/connect.php';

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['url']) && isset($_POST['type']) && isset($_POST['img']))
{
  $id = $_POST['id'];

  $sql = "DELETE FROM SYSPGM WHERE SYSID='LEAVE' AND PGMID='$id'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
