$( // 表示網頁完成後才會載入
    function() {
        $.ajax({
            url: 'ajax/index_ajax.php',
            data: { oper: 'qry_cond1' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                if (JData.error_code)
                    message(JData.error_message, "danger", 5000);
                else {
                    $('#_content').empty();
                    for (var i = 0; i < JData.ID.length; i++) {
                        var row = "<tr>";
                        row = row + "<td>" + JData.ID[i] + "</td>";
                        row = row + "<td>" + JData.NAME[i] + "</td>";
                        row = row + "<td>" + JData.TITLE_NAME[i] + "</td>";
                        row = row + "<td>" + JData.YEAR[i] + "</td>";
                        row = row + "</tr>";
                        $('#_content').append(row);
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
);

function CRUD(oper, id) {
    id = id || ''; //預設值
    if (oper == 3)
        if (!confirm("是否確定要刪除?")) return false;
    $.ajax({
        url: 'ajax/campus_ajax.php',
        data: {
            old_id: id,
            id: $('#id' + id).val(),
            name: $('#name' + id).val(),
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
                    for (var i = 0; i < JData.ID.length; i++) {
                        var row = "<tr>";
                        row = row + "<td><input value=" + JData.ID[i] + "  name='id" + JData.ID[i] + "' id='id" + JData.ID[i] + "' type='text' class='form-control' maxlength='2'></td>";
                        row = row + "<td><input value=" + JData.NAME[i] + " name='name" + JData.ID[i] + "' id='name" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td><button type='submit' class='btn-success' name='modify' id='modify' value=" + JData.ID[i] + " onclick='CRUD(2," + JData.ID[i] + ")'> <i class='fa fa-save'></i> </button>";
                        row = row + "    <button  type='submit' class='btn-danger' name='delete' id='delete' onclick='CRUD(3," + JData.ID[i] + ")'><i class='fa fa-times'></i></button>";
                        row = row + "</tr>";
                        $('#_content').append(row);
                    }
                    //新增列
                    var row2 = "<tr class='info'>";
                    row2 = row2 + "<td><input name='id' id='id' type='text' class='form-control' placeholder='學院代碼'></td>";
                    row2 = row2 + "<td><input name='name' id='name' type='text' class='form-control' placeholder='學院名稱'></td>";
                    row2 = row2 + "<td><button class='btn-primary' type='submit' name='new' id='new' onclick='CRUD(1)'> <i class='fa fa-plus'></i></button></td>";
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
