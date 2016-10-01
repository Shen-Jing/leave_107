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
        if (!confirm("※刪除全銜將會一併刪除其他相關基本資料，\r\n是否確定要刪除?")) return false;
    $.ajax({
        url: 'ajax/title_ajax.php',
        data: {
            old_id: id, //no-use
            year: $('#year' + id).val(),
            title_name: $('#title_name' + id).val(),
            import: $('#import' + id).prop("checked") ? 1 : 0,
            oper: oper
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code) {
                //toastr["error"](JData.error_message);
                message(JData.error_message, "danger", 5000);
            } else {
                if (oper == "0") { //查詢
                    $('#_content').empty();
                    for (var i = 0; i < JData.ID.length; i++) {
                        var row = "<tr>";
                        row = row + "<td><input value=" + JData.TITLE_NAME[i] + "  name='title_name" + JData.ID[i] + "' id='title_name" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td><input value=" + JData.YEAR[i] + " name='year" + JData.ID[i] + "' id='year" + JData.ID[i] + "' type='text' class='form-control' disabled></td>";
                        row = row + "<td><button type='button' class='btn-success' name='modify' id='modify' onclick='CRUD(2," + JData.ID[i] + ")' title='修改儲存'><i class='fa fa-save'></i> </button>";
                        row = row + "    <button  type='button' class='btn-danger' name='delete' id='delete' onclick='CRUD(3," + JData.ID[i] + ")' title='刪除'><i class='fa fa-times'></i></button>";
                        row = row + "</tr>";
                        $('#_content').append(row);
                    }
                    //新增列
                    var row2 = "<tr class='info'>";
                    row2 = row2 + "<td><input name='title_name' id='title_name' type='text' class='form-control' placeholder='新增考試全銜'></td>";
                    row2 = row2 + "<td><input name='year' id='year' type='text' class='form-control' placeholder='招生年度'></td>";
                    row2 = row2 + "<td><button class='btn-primary' type='button' name='new' id='new' onclick='CRUD(1)' title='新增年度招生資料'> <i class='fa fa-plus'></i></button> <input id='import' type='checkbox' value='1' checked> 匯入前一次招生資料</td>";
                    row2 = row2 + "</tr>";
                    $('#_content').append(row2);
                    $('#header_title_name').html(JData.TITLE_NAME[0]);//更新page-header的招生全銜
                } else if (oper == 1) { //新增
                    toastr["success"]("資料新增成功!");
                    CRUD(0); //reload
                    //alert("資料新增成功!");
                    //top.location.reload();
                } else if (oper == 2) { //修改
                    toastr["success"]("資料修改成功!");
                    CRUD(0); //reload
                    //alert("資料修改成功!");
                    //top.location.reload();
                } else if (oper == 3) { //刪除
                    toastr["success"]("資料刪除成功!");
                    CRUD(0); //reload
                    //alert("資料刪除成功!");
                    //top.location.reload();
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
