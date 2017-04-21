<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menuNav">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img src="images/NCUE_logo.png" width="38" height="38" style="display:inline"> 國立彰化師範大學 人事差假管理系統 </a>
            <!--menu toggle button -->
            <button id="menu-toggle" type="button" data-toggle="button" class="btn btn-default" style="margin-top:10px">
                <i class="fa fa-exchange fa-fw"></i>
            </button>
        </div>
        <!-- /.navbar-header -->
        <ul class="nav navbar-top-links navbar-right">
            <!-- /.dropdown 帳號-->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i><span style="font-size:13px">測試帳號</span><i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> 登出</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->

            <!-- /.dropdown 系統控制台-->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-sitemap fa-fw"></i><span style="font-size:13px">系統控制台</span><i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="pgm_node.php"><i class="fa fa-plus-square fa-fw"></i> 選單編輯介面</a>
                    </li>
                    <li><a href="grp_node.php"><i class="fa fa-plus-square fa-fw"></i> 群組編輯介面</a>
                    </li>
                    <li><a href="grp_pgm.php"><i class="fa fa-plus-square fa-fw"></i> 程式群組編輯介面</a>
                    </li>
                    <li><a href="grp_user.php"><i class="fa fa-plus-square fa-fw"></i> 用戶群組編輯介面</a>
                    </li>
                    <li><a href="temp.php"><i class="fa fa-plus-square fa-fw"></i> 模版編輯介面</a>
                    </li>
                </ul>
            </li>
            <!-- /.dropdown -->

            <!-- /.dropdown 注意事項-->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-info fa-fw"></i><span style="font-size:13px">注意事項</span><i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="logout.php"><i class="fa fa-warning fa-fw"></i> 新版注意事項</a>
                    </li>
                    <li><a href="logout.php"><i class="fa fa-question fa-fw"></i> 新進人員報到流程說明</a>
                    </li>
                    <li><a href="logout.php"><i class="fa fa-table fa-fw"></i> 線上差假系統功能表</a>
                    </li>
                </ul>
                <!-- /.dropdown-announcement -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->
