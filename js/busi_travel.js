$( // 表示網頁完成後才會載入
    function(){

        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/busi_travel_ajax.php',
            data: { oper: 'qry_first' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $('#qry_year').append(row0);
                for (var i = JData["year"]-1 ; i <= JData["year"]+1 ; i++) {
                    if (i == JData["year"])
                        var row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        var row = "<option value=" +i+ ">" + i + " </option>";
                    $('#qry_year').append(row);
                }

                var row0 = "<option selected disabled class='text-hide'>請選擇月份</option>";
                $('#qry_month').append(row0);
                for (var i = 1 ; i <= 12 ; i++) {
                    if (i == JData["month"])
                        var row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        var row = "<option value=" +i+ ">" + i + " </option>";
                    $('#qry_month').append(row);
                }

                var row0 = "<option selected value='請選擇單位'>請選擇單位</option>";
                $('#qry_dpt').append(row0);
                for (var i = 0 ; i < JData["dpt"]["DEPT_FULL_NAME"].length ; i++) {
                    var row = "<option value=" + JData["dpt"]["DEPT_NO"][i] + ">" + JData["dpt"]["DEPT_FULL_NAME"][i] + "</option>";
                    $('#qry_dpt').append(row);
                }
                //CRUD(0);//首次進入頁面query
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });


        $('#qry_year,#qry_month,#qry_dpt').change( // 抓取區域選完的資料
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0); //query
                }
            }
        );
    }
);

function CRUD(oper, id) {
    id = id || ''; //預設值
    var yyval, mmval, dptval, typeval;
    if (oper == 2)
        if (!confirm("確定銷核?")) return false;


    yyval = $ ('#qry_year').val();
    mmval = $ ('#qry_month').val();
    dptval = $ ('#qry_dpt').val();

    $('#Btable').DataTable({
        "scrollY": "500px",
        "scrollCollapse": true,
        "displayLength": 10,
        "destroy": true,
        "paginate": true,
        "lengthChange": true,
        "processing": false,
        "serverSide": false,
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
        "ajax": {
            url: 'ajax/busi_travel_ajax.php',
            data: { oper: 0, year: yyval, month: mmval, department: dptval },
            type: 'POST',
            dataType: 'json'
        },
        "columns": [
            { "name": "DEPT_SHORT_NAME" },
            { "name": "EMPL_CHN_NAME" },
            { "name": "POVDATEB" },
            { "name": "POVDATEE" },
            { "name": "POVDAYS" },
            { "name": "EPLACE" },
            { "name": "POREMARK" },
            { "name": "BUTTON1" }
        ],
    });


    $.ajax({
        url: 'ajax/busi_travel_ajax.php',
        data: { oper: oper , year: yyval, month: mmval, department: dptval, SERIALNO: id },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else{
                    if (oper == 2) { //更新
                        if(JData)
                            toastr["success"]("出差銷核成功!");
                        else
                            toastr["error"]("出差銷核有誤!");
                        CRUD(0); //reload
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}