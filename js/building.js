$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/building_ajax.php',
            data: { oper: 'qry_area' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇考區</option>";
                $('#qry_area').append(row0);
                for (var i = 0; i < JData.TEST_AREA_ID.length; i++) {
                    var row = "<option value=" + JData.TEST_AREA_ID[i] + ">" + JData.TEST_AREA_NAME[i] + "</option>";
                    $('#qry_area').append(row);
                }
                //CRUD(0);//query
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });


        $('#qry_area').change( // 抓取區域選完的資料
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0); //query
                }
            }
        );
    }
);

function CRUD(oper, id) {
    id = id || ''; //預設值
    if (oper == 3)
        if (!confirm("是否確定要刪除?")) return false;
    $.ajax({
        url: 'ajax/building_ajax.php',
        data: {
            old_id: id,
            building_id: $('#building_id' + id).val(),
            building_name: $('#building_name' + id).val(),
            class_count: $('#class_count' + id).val(),
            test_area_id: $('#qry_area').val() || '1',
            oper: oper
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
                    for (var i = 0; i < JData.BUILDING_ID.length; i++) {
                        var row = "<tr>";
                        row = row + "<td><input value=" + JData.BUILDING_ID[i] + "  name='building_id" + JData.BUILDING_ID[i] + "' id='building_id" + JData.BUILDING_ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td><input value=" + JData.BUILDING_NAME[i] + " name='building_name" + JData.BUILDING_ID[i] + "' id='building_name" + JData.BUILDING_ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td><input value=" + JData.CLASS_COUNT[i] + " name='class_count" + JData.BUILDING_ID[i] + "' id='class_count" + JData.BUILDING_ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td><button type='button' class='btn-success' name='modify' id='modify' value=" + JData.BUILDING_ID[i] + "  onclick='CRUD(2," + JData.BUILDING_ID[i] + ")' title='修改儲存'> <i class='fa fa-save'></i> </button>";
                        row = row + "    <button type='button' class='btn-danger' name='delete' id='delete' onclick='CRUD(3," + JData.BUILDING_ID[i] + ")'><i class='fa fa-times' title='刪除'></i></button></td>";
                        row = row + "</tr>";
                        $('#_content').append(row);
                    }
                    //新增列
                    var row2 = "<tr class='info'>";
                    row2 = row2 + "<td><input name='building_id' id='building_id' type='text' class='form-control' placeholder='代碼'></td>";
                    row2 = row2 + "<td><input name='building_name' id='building_name' type='text' class='form-control' placeholder='大樓名稱'></td>";
                    row2 = row2 + "<td><input name='class_count' id='class_count' type='text' class='form-control' placeholder='教室數量'></td>";
                    row2 = row2 + "<td><button class='btn-primary' type='button' name='new' id='new' onclick='CRUD(1)' title='新增'> <i class='fa fa-plus'></i></button></td>";
                    row2 = row2 + "</tr>";
                    $('#_content').append(row2);
                } else if (oper == 1) { //新增
                    toastr["success"]("資料新增成功!");
                    CRUD(0); //reload
                } else if (oper == 2) { //修改
                    toastr["success"]("資料修改成功!");
                    CRUD(0); //reload
                } else if (oper == 3) { //刪除
                    toastr["success"]("資料刪除成功!");
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
        error: function(xhr, ajaxOptions, thrownError) {}
    });
}
