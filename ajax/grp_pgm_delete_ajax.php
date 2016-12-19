<?
include '../inc/connect.php';

if (isset($_POST['grp']) && isset($_POST['pgm']))
{
  $grp = $_POST['grp'];
  $pgm = $_POST['pgm'];

  $sql = "DELETE FROM SYSGRPPGM WHERE SYSID='LEAVE' AND GRPID='$grp' AND PGMID LIKE '$pgm%'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
