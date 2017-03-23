// 選擇條件預先帶出現在時間用
var date = new Date();
var year = date.getFullYear() - 1911;
var ad_year = date.getFullYear();
var month = date.getMonth() + 1;
$( // 表示網頁完成後才會載入
  function() {
      $("body").tooltip({
          selector: "[title]"
      });
      $.ajax({
          url: 'ajax/chief_print_ajax.php',
          data: {
            oper: 'qry_year',
          },
          type: 'POST',
          dataType: "json",
          success: function(JData) {
              var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
              $('#qry_year').append(row0);
              for (var i = JData.END_YEAR - 3; i <= parseInt( JData.END_YEAR ) + 1 ; i++) {
                  var row = "<option value=" + i + ">" + i + " </option>";
                  $('#qry_year').append(row);
              }
              // 預先帶出當年
              $('#qry_year').val("" + year);

              var row0 = "<option selected disabled class='text-hide'>請選擇月份</option>";
              $('#qry_month').append(row0);
              for (var i = 1; i <= 12 ; i++) {
                  var row = "<option value=" + i + ">" + i + " </option>";
                  $('#qry_month').append(row);
              }
              // 預先帶出當月
              $('#qry_month').val("" + month);

              CRUD(0); //首次進入頁面query
          },
          error: function(xhr, ajaxOptions, thrownError) {
          }
      });

      // 選擇後，即可顯示資料
      $('#qry_year, #qry_month').change(
          function(e) {
              // 但是必須年月皆有選取值
              if ($(':selected', this).val() != null && $('#qry_year').val() != null){
                  CRUD(0);
              }
          }
      )
});

function CRUD(oper) {
    $.ajax({
        url: 'ajax/chief_print_ajax.php',
        data: {
            oper: oper,
            year: $('#qry_year').val(),
            month: $('#qry_month').val(),
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else {
                if (oper == "0") { //查詢
                    $('#_content').empty();
                    data_length = JData.EMPL_CHN_NAME.length;
                    if (data_length == 0) {
                        $('#_content').append("<tr><td colspan='7'>目前尚無資料</td></tr>");
                    }
                    else {
                        $('#_content').empty();
                        for (var i = 0; i < data_length; i++) {
                            var row = "<tr>";
                            // 中文名字（李_朗）
                            row = row + "<td>" + JData.EMPL_CHN_NAME[i] + "</td>";
                            // 假別`（出差）
                            row = row + "<td>" + JData.CODE_CHN_ITEM[i] + "</td>";
                            // 起始日期（1050131）
                            row = row + "<td>" + JData.POVDATEB[i] + "</td>";
                            // 結束日期（1050204）
                            row = row + "<td>" + JData.POVDATEE[i] + "</td>";
                            // 總天數/時數（5/7）
                            row = row + "<td>" + JData.POVDAYS[i] + "天" + JData.POVHOURS[i] + "時</td>";
                            // 差假事由
                            row = row + "<td>" + JData.POREMARK[i] + "</td>";
                            // 補印
                            row = row + "<td><button type='button' class='btn btn-default' name='print' onclick='printPDF(" + JData.SERIALNO[i] +");' title='補印'>補印</button></td>" ;
                            row = row + "</tr>";
                            $('#_content').append(row);
                        }
                    }
                }
            }
        },
        beforeSend: function() {
            $('#loading-data').show();
        },
        complete: function() {
            $('#loading-data').hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
        }
    });
}

function printPDF(serialno){
    $('#form1').attr("action", "rpt/chief_print_list.php?serialno=" + serialno)
        .attr("method", "post").attr("target", "_blank");
    $('#form1').submit();
}
