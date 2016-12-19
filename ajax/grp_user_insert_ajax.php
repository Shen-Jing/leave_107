<?
include '../inc/connect.php';

if (isset($_POST['grp']) && isset($_POST['user']))
{
  $grp = $_POST['grp'];
  $user = $_POST['user'];

  $sql = "INSERT INTO SYSGRPUSER (GRPID,SYSID,USERID) VALUES ('$grp','LEAVE','$user')";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
