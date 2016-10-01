// BAG_ID DDDAASSCCC
//        DDD = 科系
//        DDDAASS = 科目
//        CCC = 教室
// MAKENUMBER DDDAAXXXSS
//            XXX = 准考證流水號

var bag_timer;
var barcode_timer;
var bag_id;
var bag_no;
var order = 0;
var record = new Array(null);
var first_score_record = new Array(null);
var first_on_off_record = new Array(null);
var one_total;
var two_total;
var storing = 1;

$(
    function() {
        $('#dept_subject').hide();
        $('#Btable').hide();
        $('#store').hide();
        $('#bag').focus();
        creat_bag_timer(618);
    }
);

$("#bag_serial").keypress(function(e) {
    if (e.which == 13) { //enter
        $("#makenumber").focus();
    }
});

//等待輸入試卷袋號碼
function creat_bag_timer(timing) {
    bag_timer = setInterval(function() {
        bag_id = $('#bag').val();
        if (bag_id.length >= 10 && bag_id.substr(0,1)>=0 && bag_id.substr(0,1)<=9) {
            //clearInterval(bag_timer);
            GetInfo(parseInt(bag_id));
        }
    }, timing);
}


//等待輸入製卷碼
function creat_barcode_timer(timing) {
    barcode_timer = setInterval(function() {
        if ($('#makenumber').val().length >= 10) {
            //clearInterval(barcode_timer);
            barcode = $('#makenumber').val();
            $('#makenumber').val("");
            CheckData(barcode);
        }
    }, timing);
}

