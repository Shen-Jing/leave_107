$( 
	function() {
		$("body").tooltip({
            selector: "[title]"
        });
		init_table();
	}
    
);

var oTable;
function init_table() 
{
    oTable = 
    $('#oTable').DataTable({
        "ajax": {
            url: 'ajax/update_all_ajax.php',
            data: {
                oper : 'load'
            },
            type: 'POST',
            dataType: 'json'
        },
		"deferRender": true,
		"columns": [
			{ "data": "DEPT_SHORT_NAME" },
            { "data": "EMPL_CHN_NAME" },
            { "data": "CODE_CHN_ITEM" },
            { "data": "POVDATEB" },
            { "data": "POVDATEE" },
            { "data": "POVTIMEB" },
			{ "data": "POVDAYS" },
            { "data": "APPDATE" },
			{ "data": "CONDITION" },
			{ "data": "CURENTSTATUS" }
        ],
		"columnDefs":[
			{
				"targets": 10,
				"data": null,
				"defaultContent": "<button id='editrow' class='btn-primary' type='button' title='編輯'><i class='fa fa-edit'></i></button><button id='delrow' class='btn-danger' type='button' title='刪除'><i class='fa fa-trash-o'></i></button>"
			}
		],
        "buttons": [
        'print','excel'
        ],
        "dom": '<"top"l>Bftrip'
    });
	
	$('#oTable tbody').on('click', 'button#delrow', function() {
            var rowid = oTable.row($(this).parents('tr')).id();
            if (!confirm("確定要刪除此筆資料 ( " + rowid + " ) ?")) return false;
            $.ajax({
                url: 'ajax/update_all_ajax.php',
                data: {
                    oper: 'del'
                },
                type: 'POST',
                dataType: 'json',
                success: function(JData) {
                    oTable.ajax.reload();
                },
                error: function(xhr, ajaxOptions, thrownError) {}
            });
    });
}



 function sel_years_onchange()
 {
     sel_year = document.getElementById("sel_years")
                .options[document.getElementById("sel_years").selectedIndex]
                .text;

     oTable.ajax.reload();
 } 