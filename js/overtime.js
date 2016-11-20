$( // 表示網頁完成後才會載入
    function date_fill()
    {

        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/overtime_ajax.php',
            data: { oper: 'qry_first' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {

                $('#empl_no').append(JData["empl_no"]);
                $('#empl_name').append(JData["empl_name"]);
                $('#dname').append(JData["dname"]);
                $('#tname').append(JData["tname"]);

                //年份
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $('#uyear,#byear,#eyear').append(row0);

                for (var i = parseInt( JData["year"] ) -1 ; i <= parseInt( JData["year"] ) + 1 ; i++) {
                    if (i == parseInt( JData["year"] ))
                        var row = "<option value=" + i + " selected>" + i + "</option>";
                    else
                        var row = "<option value=" + i + ">" + i + " </option>";
                    $('#uyear,#byear,#eyear').append(row);
                }

                //月份
                var row0 = "<option selected disabled class='text-hide'>請選擇月份</option>";
                $('#umonth,#bmonth,#emonth').append(row0);

                for (var i = 1 ; i <= 12 ; i++) {
                    if (i == parseInt( JData["month"] ))
                        var row = "<option value=" + i + " selected>" + i + "</option>";
                    else
                        var row = "<option value=" + i + ">" + i + " </option>";
                    $('#umonth,#bmonth,#emonth').append(row);
                }

                //日
                var row0 = "<option selected disabled class='text-hide'>請選擇日期</option>";
                $('#uday,#bday,#eday').append(row0);

                var monthday = ["31","28","31","30","31","30","31","31","30","31","30","31"];
                var bmd = monthday[ JData["month"] - 1 ];    //開始那個月的日數
                            if( JData["month"] == 2 && ( ( ( JData["month"] + 1911 ) %4 == 0  && ( JData["year"] + 1911 ) % 100 != 0 ) ) || ( JData["year"] + 1911 ) % 400 == 0 )//閏年且為二月
                                bmd = bmd + 1;

                for (var i = 0 ; i <= bmd ; i++) {
                    if (i == parseInt( JData["date"] ))
                        var row = "<option value=" + i + " selected>" + i + "</option>";
                    else
                        var row = "<option value=" + i + ">" + i + " </option>";
                    $('#uday,#bday,#eday').append(row);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $.ajax({
            url: 'ajax/overtime_ajax.php',
            data: { oper: 'btime' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0="<option value=''>請選擇</option><option value='0800'>08:00</option>";

                for(var i=0 ; i < JData.DO_TIME2.length ; i++)
                {
                    var do_time2 = JData.DO_TIME2[i];
                    var do_time = JData.DO_TIME[i];

                    row0 = row0 + "<option value=" + do_time + ">" + do_time2 + "</option>";
                }

                row0 = row0 + "<option value='1300'>13:00</option>";
                row0 = row0 + "<option value='1700'>17:00</option>";

                $('#btime').append(row0);

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $.ajax({
            url: 'ajax/overtime_ajax.php',
            data: { oper: 'btime_cn' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {

                var row = "";
                var cn = JData.COUNT;

                for (var i = 1 ; i <= 30 ; i++)
                {
                    if (i < 10)
                        if (cn > 0)
                            row = row + "<option value='160" + i + "'>16:0" + i + "</option>";
                        else
                            row = row + "<option value='170" + i + "'>17:0" + i + "</option>";
                    else
                        if ( cn > 0)
                            row = row + "<option value='16" + i + "'>16:" + i + "</option>";
                        else
                            row = row + "<option value='17" + i + "'>17:" + i + "</option>";
                }

                $('#btime').append(row);

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $.ajax({
            url: 'ajax/overtime_ajax.php',
            data: { oper: 'etime' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option value=''>請選擇</option><option value='1200'>12:00</option><option value='1700'>17:00</option>";

                for(var i=0 ; i < JData.DO_TIME2.length ; i++)
                {
                    var do_time2 = JData.DO_TIME2[i];
                    var do_time = JData.DO_TIME[i];

                    row0 = row0 + "<option value=" + do_time + ">" + do_time2 + "</option>";
                }

                $('#etime').append(row0);

            },
            error: function(xhr, ajaxOptions, thrownError) {/*console.log(xhr.responseText);alert(xhr.responseText);*/}
        });

    }
);


function timesum()
{
    var uyval, umval, udval;
    var byval, bmval, bdval;
    var eyval, emval, edval;
    var btval, etval, reval;

    uyval = $('#uyear').val();

    umval = $('#umonth').val();

    udval = $('#uday').val();

    byval = $('#byear').val();

    bmval = $('#bmonth').val();

    bdval = $('#bday').val();

    eyval = $('#eyear').val();

    emval = $('#emonth').val();

    edval = $('#eday').val();

    btval = $('#btime').val();

    etval = $('#etime').val();

    //var row0="<option value=' " + reasonstr + "'>" + reasonstr + "</option>"
    //$('#reason_sub').append(row0);

    //recho = document.holiday.etime.selectedIndex;
    //reval = document.holiday.etime.options[recho].value;

    //var reasonstr = document.holiday.reason.value;
    var reason = $('#reason').val();
    /*for(var i = 0 ; i<=reasonstr.length ;i++)
        reason[i] = parseInt( reasonstr.charCodeAt(i) );*/
    //alert(reason);

    $.ajax({
        url: 'ajax/overtime_ajax.php',
        data:{  oper: 'timesum' ,
                uyear: uyval, umonth: umval, uday: udval,
                byear: byval, bmonth: bmval, bday: bdval,
                eyear: eyval, emonth: emval, eday: edval,
                btime: btval, etime: etval, reason: reason
            },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else
            {
                alert(JData);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}