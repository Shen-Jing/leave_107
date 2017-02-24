<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
            <?
            $userid = $_SESSION['empl_no'];

            $sql = "SELECT substr(to_char(SYSDATE, 'yyyymmdd'), 1, 4) - '1911' end_year,
            substr(to_char(SYSDATE, 'yyyymmdd'), 5, 2) end_month FROM dual";
            $y = $db -> query_array($sql);

            $sql = "SELECT dept_no, dept_full_name
				    FROM stfdept
				    WHERE dept_no in (SELECT crjb_depart
						    					  	FROM   psfcrjb
						    					  	WHERE  crjb_empl_no='$userid'
												      AND    crjb_quit_date is null
						    					  	UNION
						    					  	SELECT dept_no
						    					  	FROM   dept_boss
						    					  	WHERE  boss_no='$userid'
						    					  	AND    dept_no='O20')";
            $d = $db -> query_array($sql);
            ?>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        查詢條件
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-4">
                                            <!-- 年份 -->
                                            <select id="qry_year" name="qry_year" class="form-control">
                                                <option selected disabled class='text-hide'>請選擇年份</option>
                                                <?
                                                for ($i = $y['END_YEAR'][0]-2; $i <= $y['END_YEAR'][0]+1; $i++) {
                                                    echo "<option value=$i>$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <!-- 月份 -->
                                            <select id="qry_month" name="qry_month" class="form-control">
                                                <option selected disabled class='text-hide'>請選擇月份</option>
                                                <?
                                                for ($i = 1; $i <= 12; $i++) {
                                                    echo "<option value=$i>$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <!-- 單位 -->
                                            <select id="qry_dept" name="qry_dept" class="form-control">
                                                <!--<option selected disabled class='text-hide'>請選擇單位</option>-->
                                                <?
                                                var_dump ($d['DEPT_NO']);
                                                for ($i=0; $i < count($d['DEPT_NO']); $i++) {
                                                    echo "<option value=".$d['DEPT_NO'][$i].">".$d['DEPT_FULL_NAME'][$i]."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        差假狀況
                                    </div>
                                    <div class="panel-body" id="container">
                                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>單位</th>
                                                    <th>姓名</th>
                                                    <th>假別</th>
                                                    <th>起始日</th>
                                                    <th>終止日</th>
                                                    <th>起始</th>
                                                    <th>終止</th>
                                                    <th>總時數</th>
                                                    <th>代理簽</th>
                                                    <th>直屬簽</th>
                                                    <th>單位簽</th>
                                                    <th>代理人</th>
                                                    <th>處理狀況</th>
                                                </tr>
                                            </thead>
                                            <tbody id="_content">
                                            </tbody>
                                        </table>
                                        <div id="loading" class="text-center" style="display:none">
                                            <img src="images/loading.gif">
                                        </div>
                                    </div>
                                    <!--<div class="panel-footer">
                                    </div>-->
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