//取得系所及考科
function GetInfo(bag) {
    $.ajax({
        url: 'ajax/pen_grade_nd_ajax.php',
        data: {
            bag: bag,
            info: 1
        },
        type: 'POST',
        dataType: 'json',
        success: function(Jdata) {
            //clearInterval(bag_timer);
            if (Jdata.DNAME.length === 0) {
                alert("試卷袋號碼錯誤，請重新輸入!");
                $('#bag').val("");
                $('#bag_serial').val("");
                $('#dept').html("");
                $('#subject').html("");
                $('#bag').focus();
                creat_bag_timer(618);
            } else if (Jdata.ONE[0].length === 0) {
                alert("此包試卷尚未輸入第一次成績！！");
                $('#bag').val("");
                $('#bag').focus();
                //window.location = 'pen_grade.php';
            } else if (Jdata.TWO[0]) {
                alert("此包試卷已經完成兩次成績輸入了！！");
                $('#bag').val("");
                $('#bag').focus();
                //window.location = 'home.php';
            } else {
                one_total = Jdata.BNO.length;
                two_total = 0;
                for (var i = 0; i < Jdata.BNO.length; i++) {
                    if (Jdata.ONE[i] == 1) {
                        ++two_total; //總筆數
                    } else {
                        break;
                    }
                }
                $('#bag_serial').val(Jdata.BNO[0]);
                bag_no = Jdata.BNO[0];
                $('#dept').text("系所：" + Jdata.DNAME[0]);
                $('#subject').text("考試科目：" + Jdata.SNAME[0]);
                $('#Btable').show();
                $('#dept_subject').show();
                $('#store').show();
                bag_timer = setInterval(function() {
                    if ($('#bag').val() != bag_id) {
                        //clearInterval(bag_timer);
                        $('#Btable').hide();
                        $('#storeR').hide();
                        $('#info_table').hide();
                        record = [null];
                        $('#_content').empty();
                        creat_bag_timer(618);
                    }
                }, 1618);
                bag_id = $('#bag').val().substr(0, 10);
                order = 0;
                $('#makenumber').focus();
                creat_barcode_timer(618);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            creat_bag_timer(618);
            alert("發生錯誤！！請重新整理後再試一次![error code: GI201]");
        }
    });
}

function CheckData(Din) {
    $.ajax({
        url: 'ajax/pen_grade_nd_ajax.php',
        data: {
            check_exist: 1,
            barcode: Din,
            bag: bag_id
        },
        type: 'POST',
        dataType: 'json',

        success: function(Jdata) {
            if (Jdata.MAKENUMBER[0] == -1) {
                alert("製卷碼錯誤，請重新輸入!");
                $('#makenumber').val("");
                $('#makenumber').focus();
                creat_barcode_timer(618);
            } else {
                MakeInput(Din, Jdata.FIRSTSCORE[0], Jdata.ON_OFF_EXAM[0], Jdata.ORDERNUMBER[0]);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $('#makenumber').val("");
            $('#makenumber').focus();
            creat_barcode_timer(618);
            alert("執行發生錯誤，請重新整理後再試一次！ [error code: CD201]");
        }
    });
}

function InputEnter() {
    if (event.keyCode == 13) //enter
        CheckInput(idx);
}
//檢查輸入的成績格式是否正確
function CheckInput(idx) {
    var score = $('#' + record[idx]).val();
    if (score === '') {
        return;
    } else if ($('#' + record[idx]).data("checked") != score) {
        if (score == "/" || score == "*" || score == "x" || score == "X" || score == "=" || score == "-" || score == "＝" || score == "－" || score == "＊" || score == "／" || score == "\\" || score == "Ｘ" || score == "缺考" || score == "缺") {
            $('#' + record[idx]).val("缺考");
            if (first_on_off_record[idx] != 1) {
                alert("第" + idx + "張試卷碼( " + record[idx] + " )的第二次輸入：" + $('#' + record[idx]).val() + "，\n與第一次的成績：" + first_score_record[idx] + "分，不一致！");
                $('#' + record[idx]).focus();
            }
        } else if (score > -1 && score < 101) {
            if (first_on_off_record[idx] != 0) { //第一次輸入缺考
                alert("第" + idx + "張試卷碼( " + record[idx] + " )的成績：" + $('#' + record[idx]).val() + "分，\n與第一次的成績：" + first_score_record[idx] + "分（缺考），不一致！");
                $('#' + record[idx]).focus();
            } else if ($('#' + record[idx]).val() != first_score_record[idx]) {
                alert("第" + idx + "張試卷碼( " + record[idx] + " )的成績：" + $('#' + record[idx]).val() + "分，\n與第一次的成績：" + first_score_record[idx] + "分，不一致！");
                $('#' + record[idx]).focus();
            } else {
                if (event.keyCode == 13) //enter
                    $('#makenumber').focus();
            }
        } else {
            alert("第" + idx + "張試卷\n試卷條碼為 " + record[idx] + "的成績輸入格式有誤！\n請重新輸入！");
            $('#' + record[idx]).val("");
            $('#' + record[idx]).focus();
            return;
        }
    }
    $('#' + record[idx]).data("checked", score);
}

//檢查製卷碼成績是否已輸入
function MakeInput(Din, first_score, on_off, first_order) {
    for (var i = 0; i <= order; i++) {
        if (record[i] == Din) {
            alert(Din + "已輸入過 (流水號：" + i + ")");
            $('#makenumber').val("");
            creat_barcode_timer(618);
            return;
        }
    }
    ++order;
    record.push(Din);
    first_score_record.push(first_score);
    first_on_off_record.push(on_off);
    if (on_off == 1) {
        first_score = "缺考";
    }
    var Gin = "<div class=\"input-field\" id=\"grade_inF\"><input size=\"5\" id=\"" + Din + "\" type=\"text\" class=\"text-center\" onkeyup=\"InputEnter(" + order + ")\" onblur=\"CheckInput(" + order + ");\" style='font-size:17px;font-weight:bold;color:#0000FF'></div>";
    var data = "<tr><td>" + first_order + "/" + one_total + "</td><td>" + order + "  /" + two_total + "</td><td>" + Din + "</td><td>" + first_score + "</td><td>" + Gin + "</td></tr>";
    $('#_content').append(data);
    $('#makenumber').val("");
    creat_barcode_timer(618);
    $('#' + Din).focus();
}

//送出前再全部檢查一遍
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

//儲存
function store() {
    if (!confirm("是否確定要儲存?"))
        return false;
    if (order != two_total) {
        alert("試卷張數與資料庫記錄張數不符合，操作終止！");
        $('#makenumber').focus();
        return;
    }
    for (; storing < record.length; storing++) {
        if (DoubleCheckInput(storing)) {
            alert("成績輸入有誤，請確認 第 " + storing + " 張／條碼為 " + record[storing] + " 的試卷！！");
            $('#' + record[storing]).focus();
            return;
        }
        //$('#' + record[storing]).prop("disabled", true);
    }
    
    // clearInterval(barcode_timer);
    // clearInterval(bag_timer);
    // $('#bag').prop("disabled", true);
    // $('#bag_serial').prop("disabled", true);
    // $('#makenumber').prop("disabled", true);

    //alert("資料儲存中。。。\n按下確定後，請等待成功存入之訊息！");
    StoreData();
}

function StoreData() {
    var grade = [null];
    for (var i = 1; i < record.length; i++) {
        grade.push($('#' + record[i]).val());
    }
    $.ajax({
        url: 'ajax/pen_grade_nd_ajax.php',
        data: {
            store: 1,
            bag: bag_id,
            makenum: record,
            score: grade,
            bag_serial: bag_no,
            size: record.length
        },
        type: 'POST',
        dataType: 'json',
        success: function(Jdata) {
            console.log(Jdata);
            var check_point = 0;
            for (var i = 1; i < record.length; i++) {
                if (Jdata.SCORE[i - 1] == grade[i] && Jdata.MAKENUMBER[i - 1] == record[i] && Jdata.IS_SECONDINPUT[i - 1] == 1) {
                    ++check_point;
                } else {
                    check_point = 0; //儲存後只要有一筆不同即算失敗
                    break;
                }
            }
            if (check_point > 0) {
                alert("試卷袋" + bag_id + " 第二次成績儲存成功！！");
                location.reload();
            } else {
                alert("儲存時發生錯誤！！ [error code: SD202]");
            }
        },
        bforeSend: function() {
            $('#loading').show();
            $('#store').empty();
        },
        complete: function() {
            $('#loading').hide();
            location.reload();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert("發生錯誤！！請重新整理後再試一次！！ [error code: SD201]");
        }
    });
}

//no-use
function instruction_ok() {
    if ($("#bag").val() === "") {
        $('#bag').focus();
    } else if ($("#bag_serial").val() === "") {
        $('#bag_serial').focus();
    } else if ($("#" + record[order]).val() === "") {
        $("#" + record[order]).focus();
    } else {
        $("#makenumber").focus();
    }
}
