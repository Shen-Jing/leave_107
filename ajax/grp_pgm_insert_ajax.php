<?
include '../inc/connect.php';

if (isset($_POST['grp']) && isset($_POST['pgm']))
{
  $grp = $_POST['grp'];
  $pgm = $_POST['pgm'];

  $sql = "INSERT INTO SYSGRPPGM (GRPID,SYSID,PGMID) (SELECT '$grp','LEAVE',PGMID FROM SYSPGM WHERE SYSID='LEAVE' AND PGMID LIKE '$pgm%')";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
