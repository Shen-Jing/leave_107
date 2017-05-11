<? include("inc/header.php"); ?>
    <? include("inc/navi.php"); ?>
        <? include("inc/sidebar.php"); ?>
<style>
  h3{
    font-family: "微軟正黑體";
  }
  li{
    font-family: "微軟正黑體";
  }
  table{
    font-family: "微軟正黑體";
  }

</style>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid" >
    <? include ("inc/page-header.php"); ?>
    <div class="panel panel-primary">
        <div class="panel-heading" style="text-align:left">
            非請假調補課申請單填寫(<font color="red">請每班各填寫一份，僅限於進修部課程</font>)
        </div>
        <div class="panel-body panel-height">
                <div class="row">
                    <div align="center">
                        <div class='form-group'>
                            <div class="col-md-4">請選擇年份:
                                <select class='form-control' style='width:auto; display: inline-block;' id="class_year" name='class_year'></select>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="panel-body">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <font STYLE="font-family:微軟正黑體">填寫記錄</font><font color="red">(僅限於進修部課程)</font>
                        </div>
                        <div class="table-responsive">
                                <div class="form-group">
                                    <div id="_content">
                                        <div class='table-responsive'>
                                            <table class="table table-striped table-condensed table-hover table-bordered nowrap" id="Btable" cellspacing="0">
                                                <thead>
                                                    <tr style="font-weight:bold">
                                                        <th style='text-align:center;'>調補課編號</th>
                                                        <th style='text-align:center;'>填寫日期</th>
                                                        <th style='text-align:center;'>查詢內容</th>
                                                        <th style='text-align:center;'>修改</th>
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
                </div>

            </div>

            <!-- 修改頁面 -->
            <div class="modal fade" id="ChangeModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                         <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <h4 class="modal-title" id="ModalLabel2">本次調補課填寫內容</h4>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div  id="class-modal">
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div class="panel-body" id="data_modify">
                                <div class="panel panel-primary">
                                    <div class="panel-heading" style="text-align:left">
                                            國立彰化師範大學調補課申請單填寫作業(<font color="red">請每班各填寫一份，僅限於進修部課程</font>)
                                    </div>
                                    <div class="panel-body panel-height">
                                        <div class='table-responsive'>
                                            <table class="table table-striped table-condensed table-hover table-bordered nowrap" id="Vtable" cellspacing="0">
                                                <thead>
                                                    <tr style="font-weight:bold">
                                                        <th style='text-align:center;'>上課班別</th>
                                                        <th style='text-align:center;'>開課代碼</th>
                                                        <th style='text-align:center;'>科目名稱</th>
                                                        <th style='text-align:center;'>原上課時間</th>
                                                        <th style='text-align:center;'>補課時間</th>
                                                        <th style='text-align:center;'>補課教室</th>
                                                        <th style='text-align:center;'>補課節次</th>
                                                        <th style='text-align:center;'>備註</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="view_data">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                關閉
                            </button>
                        </div>
                    </div>
                </div>
            </div>

          </div>
      </div>
    </form>
    </div>

  </div>
    </div>
      <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<? include("inc/footer.php"); ?>
