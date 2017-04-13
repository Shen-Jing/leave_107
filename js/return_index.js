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
            url: 'ajax/return_index_ajax.php',
            type: 'POST',
            dataType: 'json'
        },
        "columns": [
            { "name": "SYSID" },
            { "name": "PRGNAME" },
        ]
    });
});
// function cancelclick(serialno)
// {
//   $.ajax({
//       url: 'ajax/off_trip_ajax.php',
//       data:{  oper: 'canceled',
//               serialnoVar: serialno,
//           },
//       type: 'POST',
//       dataType: "json",
//       success:function(){toastr["success"]("該筆資料已取消!!");
//       $('#Btable').DataTable().ajax.reload();},
//       error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
//   });
// }
