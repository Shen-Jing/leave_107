$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });

        // 查詢單位
        $.ajax({
            url: 'ajax/p_search_overtime_idx_ajax.php',
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

        // 改變滑鼠游標樣式
        $('#container tbody').on('mouseover', 'tr', function() {
            this.style.cursor = 'pointer';
        });

        // 詳細加班記錄
        $('#container tbody').on('click', 'tr', function() {
             // 目前所在列的員工編號
            current_emplno = $(this).closest('tr').find('td:nth-child(1)').text();
            // 目前所在列的員工姓名
            current_emplname = $(this).closest('tr').find('td:nth-child(2)').text();

            $.ajax({
                url: 'ajax/overtime_data.php',
                data: {
                    empl_no: current_emplno,
                    empl_name: current_emplname
                },
                type: 'POST',
                dataType: 'text',
                success: function(JData) {
                    $("#modal-detail .modal-title").html("加班記錄");
                    $("#modal-detail .modal-body").html(JData);
                    $("#modal-detail").modal("show"); //弹出框show
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });
        });
    }
);

function CRUD(oper) {
    if (oper == 3){
      if (!confirm("是否確定要刪除?"))
        return false;
    }

    $.ajax({
        url: 'ajax/p_search_overtime_idx_ajax.php',
        data: {
            oper: oper,
            dept_id: $('#qry_dept').val() || '1',
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                //toastr["error"](JData.error_message);
                message(JData.error_message, "danger", 5000);
            else {
                if (oper == "0") { //查詢
                    $('#_content').empty();
                    if (JData.EMPL_NO.length == 0){
                        // 此處不懂為何明明<th>有3格，colspan設定3會導致多凸出「功能」的那一塊
                        $('#_content').append("<tr><td colspan='2'>該單位無人員資料<td></tr>");
                    }
                    else {
                        for (var i = 0; i < JData.EMPL_NO.length; i++) {
                            var row = "<tr>";
                            // 人員代號
                            row = row + "<td>" + JData.EMPL_NO[i] + "</td>";
                            // 姓名
                            row = row + "<td>" + JData.EMPL_CHN_NAME[i] + "</td>";
                            // 加班記錄
                            row = row + "<td><button type='button' class='btn-info detail' id='detail' value=" + JData.EMPL_NO[i] + " title='加班記錄'> <i class='fa fa-info'></i> </button>";
                            // 修改儲存、刪除按鈕
                            // row = row + "    <button type='button' class='btn-success' name='modify' id='modify' value=" + JData.EMPL_NO[i] + " title='修改儲存'> <i class='fa fa-save'></i> </button>";
                            // row = row + "    <button type='button' class='btn-danger' name='delete' id='delete' onclick='CRUD(3, " + JData.EMPL_NO[i] + "'><i class='fa fa-times' title='刪除'></i></button></td>";
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
            console.log(xhr.responseText);
        }
    });
}
