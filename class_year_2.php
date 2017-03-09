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
    <form name="infoForm" action="class_index_2.php" method="POST">
    <div class="panel panel-primary">
        <div class="panel-heading" style="text-align:left">
            非請假調補課申請單填寫
        </div>
        <div class="panel-body panel-height">
                <div class="row">
                    <div align="center">
                        <div class='form-group'>
                            <div class="col-md-3">請選擇目前學年度:
                                <select class='form-control' style='width:auto; display: inline-block;' id="class_year" name='class_year'></select>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class="col-md-3">請選擇學期:
                                <select class='form-control' style='width:auto; display: inline-block;' id="class_acadm" name='class_acadm'>
                                    <option selected disabled class='text-hide'>請選擇學期</option>
                                    <option value='1'>1</option>
                                    <option value='2'>2</option>
                                    <option value='3'>3</option>
                                    <option value='4'>4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Modal Body -->
            <div class="modal-body">
              <div class="panel-body">
                <div class="panel panel-primary">
                <div class="panel-heading">
                    <font STYLE="font-family:微軟正黑體">老師本次已填寫之記錄</font><font color="red">(僅限於進修部課程)</font>
                </div>
                <div class="table-responsive">

                  <table class="table table-striped table-bordered dt-responsive nowrap" >




                    <div class="form-group">
                        <!--link rel="stylesheet" href="style.css"-->
                        <div id="class_content">
                        </div>
                    </div>

                </table>
                </div>

                </div>
                </div>


                <div class="panel-body" id="data_modify">
                  <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align:left">
                            國立彰化師範大學調補課申請單填寫作業(<font color="red">請每班各填寫一份，僅限於進修部課程</font>)
                        </div>
                        <div class="panel-body panel-height">
                            <table class="table table-bordered" >
                        <thead>
                                <tr>
                                    <td class="td1" align="center">請假期間</td>
                                    <td colspan="3" id="holiday_time"><font size="4">

                                    </td>
                                </tr>
                                </thead>

                        <thead>
                                <tr>
                                    <td class="td1" align="center">請假事由</td>
                                    <td colspan="3" id="holidy_mark"><font size="4">

                                    </td>
                                </tr>
                                </thead>

                                <thead>
                                <tr>
                                    <td class="col-md-2 td1" align="center">科目名稱</td>
                                    <td class="col-md-4">


                                                <select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='subject-name' name='subject-name' onChange=''></select>

                                                <font size='2' color='darkred'>(如果沒有選項，表示您學年與學期選錯了，請返回上一步) </font>


                                    </td>
                          <td class="col-md-2 td1" align="center">上課班別</td>
                                    <td class="col-md-4" id='class-name'>

                                    </td>
                                </tr>
                                </thead>

                        <thead>
                                <tr>
                                    <td class="col-md-2 td1" align="center">原上課日期</td>
                                    <td class="col-md-4">
                                        <div class='form-group'>
                              <div class='col-md-12'>
                                                <select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='ocyear' name='ocyear' onChange=''></select>年
                                                <select class='form-control' style='width:auto; display: inline-block;' id='ocmonth' name='ocmonth' onChange=''></select>月
                                                <select class='form-control' style='width:auto; display: inline-block;' id='ocday' name='ocday' onChange=''></select>日
                                            </div>
                                        </div>
                                    </td>

                          <td class="col-md-2 td1" align="center">原上課節次等</td>
                                        <td class="col-md-4" id="scr_period"></td>
                                </tr>
                                </thead>

                        <thead>
                                <tr>
                                    <td class="col-md-2 td1" align="center">調補課日期</td>
                                    <td class="col-md-4">
                                        <div class='form-group'>
                              <div class='col-md-12'>
                                                <select class='form-control' style='width:auto; display: inline-block;' data-style= 'btn-default'  id='ccyear' name='ccyear' onChange=''></select>年
                                                <select class='form-control' style='width:auto; display: inline-block;' name='ccmonth' id='ccmonth' onChange=''></select>月
                                                <select class='form-control' style='width:auto; display: inline-block;' id='ccday' name='ccday' onChange=''></select>日
                                            </div>
                                        </div>
                                    </td>

                          <td class="col-md-2 td1" align="center">補課節次</td>
                                        <td class="col-md-4" >第<input type="text" class="form-control" name="class_section2" id="class_section2" value="" size="25" maxlength="30" required>節</td>
                                </tr>
                                </thead>

                        <thead>
                                <tr>
                                    <td class="td1" align="center">補課教室</td>
                                    <td colspan="3"><input type="text" class="form-control" name="class_room" id="class_room" value="" size="25" maxlength="30" required><font size="4">

                                    </td>
                                </tr>
                                </thead>
                        <thead>
                                <tr>
                                    <td class="td1" align="center">備註</td>
                                    <td colspan="3"><input type="text" class="form-control" name="class_memo" id="class_memo" value="" size="25" maxlength="30" required><font size="4">

                                    </td>
                                </tr>
                        <tr>
                                    <td colspan="4" align="center">
                                        <button class="btn btn-primary" name="close" onclick='closeM()'>離開或被退重送</button>
                            <button class="btn btn-primary" name="check" onclick='CheckData()'>本班資料儲存</button>
                                    </td>
                                </tr>
                                </thead>





                            </table>
                        </div>
                    </form>
                    </center>
                  </div>
                  </div>


            </div>
            <!-- Modal Footer -->
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    關閉
                </button>
                <button id="btn-save" type="button" class="btn btn-primary">
                    儲存
                </button>
            </div> -->
            <!-- <div align="center">
                <button type="submit" class="btn btn-primary" name="check" >下一步</button>
            </div> -->
            <!-- onclick='timesum();' -->
            <div class="alert alert-warning">
              <i class="fa fa-warning">
                注意!
                <ol>
                  <li>
                    本功能限進修學院。
                  </li>
                  <li>
                    請選對學年度及學期別。
                  </li>
                </ol>
              </i>
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
