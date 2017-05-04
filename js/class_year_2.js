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

        $('#class_year,#class_acadm').change( // 抓取區域選完的資料
            function(e) {
                if ($('#class_year').val() !== null && $('#class_acadm').val() !== null) {
                    $('#store').attr("disabled", false);
                    $('#insert').attr("disabled", false);
                    CRUD(0,"init"); //query
                }
            }
        );

  }
);

function toDate(dateStr) {
    const [year, month, day] = dateStr.split("/")
    return new Date(year, month - 1, day)
}

function CRUD(oper, status) {

    if(oper == 0)
    {
        //本次填寫紀錄
        $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_record', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val() },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
              $('#_content').empty();

              if (JData.error_code)
                  toastr["error"](JData.error_message);
              else{
              if (JData.length == "0"){
                  var row_part_new = "<center style='color:red'>無任何記錄。</center>";
                  $('#_content').append(row_part_new);

              }else {
                var row0 ="";
                row0 = row0 + "<table class='table table-bordered col-md-8'><tbody><tr>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>上課班別</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>科目名稱</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>原上課時間</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>補課時間</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>補課教室</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>補課節次</td>";
                if(JData["SERIALNO"].length != 0)
                    row0 = row0 + "<td class='td1' style='text-align:center;'>刪除或修改申請單</td>";
                row0 = row0 + "</tr>";

                for(var i = 0 ; i < JData["CLASS_NAME"].length ; i++){

                  row0 = row0 + "<tr><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_NAME"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_SUBJECT"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_DATE"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_DATE2"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_ROOM"][i];

                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_SECTION2"][i];
                  if( JData["SERIALNO"][i] != null )
                  {
                    row0 = row0 + "</td><td  style='text-align:center;'>" ;
                    row0 = row0 + "<button id='delete' name='delete' class='btn-danger' type='button' onclick='DeleteRow(" + JData["CLASS_NO"][i] + "," + JData["SERIALNO"][i] + ")'; title='刪除'>刪除</button>" ;
                    row0 = row0 + " <button id='edit' name='edit' class='btn-primary' type='button' onclick='EditRow(" + JData["CLASS_NO"][i] + "," + JData["SERIALNO"][i] + "," + 2 + ")' title='修改'>修改</button>" ;
                  }
                  row0 = row0 + "</td></tr>";

                }
                row0 = row0 + "</tbody></table>";
                $('#_content').append(row0);
              }
            }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        if(status != "delete")
        {
            $('#class_section2, #class_room, #class_memo').blur();
            $('#class_section2, #class_room, #class_memo').css("background-color","white");
            $('#class_section2, #class_room, #class_memo').val("");

            //填寫表單部分
            //科目
            $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_subject', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val() },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                $('#subject_name').empty();

                if (JData.error_code)
                    toastr["error"](JData.error_message);
                else
                {
                    var row0 = "<option selected disabled value = '' class='text-hide'>請選擇科目</option>";

                    for(var i = 0 ; i < JData["SCR_SELCODE"].length ; i++)
                    {
                        row0 = row0 + "<option class_no = '' value='" + JData["SCR_SELCODE"][i] + "'>" + JData["SCR_SELCODE"][i] + JData["SUB_NAME"][i] + "</option>";
                    }
                    $('#subject_name').append(row0);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
            });

            //上課班別
            $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_class_id', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val() },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                $('#class_name').empty();
                $('#scr_period').empty();

                if (JData.error_code)
                    toastr["error"](JData.error_message);
                else
                {
                    $('#class_name').append(JData["class_name"]);
                    $('#scr_period').append(JData["scr_period"]);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
            });

            //補課節次
            $ ('#class_section2_1').empty();
            row0 = "<option selected disabled class='text-hide'>請選擇節次</option>";
            $ ('#class_section2_1').append(row0);
            for (var i = 1; i <= 13 ; i++)
            {
                row = "<option value=" +i+ ">" + i + " </option>";
                $ ('#class_section2_1').append(row);
            }

            $ ('#class_section2_2').empty(0);
            row0 = "<option selected disabled class='text-hide'>請選擇節次</option>";
            $ ('#class_section2_2').append(row0);
            for (var i = 1; i <= 13 ; i++)
            {
                    row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#class_section2_2').append(row);
            }

            var start_options = {
                ignoreReadonly: true,
                defaultDate: date,
                maxDate: date,
                format: 'YYYY/MM/DD',
                tooltips: {
                    clear: "清除所選",
                    close: "關閉日曆",
                    decrementHour: "減一小時",
                    decrementMinute: "Decrement Minute",
                    decrementSecond: "Decrement Second",
                    incrementHour: "加一小時",
                    incrementMinute: "Increment Minute",
                    incrementSecond: "Increment Second",
                    nextMonth: "下個月",
                    nextYear: "下一年",
                    pickHour: "Pick Hour",
                    pickMinute: "Pick Minute",
                    pickSecond: "Pick Second",
                    prevMonth: "上個月",
                    prevYear: "前一年",
                    selectMonth: "選擇月份",
                    selectTime: "選擇時間",
                    selectYear: "選擇年份",
                    today: "今日日期",
                },
                locale: 'zh-tw',
            }
            var end_options = {
                ignoreReadonly: true,
                defaultDate: date,
                minDate: start_options.defaultDate,
                format: 'YYYY/MM/DD',
                tooltips: {
                    clear: "清除所選",
                    close: "關閉日曆",
                    decrementHour: "減一小時",
                    decrementMinute: "Decrement Minute",
                    decrementSecond: "Decrement Second",
                    incrementHour: "加一小時",
                    incrementMinute: "Increment Minute",
                    incrementSecond: "Increment Second",
                    nextMonth: "下個月",
                    nextYear: "下一年",
                    pickHour: "Pick Hour",
                    pickMinute: "Pick Minute",
                    pickSecond: "Pick Second",
                    prevMonth: "上個月",
                    prevYear: "前一年",
                    selectMonth: "選擇月份",
                    selectTime: "選擇時間",
                    selectYear: "選擇年份",
                    today: "今日日期",
                },
                locale: 'zh-tw',
            }
            end_options.useCurrent = false;


            $('#origin_time').datetimepicker(start_options);
            $('#change_time').datetimepicker(end_options);
            $("#origin_time").on("dp.change", function (e) {
                // 調補課日期的最早為原上課日期所選
                $('#change_time').data("DateTimePicker").minDate(e.date);
                // 將調補課日期同步為原上課日期所選
                $('#change_time').data("DateTimePicker").date(e.date);
            });

        }
    }

}

