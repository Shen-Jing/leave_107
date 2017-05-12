<?
include '../inc/connect.php';

// 防止惡意連入
if (isset($_POST['oper']) && isset($_POST['id']) && isset($_POST['name']))
{
  $id = $_POST['id'];
  $name = $_POST['name'];
  // 新增
  if ($_POST['oper'] == 1)
    $sql = "INSERT INTO SYSGRP (GRPID,SYSID,GRPNAME) VALUES ('$id','LEAVE','$name')";
  // 更新
  else if ($_POST['oper'] == 2)
    $sql = "UPDATE SYSGRP SET GRPNAME='$name' WHERE SYSID='LEAVE' AND GRPID='$id'";
  // 刪除
  else
    $sql = "DELETE FROM SYSGRP WHERE SYSID='LEAVE' AND GRPID='$id'";
  echo $sql."\r\n";
  $db -> query_array ($sql);
}
