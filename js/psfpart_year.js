$(
    function() {

        $ .ajax({
            url: 'ajax/psfpart_year_ajax.php',
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
                CRUD(0); //進入頁面首次query
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $ ('#qry_dpt').change( // 抓取區域選完的資料
            function(e) {
                if ($ (':selected', this).val() !== '')
                {
                    CRUD(0); //query
                }
            }
        );
    }
);

function CRUD(oper) {

    var dptval;
    dptval= $ ('#qry_dpt').val();
    $.jgrid.gridUnload('#jqGrid');

    var oGrid =
    $("#jqGrid").jqGrid({
        url: 'ajax/psfpart_year_ajax.php',
        editurl: 'ajax/psfpart_year_ajax.php',
        postData: { oper: 0, dpt: dptval},
        pager: "#jqGridPager",
        colModel: [
            {
                label: '單位',
                name: 'DEPT_FULL_NAME',
                width: 250,
                editable: true,
                editoptions:{readonly:'readonly'}
            },
            {
                label: '姓名',
                name: 'EMPL_NO',
                width: 120,
                editable: true, // must set editable to true if you want to make the field editable
                editoptions:{readonly:'readonly'}
            },
            {
                label: '到職日期',
                name: 'CRJB_ASSI_DATE',
                width: 120,
                editable: true,
                editoptions:{readonly:'readonly'}
            },
            {
                label: '至去年年底年資 ',
                name: 'lastyear',
                width: 120,
                editable: true,
                editoptions:{readonly:'readonly'}
            },
            {
                label: '異動日期',
                name: 'RETIRE_MONTH',
                width: 120,
                editable: true,
                editoptions:{readonly:'readonly'}
            },
            {
                label: '異動原因',
                name: 'PSFPART_REMARK',
                width: 500,
                editable: true,
                editoptions:{readonly:'readonly'}
            },
            {
                label: '到校日期',
                name: 'EMPL_ARRIVE_SCH_DATE',
                width: 120,
                editable: true,
                editoptions:{readonly:'readonly'}
            },
            {
                label: '正確到校日期 ',
                name: 'SENIOR',
                width: 120,
                editable: true
            }
        ]
    }).jqGrid("clearGridData", true).jqGrid('setGridParam', {datatype:'json'}).trigger("reloadGrid");


    $("ui-jqgrid tr.jqgrow td").css("white-space","normal !important");
    $("ui-jqgrid tr.jqgrow td").css("height", "auto");
    $("ui-jqgrid tr.jqgrow td").css("vertical-align","text-top");
    $("ui-jqgrid tr.jqgrow td").css("padding-top","2px");

    oGrid.jqGrid("navGrid", '#jqGridPager', {
    },
    // options for the Edit Dialog
    {

        afterSubmit: function(response, postdata) {
            oGrid.jqGrid('setGridParam', {datatype:'json'}).trigger('reloadGrid'); //Reload after submit
            if(response.responseText == null){
                bootbox.alert({
                    message: '錯誤!',
                    backdrop: true
                })
                return [false,"error!"];
            }
            else{
                bootbox.alert({
                    message: '成功!',
                    backdrop: true,
                    size: 'small'
                });

                return [true,"OK"];
            }
        }
    },
    // options for the Add Dialog
    {
        //template: modal
    },
    // options for the Delete Dailog
    {
    });

}