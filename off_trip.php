<?php
   session_start();
   $conn=OCILOGON("exampg","exampg_vm","//120.107.186.109/project") or die("failed");
     $userid =$_SESSION["empl_no"];
?>
<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
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
                                        取消國民旅遊
                                    </div>

                                    <div class="panel-body">
                                      <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>姓名</th>
                                            <th>假別</th>
                                            <th>起始日期</th>
                                            <th>中止日期</th>
                                            <th>起始時間</th>
                                            <th>中止時間</th>
                                            <th>總時間</th>
                                            <th>職務代理人</th>
                                            <th>不刷旅遊卡了</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php

                                      $sql = "SELECT count(*) count
                                    				FROM holidayform
                                    				where POCARD='$userid'
                                    				and  trip=2
                                    				and condition <> -1";
                                    	$stmt=ociparse($conn,$sql);
                                    	ociexecute($stmt,OCI_DEFAULT);
                                    	if (OCIFETCH($stmt))
                                    	   $count=ociresult($stmt,"COUNT");
                                     if ($count==0)
                                      	echo "";
                                     else{
                                    	$SQLStr ="SELECT empl_chn_name,h.POCARD,pc.CODE_CHN_ITEM,h.POVDATEB,h.POVDATEE,
                                    	h.POVHOURS,h.POVTIMEB,h.POVTIMEE,h.POVDAYS,h.ABROAD,h.AGENTNO,
                                        h.serialno,h.CURENTSTATUS
                                    	FROM psfempl p,holidayform h,psqcode pc
                                    	where h.POCARD='$userid'
                                    	and condition <> -1
                                    	and  trip=2
                                    	and  p.empl_no=h.pocard
                                    	and  pc.CODE_KIND='0302'
                                    	and  pc.CODE_FIELD=h.POVTYPE
                                    	order by h.POCARD,h.POVDATEB,h.POVHOURS";

                                         $res = OCIParse($conn,$SQLStr);//liru update
                                         OCIExecute($res);

                                      while (OCIFetchInto($res, $row, OCI_ASSOC)){//liru update

                                    		$poname = $row['EMPL_CHN_NAME'];
                                    		$pocard = $row['POCARD'];
                                    		$povtype= $row['CODE_CHN_ITEM'];
                                    		$povdateB = $row['POVDATEB'];
                                    		$povdatee = $row['POVDATEE'];
                                    		$povhours = $row['POVHOURS'];
                                    		$povdays  = $row['POVDAYS'];
                                    		$povtimeb = $row['POVTIMEB'];  //liru add
                                    		$povtimee  = $row['POVTIMEE'];  //liru add
                                    		$abroad  = $row['ABROAD'];
                                    		$agentno  = $row['AGENTNO'];
                                    		$serialno = $row['SERIALNO'];

                                    		$SQLStr2 = "SELECT EMPL_CHN_NAME FROM PSFEMPL where EMPL_NO='$agentno' ";
                                    		$stmt=ociparse($conn,$SQLStr2);
                                    		ociexecute($stmt,OCI_DEFAULT);
                                    		$agentname='';
                                    		if (OCIFETCH($stmt))
                                    		   $agentname=ociresult($stmt,"EMPL_CHN_NAME");

                                    		//...........................................................
                                    		//10201 add
                                    				if (strlen($povtimeb) > 2)
                                    					$povtimeb=substr($povtimeb,0,2).":".substr($povtimeb,2,2);

                                    				if (strlen($povtimee) > 2)
                                    					$povtimee=substr($povtimee,0,2).":".substr($povtimee,2,2);
                                    		//...........................................................

                                    		echo "<tr><th>" ;
                                    		echo $poname ;
                                    		echo "</th><th>" ;
                                    		echo $povtype ;
                                    		echo "</th><th>" ;
                                    		echo $povdateB ;
                                    		echo "</th><th>" ;
                                    		echo $povdatee ;
                                    		echo "</th><th>" ;
                                    		echo $povtimeb ;
                                    		echo "</th><th>" ;
                                    		echo $povtimee ;
                                    		echo "</th><th>" ;
                                    		echo $povdays."天".$povhours."時" ;
                                    		echo "</th><th>" ;
                                    		echo $agentname;
                                    		echo "</th><th>" ;
                                    		echo "<button type=\"button\" class=\"btn btn-default\">取消</button>" ;
                                    		echo "</th></tr>";
                                    	}
                                      ?>


                                    </tbody>
                                  </table>
                                <? } ?>
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
