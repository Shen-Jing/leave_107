        <!-- Sidebar wrapper over SB Admin 2 sidebar -->
        <div id="sidebar-wrapper">
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                      <li>
                        <a href='#'><i class='fa fa-edit fa-fw'></i><span class='masked'>簽核<span class='fa arrow'></span></span></a>
                      </li>
                      <li>
                        <a href='#'><i class='fa fa-suitcase fa-fw'></i><span class='masked'>請假管理<span class='fa arrow'></span></span></a>
                        <ul class="nav nav-second-level">
                          <li>
                            <a href='holiday_form.php'><span class='masked'>行政人員差假</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>單位差假狀況</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>修改假單</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>取消國民旅遊</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>個人差假統計資料</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>個人差假明細資料</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>上下班刷卡資料</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>加班申請作業</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>加班記錄查詢</span></a>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <a href='#'><i class='fa fa-car fa-fw'></i><span class='masked'>差勤管理<span class='fa arrow'></span></span></a>
                        <ul class="nav nav-second-level">
                          <li>
                            <a href='#'><span class='masked'>國民旅遊名單</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>加班審核</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>加班已審核</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>加班資料修改</span></a>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <a href='#'><i class='fa fa-building fa-fw'></i><span class='masked'>制度管理<span class='fa arrow'></span></span></a>
                        <ul class="nav nav-second-level">
                          <li>
                            <a href='#'><span class='masked'>有課老師請假名單</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>承辦員調補課簽核</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>所有調補課簽核狀況</span></a>
                          </li>
                        </ul>
                      </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </div>
        </nav>
        <?

        function ShowSidebar($node)
        {
            echo "<li>";
            if (hasChild)
                echo "<a href='#'><i class='fa $folder_img fa-fw'></i><span class='masked'>$pgmname<span class='fa arrow'></span></span></a>";
            else
                echo "<a href=''></a>";

            echo "</li>";

        }



        ?>
