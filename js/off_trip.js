$(document).ready(function()
{
    //table <thead> is necessary.
    $('#Btable').DataTable({
        "scrollCollapse": true,
        "displayLength": 10,
        "paginate": true,
        "lengthChange": true,
        "processing": false,
        "serverSide": false,
        "ajax": {
            url: 'ajax/off_trip_ajax.php',
            type: 'POST',
            dataType: 'json'
        },
        "columns": [
            { "name": "EMPL_CHN_NAME" },
            { "name": "CODE_CHN_ITEM" },
            { "name": "POVDATEB" },
            { "name": "POVDATEE" },
            { "name": "POVTIMEB" },
            { "name": "POVTIMEE" },
            { "name": "POVHOURSDAYS" },
            { "name": "AGENTNAME" },
            { "name": "BUTTON1" }
        ]

    });
});
