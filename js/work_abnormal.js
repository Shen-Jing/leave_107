var date = new Date();
var year = date.getFullYear() - 1911;
var ad_year = date.getFullYear();
var month = date.getMonth() + 1;
$( // 表示網頁完成後才會載入
    function() {
        var start_options = {
            defaultDate: new Date(),
            format: 'YYYY/MM/DD',
            ignoreReadonly: true,
            maxDate: new Date(new Date().setFullYear(new Date().getFullYear() + 1, 0, 1)),
            minDate: new Date(new Date().setFullYear(new Date().getFullYear() - 1, 0, 1)),
            tooltips: {
                clear: "清除所選",
                close: "關閉日曆",
                decrementHour: "減一小時",
                decrementMinute: "Decrement Minute",
                decrementSecond: "Decrement Second",
                incrementHour: "加一小時",
                incrementMinute: "Increment Minute",
                incrementSecond: "Increment Second",
                nextCentury: "下個世紀",
                nextDecade: "後十年",
                nextMonth: "下個月",
                nextYear: "下一年",
                pickHour: "Pick Hour",
                pickMinute: "Pick Minute",
                pickSecond: "Pick Second",
                prevCentury: "上個世紀",
                prevDecade: "前十年",
                prevMonth: "上個月",
                prevYear: "前一年",
                selectDecade: "選擇哪十年",
                selectMonth: "選擇月份",
                selectTime: "選擇時間",
                selectYear: "選擇年份",
                today: "今日日期",
            },
            locale: 'zh-tw',
        }

        $('#qry_ymd').datetimepicker(start_options);

        // 查詢單位若有改變也要query
        $('#qry_ymd').on('input', function() {
            CRUD(0);
            alert("dffd");
        });
    });

function CRUD(oper, empl_no, over_date) {
    $.ajax({
        url: 'ajax/work_abnormal_ajax.php',
        data: {
            oper: oper,
            select_date: $('#qry_ymd').val(),
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else {
                if (oper == "0") { //查詢
                    $('#_content').empty();
                    data_length = JData.EMPL_NAME.length;
                    if (data_length == 0) {
                        $('#_content').append("<tr><td colspan='6'>無記錄</td></tr>");
                    }
                    else {
                        $('#_content').empty();
                        for (var i = 0; i < data_length; i++) {
                            var memo = JData.MEMO[i],
                            rmemo = JData.RETURN_MEMO[i];
                            if (memo == '')  memo = '-';
                            if (rmemo == '') rmemo = '-';
                            var row = "<tr>";
                            // 5000865
                            row = row + "<td>" + JData.EMPL_NO[i] + "</td>";
                            // 姓名
                            row = row + "<td>" + JData.EMPL_NAME[i] + "</td>";
                            // 日期（1050330）
                            row = row + "<td>" + JData.DO_DAT[i] + "</td>";
                            // 時間（0833）
                            row = row + "<td>" + JData.DO_TIME[i] + "</td>";
                            // 異常原因（遲到刷卡）
                            row = row + "<td>" + memo + "</td>";
                            // 回覆原因（ - ）
                            row = row + "<td>" + rmemo + "</td>";
                            row = row + "</tr>";
                            $('#_content').append(row);
                        }
                    }
                }
            }
        },
        beforeSend: function() {
            $('#loading').show();
        },
        complete: function() {
            $('#loading').hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
        }
    });
}
