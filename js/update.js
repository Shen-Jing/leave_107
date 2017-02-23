$(function() {
    $("body").tooltip({
        selector: "[title]"
    });

    init_table();
});

function init_table() {
    $('#update_table').DataTable({
        "columns": [
            { "data": "EMPL_CHN_NAME" },
            { "data": "CODE_CHN_ITEM" },
            { "data": "POVDATEB" },
            { "data": "POVDATEE" },
            { "data": "POVTIMEB" },
            { "data": "POVTIMEE" },
            { "data": "AGGRETIME" },
            { "data": "AGENTNAME" },
            { "data": "SERIALNO" },
        ],
        "columnDefs": [{
                "targets": 8,
                "data": "SERIALNO",
                "orderable": false,
                "render": function(data) { return "<a target='_new' href='update_form.php?sn=" + data + "' class='btn btn-warning'><i class='fa fa-pencil'></i></a>" }
            },
            { "className": "dt-center", "targets": "_all" }
        ],
        "ajax": {
            url: 'ajax/update_ajax.php',
            type: 'POST',
            dataType: 'json'
        },
        "dom": 'tp'
    });
}