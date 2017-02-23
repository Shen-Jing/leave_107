$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });

        // 查詢單位
        $.ajax({
            url: 'ajax/view_call_off_ajax.php',
            data: {
                oper: 'qry_data',
                serialno: $('#hide-serial').text()
            },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
              // 請假資料 (message li)
              var html_str = "<li>" + JData.holli_data.name + "(" + JData.holli_data.title_name + ")" + "</li>";
              if (JData.holli_data.POVDATEB > JData.holli_data.ndate)
          		    html_str += "請假日期：" + JData.holli_data.POVDATEB + "至" + JData.holli_data.POVDATEE + "<span style=\"color: red\">(時間未到)</span><br>";
              else
                  html_str += "請假日期：" + JData.holli_data.POVDATEB + "至" + JData.holli_data.POVDATEE + "<br>";
          	  html_str += "請假時間：" + JData.holli_data.POVTIMEB + "時至" + JData.holli_data.POVTIMEE + "時";

              $('#message > div > ul').html(html_str);

              // 刷卡記錄
              $('#_card-data').empty();
              data_length = JData.card_data.DO_DAT.length;
              if (data_length == 0) {
                  $('#_card-data').append("<tr><td colspan='3'>無資料</td></tr>");
              }
              else {
                  for (var i = 0; i < data_length; i++) {
                      var row = "<tr>";
                      // 日期（1050302）
                      row = row + "<td>" + JData.card_data.DO_DAT[i] + "</td>";
                      // 刷卡時間（0801）
                      row = row + "<td>" + JData.card_data.DO_TIME[i] + "</td>";
                      // 假別（加班)
                      row = row + "<td>" + JData.card_data.MEMO[i] + "</td>";
                      row = row + "</tr>";
                      $('#_card-data').append(row);
                  }
              }
            },
            error: function(xhr, ajaxOptions, thrownError) {
            }
        });
    }
);
