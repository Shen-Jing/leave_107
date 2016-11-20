<?
$sql ="select distinct pgmid,pgmname,pgmlevel,pgmsort,pgmurl,pgmtype ,parent_folder,folder_img
    from syspgm
    where sysid='LEAVE'
    order by pgmid,pgmsort";
$d = $db -> query_array($sql);
?>
﻿        <!-- Sidebar wrapper over SB Admin 2 sidebar -->
        <div id="sidebar-wrapper">
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
<<<<<<< HEAD
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
                            <a href='update.php'><span class='masked'>修改假單</span></a>
                          </li>
                          <li>
                            <a href='off_trip.php'><span class='masked'>取消國民旅遊</span></a>
                          </li>
                          <li>
                            <a href='holiday_view.php'><span class='masked'>個人差假狀況(上)</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>個人差假統計資料</span></a>
                          </li>
                          <li>
                            <a href='search_detail.php'><span class='masked'>個人差假明細資料</span></a>
                          </li>
                          <li>
                            <a href='search_card.php'><span class='masked'>上下班刷卡資料</span></a>
                          </li>
                          <li>
                            <a href='overtime.php'><span class='masked'>加班申請作業</span></a>
                          </li>
                          <li>
                            <a href='overtime_query.php'><span class='masked'>加班記錄查詢</span></a>
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
                            <a href='overtime_check.php'><span class='masked'>加班審核</span></a>
                          </li>
                          <li>
                            <a href='#'><span class='masked'>加班已審核</span></a>
                          </li>
                          <li>
                            <a href='p_search_overtime_idx.php'><span class='masked'>加班資料修改</span></a>
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
=======
                      <?
                        $parent_previous = "";
                        for ($i=0; $i < sizeof($d['PGMNAME']); $i++) {
                          $new = '';
                          if ($d['PGMTYPE'][$i])
                            $new = " target='_blank'";

                          $pgmid = $d['PGMID'][$i] ;
                          $pgmurl = $d['PGMURL'][$i] ;
                          $pgmname =  $d['PGMNAME'][$i] ;
                          $parent_folder = $d['PARENT_FOLDER'][$i] ; //父節點
                          $folder_img = $d['FOLDER_IMG'][$i] ; //節點圖示

                          if($i>0 && $parent_folder<>$parent_previous)//換根節點
                          {
                            $len = strlen($parent_previous) - strlen($parent_folder) ;
                            if ($len==5)//ex. A07 -- >"" 相差2層
                              echo "</ul></li>\r\n</ul></li>\r\n</li>";
                            if ($len==3)//ex. A07 -- >"" 相差2層
                              echo "</ul></li>\r\n</ul></li>\r\n";
                            else //ex. A07-->A
                              echo "</ul></li>\r\n";
                          }

                          echo "<li>\r\n";
                          if ($pgmurl == "")
                          {
                            echo "<a href='#'><i class='fa $folder_img fa-fw'></i><span class='masked'>$pgmname<span class='fa arrow'></span></span></a>\r\n";
                            if (strlen($pgmid) == 3)
                              echo "<ul class='nav nav-third-level'>\r\n";
                            else
                              echo "<ul class='nav nav-second-level'>\r\n";
                          }else
                            echo "<a href='$pgmurl'$new><i class='fa $folder_img fa-fw'></i><span class='masked'>$pgmname</span></a>\r\n</li>\r\n";

                          $parent_previous = $parent_folder;
                          if($pgmurl == "")
                            if (strcmp($d['PARENT_FOLDER'][$i+1], $pgmid) == 0)
                              $parent_previous = $pgmid;
                            else
                              echo "</ul></li>\r\n";
                        }
                      ?>
>>>>>>> 8d59b7651843cfbcf88a3ce5fb7a7b8962e82dfd
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
