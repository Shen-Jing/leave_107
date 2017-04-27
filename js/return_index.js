$(document).ready(function()
{
    table =
    $('#Btable').DataTable({
        "responsive": true,
        "scrollCollapse": true,
        "displayLength": 10,
        "paginate": true,
        "lengthChange": true,
        "processing": false,
        "serverSide": false,
        "ajax": {
            url: 'ajax/return_index_ajax.php',
            data: {
              oper: 0,
            },
            type: 'POST',
            dataType: 'json'
        },
        "columns": [
          { "width": "10%" },
          { "width": "80%" },
          { "width": "10%" }
        ]
    });

});

function CRUD(oper, old_id) {
    old_id = old_id || ''; //預設值

    if (oper == 3)
        if (!confirm("確定刪除?")) return false;

    $.ajax({
        url: 'ajax/return_index_ajax.php',
        data: {
          oper: oper,
          // old_id在刪除的oper時，需刪除的sysid；新增時則是傳入-1，沒用途
          old_id: old_id,
          // new_id在新增、刪除皆無用途
          new_id: $('#sysid' + old_id).val(),
          prgname: $('#prgname' + old_id).val(),
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else{
                if (oper == 0) { //更新
                    table.ajax.reload();
                } else if (oper == 1) { //新增
                    if (JData.result != "")
                        toastr["success"](JData.result);
                    else
                        toastr["success"]("資料新增成功!");
                    CRUD(0); //reload
                } else if (oper == 2) { //修改
                    if (JData.result != "")
                        toastr["success"](JData.result);
                    else
                        toastr["success"]("資料儲存修改成功!");
                    CRUD(0); //reload
                } else if (oper == 3) { //刪除
                    if (JData.result != "")
                        toastr["success"](JData.result);
                    else
                        toastr["success"]("資料刪除成功!");
                    CRUD(0); //reload
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}
