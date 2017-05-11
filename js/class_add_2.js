var date = new Date();
var year = date.getFullYear() - 1911;
$(
    function (){
        $ ('#class_year').empty();
        row0 = "<option selected disabled value = '' class='text-hide'>請選擇學年度</option>";
        $ ('#class_year').append(row0);
        for (var i = year - 1 ; i <= year + 1 ; i++)
        {
            if (i == year)
                row = "<option value=" +i+ " selected>" + i + "</option>";
            else
                row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#class_year').append(row);
        }
        CRUD(0);
  }
);

$('#class_year').change( // 抓取區域選完的資料
    function(e) {
        if ($('#class_year').val() !== "") {
            // alert($('#class_year').val());
            CRUD(0); //query
        }
    }
);


function CRUD(oper) {

    var yyval, empl_no, depart;

    yyval = $ ('#class_year').val();
    // empl_no = $ ('#qry_dpt_empl').val();
    // depart = $ ('#depart').text();

    // alert("CRUD");

    if(oper == 0)
    {
        $('#Btable').DataTable({
            "scrollY": "500px",
            "scrollCollapse": true,
            "displayLength": 10,
            "destroy": true,
            "columnDefs": [
                {"className": "dt-center", "targets": "_all"}
            ],
            "ajax": {
                url: 'ajax/class_add_2_ajax.php',
                data: { oper: 0, p_year: yyval},
                type: 'POST',
                dataType: 'json'
            },
            "columns": [
                { "name": "DEPT_SHORT_NAME" },
                { "name": "EMPL_CHN_NAME" },
                { "name": "BUTTON1" },
                { "name": "BUTTON2" }
            ],
        });
    }

}

function View(serialno)
{
    $.ajax({
            url: 'ajax/class_add_2_ajax.php',
            data: { oper: 'view', serialno: serialno },
            type: 'POST',
            dataType: "json",
            success: function(JData) {

                if (JData.error_code)
                    toastr["error"](JData.error_message);
                else
                {
                    $('#view_data').empty();
                    $("#ChangeModal1").modal("hide");
                    $("#ChangeModal2 .modal-title").html("本次調補課填寫內容");
                    $("#ChangeModal2").modal("show"); //弹出框show

                    $('#view_data').append(JData);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });
}

function Edit(serialno)
{
    if(confirm("確定要刪除嗎?"))
    $.ajax({
        url: 'ajax/class_year_2_ajax.php',
        data: { oper: 'del', serialno: serialno, class_no: classno},
        type: 'POST',
        dataType: "json",
        success: function(JData) {

            if (JData.error_code)
                toastr["error"](JData.error_message);
            else
            {
                if(JData.length == 7)
                    toastr["success"](JData);
                else
                    toastr["error"](JData);

                CRUD(0,"delete");
            }

        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}

//bootstrapValidator
$("#no_holiday_form")
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
    var $form = $(e.target),     // Form instance
        $button = $form.data('bootstrapValidator').getSubmitButton();

        switch ($button.attr('id')) {
            case 'insert':
                var class_year = $('#class_year').val();
                var class_acadm = $('#class_acadm').val();

                $.ajax({
                    url: 'ajax/class_year_2_ajax.php',
                    data: { oper: 'new', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val(), class_subject: $('#subject_name').val(),
                            class_name: $('#class_name').text(), scr_period: $('#scr_period').text() , class_section2_1: $('#class_section2_1').val(),
                             class_section2_2: $('#class_section2_2').val(), class_room: $('#class_room').val(), class_memo: $('#class_memo').val(), origin_time: $('#origin_time').val(),
                            change_time: $('#change_time').val() },
                    type: 'POST',
                    dataType: "json",
                    success: function(JData) {

                        if (JData.error_code)
                            toastr["error"](JData.error_message);
                        else
                        {
                            if(JData.length == 7)
                            {
                                toastr["success"](JData);
                                CRUD(0,"insert");
                            }
                            else
                            {
                                alert(JData);
                                toastr["error"](JData);
                            }
                        }

                    },
                    error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
                });
                break;

            case 'store':
                var class_year = $('#class_year').val();
                var class_acadm = $('#class_acadm').val();

                $.ajax({
                    url: 'ajax/class_year_2_ajax.php',
                    data: { oper: 'store', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val(), class_subject: $('#subject_name').val(),
                            class_name: $('#class_name').text(), scr_period: $('#scr_period').text() , class_section2_1: $('#class_section2_1').val(),
                             class_section2_2: $('#class_section2_2').val(), class_room: $('#class_room').val(), class_memo: $('#class_memo').val(), origin_time: $('#origin_time').val(),
                            change_time: $('#change_time').val() },
                    type: 'POST',
                    dataType: "json",
                    success: function(JData) {

                        if (JData.error_code)
                            toastr["error"](JData.error_message);
                        else
                        {
                            if(JData == "簽核完成!")
                            {
                                toastr["success"](JData);
                                CRUD(0,"insert");
                            }
                            else
                            {
                                alert(JData);
                                toastr["error"](JData);
                            }
                        }

                    },
                    error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
                });
                break;
        }

        e.preventDefault();
        // e.unbind();

});


//bootstrapValidator
$("#update_form")
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
        $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'update', serial_no: $('#update_btn').val(), class_no: $('#update_btn').attr("name"), class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val(), class_subject: $('#edit_subject_name').val(),
                    class_name: $('#edit_class_name').text(), scr_period: $('#edit_scr_period').text() , class_section2_1: $('#edit_class_section2_1').val(),  class_section2_2: $('#edit_class_section2_2').val(), class_room: $('#edit_class_room').val(),
                    class_memo: $('#edit_class_memo').val(), edit_origin_time: $('#edit_origin_time').val(), edit_change_time: $('#edit_change_time').val() },
            type: 'POST',
            dataType: "json",
            success: function(JData) {

                if (JData.error_code)
                    toastr["error"](JData.error_message);
                else
                {
                    if(JData.length == 7)
                    {
                        toastr["success"](JData);
                        CRUD(0,"update");
                        $("#ChangeModal2").modal("hide");
                    }
                    else
                    {
                        alert(JData);
                        toastr["error"](JData);
                    }
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
    e.preventDefault();
});
