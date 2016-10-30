$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });

        // 查詢單位
        $.ajax({
            url: 'ajax/holiday_form_ajax.php',
            data: {
              oper: 'qry_item',
              empl_no: $('#empl_no').text(),
            },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                // 單位 select欄位
                var row0 = "<option selected disabled class='text-hide'>請選擇單位</option>";
                $('#qry_dept').append(row0);
                for (var i = 0; i < JData.qry_dept.DEPT_NO.length; i++) {
                    var row = "<option value=" + JData.qry_dept.DEPT_NO[i] + ">" + JData.qry_dept.DEPT_FULL_NAME[i] + "</option>";
                    $('#qry_dept').append(row);
                }

                // 職稱欄位
                $('#qry_title').text(JData.qry_title.CODE_CHN_ITEM[0]);

                // 假別 select欄位
                var row0 = "<option selected disabled class='text-hide'>請選擇假別</option>";
                $('#qry_vtype').append(row0);
                for (var i = 0; i < JData.qry_vtype.CODE_FIELD.length; i++) {
                    var row = "<option value=" + JData.qry_vtype.CODE_FIELD[i] + ">" + JData.qry_vtype.CODE_CHN_ITEM[i] + "</option>";
                    $('#qry_vtype').append(row);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }
        });

    }
);
