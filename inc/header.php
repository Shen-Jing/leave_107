<?
include_once(dirname(__FILE__) . "/check.php");
$now = basename($_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>國立彰化師範大學 人事差假管理系統</title>


    <link href="css/core.css" rel="stylesheet">

    <link href="css/vendor.css" rel="stylesheet">
    <!-- bootstrap validator -->
    <link href="bower_components/bootstrapvalidator/dist/css/bootstrapValidator.min.css" rel="stylesheet">


	<?php if( file_exists("css/".basename($_SERVER['PHP_SELF'], ".php").".css") ): ?>
    <link href="css/<?=basename($_SERVER['PHP_SELF'], ".php")?>.css" rel="stylesheet"/>
	<?php endif; ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<? @include_once( (dirname(__DIR__)) . "/page_editor/components/new_thread_css.php" ); ?>

</head>

<body>
