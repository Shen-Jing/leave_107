$( // 表示網頁完成後才會載入
    function ()
    {

        $("body").tooltip({
            selector: "[title]"
        });

        var start_options = {
            defaultDate: new Date(),
            format: 'YYYY/MM/DD',
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
        var end_options = start_options;
        end_options.useCurrent = false;
        // clock_options.format = 'YYYY/MM/DD HH時';

        // 請假開始日期、結束日期

        $('#begin_time').datetimepicker(start_options);
        $('#signed_date').datetimepicker(start_options);
        $('#end_time').datetimepicker(end_options);
        $("#begin_time").on("dp.change", function (e) {
            // end date的最小為start date所選
            $('#end_time').data("DateTimePicker").minDate(e.date);
            // 將end date的initial date同步為start date所選
            $('#end_time').data("DateTimePicker").date(e.date);
        });

        // $("holiday").bootstrapValidator({
        //     feedbackIcons: {
        //         valid: 'fa fa-check',
        //         invalid: 'fa fa-times',
        //         validating: 'fa fa-refresh'
        //     },
        //     // submitButtons: 'button[type="button"]',
        //     // excluded: [':not(:visible)'],
        //     fields: {
        //         btime: {
        //             trigger: "change",
        //             validators: {
        //                 notEmpty: {
        //                     message: '請選擇開始加班刷卡時間'
        //                 }
        //             }
        //         },
        //         etime: {
        //             trigger: "change",
        //             validators: {
        //                 notEmpty: {
        //                     message: '請選擇結束加班刷卡時間'
        //                 }
        //             }
        //         },
        //         begin_time: {
        //             trigger: "change",
        //             validators: {
        //                 notEmpty: {
        //                     message: '請假日期不可空白'
        //                 },
        //                 date: {
        //                     format: 'YYYY/MM/DD',
        //                     message: '不正確的日期格式！'
        //                 }
        //             }
        //         },
        //         end_time: {
        //             trigger: "change",
        //             validators: {
        //                 notEmpty: {
        //                     message: '請假日期不可空白'
        //                 },
        //                 date: {
        //                     format: 'YYYY/MM/DD',
        //                     message: '不正確的日期格式！'
        //                 }
        //             }
        //         },
        //         reason: {
        //             trigger: "blur",
        //             validators: {
        //                 notEmpty: {
        //                     message: '請輸入加班簽呈文號或加班原因'
        //                 },
        //             }
        //         },
        //     }
        // });

        $.ajax({
            url: 'ajax/overtime_ajax.php',
            data: { oper: 'qry_first' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {

                $('#empl_no').append(JData["empl_no"]);
                $('#empl_name').append(JData["empl_name"]);
                $('#dname').append(JData["dname"]);
                $('#tname').append(JData["tname"]);

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $.ajax({
            url: 'ajax/overtime_ajax.php',
            data: { oper: 'btime' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0="<option value=''>請選擇</option><option value='0800'>08:00</option>";

                for(var i=0 ; i < JData.DO_TIME2.length ; i++)
                {
                    var do_time2 = JData.DO_TIME2[i];
                    var do_time = JData.DO_TIME[i];

                    row0 = row0 + "<option value=" + do_time + ">" + do_time2 + "</option>";
                }

                row0 = row0 + "<option value='1300'>13:00</option>";
                row0 = row0 + "<option value='1700'>17:00</option>";

                $('#btime').append(row0);

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $.ajax({
            url: 'ajax/overtime_ajax.php',
            data: { oper: 'btime_cn' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {

                var row = "";
                var cn = JData.COUNT;

                for (var i = 1 ; i <= 30 ; i++)
                {
                    if (i < 10)
                        if (cn > 0)
                            row = row + "<option value='160" + i + "'>16:0" + i + "</option>";
                        else
                            row = row + "<option value='170" + i + "'>17:0" + i + "</option>";
                    else
                        if ( cn > 0)
                            row = row + "<option value='16" + i + "'>16:" + i + "</option>";
                        else
                            row = row + "<option value='17" + i + "'>17:" + i + "</option>";
                }

                $('#btime').append(row);

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $.ajax({
            url: 'ajax/overtime_ajax.php',
            data: { oper: 'etime' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option value=''>請選擇</option><option value='1200'>12:00</option><option value='1700'>17:00</option>";

                for(var i=0 ; i < JData.DO_TIME2.length ; i++)
                {
                    var do_time2 = JData.DO_TIME2[i];
                    var do_time = JData.DO_TIME[i];

                    row0 = row0 + "<option value=" + do_time + ">" + do_time2 + "</option>";
                }

                $('#etime').append(row0);

            },
            error: function(xhr, ajaxOptions, thrownError) {/*console.log(xhr.responseText);alert(xhr.responseText);*/}
        });

    }
);


function timesum()
{
    var signed_date, begin_time, end_time;
    var btval, etval, reval;

    signed_date = $('#signed_date').val();
    begin_time = $('#begin_time').val();
    end_time = $('#end_time').val();

    btval = $('#btime').val();

    etval = $('#etime').val();

    var reason = $('#reason').val();

    $.ajax({
        url: 'ajax/overtime_ajax.php',
        data:{  oper: 'timesum' , signed_date: signed_date, begin_time: begin_time,
                end_time: end_time,
                btime: btval, etime: etval, reason: reason
            },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else
            {
                alert(JData);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}