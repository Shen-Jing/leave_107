var arr_dept;
$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/overtime_check_ajax.php',
            data: {
              oper: 'qry_year_and_dept'
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

                var row0 = "<option selected disabled class='text-hide'>請選擇單位</option>";
                var row_length = JData.qry_dept.DEPT_NO.length;
                $('#qry_dept').append(row0);
                for (var i = 0; i < row_length; i++) {
                    var row = "<option value=" + JData.qry_dept.DEPT_NO[i] + ">" + JData.qry_dept.DEPT_FULL_NAME[i] + "</option>";
                    $('#qry_dept').append(row);
                }
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

function view_card(empl_no, over_date) {
  alert(empl_no);
    // 取得詳細刷卡記錄
    $.ajax({
        url: 'ajax/view_card.php',
        data: {
            empl_no: empl_no,
            over_date: over_date
        },
        type: 'POST',
        dataType: "text",
        success: function(JData) {
            $("#modal-detail .modal-title").html("刷卡記錄");
            $("#modal-detail .modal-body").html(JData);
            $("#modal-detail").modal("show"); //弹出框show
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
        }
    });
}

function CRUD(oper, empl_no, over_date) {
    if (oper == 3)
        if (!confirm("是否確定要刪除?")) return false;
    if (oper == 4)
        if (!confirm("要通過審核嗎?")) return false;
    $.ajax({
        url: 'ajax/overtime_check_ajax.php',
        data: {
            oper: oper,
            empl_no: empl_no,
            over_date: over_date,
            nouse_time: $('#nouse_time' + over_date).val(),
            year: $('#qry_year').val(),
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
                    data_length = JData.DRAW_DATE.length;
                    if (data_length == 0) {
                        $('#_content').append("<tr><td colspan='9'>目前尚無資料</td></tr>");

                    }
                    else {
                        var day_list = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
                        for (var i = 0; i < JData.DRAW_DATE.length; i++) {
                            var date = JData.OVER_DATE[i].substr(0, 3) + '/' + JData.OVER_DATE[i].substr(3, 2) + '/' + JData.OVER_DATE[i].substr(5, 2);
                            var day = new Date(date).getDay();
                            var row = "<tr>";
                            // 單位（系統開發組）
                            row = row + "<td>" + JData.DEPART[i] + "</td>";
                            // 姓名（李_朗）
                            row = row + "<td>" + JData.EMPL_CHN_NAME[i] + JData.EMPL_NO[i] + "</td>";
                            // 提簽日期（1050101)
                            row = row + "<td>" + JData.DRAW_DATE[i] + "</td>";
                            // 文號（原因：1234/值班/免）
                            row = row + "<td>" + JData.REASON[i] + "</td>";
                            // 加班日（日期 星期X）
                            row = row + "<td>" + JData.OVER_DATE[i] + day_list[day] + "</td>";
                            // 開始時間（1700）
                            row = row + "<td>" + JData.DO_TIME_1[i] + "</td>";
                            // 結束時間（2035）
                            row = row + "<td>" + JData.DO_TIME_2[i] + "</td>";
                            // 時數（0）
                            row = row + "<td><input value='" + JData.NOUSE_TIME[i] + "' type='number' min='0' max='99' id='nouse_time" + JData.OVER_DATE[i] + "' class='form-control'></td>";
                            // 功能區
                            // 查看刷卡記錄（info button）
                            row = row + "<td><button type='button' class='btn-info' name='card' title='刷卡記錄' onclick='view_card(\"" + JData.EMPL_NO[i] + "\", \"" + JData.OVER_DATE[i] + "\")'><i class='fa fa-info'></i> </button>";
                            // 審核加班
                            row = row + "    <button type='button' class='btn-warning' name='check' title='審核加班' onclick='CRUD(4, \"" + JData.EMPL_NO[i] + "\", \"" + JData.OVER_DATE[i] + "\")'><i class='fa fa-check'></i> </button>";
                            // 修改儲存按鈕
                            row = row + "    <button type='button' class='btn-success' name='modify' id='modify' title='修改儲存' onclick='CRUD(2, \"" + JData.EMPL_NO[i] + "\", \"" + JData.OVER_DATE[i] + "\")'><i class='fa fa-save'></i> </button></td>";
                            row = row + "</tr>";
                            $('#_content').append(row);
                        }
                    }

                } else if (oper == 1) { //新增
                    toastr["success"]("資料新增成功!");
                    CRUD(0); //reload
                } else if (oper == 2) { //修改
                    toastr["success"]("資料修改成功!");
                    CRUD(0); //reload
                } else if (oper == 3) { //刪除
                    toastr["success"]("資料刪除成功!");
                    CRUD(0); //reload
                } else if (oper == 4) { //審核加班
                    toastr["success"]("資料審核成功!");
                    CRUD(0); //reload
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
