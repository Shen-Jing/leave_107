<?php
//putenv("PATH=C:\\oracle\\ora92\\bin;c:\\instantclient;c:\\xampp\\php\\ext");
//putenv("LD_LIBRARY_PATH=c:\xampp\php\ext");
   //putenv("NLS_LANG=TRADITIONAL CHINESE_TAIWAN.ZHT16BIG5");
   $con=oci_connect("exampg","exampg_vm","//120.107.186./exampg") or die("連線失敗");
   echo "OK2!";
?>
