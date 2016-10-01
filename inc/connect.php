<?php
    include_once(dirname(__FILE__) . "/config.php"); //設定BASE_PATH及資料庫帳密   
    include_once(dirname(__FILE__) . "/oracle.php");    
    $db = new ORACLE($db_user, $db_passwd, $db_server);

    # 使用說明
    # 所有引入此ＰＨＰ的頁面皆可以使用此物件導向的資料庫存取、此物件已設定自動轉換編碼：顯示編碼＝>ＵＴＦ-8 ; 資料庫資料編碼＝>BIG5
    # 方法範例如下
    #
    # 資料新增、修改、刪除：
    # $str = "UPDATE ......" "DELETE ......" "INSERT INTO ......"
    # $db -> query($str);
    #
    # 資料抓取
    # $str = "SELECT * FROM SIDEBAR";
    # $sidebar = $db -> query_array($str); // 可以做到多重結果抓取
    #
    # $str = "SELECT * FROM SCHOOL order by ID DESC";
    # $school = $db -> query_array($str); // 第二份資料
    #
    # for($i = 0; $i < sizeof($sidebar['NAME']); ++$i)
    # {
    #    echo $sidebar['NAME'][$i];
    #    echo "<br>";
    #
    #    echo $school['ID'][$i % sizeof($school['ID'])];
    #    echo "<br>";
    # }
    #
?>
