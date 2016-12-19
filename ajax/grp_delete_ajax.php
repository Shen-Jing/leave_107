<?
include '../inc/connect.php';

if (isset($_POST['id']))
{
  $id = $_POST['id'];

  $sql = "DELETE FROM SYSGRP WHERE SYSID='LEAVE' AND GRPID='$id'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
