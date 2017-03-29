<?
$id = $_SESSION['_ID'];
$sql ="select distinct p.pgmid,pgmname,pgmlevel,pgmsort,pgmurl,pgmtype ,parent_folder,folder_img
    from syspgm p, sysgrppgm gp, sysgrpuser gu
    where p.sysid='LEAVE' and gp.sysid='LEAVE' and gu.sysid='LEAVE' and userid='$id' and gu.grpid=gp.grpid and gp.pgmid=p.pgmid order by p.pgmid,pgmsort";
$d = $db -> query_array($sql);
?>
﻿        <!-- Sidebar wrapper over SB Admin 2 sidebar -->
        <div id="sidebar-wrapper">
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav collapse navbar-collapse" id="menuNav">
                    <ul class="nav" id="side-menu">
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
                              echo "<ul class='nav nav-second-level collapse in'>\r\n";
                          }else
                            echo "<a href='$pgmurl'$new><i class='fa $folder_img fa-fw'></i>$pgmname</a>\r\n</li>\r\n";

                          $parent_previous = $parent_folder;
                          if($pgmurl == "")
                            if (strcmp($d['PARENT_FOLDER'][$i+1], $pgmid) == 0)
                              $parent_previous = $pgmid;
                            else
                              echo "</ul></li>\r\n";
                        }
                      ?>
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
