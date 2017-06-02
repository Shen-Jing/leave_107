$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/class_apply_ajax.php',
            data: {
              oper: 'qry_dept'
            },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇學院</option>";
                $('#qry_dept').append(row0);
                for (var i = 0; i < JData.DEPT_NO.length; i++) {
                    var row = "<option value=" + JData.DEPT_NO[i] + ">" + JData.DEPT_NO[i] + JData.DEPT_FULL_NAME[i] + "</option>";
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

        // 點選該列任意處，跳出詳細記錄
        $('#container tbody').on('click', 'tr', function() {
             // 取得目前所在列的serialno（位在最後一個td(被隱藏)）
            current_serialno = $(this).closest('tr').find('td:nth-last-child(1)').text();
            view_call_off(current_serialno);
        });

        // 選擇年份後，即可顯示資料
        $('#qry_dept').change(
            function(e) {
                if ($(':selected', this).val() !== ''){
                    CRUD(0);
                }
            }
        )
    });

function CRUD(oper, serialno) {
    if (oper == 4)
        if (!confirm("確定要取消嗎?")) return false;
    $.ajax({
        url: 'ajax/class_apply_ajax.php',
        data: {
            oper: oper,
            dept: $('#qry_dept').val(),
            serialno: serialno
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else {
                if (oper == "0") { //查詢
                    $('#_content').empty();
                    data_length = JData.class_apply.EMPL_CHN_NAME.length;
                    if (data_length == 0) {
                        $('#_content').append("<tr><td colspan='12'>目前尚無資料</td></tr>");
                    }
                    else {
                        for (var i = 0; i < data_length; i++) {
                            var row = "<tr>";
                            // 單位（系統開發組）
                            row = row + "<td>" + JData.short_dept + "</td>";
                            // 姓名（李_朗）
                            row = row + "<td>" + JData.class_apply.EMPL_CHN_NAME[i] + "</td>";
                            // 假別（加班)
                            row = row + "<td>" + JData.class_apply.CODE_CHN_ITEM[i] + "</td>";
                            if (JData.class_apply.ABROAD[i] == '0')
                                JData.class_apply.ABROAD[i] = '未出國';
                            else
                                JData.class_apply.ABROAD[i] = '出國';
                            // 出國否（未出國）
                            row = row + "<td>" + JData.class_apply.ABROAD[i] + "</td>";
                            // 起始日（1050302）
                            row = row + "<td>" + JData.class_apply.POVDATEB[i] + "</td>";
                            // 終止日（1050302）
                            row = row + "<td>" + JData.class_apply.POVDATEE[i] + "</td>";
                            // 起始時間（16）
                            row = row + "<td>" + JData.class_apply.POVTIMEB[i] + "</td>";
                            // 終止時間（17）
                            row = row + "<td>" + JData.class_apply.POVTIMEE[i] + "</td>";
                            // 天數（0天1時）
                            row = row + "<td>" + JData.class_apply.POVDAYS[i] + "天" + JData.class_apply.POVHOURS[i] + "時</td>";
                            // 功能區
                            // 簽核
                            row = row + "<td><button type='button' class='btn-warning' name='check' title='取消(真的取消假單)' onclick='CRUD(4, " + JData.class_apply.SERIALNO[i] + ")'><i class='fa fa-check'></i> </button>";
                            // serialno(隱藏td，取詳細資料方便用)
                            row = row + "<td style='display: none;'>" + JData.class_apply.SERIALNO[i] + "</td>";
                            row = row + "</tr>";
                            $('#_content').append(row);

                        }
                    }
                } else if (oper == 4) { //取消假單
                    toastr["success"](JData.submit_result + "本假單取消成功!");
                    CRUD(0); //reload
                } else if (oper == 5) { //取消假單
                    toastr["success"](JData.submit_result + "本假單不取消成功!");
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
