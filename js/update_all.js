$(function() {
    initYearMonthsSel();

    initTable();

    initSearchModal();


    $('#unpassed').on('change', function() { oTable.ajax.reload(); });
});

// use global variable for datatable
var oTable;

function initYearMonthsSel() {
    var fulldate = new Date();
    var thismonth = fulldate.getMonth() + 1;
    var thisyear = fulldate.getUTCFullYear() - 1911;

    for (var i = 1; i <= 12; i++)
        $("#qry_month").append('<option value=' + i + '>' + i + '</option>');

    for (var i = 0, year = thisyear - 3; i < 5; i++, year++)
        $("#qry_year").append('<option value=' + year + '>' + year + '</option>');

    $("#qry_year, #qry_month").on("change", function(e) {
        oTable.ajax.reload();
    });

    $("#qry_year").val(thisyear);
    $("#qry_month").val(thismonth);
}


function initTable() {
    oTable =
        $('#oTable').DataTable({
            "dom": 'Bfrtip',
            "responsive": true,
            "processing": true,
            "language": {
                "processing": '<i class="fa fa-refresh fa-spin fa-lg fa-fw"> </i> 讀取中...'
            },
            "ajax": {
                url: 'ajax/update_all_ajax.php',
                data: function(d) {
                    d.oper = "load";
                    d.year = $('#qry_year').val();
                    d.month = $('#qry_month').val();
                    d.unpassed = $('#unpassed').prop("checked");
                },
                type: 'POST',
                dataType: 'json'
            },
            //"deferRender": true, 
            "columns": [
                { "data": "DEPTNAME" },
                { "data": "PONAME" },
                { "data": "POVTYPE" },
                { "data": "POVDATEB" },
                { "data": "POVDATEE" },
                { "data": "POVTIMEB" },
                { "data": "AGGRETIME" },
                { "data": "APPDATE" },
                { "data": "CONDITION" },
                { "data": "CURENTSTATUS" }
            ],
            "columnDefs": [{
                    "targets": 10,
                    "orderable": false,
                    "data": "SERIALNO",
                    "render": function(data) {
                        // 取消
                        return "<button type='button' class='btn btn-primary' onclick='openHoildayForm(" + data + ")'><i class='fa fa-edit' aria-hidden='true'></i></button>";
                    }
                },
                { "className": "dt-center", "targets": "_all" }
            ],
            "buttons": [{
                    text: '選擇人員',
                    action: function(e, dt, node, config) {
                        $('#Modalsearch').modal('show');
                    },
                },
                {
                    text: '顯示全部',
                    action: function(e, dt, node, config) {
                        $('#qry_emps').val('').trigger('change');
                    }
                }
            ]
        });
    $('#oTable').on('click', 'tbody tr', function() {
        eval($(this).find('td > button').attr('onclick'));
    });
}

function initSearchModal() {
    $.post(
        'ajax/update_all_ajax.php', { oper: 'qrydept' },
        function(data) {
            for (var i = 0; i < data.length; i++) {
                $('#qry_dept')
                    .append($("<option></option>")
                        .attr("value", data[i].DEPT_NO)
                        .text(data[i].DEPT_FULL_NAME));
            }

            $('#qry_dept').on('change', function() {
                fnQrydept();
            });

            var fnQrydept = function() {
                $.post(
                    'ajax/update_all_ajax.php', { oper: 'qryemps', dept: $('#qry_dept').val() },
                    function(data) {
                        $('#qry_emps').html('').val('').append('<option value="">請選擇人員</option>');
                        for (var i = 0; i < data.length; i++) {
                            var item = data[i].EMPL_CHN_NAME;
                            $('#qry_emps')
                                .append($("<option></option>")
                                    .attr("value", item)
                                    .text(item)
                                );
                        }
                    },
                    'json'
                )
            };

            $('#qry_emps').on('change', function() {
                oTable.columns(1)
                    .search(this.value, false, false)
                    .draw();
            });

        },
        'json'
    );
}

function openHoildayForm(sn) {
    if (typeof sn === 'undefined')
        return;

    var url = "update_form.php?fra=1&sn=" + sn;

    $('.NewPage-IFrame').attr("src", url);
    $('#fullscrModal').modal('show');
}