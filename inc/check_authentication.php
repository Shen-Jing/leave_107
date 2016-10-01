<?
    if (!isset($_SESSION['_ID'])) {
        $_SESSION['logout'] = 1;
        header('Content-Type: text/html; charset=utf-8');
        echo "<script>top.location.href='login.php';</script>";
        exit;
    }
?>