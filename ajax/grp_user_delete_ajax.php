<?
include '../inc/connect.php';

if (isset($_POST['grp']) && isset($_POST['user']))
{
  $grp = $_POST['grp'];
  $user = $_POST['user'];

  $sql = "DELETE FROM SYSGRPUSER WHERE SYSID='LEAVE' AND GRPID='$grp' AND USERID='$user'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