function DeleteRow(classno, serialno)
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
$("#no_holiday_form").bootstrapValidator({
    live: 'submitted',
    fields: {
        subject_name: {
            validators: {
                notEmpty: {
                    message: '請選擇科目'
                }
            }
        },
        origin_time: {
            validators: {
                notEmpty: {
                    message: '原上課日期日期不可空白'
                },
                date: {
                    format: 'YYYY/MM/DD',
                    message: '不正確的日期格式！'
                }
            }
        },
        change_time: {
            validators: {
                notEmpty: {
                    message: '調補課日期不可空白'
                },
                date: {
                    format: 'YYYY/MM/DD',
                    message: '不正確的日期格式！'
                }
            }
        },
        class_section2_1: {
            validators: {
                notEmpty: {
                    message: '請選擇補課節次'
                }
            }
        },
        class_section2_2: {
            validators: {
                notEmpty: {
                    message: '請選擇補課節次'
                },
            }
        },
        class_room: {
            validators: {
                notEmpty: {
                    message: '請填寫補課教室'
                },
            }
        }

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

function EditRow(classno, serialno)
{
    var edit_class_selcode;
    var edit_class_code;
    var edit_class_name;
    var edit_scr_period;
    var edit_cyear_origin = null;
    var edit_cmonth_origin;
    var edit_cday_origin;
    var edit_dyear_origin = null;
    var edit_dmonth_origin;
    var edit_dday_origin;
    var edit_class_section2_1;
    var edit_class_section2_2;
    var edit_class_memo;
    var edit_class_room;

    //所選紀錄之資料
    $.ajax({
        url: 'ajax/class_year_2_ajax.php',
        data: { oper: 'edit_fill', serialno: serialno, class_no: classno },
        type: 'POST',
        dataType: "json",
        success: function(JData) {

            if (JData.error_code)
                toastr["error"](JData.error_message);
            else
            {

                $("#ChangeModal1").modal("hide");
                $("#ChangeModal2 .modal-title").html("紀錄修改");
                $("#ChangeModal2").modal("show"); //弹出框show
                $('#update').empty();
                $('#update').append("<button type='submit' class='btn btn-primary' name='" + classno + "' id='update_btn' value='"+ serialno + "'>修改資料儲存</button>");

                $('#edit_class_name, #edit_scr_period, #edit_class_section2_1, #edit_class_section2_2, #edit_class_room, #edit_class_memo, #edit_origin_time, #edit_change_time').empty();

                edit_class_selcode = JData.CLASS_SELCODE;
                edit_class_code = JData.CLASS_CODE;
                edit_class_name  = JData.CLASS_NAME;
                edit_scr_period = JData.CLASS_SECTION;
                edit_class_section2_1 = JData.CLASS_SECTION2.toString().split("-")[0];
                edit_class_section2_2 = JData.CLASS_SECTION2.toString().split("-")[1];
                edit_cyear_origin = (parseInt(JData.CLASS_DATE.toString().substring(0,3))+1911).toString();
                edit_cmonth_origin = JData.CLASS_DATE.toString().substring(3,5);
                edit_cday_origin = JData.CLASS_DATE.toString().substring(5,7);
                edit_dyear_origin = (parseInt(JData.CLASS_DATE2.toString().substring(0,3))+1911).toString();
                edit_dmonth_origin = JData.CLASS_DATE2.toString().substring(3,5);
                edit_dday_origin = JData.CLASS_DATE2.toString().substring(5,7);
                edit_class_memo = JData.CLASS_MEMO;
                edit_class_room = JData.CLASS_ROOM;

                $('#edit_class_name').append(edit_class_code + edit_class_name);
                $('#edit_scr_period').append(edit_scr_period);

                $('#edit_class_room').val(edit_class_room);
                $('#edit_class_memo').val(edit_class_memo);

                var _eco = edit_cyear_origin + "/" + edit_cmonth_origin + "/" + edit_cday_origin;
                var _edo = edit_dyear_origin + "/" + edit_dmonth_origin + "/" + edit_dday_origin;

                var eco = toDate(_eco);
                var edo = toDate(_edo);

                //補課節次
                row0 = "<option disabled class='text-hide'>請選擇節次</option>";
                $ ('#edit_class_section2_1').append(row0);
                for (var i = 1; i <= 13 ; i++)
                {
                    if(i == parseInt(edit_class_section2_1) )
                        row = "<option value=" +i+ " selected>" + i + " </option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#edit_class_section2_1').append(row);
                }
                row0 = "<option disabled class='text-hide'>請選擇節次</option>";
                $ ('#edit_class_section2_2').append(row0);
                for (var i = 1; i <= 13 ; i++)
                {
                    if(i == parseInt(edit_class_section2_2) )
                        row = "<option value=" +i+ " selected>" + i + " </option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#edit_class_section2_2').append(row);
                }

                //原上課日期及調補課日期
                var edit_start_options = {
                    ignoreReadonly: true,
                    defaultDate: eco,
                    maxDate: date,
                    format: 'YYYY/MM/DD',
                    tooltips: {
                        clear: "清除所選",
                        close: "關閉日曆",
                        decrementHour: "減一小時",
                        decrementMinute: "Decrement Minute",
                        decrementSecond: "Decrement Second",
                        incrementHour: "加一小時",
                        incrementMinute: "Increment Minute",
                        incrementSecond: "Increment Second",
                        nextMonth: "下個月",
                        nextYear: "下一年",
                        pickHour: "Pick Hour",
                        pickMinute: "Pick Minute",
                        pickSecond: "Pick Second",
                        prevMonth: "上個月",
                        prevYear: "前一年",
                        selectMonth: "選擇月份",
                        selectTime: "選擇時間",
                        selectYear: "選擇年份",
                        today: "今日日期",
                    },
                    locale: 'zh-tw',
                }
                var edit_end_options = {
                    ignoreReadonly: true,
                    defaultDate: edo,
                    format: 'YYYY/MM/DD',
                    tooltips: {
                        clear: "清除所選",
                        close: "關閉日曆",
                        decrementHour: "減一小時",
                        decrementMinute: "Decrement Minute",
                        decrementSecond: "Decrement Second",
                        incrementHour: "加一小時",
                        incrementMinute: "Increment Minute",
                        incrementSecond: "Increment Second",
                        nextMonth: "下個月",
                        nextYear: "下一年",
                        pickHour: "Pick Hour",
                        pickMinute: "Pick Minute",
                        pickSecond: "Pick Second",
                        prevMonth: "上個月",
                        prevYear: "前一年",
                        selectMonth: "選擇月份",
                        selectTime: "選擇時間",
                        selectYear: "選擇年份",
                        today: "今日日期",
                    },
                    locale: 'zh-tw',
                }
                edit_start_options.useCurrent = false;
                edit_end_options.useCurrent = false;

                $('#edit_origin_time').datetimepicker(edit_start_options);
                $('#edit_change_time').datetimepicker(edit_end_options);
                $('#edit_origin_time').data("DateTimePicker").date(eco);
                $('#edit_change_time').data("DateTimePicker").date(edo);

                $("#edit_origin_time").on("dp.change", function (e) {
                    // 調補課日期的最早為原上課日期所選
                    $('#edit_change_time').data("DateTimePicker").minDate(e.date);
                    // 將調補課日期同步為原上課日期所選
                    $('#edit_change_time').data("DateTimePicker").date(e.date);
                });
            }

        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });


    //修改表單部分
    //科目
    $.ajax({
    url: 'ajax/class_year_2_ajax.php',
    data: { oper: 'qry_subject', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val() },
    type: 'POST',
    dataType: "json",
    success: function(JData) {
        $('#edit_subject_name').empty();

        if (JData.error_code)
            toastr["error"](JData.error_message);
        else
        {
            var row0 = "<option disabled value = '' class='text-hide'>請選擇科目</option>";

            for(var i = 0 ; i < JData["SCR_SELCODE"].length ; i++)
            {
                if(JData["SCR_SELCODE"][i] == edit_class_selcode)
                    row0 = row0 + "<option class_no = '' value='" + JData["SCR_SELCODE"][i] + "' selected>" + JData["SCR_SELCODE"][i] + JData["SUB_NAME"][i] + "</option>";
                else
                    row0 = row0 + "<option class_no = '' value='" + JData["SCR_SELCODE"][i] + "'>" + JData["SCR_SELCODE"][i] + JData["SUB_NAME"][i] + "</option>";
            }

            $('#edit_subject_name').append(row0);
        }

    },
    error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}

//bootstrapValidator
$("#update_form").bootstrapValidator({
    live: 'submitted',
    fields: {
        edit_subject_name: {
            validators: {
                notEmpty: {
                    message: '請選擇科目'
                }
            }
        },
        edit_origin_time: {
            validators: {
                notEmpty: {
                    message: '原上課日期日期不可空白'
                },
                date: {
                    format: 'YYYY/MM/DD',
                    message: '不正確的日期格式！'
                }
            }
        },
        edit_change_time: {
            validators: {
                notEmpty: {
                    message: '調補課日期不可空白'
                },
                date: {
                    format: 'YYYY/MM/DD',
                    message: '不正確的日期格式！'
                }
            }
        },
        edit_class_section2_1: {
            validators: {
                notEmpty: {
                    message: '請選擇補課節次'
                }
            }
        },
        edit_class_section2_2: {
            validators: {
                notEmpty: {
                    message: '請選擇補課節次'
                },
            }
        },
        edit_class_room: {
            validators: {
                notEmpty: {
                    message: '請輸入補課教室'
                },
            }
        }
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
