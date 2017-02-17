var arr_dept;
var dept_to_short;
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
            url: 'ajax/all_sec_ajax.php',
            data: {
              oper: 'qry_ye_mon_dept'
            },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                var end_year = parseInt(JData.qry_year.END_YEAR[0]);
                $('#qry_year').append(row0);
                for (var i = end_year - 1; i <= end_year + 1; i++) {
                    var row = "<option value=" + i + ">" + i + "</option>";
                    $('#qry_year').append(row);
                }
                // 預先帶出當年
                $('#qry_year').val("" + year);

                var row0 = "<option selected disabled class='text-hide'>請選擇月份</option>";
                $('#qry_month').append(row0);
                for (var i = 1; i <= 12; i++) {
                    var row = "<option value=" + i + ">" + i + "</option>";
                    $('#qry_month').append(row);
                }
                // 預先帶出當年
                $('#qry_month').val("" + month);

                var row0 = "<option selected disabled class='text-hide'>請選擇單位</option>";
                var row_length = JData.qry_dept.DEPT_NO.length;
                $('#qry_dept').append(row0);
                for (var i = 0; i < row_length; i++) {
                    var row = "<option value=" + JData.qry_dept.DEPT_NO[i] + ">" + JData.qry_dept.DEPT_FULL_NAME[i] + "</option>";
                    $('#qry_dept').append(row);
                }

                // 部門縮寫的key value array
                dept_to_short = JData.qry_short_dept;
            },
            error: function(xhr, ajaxOptions, thrownError) {
            }
        });

        // 改變滑鼠游標樣式
        $('#container tbody').on('mouseover', 'tr', function() {
            this.style.cursor = 'pointer';
        });

        // 選擇年份後，即可顯示資料
        $('#qry_year').change(
            function(e) {
                if ($(':selected', this).val() !== ''){
                    CRUD(0);
                }
            }
        )

        // 查詢月份若有改變也要query
        $('#qry_month').change(
            function(e) {
                // 但是必須有選取年份
                if ($(':selected', this).val() != null && $('#qry_year').val() != null){
                    CRUD(0);
                }
            }
        )

        // 查詢單位若有改變也要query
        $('#qry_dept').change(
            function(e) {
                // 但是必須有選取年份
                if ($(':selected', this).val() != null && $('#qry_year').val() != null){
                    CRUD(0);
                }
            }
        )
    });

function CRUD(oper, empl_no, over_date) {
    $.ajax({
        url: 'ajax/all_sec_ajax.php',
        data: {
            oper: oper,
            year: $('#qry_year').val(),
            month: $('#qry_month').val(),
            dept: $('#qry_dept').val()
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
                        $('#_content').append("<tr><td colspan='10'>目前尚無資料</td></tr>");
                    }
                    else {
                        $('#_content').empty();
                        for (var i = 0; i < data_length; i++) {
                            var dept = JData.DEPART[i];
                            var dept_short = dept_to_short[dept];
                            var row = "<tr>";
                            // 縮寫單位（系統開發組）
                            row = row + "<td>" + dept_short + "</td>";
                            // 姓名（李_朗）
                            row = row + "<td>" + JData.EMPL_CHN_NAME[i] + "</td>";
                            // 假別`（出差）
                            row = row + "<td>" + JData.CODE_CHN_ITEM[i] + "</td>";
                            // 起始日（1050131）
                            row = row + "<td>" + JData.POVDATEB[i] + "</td>";
                            // 終止日（1050204）
                            row = row + "<td>" + JData.POVDATEE[i] + "</td>";
                            // 起始（8）
                            if (JData.POVTIMEB[i].length > 2){
                              JData.POVTIMEB[i] =
                              JData.POVTIMEB[i].substr(0, 2) + ":" +
                              JData.POVTIMEB[i].substr(2);
                            }
                            row = row + "<td>" + JData.POVTIMEB[i] + "</td>";
                            // 天/時（5/7）
                            row = row + "<td>" + JData.POVDAYS[i] + "/" + JData.POVHOURS[i] + "</td>";
                            // 人事承辦員（1041218）
                            row = row + "<td>" + JData.PERONE_SIGND[i] + "</td>";
                            // 人事主任（1041221）
                            row = row + "<td>" + JData.PERTWO_SIGND[i] + "</td>";
                            // 秘書簽（1041222）
                            row = row + "<td>" + JData.SECONE_SIGND[i] + "</td>";
                            row = row + "</tr>";
                            $('#_content').append(row);

                            // ONESIGND, TWOSIGND等等欄位似乎沒有使用到，但舊頁面有
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
