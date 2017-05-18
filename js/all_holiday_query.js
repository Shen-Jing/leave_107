$(
    //table <thead> is necessary.
    function (){
      var row0 = "<option selected disabled class='text-hide'>請選擇單位</option>";
      $('#id_flag').append(row0);
      var row = "<option value=" + 0 + " selected>" + "正式職員" + "</option>";
      $('#id_flag').append(row);
      var row = "<option value=" + 1 + " selected>" + "專任教師" + "</option>";
      $('#id_flag').append(row);
      var row = "<option value=" + 3 + " selected>" + "約用助理(即單工)" + "</option>";
      $('#id_flag').append(row);
      var row = "<option value=" + 5 + " selected>" + "專案助理" + "</option>";
      $('#id_flag').append(row);
      var row = "<option value=" + 6 + " selected>" + "專案行政助理" + "</option>";
      $('#id_flag').append(row);
      var row = "<option value=" + 7 + " selected>" + "約用行政助理" + "</option>";
      $('#id_flag').append(row);
      var row = "<option value=" + 9 + " selected>" + "一級主管" + "</option>";
      $('#id_flag').append(row);

      $ .ajax({
          url: 'ajax/all_holiday_query_ajax.php',
          data: { oper: 'qry_year' },
          type: 'POST',
          dataType: "json",
          success: function(JData) {
              row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
              $ ('#tyear').append(row0);
              for (var i = JData["year"] - 3 ; i <= JData["year"] ; i++)
              {
                  if (i == JData["year"])
                      row = "<option value=" +i+ " selected>" + i + "</option>";
                  else
                      row = "<option value=" +i+ ">" + i + " </option>";
                  $ ('#tyear').append(row);
              }

              row0 = "<$option selected disabled class='text-hide'>請選擇月份</option>";
              $ ('#tmonth').append(row0);
              for (var i = 1; i <= 12 ; i++)
              {
                  if (i == JData["month"] )
                      row = "<option value=" +i+ " selected>" + i + "</option>";
                  else
                      row = "<option value=" +i+ ">" + i + " </option>";
                  $ ('#tmonth').append(row);
              }

              row0 = "<$option selected disabled class='text-hide'>請選擇日期</option>";
              $ ('#tday').append(row0);
              for (var i = 1; i <= 31 ; i++)
              {
                  if (i == JData["day"] )
                      row = "<option value=" +i+ " selected>" + i + "</option>";
                  else
                      row = "<option value=" +i+ ">" + i + " </option>";
                  $ ('#tday').append(row);
              }
              ///////////////////////////////////////////////////////////////////////////////////////////////////////


              row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
              $ ('#syear').append(row0);
              for (var i = JData["year"] - 3 ; i <= JData["year"] ; i++)
              {
                  if (i == JData["year"])
                      row = "<option value=" +i+ " selected>" + i + "</option>";
                  else
                      row = "<option value=" +i+ ">" + i + " </option>";
                  $ ('#syear').append(row);
              }

              row0 = "<$option selected disabled class='text-hide'>請選擇月份</option>";
              $ ('#smonth').append(row0);
              for (var i = 1; i <= 12 ; i++)
              {
                  if (i == JData["month"] )
                      row = "<option value=" +i+ " selected>" + i + "</option>";
                  else
                      row = "<option value=" +i+ ">" + i + " </option>";
                  $ ('#smonth').append(row);
              }

              row0 = "<$option selected disabled class='text-hide'>請選擇日期</option>";
              $ ('#sday').append(row0);
              for (var i = 1; i <= 31 ; i++)
              {
                  if (i == JData["day"] )
                      row = "<option value=" +i+ " selected>" + i + "</option>";
                  else
                      row = "<option value=" +i+ ">" + i + " </option>";
                  $ ('#sday').append(row);
              }
          },
          error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
      });

      $ ('#tyear,#tmonth,#tday,#syear,#smonth,#sday,#id_flag').change( // 抓取區域選完的資料
          function(e) {
              if ($ (':selected', this).val() !== '')
              {
                  CRUD(0); //query
              }
          }
      );


  }
);
function CRUD(oper, id) {
  id = id || ''; //預設值
  var flagval,tyval, tmval, tdval,syval, smval, sdval;
  alert("In Func!");
  flagval=$ ('#id_flag').val();
  tyval=$ ('#tyear').val();
  tmval=$ ('#tmonth').val();
  tdval=$('#tday').val();
  syval=$ ('#syear').val();
  smval=$ ('#smonth').val();
  sdval=$('#sday').val();

  $('#Btable').DataTable({
    "scrollCollapse": true,
    "displayLength": 10,
    "paginate": true,
    "destroy": true,
    "lengthChange": true,
    "processing": false,
    "serverSide": false,
    "dom": 'Bfrtip',
    "ajax": {
        url: 'ajax/all_holiday_query_ajax.php',
        data: { oper: 0, flag:flagval,tyearval:tyval,tmonthval:tmval,tdayval:tdval,syearval:syval,smonthval:smval,sdayval:sdval },
        type: 'POST',
        dataType: 'json'
    },
    "columns": [
        { "name": "DEPT_NAME" },
        { "name": "TITLE_NAME" },
        { "name": "EMPL_NAME" },
        { "name": "D1" },
        { "name": "D2" },
        { "name": "D3" },
        { "name": "D4" },
        { "name": "D5" },
        { "name": "D6" },
        { "name": "D7" },
        { "name": "D8" },
        { "name": "D9" },
        { "name": "D10" },
        { "name": "D11" },
        { "name": "D12" },
        { "name": "D13" },
        { "name": "D14" },
        { "name": "D15" },
        { "name": "D16" },
        { "name": "D17" }
    ],
    "buttons": [
      'excel'
    ]

});

/*<thead>
<tr style="font-weight:bold">
    <th>單位</th>
    <th>職稱</th>
    <th>姓名</th>
    <th>出差</th>
    <th>公假(02)</th>
    <th>公假(03)</th>
    <th>生理假</th>
    <th>家庭照顧假</th>
    <th>延長病假</th>
    <th>事假</th>
    <th>病假</th>
    <th>休假(公)</th>
    <th>婚假</th>
    <th>婉假</th>
    <th>喪假</th>
    <th>加班補休</th>
    <th>暑休</th>
    <th>寒休</th>
    <th>特休(勞)</th>
    <th>其他</th>
</tr>
</thead>

</table>*/

/*$.ajax({
    url: 'ajax/all_holiday_query_ajax.php',
    data: {oper: 0, flag:flagval,tyearval:tyval,tmonthval:tmval,tdayval:tdval,syearval:syval,smonthval:smval,sdayval:sdval},
    type: 'POST',
    dataType: "json",
    success: function(JData) {
      $('#Btable').empty();
      if (JData.error_code)
          toastr["error"](JData.error_message);
      else{

        var row0 ="";
        row0 = row0 + "<table  class='table table-striped table-bordered' width='100%'' cellspacing='0'><tbody><tr>";
        row0 = row0 + "<td class='td1' style='text-align:center;'>單位</td>";
        row0 = row0 + "<td class='td1' style='text-align:center;'>假別</td>";
        row0 = row0 + "<td class='td1' style='text-align:center;'>職稱</td></tr>";


        for(var i=0;i<JData.DEPT_NAME;i++){

          row0 = row0 + "<tr><td  style='text-align:center;'>" ;
          row0 = row0 + JData.DEPT_NAME[i];
          row0 = row0 + "</td><td  style='text-align:center;'>" ;
          row0 = row0 + JData.TITLE_NAME[i];
          row0 = row0 + "</td><td  style='text-align:center;'>" ;
          row0 = row0 + JData.EMPL_NAME[i];
          row0 = row0 + "</td></tr>";




        }
        row0 = row0 + "</tbody></table>";
        $('#Btable').append(row0);

    }

    },
    error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
});*/
}
