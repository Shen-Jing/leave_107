<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php");?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <? include ("inc/page-header.php"); ?>
                      <form class="form-horizontal" role="form" name="form1" id="form1" action="" method="post" target="right">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="message">
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        個人差假資料明細
                                    </div>
                                    <div class="panel-body">
                                      <div class="panel panel-success">
                                      <div class="panel-heading">
                                          <font STYLE="font-family:微軟正黑體">請選擇查詢年度</font>
                                      </div>
                                      <div class="panel-body">
                                        <select class="form-control" name="p_menu" id="detail_year">
                                    
                                        </select>
                                      </div>
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
                                      <table id="Table_Detail" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                      <tr style="font-weight:bold">
                                          <th>日期</th>
                                          <th>假別天數</th>
                                          <th>日期</th>
                                          <th>假別天數</th>
                                          <th>日期</th>
                                          <th>假別天數</th>
                                      </tr>
                                      <?
                                      $sql = "SELECT count(*) count
                                      		FROM holidayform
                                      		where POCARD  in ('$userid','$empl_no')
                                      		and CONDITION<>'-1' and condition<>'2'
                                      		and POVDATEB>='$begin_date'
                                      		and POVDATEE<='$end_date'";
                                      $data = $db -> query_array($sql);
                                      $count=sizeof($data);
                                      if($count>0){
                                        echo "succes";
                                      $SQLStr ="SELECT h.POCARD,substr(pc.CODE_CHN_ITEM,1,2)  code_chn_item,h.POVDATEB,h.POVHOURS,h.POVDAYS,h.CONDITION,
                                      h.povtimeb,h.povtimee
                                      FROM holidayform h,psqcode pc
                                      where h.POCARD in ('$userid','$empl_no')
                                      and h.CONDITION<>'-1' and condition<>'2'
                                      and POVDATEB>='$begin_date'
                                      and POVDATEE<='$end_date'
                                      and pc.CODE_KIND='0302'
                                      and pc.CODE_FIELD=h.POVTYPE
                                        union
                                        SELECT h.POCARD,pc.CODE_CHN_ITEM,h.POVDATEB,h.POVHOURS,h.POVDAYS,h.CONDITION,
                                        h.povtimeb,h.povtimee
                                      FROM holidayform h,psqcode pc
                                      where h.POCARD='$userid'
                                      and h.CONDITION<>'-1' and condition<>'2'
                                      and POVDATEB<='$begin_date'
                                      and POVDATEE>='$begin_date'
                                      and pc.CODE_KIND='0302'
                                      and pc.CODE_FIELD=h.POVTYPE
                                        union
                                        SELECT h.POCARD,pc.CODE_CHN_ITEM,h.POVDATEB,h.POVHOURS,h.POVDAYS,h.CONDITION,
                                        h.povtimeb,h.povtimee
                                      FROM holidayform h,psqcode pc
                                      where h.POCARD='$userid'
                                      and h.CONDITION<>'-1' and condition<>'2'
                                      and POVDATEB<='$end_date'
                                      and POVDATEE>='$end_date'
                                      and pc.CODE_KIND='0302'
                                      and pc.CODE_FIELD=h.POVTYPE
                                      order by POCARD,POVDATEB";
                                      $row = $db -> query_array($SQLStr);
                                      $col=1;
                                      echo "ok 1";
                                      echo $row['POCARD'][0];
                                      for($i = 0; $i < sizeof($row['POCARD']); ++$i){
                                        $pocard = $row['POCARD'][$i];
                                    		$povtype= $row['CODE_CHN_ITEM'][$i];
                                    		$povdateB = $row['POVDATEB'][$i];
                                    		$povhours = $row['POVHOURS'][$i];
                                    		$povdays  = $row['POVDAYS'][$i];
                                    		$condition  = $row['CONDITION'][$i];
                                        echo "ok";
                                        if($col==1)
                                    			echo "<tr>";  //�X�t�B�����B�ư� �T�ӬO�@�C

                                    		$pohdaye=0;
                                    		$pohoure=0;

                                            if ($pohdaye=='') $pohdaye=0;
                                            if ($pohoure=='') $pohoure=0;
                                            if ($condition=='1')
                                    			echo "<th>".$povdateB."</th><th>".$povtype." ".$povdays."日".$povhours."時"."</th>";
                                    		else
                                    			echo "<th><font color=\"red\">".$povdateB."</font></th><th>".$povtype." ".$povdays."日".$povhours."時"."</th>";

                                    		$col++;
                                    		if($col==4)
                                    		{
                                    			$col=1;
                                    			echo "</tr>";
                                    		}
                                      }
                                      }
                                      ?>

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
                      </form>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
            <? include("inc/footer.php"); ?>
