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

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap select-picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <link href="css/sbadmin2-sidebar-toggle.css" rel="stylesheet" type="text/css">

    <!-- bootstrap datepicker -->
    <link href="bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     <link href="css/toastr.min.css" rel="stylesheet"/>
     <link href="css/dataTables.bootstrap.min.css" rel="stylesheet"/>
     <link href="css/dataTables_sorticon.css" rel="stylesheet"/>
     <link href="css/buttons.dataTables.min.css" rel="stylesheet"/>
     <link href="css/responsive.dataTables.min.css" rel="stylesheet"/>
     <link href="css/ui.jqgrid-bootstrap.css" rel="stylesheet"/>

    <!-- Custom Fonts -->
    <link href="css/ncue.css" rel="stylesheet" type="text/css">

    <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
	
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
