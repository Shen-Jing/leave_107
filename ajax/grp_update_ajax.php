<?
include '../inc/connect.php';

if (isset($_POST['id']) && isset($_POST['name']))
{
  $id = $_POST['id'];
  $name = $_POST['name'];
  $sql = "UPDATE SYSGRP SET GRPNAME='$name' WHERE SYSID='LEAVE' AND GRPID='$id'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
?>
