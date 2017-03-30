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
            // alert("submit");
            $("#ChangeModal2 .modal-title").html("不休假獎金清冊");
            $("#ChangeModal2").modal("show"); //弹出框show

            var do_process = $('input[name=do_process]:checked').val();
            var oper;
            if(do_process == 3)
                oper = 2;

            var who = $('input[name=id]:checked').val();

            $('#Btable').DataTable({
                "scrollY": "500px",
                "scrollCollapse": true,
                "displayLength": 10,
                "destroy": true,
                "columnDefs": [
                    {"className": "dt-center", "targets": "_all"}
                ],
                "ajax": {
                    url: 'ajax/no_rest_ajax.php',
                    data: { oper: oper, who: who },
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
                    { "name": "APPDATE" }
                ]
            });

            // $.ajax({
            //     url: 'ajax/no_rest_ajax.php',
            //     data: { oper: oper, who: who},
            //     type: 'POST',
            //     dataType: "json",
            //     success: function(JData) {
            //         alert(JData);

            //     },
            //     error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
            // });

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