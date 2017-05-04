$( // 表示網頁完成後才會載入
    function() {
        var start_options = {
            defaultDate: new Date(),
            format: '',
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
        // deep copy，否則tot_options改時間格式會動到start_options
        var end_options = $.extend(true, {}, start_options),
        tot_options = $.extend(true, {}, start_options);
        end_options.useCurrent = false;

        start_options.format = end_options.format = "YYYY/MM";
        tot_options.format = "YYYY";
        tot_options.maxDate = new Date(new Date().setFullYear(new Date().getFullYear(), 11, 1));

        // 差假明細開始年月、結束年月
        $('#start_ym').datetimepicker(start_options);
        $('#end_ym').datetimepicker(end_options);
        // 差假統計年份
        $('#qry_year').datetimepicker(tot_options);
        $("#start_ym").on("dp.change", function (e) {
            // end date的最小為start date所選
            $('#end_ym').data("DateTimePicker").minDate(e.date);
            // 將end date的initial date同步為start date所選
            $('#end_ym').data("DateTimePicker").date(e.date);
        });

        // 預設一開始顯示當年的1月到12月資料
        $('#start_ym').data("DateTimePicker").date(new Date(new Date().setFullYear(new Date().getFullYear(), 0, 1)));
        $('#end_ym').data("DateTimePicker").date(new Date(new Date().setFullYear(new Date().getFullYear(), 11, 1)));

        detail_table =
        $('#detail_table').DataTable({
            "responsive": true,
            "scrollCollapse": true,
            "displayLength": 10,
            "paginate": true,
            "lengthChange": true,
            "processing": false,
            "serverSide": false,
            "ajax": {
                url: 'ajax/p_search_detail_idx_ajax.php',
                type: 'POST',
                data: function (d) {
                    d.oper = "detail",
                    d.empl_no = $('#qry_empl option:selected').val(),
                    d.empl_name = $('#qry_empl option:selected').text(),
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

        tot_ed_table =
        $('#tot_ed_table').DataTable({
            "responsive": true,
            "scrollCollapse": true,
            "displayLength": 10,
            "paginate": true,
            "lengthChange": true,
            "processing": false,
            "serverSide": false,
            "ajax": {
                url: 'ajax/p_search_detail_idx_ajax.php',
                type: 'POST',
                data: function (d) {
                    d.oper = "tot",
                    d.table_cat = "ed",
                    d.empl_no = $('#qry_empl option:selected').val(),
                    d.empl_name = $('#qry_empl option:selected').text(),
                    d.tot_year = $('#qry_year').val();
                },
                dataType: 'json'
            },
            "columns": [
              { "name": "vtype" },
              { "name": "pohdaye" },
              { "name": "pohoure" },
            ]
        });

        tot_ing_table =
        $('#tot_ing_table').DataTable({
            "responsive": true,
            "scrollCollapse": true,
            "displayLength": 10,
            "paginate": true,
            "lengthChange": true,
            "processing": false,
            "serverSide": false,
            "ajax": {
                url: 'ajax/p_search_detail_idx_ajax.php',
                type: 'POST',
                data: function (d) {
                    d.oper = "tot",
                    d.table_cat = "ing",
                    d.empl_no = $('#qry_empl option:selected').val(),
                    d.empl_name = $('#qry_empl option:selected').text(),
                    d.tot_year = $('#qry_year').val();
                },
                dataType: 'json'
            },
            "columns": [
              { "name": "vtype" },
              { "name": "pohdaye" },
              { "name": "pohoure" },
            ]
        });

        // 明細的開始、結束年月變動時
        $('#start_ym, #end_ym').on('dp.change', function() {
            detail_table.ajax.reload();
        });

        // 統計的年份變動時
        $('#qry_year').on('dp.change', function() {
            tot_ed_table.ajax.reload();
            tot_ing_table.ajax.reload();
        });

        // 查詢單位
        $.ajax({
            url: 'ajax/p_search_detail_idx_ajax.php',
            data: { oper: 'qry_dept' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇單位</option>";
                $('#qry_dept').append(row0);
                for (var i = 0; i < JData.DEPT_NO.length; i++) {
                    var row = "<option value=" + JData.DEPT_NO[i] + ">" + JData.DEPT_FULL_NAME[i] + "</option>";
                    $('#qry_dept').append(row);
                }
                $('#qry_dept').val("M80");
                CRUD(0);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }
        });

        $('#qry_dept').change( // 選擇系所後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0); //query
                }
            }
        );

        $('#qry_empl').change( // 選擇不同人員系所後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    // 查詢明細
                    detail_table.ajax.reload();
                    tot_ed_table.ajax.reload();
                    tot_ing_table.ajax.reload();
                }
            }
        );
    }
);

function CRUD(oper) {
    $.ajax({
        url: 'ajax/p_search_detail_idx_ajax.php',
        data: {
            oper: oper,
            dept_id: $('#qry_dept').val(),
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                //toastr["error"](JData.error_message);
                message(JData.error_message, "danger", 5000);
            else {
                if (oper == "0") { // 根據單位查詢人員
                    $('#qry_empl').empty();
                    if (JData.EMPL_NO.length == 0){
                        $('#qry_empl').append("<option>該單位無人員資料</option>");
                    }
                    else {
                        var row0 = "<option selected disabled class='text-hide'>請選擇人員</option>";
                        $('#qry_empl').append(row0);
                        for (var i = 0; i < JData.EMPL_NO.length; i++) {
                            var row = "<option value=" + JData.EMPL_NO[i] + ">" + JData.EMPL_CHN_NAME[i] + "</option>";
                            $('#qry_empl').append(row);
                        }
                    }
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
        }
    });
}
