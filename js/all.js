$( // 表示網頁完成後才會載入
    function (){

        $ ("body").tooltip({
            selector: "[title]"
        });

        $ .ajax({
            url: 'ajax/all_ajax.php',
            data: { oper: 'qry_year' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $ ('#qry_year').append(row0);
                for (var i = JData["year"] - 3 ; i <= JData["year"] ; i++)
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
            url: 'ajax/all_ajax.php',
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

        $ .ajax({
            url: 'ajax/all_ajax.php',
            data: { oper: 'qry_dpt_empl' ,dpt: $('#qry_dpt').val() },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                // alert("success");
                $ ('#qry_dpt_empl').empty();
                row0 = "<option value='請選擇人員' selected>請選擇人員</option>";
                for (var i = 0; i < JData.EMPL_NO.length ; i++)
                {
                    row0 = row0 + "<option value=" + JData.EMPL_NO[i] + ">" + JData.EMPL_CHN_NAME[i] + "</option>";
                }
                    $ ('#qry_dpt_empl').append(row0);

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $ .ajax({
            url: 'ajax/all_ajax.php',
            data: { oper: 'qry_type' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option value='請選擇假別' selected >請選擇假別</option>";
                $ ('#qry_type').append(row0);
                for (var i = 0; i < JData.CODE_FIELD.length ; i++)
                {
                    row = "<option value=" + JData.CODE_FIELD[i] + ">" + JData.CODE_CHN_ITEM[i] + "</option>";
                    $ ('#qry_type').append(row);
                }
                //CRUD(0);
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $('#qry_dpt').change(
            function(e){
                if ($ (':selected', this).val() !== '' && $ (':selected', this).val() !== '請選擇單位')
                {
                    $ .ajax({
                        url: 'ajax/all_ajax.php',
                        data: { oper: 'qry_dpt_empl' ,dpt: $('#qry_dpt').val() },
                        type: 'POST',
                        dataType: "json",
                        success: function(JData) {

                            $ ('#qry_dpt_empl').empty();
                            row0 = "<option selected value='請選擇人員'>請選擇人員</option>";
                            for (var i = 0; i < JData.EMPL_NO.length ; i++)
                            {
                                row0 = row0 + "<option value=" + JData.EMPL_NO[i] + ">" + JData.EMPL_CHN_NAME[i] + "</option>";
                            }
                                $ ('#qry_dpt_empl').append(row0);
                            CRUD(0);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
                    });
                }

            }
        );

        $ ('#qry_year, #qry_month, #qry_type, #qry_dpt_empl').change( // 抓取區域選完的資料
            function(e) {
                if ($ (':selected', this).val() !== '')
                {
                    CRUD(0); //query
                }
            }
        );

        $('body').on('click', "#Btable tbody tr",function(){

            $("#myModal").modal("show");
            $("#DEPT_SHORT_NAME").text($(this).closest('tr').children()[0].textContent);
            $("#EMPL_CHN_NAME").text($(this).closest('tr').children()[1].textContent);
            $("#CODE_CHN_ITEM").text($(this).closest('tr').children()[2].textContent);
            $("#POVDATEB").text($(this).closest('tr').children()[3].textContent);
            $("#POVDATEE").text($(this).closest('tr').children()[4].textContent);
            $("#POVTIMEB").text($(this).closest('tr').children()[5].textContent);
            $("#POVHOURSDAYS").text($(this).closest('tr').children()[6].textContent);
            $("#TWOSIGND").text($(this).closest('tr').children()[7].textContent);
            $("#PERONE_SIGND").text($(this).closest('tr').children()[8].textContent);
            $("#PERTWO_SIGND").text($(this).closest('tr').children()[9].textContent);
            $("#SECONE_SIGND").text($(this).closest('tr').children()[10].textContent);
            $("#APPDATE").text($(this).closest('tr').children()[11].textContent);
        });

    }
);

function CRUD(oper, id) {
    id = id || ''; //預設值
    var yyval, mmval, dptval, typeval, empl_no;

    yyval = $ ('#qry_year').val();
    mmval = $ ('#qry_month').val();
    dptval= $ ('#qry_dpt').val();
    typeval = $ ('#qry_type').val();
    empl_no = $ ('#qry_dpt_empl').val();


    $('#Btable').DataTable({
        "scrollY": "500px",
        "scrollCollapse": true,
        "displayLength": 10,
        "destroy": true,
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
        "ajax": {
            url: 'ajax/all_ajax.php',
            data: { oper: 0, p_year: yyval, p_month: mmval, dpt: dptval, type: typeval ,empl_no: empl_no },
            type: 'POST',
            dataType: 'json'
        },
        "columns": [
            { "name": "DEPT_SHORT_NAME" },
            { "name": "EMPL_CHN_NAME" },
            { "name": "CODE_CHN_ITEM" },
            { "name": "POVDATEB" },
            { "name": "POVDATEE" },
            { "name": "POVTIMEB" },
            { "name": "POVHOURSDAYS" },
            { "name": "TWOSIGND" },
            { "name": "PERONE_SIGND" },
            { "name": "PERTWO_SIGND" },
            { "name": "SECONE_SIGND" },
            { "name": "APPDATE" }
        ],

    });


}