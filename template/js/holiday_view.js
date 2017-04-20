$(function() {
    $("body").tooltip({
        selector: "[title]"
    });

    var Year = new Date().getFullYear() - 1910;
    for (var i = 95; i <= Year; i++)
        $('#sel_years').append('<option value="' + i + '">' + i + '</option>');

    init_table();
});

function init_table() {
    $('#Btable_passing').DataTable({
        "columns": [
            { "data": null },
            { "data": "EMPL_CHN_NAME" },
            { "data": "CODE_CHN_ITEM" },
            { "data": "POVDATEB" },
            { "data": "POVDATEE" },
            { "data": "POVTIMEB" },
            { "data": "POVTIMEE" },
            { "data": "POVTOTIME" },
            { "data": "AGENTSIGND" },
            { "data": "ONESIGND" },
            { "data": "TWOSIGND" }
        ],
        "columnDefs": [
            { "targets": 0, "orderable": false, "defaultContent": "<button class='btn-default details-control' type='button' title='詳細資料'><i class='fa fa-info'></i>" },
            { "className": "dt-center", "targets": "_all" }
        ],
        "ajax": {
            url: 'ajax/holiday_view_ajax.php',
            data: function(d) {
                d.tbid = 1,
                    d.year = $('#sel_years').val();
            },
            type: 'POST',
            dataType: 'json'
        },
        "buttons": [
            'print', 'pdf', 'excel'
        ],
        "dom": '<"top"l>Bftrip'
    });

    $('#Btable_canceled').DataTable({
        "columns": [
            { "data": "EMPL_CHN_NAME" },
            { "data": "CODE_CHN_ITEM" },
            { "data": "POVDATEB" },
            { "data": "POVDATEE" },
            { "data": "POVTIMEB" },
            { "data": "POVTIMEE" },
            { "data": "POVTOTIME" },
            { "data": "AGENTNAME" },
            { "data": "THREESIGND" }
        ],
        "columnDefs": [
            { "className": "dt-center", "targets": "_all" }
        ],
        "ajax": {
            url: 'ajax/holiday_view_ajax.php',
            data: function(d) {
                d.tbid = 2,
                    d.year = $('#sel_years').val();
            },
            type: 'POST',
            dataType: 'json'
        },
        "dom": 'tp'
    });

    $('#Btable_dealing').DataTable({
        "columns": [
            { "data": null },
            { "data": "EMPL_CHN_NAME" },
            { "data": "CODE_CHN_ITEM" },
            { "data": "POVDATEB" },
            { "data": "POVDATEE" },
            { "data": "POVTIMEB" },
            { "data": "POVTIMEE" },
            { "data": "POVTOTIME" },
            { "data": "AGENTSIGND" },
            { "data": "ONESIGND" },
            { "data": "TWOSIGND" }
        ],
        "columnDefs": [
            { "targets": 0, "orderable": false, "defaultContent": "<button class='btn-default details-control' type='button' title='詳細資料'><i class='fa fa-info'></i>" },
            { "className": "dt-center", "targets": "_all" }
        ],
        "ajax": {
            url: 'ajax/holiday_view_ajax.php',
            data: function(d) {
                d.tbid = 3,
                    d.year = $('#sel_years').val();
            },
            type: 'POST',
            dataType: 'json'
        },
        "dom": 'tp'
    });

    $('#Btable_rejected').DataTable({
        "columns": [
            { "data": "EMPL_CHN_NAME" },
            { "data": "CODE_CHN_ITEM" },
            { "data": "POVDATEB" },
            { "data": "POVDATEE" },
            { "data": "POVTIMEB" },
            { "data": "POVTIMEE" },
            { "data": "DEPARTREASON" },
            { "data": "PERTWOSIGND" },
            { "data": "SECONE_COMMT" }
        ],
        "columnDefs": [
            { "className": "dt-center", "targets": "_all" }
        ],
        "ajax": {
            url: 'ajax/holiday_view_ajax.php',
            data: function(d) {
                d.tbid = 4,
                    d.year = $('#sel_years').val();
            },
            type: 'POST',
            dataType: 'json'
        },
        "dom": 'tp'
    });

    $('#Btable_dealing tbody').on('click', 'button.details-control', function() {
        var row = $('#Btable_dealing').DataTable().row($(this).closest('tr'));
        if (row.child.isShown())
            row.child.hide();
        else
            row.child(getDetail(row.data())).show();
    });
    $('#Btable_passing tbody').on('click', 'button.details-control', function() {
        var row = $('#Btable_passing').DataTable().row($(this).closest('tr'));
        if (row.child.isShown())
            row.child.hide();
        else
            row.child(getDetail(row.data())).show();

    });

    $('#sel_years').on('change', function() {
        $('.dt-Table').DataTable().ajax.reload();
    });
}

function getDetail(d) {
    return '<p>院長：' + d.THREESIGND + '</p>' +
        '<hr>' +
        '<p>人事承辦員：' + d.PERONE_SIGND + '</p>' +
        '<hr>' +
        '<p>人事主任：' + d.PERTWO_SIGND + '</p>' +
        '<hr>' +
        '<p>秘書室：' + d.SECONE_SIGND + '</p>' +
        '<hr>' +
        '<p>備註：' + d.POREMARK + '</p>';
}