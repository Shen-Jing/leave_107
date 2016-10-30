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

                $('#empl_no').append(JData["empl_no"][0]);
                $('#empl_name').append(JData["empl_name"][0]);
                $('#dname').append(JData["dname"][0]);
                $('#tname').append(JData["tname"][0]);

                //年份
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $('#uyear,#byear,#eyear').append(row0);

                for (var i = parseInt( JData["year"][0] ) -1 ; i <= parseInt( JData["year"][0] ) + 1 ; i++) {
                    if (i == parseInt( JData["year"][0] ))
                        var row = "<option value=" + i + " selected>" + i + "</option>";
                    else
                        var row = "<option value=" + i + ">" + i + " </option>";
                    $('#uyear,#byear,#eyear').append(row);
                }

                //月份
                var row0 = "<option selected disabled class='text-hide'>請選擇月份</option>";
                $('#umonth,#bmonth,#emonth').append(row0);

                for (var i = 1 ; i <= 12 ; i++) {
                    if (i == parseInt( JData["month"][0] ))
                        var row = "<option value=" + i + " selected>" + i + "</option>";
                    else
                        var row = "<option value=" + i + ">" + i + " </option>";
                    $('#umonth,#bmonth,#emonth').append(row);
                }

                //日
                var row0 = "<option selected disabled class='text-hide'>請選擇日期</option>";
                $('#uday,#bday,#eday').append(row0);

                var monthday = ["31","28","31","30","31","30","31","31","30","31","30","31"];
                var bmd = monthday[ JData["month"][0] - 1 ];    //開始那個月的日數
                            if( JData["month"][0] == 2 && ( ( ( JData["month"][0] + 1911 ) %4 == 0  && ( JData["year"][0] + 1911 ) % 100 != 0 ) ) || ( JData["year"][0] + 1911 ) % 400 == 0 )//閏年且為二月
                                bmd = bmd + 1;

                for (var i = 0 ; i <= bmd ; i++) {
                    if (i == parseInt( JData["date"][0] ))
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
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        /*$('uyear,#byear,#eyear,#umonth,#bmonth,#emonth,#uday,#bday,#eday').change( // 抓取區域選完的資料
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0); //query
                }
            }
        );*/
    }
);

function timesum()
{
    var uycho, umcho, udcho, uyval, umval, udval;
    var bycho, bmcho, bdcho, byval, bmval, bdval;
    var eycho, emcho, edcho, eyval, emval, edval;
    var btcho, btval, etcho, etval;

    uycho = document.holiday.uyear.selectedIndex;
    uyval = document.holiday.uyear.options[uycho].value;

    umcho = document.holiday.umonth.selectedIndex;
    umval = document.holiday.umonth.options[umcho].value;

    udcho = document.holiday.uday.selectedIndex;
    udval = document.holiday.uday.options[udcho].value;

    bycho = document.holiday.byear.selectedIndex;
    byval = document.holiday.byear.options[bycho].value;

    bmcho = document.holiday.bmonth.selectedIndex;
    bmval = document.holiday.bmonth.options[bmcho].value;

    bdcho = document.holiday.bday.selectedIndex;
    bdval = document.holiday.bday.options[bdcho].value;

    eycho = document.holiday.eyear.selectedIndex;
    eyval = document.holiday.eyear.options[eycho].value;

    emcho = document.holiday.emonth.selectedIndex;
    emval = document.holiday.emonth.options[emcho].value;

    edcho = document.holiday.eday.selectedIndex;
    edval = document.holiday.eday.options[edcho].value;

    btcho = document.holiday.btime.selectedIndex;
    btval = document.holiday.btime.options[btcho].value;

    etcho = document.holiday.etime.selectedIndex;
    etval = document.holiday.etime.options[etcho].value;

    var reason = document.holiday.reason.value;


    $.ajax({
        url: 'ajax/overtime_ajax.php',
        data:{  oper: 'time' ,
                uyear: uyval, umonth: umval, uday: udval,
                byear: byval, bmonth: bmval, bday: bdval,
                eyear: eyval, emonth: emval, eday: edval,
                btime: btval, etime: etval,
                reason: reason
            },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else
            {
                alert(JData["message"][0]);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}