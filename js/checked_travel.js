$( // 表示網頁完成後才會載入
    function (){

        $ ("body").tooltip({
            selector: "[title]"
        });

        $ .ajax({
            url: 'ajax/checked_travel_ajax.php',
            data: { oper: 'qry_year' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $ ('#qry_year').append(row0);
                for (var i = JData["year"] - 1 ; i <= JData["year"]+1 ; i++)
                {
                    if (i == JData["year"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#qry_year').append(row);
                }

                row0 = "<$option selected disabled class='text-hide'>請選擇月份</option>";
                $ ('#qry_month').append(row0);
                for (var i = 1; i <= 12 ; i++)
                {
                    if (i == JData["month"] )
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#qry_month').append(row);
                }

                //CRUD(0);//首次進入頁面query
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $ .ajax({
            url: 'ajax/checked_travel_ajax.php',
            data: { oper: 'qry_dpt' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected value='請選擇單位'>請選擇單位</option>";
                $ ('#qry_dpt').append(row0);

                for (var i = 0; i < JData.DEPT_NO.length ; i++)
                {
                    var depart = JData.DEPT_NO[i];
                    var dept_name = JData.DEPT_FULL_NAME[i];
                    row = "<option value=" + depart + ">" + dept_name + "</option>";

                    $ ('#qry_dpt').append(row);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });


        $ ('#qry_year,#qry_month,#qry_dpt').change( // 抓取區域選完的資料
            function(e) {
                if ($ (':selected', this).val() !== '')
                {
                    CRUD(0); //query
                }
            }
        );
    }
);

function CRUD(oper, id) {
    id = id || ''; //預設值
    var yyval, mmval, dptval, typeval;

    yyval = $ ('#qry_year').val();
    mmval = $ ('#qry_month').val();
    dptval= $ ('#qry_dpt').val();

    $('#Btable').DataTable({
        "scrollY": "500px",
        "scrollCollapse": true,
        "displayLength": 10,
        "destroy": true,
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
        "ajax": {
            url: 'ajax/checked_travel_ajax.php',
            data: { oper: 0, p_year: yyval, p_month: mmval, dpt: dptval},
            type: 'POST',
            dataType: 'json'
        },
        "columns": [
            { "name": "DEPT_SHORT_NAME" },
            { "name": "EMPL_CHN_NAME" },
            { "name": "POVDATEB" },
            { "name": "POVDATEE" },
            { "name": "POVDAYS" },
            { "name": "POREMARK" },
            { "name": "TRAVEL_DATE" }
        ]
    });
}