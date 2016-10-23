<? include("inc/header.php"); ?>

<? include("inc/navi.php"); ?>

<? include("inc/sidebar.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                 <? include ("inc/page-header.php"); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                
                                <p>請選擇查詢年度：</p>
                                <select name=p_menu>
                                <?
                                    $today  = getdate();
                                    $year   = $today["year"] - 1911;
                                    for ($j=95;$j<=$year+1;$j++)
                                        echo "<option value=".$j.">".$j."</option>";   
                                ?>   
                                </select>
                                
                                <H3>已取消假單</H3>
                                您目前無任何取消假單記錄。
                                <br><hr>
                                <H3>處理中假單</H3>您目前無任何請假記錄。
                                <br><hr>
                                <H3>未通過假單</H3>
                                您目前無任何記錄。
                                <br><hr>
                                <br>
                                
                                <H3>已核准假單</H3>
                                <table id="Btable" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr style="font-weight:bold">
                                            <th>姓名</th>
                                            <th>假別</th>
                                            <th>起始日</th>
                                            <th>終止日</th>
                                            <th>起始</th>
                                            <th>終止</th>
                                            <th>總時數</th>
                                            <th>代理簽核</th>
                                            <th>直屬簽核</th>
                                            <th>單位簽核</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

<? include("inc/footer.php"); ?>

<script>
$(document).ready(function() 
{
    //table <thead> is necessary.
    $('#Btable').DataTable({
        "scrollCollapse": true,
        "displayLength": 10,
        "paginate": true,
        "lengthChange": true,
        "processing": false,
        "serverSide": false,
        "ajax": {
            url: 'ajax/holiday_view_ajax.php',
            type: 'POST',
            dataType: 'json'
        },
        "columns": [
            { "name": "empl_chn_name" },
            { "name": "code_chn_item" },
            { "name": "povdateb" },
            { "name": "povdatee" },
            { "name": "povtimes" },
            { "name": "povtimeb" },
            { "name": "povtimee" },
            { "name": "agentsignd" },
            { "name": "onesignd" },
            { "name": "twosignd" }
        ]

    });
});
</script>