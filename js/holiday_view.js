$( 
	function() {
		$("body").tooltip({
            selector: "[title]"
        });
		init_table();
	}
    
);

var otable_passing,
    otable_canceled,
    otable_dealing,
    otable_rejected;


var sel_year = "95";

function init_table() 
{
    //table <thead> is necessary.
    otable_passing = 
    $('#Btable_passing').DataTable({
        "ajax": {
            url: 'ajax/holiday_view_ajax.php',
            data: {
                tbid : 1,
                year : sel_year
            },
            type: 'POST',
            dataType: 'json'
        },
        "buttons": [
        'print','pdf','excel'
        ],
        "dom": '<"top"l>Bftrip'
    });

    otable_canceled = 
     $('#Btable_canceled').DataTable({
        "ajax": {
            url: 'ajax/holiday_view_ajax.php',
            data: function (d){
                d.tbid = 2,
                d.year = sel_year;
            },
            type: 'POST',
            dataType: 'json'
        },
        "dom": 't'
    });

    otable_dealing = 
    $('#Btable_dealing').DataTable({
        "ajax": {
            url: 'ajax/holiday_view_ajax.php',
            data: function (d){
                d.tbid = 3,
                d.year = sel_year;
            },
            type: 'POST',
            dataType: 'json'
        },
       "dom": 't'
    });

    otable_rejected = 
    $('#Btable_rejected').DataTable({
        "ajax": {
            url: 'ajax/holiday_view_ajax.php',
            data: function (d){
                d.tbid = 4,
                d.year = sel_year;
            },
            type: 'POST',
            dataType: 'json'
        },
        "dom": 't'
    });
}

 function sel_years_onchange()
 {
     sel_year = document.getElementById("sel_years")
                .options[document.getElementById("sel_years").selectedIndex]
                .text;

     otable_rejected.ajax.reload();
     otable_canceled.ajax.reload();
     otable_passing.ajax.reload();
     otable_dealing.ajax.reload();
 } 