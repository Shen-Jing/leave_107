<?php
include '../inc/check.php';

// 查詢退回原因
if(!isset($_POST['oper']))
  exit;

if ($_POST['oper'] == 0) {
  $sql = "SELECT * FROM ps_menu
          WHERE prgid='leave' ORDER BY to_number(sysid)";
  $row = $db -> query_array($sql);
  $a['data'] = "";

  $a['data'][] = array(
    "<input value='' type='number' id='sysid-1' class='form-control' placeholder='序號（不在新增選項內）' readonly>",
    "<input value='' type='text' id='prgname-1' class='form-control' size='100' placeholder='新增差假原因'>",
    "<button type='button' class='btn-success' name='modify' id='modify' title='修改儲存' onclick='CRUD(1, -1)' title='新增儲存'><i class='fa fa-save'></i></button>"
  );
  for($i = 0; $i < count($row['SYSID']); ++$i){
    $sysid = $row['SYSID'][$i];
    $prgname = $row['PRGNAME'][$i];

    $a['data'][] = array(
      "<input value='$sysid' type='number' id='sysid$sysid' class='form-control'>",
      "<input value='$prgname' type='text' id='prgname$sysid' class='form-control' size='100'>",
      "<button type='button' class='btn-success' name='modify' id='modify' title='修改儲存' onclick='CRUD(2, $sysid)' title='儲存修改'><i class='fa fa-save'></i></button>" .
      "<button type='button' class='btn-danger' name='delete' onclick='CRUD(3, $sysid)' title='刪除'><i class='fa fa-times'></i></button>"
    );
  }
  echo json_encode($a);
  exit;
}
$result = "";
// 新增
if ($_POST['oper'] == 1) {
  // 欲新增的原因
  $reason = $_POST['prgname'];

  // 取得目前序號最大多少，也就是此序號該到插入哪裡
  $sql = "SELECT MAX(to_number(sysid)) sysid
          FROM ps_menu
          WHERE prgid='leave'";
  $data = $db -> query_array($sql);
  $sysid = 0;
  if (count($data['SYSID']) != 0){
    $sysid = $data['SYSID'][0];
    $sysid++;
  }

  // 存入資料庫
  $sql = "INSERT INTO ps_menu(PRGID,PRGNAME,SYSID) VALUES ('leave', '$reason', '$sysid')";
  $data = $db -> query($sql);
  // 若存入沒錯誤
  if (empty($data['message'])){
    $message = array("error_code" => $data['code'],
      "error_message" => $data['message'],
      "result" => $result
    );
  }
  else{
    $result = "資料修改有問題";
    $message = array("error_code" => $data['code'],
      "error_message" => $data['message'],
      "result" => $result
    );
  }

  echo json_encode($message);
  exit;
}

// 修改儲存
if ($_POST['oper'] == 2) {
  $old_id = $_POST['old_id'];
  // 欲修改成的id, 原因
  $new_id = $_POST['new_id'];
  $reason = $_POST['prgname'];
  // 修改時先刪除
	$sql = "DELETE FROM ps_menu
        WHERE prgid = 'leave'
        AND   sysid = '$old_id'";
  $data = $db -> query($sql);

  // 若刪除沒錯誤
  if (!empty($data['message'])){
    $result = "資料修改有問題";
    $message = array("error_code" => $data['code'],
      "error_message" => $data['message'],
      "result" => $result
    );
    echo json_encode($message);
    exit;
  }

  // 存入資料庫
  $sql = "INSERT INTO ps_menu(PRGID,PRGNAME,SYSID) VALUES ('leave', '$reason', '$new_id')";
  $data = $db -> query($sql);
  // 若存入沒錯誤
  if (empty($data['message'])){
    $message = array("error_code" => $data['code'],
      "error_message" => $data['message'],
      "result" => $result
    );
  }
  else{
    $result = "資料修改有問題";
    $message = array("error_code" => $data['code'],
      "error_message" => $data['message'],
      "result" => $result
    );
  }

  echo json_encode($message);
  exit;
}

// 刪除
if ($_POST['oper'] == 3) {
  $sysid = $_POST['old_id'];

  $sql = "DELETE FROM ps_menu
          WHERE prgid = 'leave'
          AND   sysid = '$sysid'";
  $data = $db -> query($sql);

  // 若刪除沒錯誤
  if (empty($data['message'])){
    $message = array("error_code" => $data['code'],
      "error_message" => $data['message'],
      "result" => $result
    );
  }
  else{
    $result = "資料刪除有問題";
    $message = array("error_code" => $data['code'],
      "error_message" => $data['message'],
      "result" => $result
    );
  }

  echo json_encode($message);
  exit;
}

?>
