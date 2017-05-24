$( // 表示網頁完成後才會載入
    function() {
        var start_options = {
            defaultDate: new Date(),
            format: 'YYYY/MM',
            ignoreReadonly: true,
            maxDate: new Date(new Date().setFullYear(new Date().getFullYear(), 11, 1)),
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

        // 條件年月
        $('#qry_ym').datetimepicker(start_options);

        Btable =
        $('#Btable').DataTable({
            "responsive": true,
            "scrollCollapse": true,
            "displayLength": 25,
            "paginate": true,
            "lengthChange": true,
            "processing": false,
            "serverSide": false,
            "ajax": {
                url: 'ajax/teacher_ajax.php',
                type: 'POST',
                data: function (d) {
                    d.oper = "teacher",
                    d.qry_date = $('#qry_ym').val()
                },
                dataType: 'json'
            },
            "columns": [
              { "name": "EMPL_CHN_NAME" },
              { "name": "CODE_CHN_ITEM" },
              { "name": "ABROAD" },
              { "name": "POVDATEB" },
              { "name": "POVDATEE" },
              { "name": "POVTIMEB" },
              { "name": "POVTIMEE" },
              { "name": "POVTIME" },
              { "name": "AGENTNAME" },
            ]
        });

        // 統計的年份變動時
        $('#qry_ym').on('dp.change', function() {
            Btable.ajax.reload();
        });

    }
);
