$(function() {
    var Year = new Date().getFullYear() - 1910;
    for (var i = 95; i <= Year; i++)
        $('#sel_years').append('<option value="' + i + '">' + i + '</option>');

    init_table();
});

function init_table() {
    $('#Btable_passing').DataTable({
		responsive: true,
        "columns": [
            { "data": "EMPL_CHN_NAME" },
            { "data": "CODE_CHN_ITEM" },
            { "data": "POVDATEB" },
            { "data": "POVDATEE" },
            { "data": "POVTIMEB" },
            { "data": "POVTIMEE" },
            { "data": "POVTOTIME" },
            { "data": "AGENTSIGND" },
            { "data": "ONESIGND" },
            { "data": "TWOSIGND" },
			{ "data": "THREESIGND" },
			{ "data": "PERONE_SIGND" },
			{ "data": "PERTWO_SIGND" },
			{ "data": "SECONE_SIGND" },
			{ "data": "POREMARK" }
        ],
        "columnDefs": [
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
		responsive: true,
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
		responsive: true,
        "columns": [
            { "data": "EMPL_CHN_NAME" },
            { "data": "CODE_CHN_ITEM" },
            { "data": "POVDATEB" },
            { "data": "POVDATEE" },
            { "data": "POVTIMEB" },
            { "data": "POVTIMEE" },
            { "data": "POVTOTIME" },
            { "data": "AGENTSIGND" },
            { "data": "ONESIGND" },
            { "data": "TWOSIGND" },
			{ "data": "THREESIGND" },
			{ "data": "PERONE_SIGND" },
			{ "data": "PERTWO_SIGND" },
			{ "data": "SECONE_SIGND" },
			{ "data": "POREMARK" }
        ],
        "columnDefs": [
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
		responsive: true,
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

    $('#sel_years').on('change', function() {
        $('.dt-Table').DataTable().ajax.reload();
    });
}
