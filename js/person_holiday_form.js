$( // 表示網頁完成後才會載入
    function()
    {

        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/person_holiday_form_ajax.php',
            data: { oper: 'qry_dpt' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {

                //單位
                var row = "<option value=''>請選擇單位</option>";
                for(var i = 0 ; i < JData.DEPT_NO.length ; i++)
                {
                    row = row + "<option value=" + JData.DEPT_NO[i] + ">" + JData.DEPT_FULL_NAME[i] + "</option>";
                }
                $('#dpt').append(row);
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $.ajax({
            url: 'ajax/person_holiday_form_ajax.php',
            data: { oper: 'qry_type' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {

                //假別
                var row = "<option value=''>請選擇假別</option>";
                for(var i = 1 ; i < JData.CODE_FIELD.length ; i++)
                {
                    row = row + "<option value=" + JData.CODE_FIELD[i] + ">" + JData.CODE_CHN_ITEM[i] + "</option>";
                }
                $('#type').append(row);
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });


        $('#dpt').change(
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0,1); //query
                }
            }
        );
        $('#user').change(
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0,2); //query
                }
            }
        );
    }
);

function CRUD(oper, id) {
    id = id || ''; //預設值

    if(id == 1)
    {
        $.ajax({
                url: 'ajax/person_holiday_form_ajax.php',
                data: { oper: 'qry_name' ,dpt: $('#dpt').val()},
                type: 'POST',
                dataType: "json",
                success: function(JData) {
                    //姓名
                    $('#user,#empl_no,#tname').empty();
                    var row="<option value=''>請選擇</option>";

                    for(var i=0 ; i < JData.EMPL_NO.length ; i++)
                    {

                        row = row + "<option value=" + JData.EMPL_NO[i] + ">" + JData.EMPL_CHN_NAME[i] + "</option>";
                    }

                    $('#user').append(row);

                },
                error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
            });
    }
    else if(id == 2)
    {
        var empl_no = $('#user').val();
        $.ajax({
                url: 'ajax/person_holiday_form_ajax.php',
                data: { oper: 'fill_data' ,dpt: $('#dpt').val(), empl_no: empl_no},
                type: 'POST',
                dataType: "json",
                success: function(JData) {

                    $('#empl_no,#tname').empty();
                    $('#empl_no').append(empl_no);
                    $('#tname').append(JData.CODE_CHN_ITEM);
                },
                error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
            });
    }
}

function holidaycheck()
{
    var dpt, user, empl_no, tname, type, hday, hour, btime, etime;

    dpt = $('#dpt').val();

    empl_no = $('#empl_no').text();

    tname = $('#tname').text();

    user = $('#user').val();

    type = $('#type').val();

    hday = $('#hday').val();

    hour = $('#hour').val();

    btime = $('#btime').val();

    etime = $('#etime').val();

    $.ajax({
        url: 'ajax/person_holiday_form_ajax.php',
        data:{  oper: 'check' ,
                dpt: dpt, empl_no: empl_no,
                tname: tname, type: type,
                hday: hday, hour: hour,
                btime: btime, etime: etime,
            },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else
            {
                alert(JData);
                // if(JData.length < 6)
                //     toastr["success"](JData);
                // else
                //     toastr["error"](JData);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });

}