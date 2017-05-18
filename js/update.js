$(function() {
    init_table();

    bootbox.setDefaults({
        locale: "zh_TW",
        backdrop: true
    });
});

var utb, ctb;

function init_table() {
    utb =
        $('#update_table').DataTable({
            // 無資料時收起
            fnInitComplete: function() {
                if (utb.data().count() == 0) {
                    togglePanel($(this).parent().parent());
                }
            },
            responsive: true,
            columns: [
                { "data": "EMPL_CHN_NAME" },
                { "data": "CODE_CHN_ITEM" },
                { "data": "POVDATEB" },
                { "data": "POVDATEE" },
                { "data": "POVTIMEB" },
                { "data": "POVTIMEE" },
                { "data": "AGGRETIME" },
                { "data": "AGENTNAME" },
                { "data": "SERIALNO" }
            ],
            columnDefs: [{
                    "targets": 8,
                    "data": "SERIALNO",
                    "orderable": false,
                    "render": function(data) {
                        // 修改
                        return "<button type='button' class='btn btn-warning'" +
                            " onclick='openHoildayForm(" + data + ")' >" +
                            "<i class='fa fa-pencil'></i>" +
                            "</button>" + "&nbsp;" +
                            // 取消
                            "<button type='button' class='btn btn-danger'" +
                            " onclick='cancelHoildayForm(0," + data + ")' >" +
                            "<i class='fa fa-times' aria-hidden='true'></i>" +
                            "</button>";
                    }
                },
                { "className": "dt-center", "targets": "_all" }
            ],
            ajax: {
                url: 'ajax/update_ajax.php',
                type: 'POST',
                dataType: 'json',
                data: { oper: "qry" }
            },
            dom: 'tp'
        });

    ctb =
        $('#cancel_table').DataTable({
            responsive: true,
            columns: [
                { data: "EMPL_CHN_NAME" },
                { data: "CODE_CHN_ITEM" },
                { data: "POVDATEB" },
                { data: "POVDATEE" },
                { data: "POVTIMEB" },
                { data: "POVTIMEE" },
                { data: "AGGRETIME" },
                { data: "AGENTNAME" },
                { data: "SERIALNO" }
            ],
            columnDefs: [{
                    "targets": 8,
                    "data": "SERIALNO",
                    "orderable": false,
                    "render": function(data) {
                        // 取消
                        return "<button type='button' class='btn btn-danger' onclick='cancelHoildayForm(1," + data + ")'><i class='fa fa-times' aria-hidden='true'></i></button>";
                    }
                },
                { "className": "dt-center", "targets": "_all" }
            ],
            ajax: {
                url: 'ajax/update_ajax.php',
                type: 'POST',
                dataType: 'json',
                data: { oper: "qry2" }
            }
        });
}

function openHoildayForm(sn) {
    if (typeof sn == 'undefined')
        return;

    var url = "update_form.php?sn=" + sn;

    $('.NewPage-IFrame').attr("src", url);
    $('#fullscrModal').modal('show');
}

function cancelHoildayForm(flag, sn) {
    bootbox.confirm({
        message: "確定要取消該假單嗎？",
        buttons: {
            confirm: {
                className: 'btn-danger'
            }
        },
        callback: function(result) {
            if (!result) return;
            jQuery.post(
                "ajax/update_ajax.php", { oper: "cancel", flag: flag, sn: sn },
                function(data) {
                    toastr['success'](data);
                    if (flag == 0)
                        utb.ajax.reload();
                    else
                        ctb.ajax.reload();
                }
            );
        }
    });
}