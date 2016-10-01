var order = 0;
var record = new Array(null);
var total;
var storing = 1;

$(
    function() {
        $("#store").hide();
        $.ajax({
            url: 'ajax/manual_review_ajax.php',
            data: { oper: 'qry_dept' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇系所</option>";
                $('#qry_dept').append(row0);
                for (var i = 0; i < JData.ID.length; i++) {
                    var row = "<option value=" + JData.ID[i] + ">" + JData.NAME[i] + "</option>";
                    $('#qry_dept').append(row);
                }
                //CRUD(0);//query
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });

        // $("#info").hide();
        // $('#main_table').hide();
        // $('#storeR').hide();
        // $("#qry_subject_field").hide();
        // $("department_field").hide();
        //$('#notify').openModal();
        // $("#school").change(function () {
        //     if ($(":selected", this).val() !== '') {
        //         var sel = $("#school").val();
        //         GetDepartment(sel);
        //     }
        // });
        $("#qry_dept").change(function() {
            if ($(":selected", this).val() !== '') {
                var sel = $("#qry_dept").val();
                GetSubject(sel);
            }
        });
        $("#qry_subject").change(function() {
            if ($(":selected", this).val() !== '') {
                var sel = $("#qry_subject").val();
                GetInfo(sel);
            }
        });
    }
);


function GetSubject(sel) {
    $.ajax({
        url: 'ajax/manual_review_ajax.php',
        data: {
            oper: 'qry_subject',
            dept: sel
        },
        type: 'POST',
        dataType: 'json',
        beforeSend: function() {
            $("#qry_subject").empty();
            $("#qry_subject").append("<option value='' disabled selected>請選擇科目</option>");
        },
        success: function(Jdata) {
            for (var i = 0, option; i < Jdata.NAME.length; i++) { // 加入項目資料
                if (Jdata.ID[i] !== '') {
                    switch (Jdata.ID[i].substr(3, 1)) {
                        case '1':
                            option = "<option value='" + Jdata.ID[i] + "'>甲組－" + Jdata.NAME[i] + "</option>";
                            break;
                        case '2':
                            option = "<option value='" + Jdata.ID[i] + "'>乙組－" + Jdata.NAME[i] + "</option>";
                            break;
                        case '3':
                            option = "<option value='" + Jdata.ID[i] + "'>丙組－" + Jdata.NAME[i] + "</option>";
                            break;
                        case '4':
                            option = "<option value='" + Jdata.ID[i] + "'>丁組－" + Jdata.NAME[i] + "</option>";
                            break;
                        case '5':
                            option = "<option value='" + Jdata.ID[i] + "'>戊組－" + Jdata.NAME[i] + "</option>";
                            break;
                        default:
                            option = "<option value='" + Jdata.ID[i] + "'>" + Jdata.NAME[i] + "</option>";
                            break;
                    }
                    $('#qry_subject').append(option);
                }
            }
        },
        error: function() {}
    });
}

function GetInfo(sel) {
    $.ajax({
        url: "ajax/manual_review_ajax.php",
        data: {
            oper: 'info',
            subject: sel
        },
        type: "POST",
        dataType: "json",
        success: function(Jdata) {
            $("#_content").empty();
            total = Jdata.PSID.length;
            //$("#info01").text("總人數：" + total + "人");
            if (Jdata.FIRSTSCORE[0]) {
                alert("此科目第一次成績已輸入過！！");
                $("#store").hide();
                return false;
                
            }
            order = 0;
            record = [null];
            for (var i = 0; i < Jdata.PSID.length; i++) {
                MakeInput(Jdata.PSID[i], Jdata.NAME[i]);
            }
            //$("#main_table").show();
            $("#store").show();
            $("#" + record[1]).focus();
        },
        error: function() {
            alert("發生錯誤！！請重新整理後再試一次[error code: MRGI101]");
        }
    });
}

function MakeInput(PSID, who) {
    ++order;
    record.push(PSID);
    var Gin = "<div class=\"input-field\"><input required size=\"5\" id=\"" + PSID + "\" type=\"text\" class=\"text-center\" onkeyup=\"CheckInput(" + order + ");\" style='font-size:17px;font-weight:bold;color:#0000FF'></div>";
    var data = "<tr><td>" + order + "</td><td>" + PSID + "</td><td>" + who + "</td><td>" + Gin + "</td></tr>";
    $('#_content').append(data);
}

function CheckInput(idx) {
    var score = $('#' + record[idx]).val();
    if (score == "/" || score == "*" || score == "x" || score == "X" || score == "=" || score == "-" || score == "＝" || score == "－" || score == "＊" || score == "／" || score == "\\" || score == "Ｘ" || score == "缺考" || score == "缺") {
        $('#' + record[idx]).val("缺考");
        //if (event.keyCode == 13)
        $('#' + record[idx + 1]).focus();
    } else if (score > -1 && score < 101) {
        if (event.keyCode == 13)
            $('#' + record[idx + 1]).focus();
    } else {
        alert("第" + idx + "張試卷\n准考證號碼為 " + record[idx] + "的成績輸入格式有誤！\n請重新輸入！");
        $('#' + record[idx]).val("");
        $('#' + record[idx]).focus();
    }
}



function DoubleCheckInput(idx) {
    var score = $('#' + record[idx]).val();
    var err = 0;
    if (score == "/" || score == "*" || score == "x" || score == "X" || score == "=" || score == "-" || score == "＝" || score == "－" || score == "＊" || score == "／" || score == "\\" || score == "Ｘ" || score == "缺考" || score == "缺") {
        $('#' + record[idx]).val(-1);
    } else if (score > -1 && score < 101) {
        // good and do nothing
    } else {
        ++err;
    }
    return err;
}

function store() {
    if (!confirm("是否確定要儲存?"))
        return false;
    for (var i = 1; i < record.length; i++) {
        if ($("#" + record[i]).val() === '') {
            alert("試卷張數與資料庫記錄張數不符合，操作終止！");
            $("#" + record[i]).focus();
            return;
        }
    }
    for (; storing < record.length; storing++) {
        if (DoubleCheckInput(storing)) {
            alert("成績輸入有誤，請確認 第 " + storing + " 張／准考證號碼為 " + record[storing] + " 的試卷！！");
            $('#' + record[storing]).focus();
            return;
        }
        //$('#' + record[storing]).prop("disabled", true);
    }   

    StoreData();
}

function StoreData() {
    var grade = [null];
    for (i = 1; i < record.length; i++) {
        grade.push($('#' + record[i]).val());
    }
    $.ajax({
        url: 'ajax/manual_review_ajax.php',
        data: {
            oper: 'store',
            psid: record,
            score: grade,
            subject: $("#qry_subject").val(),
            size: record.length
        },
        type: 'POST',
        dataType: 'json',
        success: function(Jdata) {
            var check_point = 0;
            for (var i = 1; i < record.length; i++) {
                if (Jdata.ON_OFF_EXAM[i - 1] == 1) {
                    Jdata.FIRSTSCORE[i - 1] = -1;
                }
                if (Jdata.FIRSTSCORE[i - 1] == grade[i] && Jdata.PSID[i - 1] == record[i] && Jdata.IS_INPUT[i - 1] == 1) {
                    ++check_point;
                } else {
                    check_point = 0; //儲存後只要有一筆不同即算失敗
                    break;
                }
            }
            if (check_point > 0) {
                alert("科系：" + $("#department option:selected").text() + "\n招生類別：" + $("#school option:selected").text() + "\n科目：" + $("#qry_subject option:selected").text() + "\n第一次成績儲存成功！！");
                location.reload();
            } else {
                alert("儲存作業時發生錯誤！[error code: MRSD102]");
            }
        },
        bforeSend: function() {
            $('#loading').show();
            //$('#store').empty();
        },
        complete: function() {
            $('#loading').hide();
            location.reload();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert("發生錯誤！！請重新整理後再試一次！ [error code: MRSD101]");
        }
    });
}

function instruction_ok() {
    for (var i = 1; i < record.length; i++) {
        if ($("#" + record[i]).val().length === 0) {
            $("#" + record[i]).focus();
            return;
        }
    }
}
