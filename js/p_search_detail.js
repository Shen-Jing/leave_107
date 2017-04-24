$(document).ready(function()
{
    var start_options = {
        defaultDate: new Date(),
        format: 'YYYY/MM',
        ignoreReadonly: true,
        maxDate: new Date(new Date().setFullYear(new Date().getFullYear() + 1, 0, 1)),
        minDate: new Date(new Date().setFullYear(new Date().getFullYear() - 3, 0, 1)),
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

    // 差假明細開始年月、結束年月
    $('#start_ym').datetimepicker(start_options);
    $('#end_ym').datetimepicker(end_options);
    $("#start_ym").on("dp.change", function (e) {
        // end date的最小為start date所選
        $('#end_ym').data("DateTimePicker").minDate(e.date);
        // 將end date的initial date同步為start date所選
        $('#end_ym').data("DateTimePicker").date(e.date);
    });

    table =
    $('#Btable').DataTable({
        "responsive": true,
        "scrollCollapse": true,
        "displayLength": 10,
        "paginate": true,
        "lengthChange": true,
        "processing": false,
        "serverSide": false,
        "ajax": {
            url: 'ajax/p_search_detail_ajax.php',
            type: 'POST',
            data: function (d) {
                d.oper = 0,
                d.empl_no = $('#empl_no').text(),
                d.empl_name = $('#empl_name').text(),
                d.start_date = $('#start_ym').val(),
                d.end_date = $('#end_ym').val();
            },
            dataType: 'json'
        },
        "columns": [
          { "name": "povtype" },
    	    { "name": "povdateB" },
    	    { "name": "povdateE" },
    	    { "name": "povtimeB" },
    	    { "name": "povtimeE" },
    	    { "name": "eplace" },
    	    { "name": "poremark" }
        ]
    });

    // 開始、結束年月變動時
    $('#start_ym, #end_ym').on('dp.change', function() {
        table.ajax.reload();
    });
});
