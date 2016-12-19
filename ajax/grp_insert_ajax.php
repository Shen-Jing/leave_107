<?
include '../inc/connect.php';

if (isset($_POST['id']) && isset($_POST['name']))
{
  $id = $_POST['id'];
  $name = $_POST['name'];

  $sql = "INSERT INTO SYSGRP (GRPID,SYSID,GRPNAME) VALUES ('$id','LEAVE','$name')";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
