            <?                        
                        $sql="select distinct c.pgmid,c.pgmname,c.pgmlevel,c.pgmsort,c.pgmurl,c.pgmtype ,(select pgmid from syspgm where pgmurl='$now') aid
                                from sysgrpuser a ,sysgrppgm b ,syspgm c 
                                where a.userid='".$_SESSION['_ID'] ."' and a.grpid = b.grpid and b.pgmid=c.pgmid   order by pgmid,pgmsort";                       
                        $d = $db -> query_array($sql);

                        $sql="select * from school";                               //echo "str=" . $str ;
                        $e = $db -> query_array($sql);
            ?>
            <!-- Sidebar wrapper over SB Admin 2 sidebar -->
        <div id="sidebar-wrapper">

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="form-group">
                                <label></label>
                                <select name="school_sidebar" id="school_sidebar" class="form-control" style="color:#0059b3;font-size:17px">
                                    <option selected disabled class="text-hide">請選擇招生類別</option>
                                <?
                                  for ($i=0; $i < sizeof($e['ID']); $i++) {  
                                    $school_id = $e['ID'][$i];
                                    $school_name = $e['NAME'][$i];
                                ?>
                                  <option value="<?= $school_id ?>" <?=($school_id==$_SESSION['school_id'])?"selected":"" ?>>
                                <? echo $school_name; ?>
                                  </option>;
                                <?
                                  }
                                ?>
                                </select>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <?  
                        for ($i=0; $i < sizeof($d['PGMNAME']); $i++) { 
                            if ($d['PGMTYPE'][$i]) //開新視窗
                                $new = "target='_blank'";                            
                            else
                                $new = '';
                           // if($d['PGMURL'][$i]=="") $level_flag++;
                            if($d['PGMURL'][$i]=="" && ++$level_flag>1 )
                                echo "</ul></li>";
                        ?>
                        <li>
                            <a href="<?=$d['PGMURL'][$i]?>" <?=$new?>><i class="fa <?=($d['PGMURL'][$i]=="")?"fa-tachometer":""?>  fa-fw"></i>
                            <!-- Things to be hidden on toggled are encolsed with masked -->
                                <span class="masked">
                                <?=$d['PGMNAME'][$i]?><?=($d['PGMURL'][$i]=="")?"<span class=\"fa arrow\"></span>":""?></span></a>
                            <?=($d['PGMURL'][$i]=="")?"<ul class=\"nav nav-second-level\">":""?>
                        <?=($d['PGMURL'][$i]!="")?"</li>":""?>
                        <? } ?>                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </div>
        </nav>