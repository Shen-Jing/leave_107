$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        CRUD(0);
    }
);

function CRUD(oper, id) {
    id = id || ''; //預設值
    if (oper == 3)
        if (!confirm("是否確定要刪除?")) return false;
    $.ajax({
        url: 'ajax/disobey_input_ajax.php',
        data: {
            old_id: id,
            id: $('#id' + id).val(),
            section: $('#section' + id).val(),
            disobey: $('#disobey' + id).val(),
            remark: $('#remark' + id).val(),
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
                        row = row + "<td class='col-xs-2'><input value=" + JData.ID[i] + "  name='id" + JData.ID[i] + "' id='id" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'><input value=" + JData.SECTION[i] + "  name='section" + JData.ID[i] + "' id='section" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'><input value=" + JData.DISOBEY[i] + "  name='disobey" + JData.ID[i] + "' id='disobey" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-3'><input value=" + JData.REMARK[i] + "  name='remark" + JData.ID[i] + "' id='remark" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'>" + JData.SCORE[i] + "</td>";
                        row = row + "<td class='col-xs-2'>" + JData.RESULT[i] + "</td>";
                        row = row + "<td class='col-xs-2'><button type='button' class='btn-success' name='modify' id='modify' value=" + JData.ID[i] + " onclick='CRUD(2," + JData.ID[i] + ")' title='修改儲存'> <i class='fa fa-save'></i> </button>";
                        row = row + "    <button type='button' class='btn-danger' name='delete' id='delete' onclick='CRUD(3," + JData.ID[i] + ")' title='刪除'><i class='fa fa-times'></i></button>";
                        row = row + "</tr>";
                        $('#_content').append(row);
                    }
                    //新增列
                    var row2 = "<tr class='info'>";
                    row2 = row2 + "<td><input name='id' id='id' type='text' class='form-control' placeholder='准考證號'></td>";
                    row2 = row2 + "<td><input name='section' id='section' type='text' class='form-control' placeholder='節次'></td>";
                    row2 = row2 + "<td><input name='disobey' id='disobey' type='text' class='form-control' placeholder='扣分'></td>";
                    row2 = row2 + "<td><input name='remark' id='remark' type='text' class='form-control' placeholder='備註'></td>";
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
