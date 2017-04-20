$( // 表示網頁完成後才會載入
    function ()
    {

        $("body").tooltip({
            selector: "[title]"
        });

        //bootstrapValidator
        $("#no_rest").bootstrapValidator({
            live: 'submitted',
            fields: {
                id: {
                    validators: {
                        notEmpty: {
                            message: '請選擇欲計算對象'
                        },
                    }
                },
                do_process: {
                    validators: {
                        notEmpty: {
                            message: '請選擇欲進行作業'
                        }
                    }
                },
            }
        })
        // 不論表單驗證正確與否時，皆可按下表單按鈕
        // Triggered when any field is invalid
        .on('error.field.bv', function(e, data) {
            data.bv.disableSubmitButtons(false);
        })
        // Triggered when any field is valid
        .on('success.field.bv', function(e, data) {
            data.bv.disableSubmitButtons(false);
        })
        //submit by ajax----------------------------------
        .on( 'success.form.bv' , function(e) {

            var do_process = $('input[name=do_process]:checked').val();
            var who = $('input[name=id]:checked').val();
            var oper;
            var base_month = $('#base_month').val();

            if(do_process == 1)
            {
                oper = 0;
                $.ajax({
                        url: 'ajax/no_rest_ajax.php',
                        data: { oper: oper, who: who, base_month: base_month },
                        type: 'POST',
                        dataType: "json",
                        success: function(JData) {
                            alert(JData);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
                });
            }
            else if(do_process == 3)
            {
                $("#ChangeModal2 .modal-title").html("不休假獎金清冊");
                $("#ChangeModal2").modal("show"); //弹出框show
                oper = 2;
                var rows = 0;
                var table = $('#Btable').DataTable({
                    "scrollY": "500px",
                    "scrollCollapse": true,
                    "displayLength": 10,
                    "destroy": true,
                    "spans":true,
                    "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ],
                    "ajax": {
                        url: 'ajax/no_rest_ajax.php',
                        data: { oper: oper, who: who },
                        type: 'POST',
                        dataType: 'json',
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
                        { "name": "APPDATE" }
                    ],
                    "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        // Total over all pages
                        // 改發加班費金額
                        overtime_tot = api
                            .column( 8 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        //超過14天補助
                        overforteen = api
                            .column( 9 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        //合計
                        total = api
                            .column( 10 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        // Update footer
                        // 改發加班費金額
                        $( api.column( 8 ).footer() ).html(
                            overtime_tot
                        );

                        //超過14天補助
                        $( api.column( 9 ).footer() ).html(
                            overforteen
                        );

                        //合計
                        $( api.column( 10 ).footer() ).html(
                            total
                        );

                        var info = this.api().page.info();
                        $( api.column( 0 ).footer() ).html(
                            '全部統計 ' + info.recordsTotal + ' 人'
                        );
                    },

                });
            }


            e.preventDefault();
            e.unbind();

        });
    }
);

// function action(oper) {


//     if(oper == 2)
//     {

//         var who = $('#id').val();

//         $('#Btable').DataTable({
//             "scrollY": "500px",
//             "scrollCollapse": true,
//             "displayLength": 10,
//             "destroy": true,
//             "columnDefs": [
//                 {"className": "dt-center", "targets": "_all"}
//             ],
//             "ajax": {
//                 url: 'ajax/no_rest_ajax.php',
//                 data: { oper: oper, who: who },
//                 type: 'POST',
//                 dataType: 'json'
//             },
//             "columns": [
//                 { "name": "DEPT_SHORT_NAME" },
//                 { "name": "EMPL_CHN_NAME" },
//                 { "name": "CODE_CHN_ITEM" },
//                 { "name": "POVDATEB" },
//                 { "name": "POVDATEE" },
//                 { "name": "POVTIMEB" },
//                 { "name": "POVHOURSDAYS" },
//                 { "name": "TWOSIGND" },
//                 { "name": "PERONE_SIGND" },
//                 { "name": "PERTWO_SIGND" },
//                 { "name": "APPDATE" }
//             ]
//         });

//     }

// }