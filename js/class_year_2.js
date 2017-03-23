$(
    function (){
        $("body").tooltip({
            selector: "[title]"
        });

        $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_year'},
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled value = '' class='text-hide'>請選擇學年度</option>";
                $ ('#class_year').append(row0);
                for (var i = JData - 1 ; i <= JData + 1 ; i++)
                {
                    if (i == JData)
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#class_year').append(row);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);
            alert("error!!");}
        });
        $('#class_year,#class_acadm').change( // 抓取區域選完的資料
            function(e) {
                if ($('#class_year').val() !== null && $('#class_acadm').val() !== null) {
                    CRUD(0,"init"); //query
                }
            }
        );

  }
);


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

            //原上課日期及調補課日期
            $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_dates'},
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled value = '' class='text-hide'>請選擇年份</option>";
                $ ('#cyear').empty();
                $ ('#cyear').append(row0);
                for (var i = JData["cyear"] - 1 ; i <= JData["cyear"] + 1 ; i++)
                {
                    if (i == JData["cyear"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#cyear').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇月份</option>";
                $ ('#cmonth').empty();
                $ ('#cmonth').append(row0);
                for (var i = 1 ; i <= 12 ; i++)
                {
                    if (i == JData["cmonth"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#cmonth').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
                $ ('#cday').empty();
                $ ('#cday').append(row0);
                var days = new Date( parseInt(JData["cyear"])+1911,JData["cmonth"],0).getDate();
                for (var i = 1 ; i <= days ; i++)
                {
                    if (i == JData["cday"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#cday').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇年份</option>";
                $ ('#dyear').empty();
                $ ('#dyear').append(row0);
                for (var i = JData["dyear"] - 1 ; i <= JData["dyear"] + 1 ; i++)
                {
                    if (i == JData["dyear"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#dyear').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇月份</option>";
                $ ('#dmonth').empty();
                $ ('#dmonth').append(row0);
                for (var i = 1 ; i <= 12 ; i++)
                {
                    if (i == JData["dmonth"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#dmonth').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
                $ ('#dday').empty();
                $ ('#dday').append(row0);
                var days = new Date( parseInt(JData["cyear"])+1911 ,JData["cmonth"],0).getDate();
                for (var i = 1 ; i <= days ; i++)
                {
                    if (i == JData["dday"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#dday').append(row);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);
            alert("error!!");}
            });
            $('#edit_cmonth,#edit_cyear').change(
                function(e) {
                    if ($(':selected', this).val() !== null) {
                            GetDays('ec');
                        }
                }
            );
            $('#edit_dmonth,#dedit_year').change(
                function(e) {
                    if ($(':selected', this).val() !== null) {
                            GetDays('ed');
                        }
                }
            );
        }
    }

}

function GetDays(type)
{
    if(type == 'c')
    {
        var days = new Date( parseInt($('#cyear').val())+1911, $('#cmonth').val(), 0).getDate();
        row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
        $ ('#cday').empty();
        $ ('#cday').append(row0);
        for (var i = 1 ; i <= days ; i++)
        {
            row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#cday').append(row);
        }
    }
    else if(type == 'd')
    {
        var days = new Date( parseInt($('#dyear').val())+1911, $('#dmonth').val(), 0).getDate();
        row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
        $ ('#dday').empty();
        $ ('#dday').append(row0);
        for (var i = 1 ; i <= days ; i++)
        {
            row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#dday').append(row);
        }
    }
    else if(type == 'ec')
    {
        var days = new Date( parseInt($('#edit_cyear').val())+1911, $('#edit_cmonth').val(), 0).getDate();
        row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
        $ ('#edit_cday').empty();
        $ ('#edit_cday').append(row0);
        for (var i = 1 ; i <= days ; i++)
        {
            row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#edit_cday').append(row);
        }
    }
    else if(type == 'ed')
    {
        var days = new Date( parseInt($('#edit_dyear').val())+1911, $('#edit_dmonth').val(), 0).getDate();
        row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
        $ ('#edit_dday').empty();
        $ ('#edit_dday').append(row0);
        for (var i = 1 ; i <= days ; i++)
        {
            row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#edit_dday').append(row);
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

function Insert()
{

    var class_year = $('#class_year').val();
    var class_acadm = $('#class_acadm').val();

    $.ajax({
        url: 'ajax/class_year_2_ajax.php',
        data: { oper: 'new', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val(), class_subject: $('#subject_name').val(),
                class_name: $('#class_name').text(), scr_period: $('#scr_period').text() , class_section2: $('#class_section2').val(), class_room: $('#class_room').val(),
                class_memo: $('#class_memo').val(), cyear: $('#cyear').val(), cmonth: $('#cmonth').val(), cday: $('#cday').val(),
                dyear: $('#dyear').val(), dmonth: $('#dmonth').val(), dday: $('#dday').val() },
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
                    toastr["error"](JData);
            }

        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}

function EditRow(classno, serialno)
{
    var edit_class_selcode;
    var edit_class_code;
    var edit_class_name;
    var edit_scr_period;
    var edit_cyear_origin;
    var edit_cmonth_origin;
    var edit_cday_origin;
    var edit_dyear_origin;
    var edit_dmonth_origin;
    var edit_dday_origin;
    var edit_class_section2;
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
                $('#update').append("<button class='btn btn-primary' name='update_btn' id='update_btn' onclick='Update(" + classno + ", " + serialno + ")'>修改資料儲存</button>");

                $('#edit_class_name').empty();
                $('#edit_scr_period').empty();
                $('#edit_class_section2').empty();
                $('#edit_class_room').empty();
                $('#edit_class_memo').empty();

                edit_class_selcode = JData.CLASS_SELCODE;
                edit_class_code = JData.CLASS_CODE;
                edit_class_name  = JData.CLASS_NAME;
                edit_scr_period = JData.CLASS_SECTION;
                edit_class_section2 = JData.CLASS_SECTION2;
                edit_cyear_origin = JData.CLASS_DATE.toString().substring(0,3);
                edit_cmonth_origin = JData.CLASS_DATE.toString().substring(3,5);
                edit_cday_origin = JData.CLASS_DATE.toString().substring(5,7);
                edit_dyear_origin = JData.CLASS_DATE2.toString().substring(0,3);
                edit_dmonth_origin = JData.CLASS_DATE2.toString().substring(3,5);
                edit_dday_origin = JData.CLASS_DATE2.toString().substring(5,7);
                edit_class_memo = JData.CLASS_MEMO;
                edit_class_room = JData.CLASS_ROOM;

                $('#edit_class_name').append(edit_class_code + edit_class_name);
                $('#edit_scr_period').append(edit_scr_period);
                $('#edit_class_section2').val(edit_class_section2);
                $('#edit_class_room').val(edit_class_room);
                $('#edit_class_memo').val(edit_class_memo);
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

    // //原上課日期及調補課日期
    $.ajax({
    url: 'ajax/class_year_2_ajax.php',
    data: { oper: 'qry_dates'},
    type: 'POST',
    dataType: "json",
    success: function(JData) {
        row0 = "<option  disabled value = '' class='text-hide'>請選擇年份</option>";
        $ ('#edit_cyear').empty();
        $ ('#edit_cyear').append(row0);
        for (var i = JData["cyear"] - 1 ; i <= JData["cyear"] + 1 ; i++)
        {
            if (i == edit_cyear_origin)
                row = "<option value=" +i+ " selected>" + i + "</option>";
            else
                row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#edit_cyear').append(row);
        }

        row0 = "<option selected disabled value = '' class='text-hide'>請選擇月份</option>";
        $ ('#edit_cmonth').empty();
        $ ('#edit_cmonth').append(row0);
        for (var i = 1 ; i <= 12 ; i++)
        {
            if (i == edit_cmonth_origin)
                row = "<option value=" +i+ " selected>" + i + "</option>";
            else
                row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#edit_cmonth').append(row);
        }

        row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
        $ ('#edit_cday').empty();
        $ ('#edit_cday').append(row0);
        var days = new Date( parseInt(JData["cyear"])+1911,JData["cmonth"],0).getDate();
        for (var i = 1 ; i <= days ; i++)
        {
            if (i == edit_cday_origin)
                row = "<option value=" +i+ " selected>" + i + "</option>";
            else
                row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#edit_cday').append(row);
        }

        row0 = "<option selected disabled value = '' class='text-hide'>請選擇年份</option>";
        $ ('#edit_dyear').empty();
        $ ('#edit_dyear').append(row0);
        for (var i = JData["dyear"] - 1 ; i <= JData["dyear"] + 1 ; i++)
        {
            if (i == edit_dyear_origin)
                row = "<option value=" +i+ " selected>" + i + "</option>";
            else
                row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#edit_dyear').append(row);
        }

        row0 = "<option selected disabled value = '' class='text-hide'>請選擇月份</option>";
        $ ('#edit_dmonth').empty();
        $ ('#edit_dmonth').append(row0);
        for (var i = 1 ; i <= 12 ; i++)
        {
            if (i == edit_dmonth_origin)
                row = "<option value=" +i+ " selected>" + i + "</option>";
            else
                row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#edit_dmonth').append(row);
        }

        row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
        $ ('#edit_dday').empty();
        $ ('#edit_dday').append(row0);
        var days = new Date( parseInt(JData["cyear"])+1911 ,JData["cmonth"],0).getDate();
        for (var i = 1 ; i <= days ; i++)
        {
            if (i == edit_dday_origin)
                row = "<option value=" +i+ " selected>" + i + "</option>";
            else
                row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#edit_dday').append(row);
        }

    },
    error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);
    alert("error!!");}
    });
    $('#cmonth,#cyear').change(
        function(e) {
            if ($(':selected', this).val() !== null) {
                    GetDays('ec');
                }
        }
    );
    $('#dmonth,#dyear').change(
        function(e) {
            if ($(':selected', this).val() !== null) {
                    GetDays('ed');
                }
        }
    );

}

function Update(classno, serialno)
{

    $.ajax({
        url: 'ajax/class_year_2_ajax.php',
        data: { oper: 'update', class_no: classno, serial_no: serialno, class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val(), class_subject: $('#edit_subject_name').val(),
                class_name: $('#edit_class_name').text(), scr_period: $('#edit_scr_period').text() , class_section2: $('#edit_class_section2').val(), class_room: $('#edit_class_room').val(),
                class_memo: $('#edit_class_memo').val(), cyear: $('#edit_cyear').val(), cmonth: $('#edit_cmonth').val(), cday: $('#edit_cday').val(),
                dyear: $('#edit_dyear').val(), dmonth: $('#edit_dmonth').val(), dday: $('#edit_dday').val() },
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
                    // alert(JData);
                    toastr["error"](JData);
            }

        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });

}


