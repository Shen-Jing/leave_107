<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php");
      include("inc/connect.php");?>
        <script language="JavaScript" type="text/JavaScript">
        function p_menu_onChange()
        {
            var yycho ;
	          var yyval;
            var mmcho ;
	          var mmval;
            yycho =document.form1.p_menu.selectedIndex;
	          yyval=document.form1.p_menu.options[yycho].value;
            self.location='search_detail.php?yval=' + yyval;
          }
          </script>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        個人差假資料明細
                                    </div>
                                    <?

   	                                 $today = getdate();
	                                    $year = $today["year"] - 1911;

                                      if (!IsSet($_SESSION['yy'])){
                                        $end_year = $year;
                                        $_SESSION['yy']=$year;
                                      }
                                      else{ //page updated then restore,because $end_year must be reflashed ;
                                        $end_year=$_SESSION['yy'];
                                        $_POST['p_menu']=$_GET['yval'];
                                      }

	                                     if ($_POST['p_menu']=='') $_POST['p_menu']=$year;

	                                      if($_POST['p_menu']<100)
	                                       $_POST['p_menu']="0".$_POST['p_menu'];

                                         $begin_date=$_POST['p_menu'].'0101';
	                                        $end_date=$_POST['p_menu'].'1231';

	                                         $vtype =array('01','03','04','05','06','07','08','09','21');



                                           $userid =$_SESSION['empl_no'];
                                           $name=$_SESSION['empl_name'];

                                           $empl_no="";

                                    ?>
                                    <div class="panel-body">
                                      <div class="panel-heading">
                                          <font STYLE="font-family:微軟正黑體">請選擇查詢年度</font>
                                      </div>
                                      <div class="panel-body">
                                          <select class="form-control" name="p_menu" id="qry_year" data-width="auto">
                                            <?
                                            if (!IsSet($_POST['p_menu']))
	                                           $_POST['p_menu']=$year;

                                             for ($j=95;$j<=$end_year+1;$j++)
                                             {
	                                              if ($j==$_POST['p_menu'])
	                                               echo "<option value=".$j." selected>".$j."</option>";
	                                                else
                                                  echo "<option value=".$j.">".$j."</option>";
                                                }

                                                ?>
                                          </select>
                                      </div>
                                      <div class="table-responsive">
                                        <table class="table table-striped table-bordered" width="100%" cellspacing="0">

                                        <tr style="font-weight:bold">
                                            <th>日期: <?=$begin_date?>~<?=$end_date?></th>
                                        </tr>
                                    </table>
                                        <table class="table table-striped table-bordered" width="100%" cellspacing="0">

                                    <thead>

                                        <tr style="font-weight:bold">
                                            <th>姓名</th>
                                            <th><? echo $name; ?></th>
                                            <th>員工編號</th>
                                            <th><? echo $userid; ?></th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                      <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                                      <tr style="font-weight:bold">
                                          <th>日期</th>
                                          <th>假別天數</th>
                                          <th>日期</th>
                                          <th>假別天數</th>
                                          <th>日期</th>
                                          <th>假別天數</th>
                                      </tr>


                                      </table>
                                    </tbody>
                                  </table>
                                      </div>

                                    </div>
                                    <!--<div class="panel-footer">
                                    </div>-->
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
