<? include("inc/header.php"); ?>

<? include("inc/navi.php"); ?>

<? include("inc/sidebar.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
            <? include ("inc/page-header.php"); ?>
			<!--
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                請選擇查詢年度
                            </div>
                            <div class="panel-body">
                                <select id=sel_years class="form-control" onChange=sel_years_onchange()>
                                <php
                                    $today  = getdate();
                                    $year   = $today["year"] - 1911;
                                    for ($j=95;$j<=$year+1;$j++)
                                        echo "<option value=".$j.">".$j."</option>";   
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
			-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                        <div class="panel-heading">查看及修改同仁假單</div>
                            <div class="panel-body">  
                                
                                <div>
                                    <H3 style="font-weight:bold">所有差假狀況</H3>
									
                                    <table id="oTable" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>單位</th>
												<th>姓名</th>
                                                <th>假別</th>
                                                <th>起始日</th>
                                                <th>終止日</th>
                                                <th>起始</th>
                                                <th>天/時</th>
                                                <th>填寫日期</th>
                                                <th>簽核結果</th>
                                                <th>目前位置</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

<? include("inc/footer.php"); ?>
