<?php
    session_start();
    include_once(dirname(__FILE__) . "/connect.php");

    //檢查認證
      if (!isset($_SESSION['_ID'])) {
          $_SESSION['logout'] = 1;
          header('Content-Type: text/html; charset=utf-8');
          $url = BASE_URL . "login.php" ;
          echo "<script>top.location.href='$url';</script>";
          exit;
      }

      //檢查授權
      $now = basename($_SERVER['REQUEST_URI']); //aaa.bbb.ccc/xxx ==> xxx
      // $now = str_replace($base_url , "", $_SERVER['REQUEST_URI']);
      $sql ="SELECT DISTINCT pgmname,pgmurl
          FROM syspgm
          WHERE sysid='LEAVE'
          AND syspgm.pgmurl LIKE '%$now'";
      $d = $db -> query_array($sql);
      $_SESSION["pgmname"] = $d['PGMNAME'][0];

      // $d = $db -> query_array($sql);
      // if(sizeof($d['USERID'])==0 && $now !="index.php"){
      //     header('Content-Type: text/html; charset=utf-8');
      //     $url = BASE_URL . "login.php" ;
      //     echo "<script>alert('您未被授權!');top.location.href='$url';</script>";
      //     exit;
      // }
      //
      // //檢查招生類別
      // if( (strlen($_SESSION['school_id'])!=1  || strlen($_SESSION['year'])!=3 ) && $now !="index.php"){
      //     header('Content-Type: text/html; charset=utf-8');
      //     $url = BASE_URL . "index.php" ;
      //     echo "<script>alert('請先選擇招生類別!!');top.location.href='$url';</script>";
      //     exit;
      // }

  ?>
