var date = new Date();
var year = date.getFullYear() - 1911;
$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/staff_call_off_ajax.php',
            data: {
              oper: 'qry_year'
            },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                var end_year = parseInt(JData.END_YEAR[0]);
                $('#qry_year').append(row0);
                for (var i = end_year - 1; i <= end_year + 1; i++) {
                    var row = "<option value=" + i + ">" + i + "</option>";
                    $('#qry_year').append(row);
                }
                // 預先帶出當年
                $('#qry_year').val("" + year);
                CRUD(0);
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
    });

function view_call_off(serialno) {
    // 取得詳細差假記錄
    $.ajax({
        url: 'ajax/view_call_off.php',
        data: {
            serialno: serialno,
        },
        type: 'POST',
        dataType: "text",
        success: function(JData) {
            $("#modal-detail .modal-title").html("假單刷卡記錄");
            $("#modal-detail .modal-body").html(JData);
            $("#modal-detail").modal("show"); //弹出框show
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
        }
    });
}

function CRUD(oper, serialno) {
    if (oper == 4)
        if (!confirm("確定要取消嗎?")) return false;
    $.ajax({
        url: 'ajax/staff_call_off_ajax.php',
        data: {
            oper: oper,
            year: $('#qry_year').val(),
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
                    data_length = JData.call_off.EMPL_CHN_NAME.length;
                    if (data_length == 0) {
                        $('#_content').append("<tr><td colspan='12'>目前尚無資料</td></tr>");
                    }
                    else {
                        for (var i = 0; i < data_length; i++) {
                            var row = "<tr>";
                            // 單位（系統開發組）
                            row = row + "<td>" + JData.short_dept + "</td>";
                            // 姓名（李_朗）
                            row = row + "<td>" + JData.call_off.EMPL_CHN_NAME[i] + "</td>";
                            // 假別（加班)
                            row = row + "<td>" + JData.call_off.CODE_CHN_ITEM[i] + "</td>";
                            if (JData.call_off.ABROAD[i] == '0')
                                JData.call_off.ABROAD[i] = '未出國';
                            else
                                JData.call_off.ABROAD[i] = '出國';
                            // 出國否（未出國）
                            row = row + "<td>" + JData.call_off.ABROAD[i] + "</td>";
                            // 起始日（1050302）
                            row = row + "<td>" + JData.call_off.POVDATEB[i] + "</td>";
                            // 終止日（1050302）
                            row = row + "<td>" + JData.call_off.POVDATEE[i] + "</td>";
                            // 起始時間（16）
                            row = row + "<td>" + JData.call_off.POVTIMEB[i] + "</td>";
                            // 終止時間（17）
                            row = row + "<td>" + JData.call_off.POVTIMEE[i] + "</td>";
                            // 職務代理人（施_男）
                            row = row + "<td>" + JData.agent[i] + "</td>";
                            // 天數（0天1時）
                            row = row + "<td>" + JData.call_off.POVDAYS[i] + "天" + JData.call_off.POVHOURS[i] + "時</td>";
                            // 功能區
                            // 查看詳細差假記錄（info button）
                            row = row + "<td><button type='button' class='btn-info' name='card' title='詳細記錄' onclick='view_call_off(\"" + JData.call_off.SERIALNO[i] + "\")'><i class='fa fa-info'></i> </button>";
                            // 取消
                            row = row + "    <button type='button' class='btn-warning' name='check' title='取消假單' onclick='CRUD(4, " + JData.call_off.SERIALNO[i] + ")'><i class='fa fa-times'></i> </button>";
                            row = row + "</tr>";
                            $('#_content').append(row);
                        }
                    }
                } else if (oper == 4) { //取消假單
                    toastr["success"](JData.submit_result + "本假單取消成功!");
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
