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
    "lengthChange": true,
    "processing": false,
    "serverSide": false,
    "ajax": {
        url: 'ajax/all_holiday_query_ajax.php',
        data: { oper: 0, flag:flagval,tyearval:tyval,tmonthval:tmval,tdayval:tdval,syearval:syval,smonthval:smval,sdayval:sdval },
        type: 'POST',
        dataType: 'json'
    },
    "columns": [
        { "name": "DEPT_NAME" },
        { "name": "TITLE_NAME" },
        { "name": "EMPL_NAME" }
    ]

});
}
