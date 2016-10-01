var arr_dept;
$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        //載入學院資料
        $.ajax({
            url: 'ajax/exam_class_set_ajax.php',
            data: { oper: 'qry_campus' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇學院</option>";
                $('#qry_campus').append(row0);
                for (var i = 0; i < JData.ID.length; i++) {
                    var row = "<option value=" + JData.ID[i] + ">" + JData.NAME[i] + "</option>";
                    $('#qry_campus').append(row);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });

        //載入考區資料
        $.ajax({
            url: 'ajax/exam_class_set_ajax.php',
            data: { oper: 'qry_area' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>選擇考區</option>";
                $('#qry_area').append(row0);
                for (var i = 0; i < JData.TEST_AREA_ID.length; i++) {
                    var row = "<option value=" + JData.TEST_AREA_ID[i] + ">" + JData.TEST_AREA_NAME[i] + "</option>";
                    $('#qry_area').append(row);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });


        $('#qry_campus').change( //選擇學院後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    $.ajax({
                        url: 'ajax/exam_class_set_ajax.php',
                        data: {
                            oper: 'qry_dept_org',
                            campus_id: $('#qry_campus').val()
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function(JData) {
                            $('#list1').empty();
                            for (var i = 0; i < JData.ID.length; i++) {
                                var row = "<option value=" + JData.ID[i] + ">" + JData.NAME[i] + "--" + JData.ORA_NAME[i] + "(" + JData.COUNT[i] + "人)</option>";
                                $('#list1').append(row);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {}
                    });
                }
            }
        );

        $('#qry_area').change( //選擇考區後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    $.ajax({
                        url: 'ajax/exam_class_set_ajax.php',
                        data: {
                            oper: 'qry_building',
                            test_area_id: $('#qry_area').val()
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function(JData) {
                            $('#qry_building').empty();
                            var row0 = "<option selected disabled class='text-hide'>系館</option>";
                            $('#qry_building').append(row0);
                            for (var i = 0; i < JData.BUILDING_ID.length; i++) {
                                var row = "<option value=" + JData.BUILDING_ID[i] + ">" + JData.BUILDING_NAME[i] + "</option>";
                                $('#qry_building').append(row);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {}
                    });
                }
            }
        );

        $('#qry_building').change( //選擇系館後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    $.ajax({
                        url: 'ajax/exam_class_set_ajax.php',
                        data: {
                            building_id: $('#qry_building').val(),
                            oper: 'qry_classroom'
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function(JData) {
                            if (JData.error_code)
                                toastr["error"](JData.error_message);
                            else {
                                $('#_content').empty();
                                var seat_count = 0;
                                for (var i = 0; i < JData.CLASSROOM_ID.length; i++) {
                                    var row = "<tr align='center'>";
                                    row = row + "<td style='padding:0px;margin:0px;border:0px'><input class='text-center' value = '" + JData.CLASSROOM_ID[i] + "'  name='id" + JData.CLASSROOM_ID[i] + "' id='id" + JData.CLASSROOM_ID[i] + "' type='text' size='2' disabled></td>";
                                    row = row + "<td style='padding:0px;margin:0px;border:0px'><input class='text-center' value = '" + JData.SEAT_NUMBER[i] + "' name='seat_number" + JData.CLASSROOM_ID[i] + "' id='seat_number" + JData.CLASSROOM_ID[i] + "' type='text' size='2'></td>";
                                    row = row + "<td style='padding:0px;margin:0px;border:0px'><input value = '" + JData.COMMENTS[i] + "' name='comments" + JData.CLASSROOM_ID[i] + "' id='comments" + JData.CLASSROOM_ID[i] + "' type='text' size='12'></td>";
                                    row = row + "</tr>";
                                    $('#_content').append(row);
                                    seat_count = seat_count + Number(JData.SEAT_NUMBER[i]); //加總座位數
                                }
                                $("#seat_count").html(seat_count);
                            }
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {}
                    });
                }
            }
        );

        //加總試場總座位
        $("#classroom").on("change", "[id*='seat_number']", function() {
            var seat_count = 0;
            $("[id*='seat_number']").each(function(index) {
                seat_count = seat_count + Number($(this).val()); //加總座位數
                $("#seat_count").html(seat_count);
            });
        });

        //送出排列
        $('#arrange').click(
            function() {
                var seat_count = Number($("#seat_count").html()) ;
                var arrange_count = Number($("#arrange_count").html()) ;
                if (seat_count==0 || seat_count != arrange_count) {
                    message("試場總座位與欲排列人數不相等，無法安排座位!", "danger", 5000);
                    return false;
                }
                if (!confirm("是否確定要送出排列?"))
                    return false;
                var str_arrange = combine_str(list2); //將欲排列人數(系所組別)代碼組合成字串
                $("#str_arrange").val(str_arrange); //寫入隱藏欄位
                $.ajax({
                    url: 'ajax/exam_class_set_ajax.php',
                    data: $("#form1").serialize() + "&oper=arrange",
                    type: 'POST',
                    dataType: "json",
                    success: function(JData) {
                        if (JData.error_code)
                            message(JData.error_message, "danger", 5000);
                        else {
                            toastr["success"]("試場座位配置成功!");
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {}
                });

            }
        );

        //全部重排
        $('#re_arrange').click(
            function() {
                if (!confirm("全部重排將會清除所有已配置之試場座位，是否確定要全部重排?"))
                    return false;
                // var str_arrange = combine_str(list2); //將欲排列人數(系所組別)代碼組合成字串
                // $("#str_arrange").val(str_arrange); //寫入隱藏欄位
                $.ajax({
                    url: 'ajax/exam_class_set_ajax.php',
                    data: $("#form1").serialize() + "&oper=re_arrange",
                    type: 'POST',
                    dataType: "json",
                    success: function(JData) {
                        if (JData.error_code)
                            message(JData.error_message, "danger", 5000);
                        else {
                            toastr["success"]("試場座位配置已全部重排!");
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {}
                });

            }
        );

    }
);

//<!-- select box move up /down /left/right-->
var c = 0;
//加總欲排列人數
function list2_count() {
    var arrange_count = 0;
    $("#list2 option").each(function(index, el) {
        var pos1 = $(this).text().indexOf('(');
        var pos2 = $(this).text().indexOf('人)');
        //console.log($(this).text().substring(pos1 + 1, pos2));
        arrange_count = arrange_count + Number($(this).text().substring(pos1 + 1, pos2));
    });
    $("#arrange_count").html(arrange_count);
};

function move(fbox, tbox) {
    c = 0;
    var flag = 0; //判斷目的選單是否已存在相同文字
    for (var i = 0; i < fbox.options.length; i++) {
        if (fbox.options[i].selected && fbox.options[i].value != "") {
            fbox.options[i].selected = false;
            c++;
            var no = new Option();
            no.value = fbox.options[i].value;
            no.text = fbox.options[i].text;
            flag = 0;
            for (var j = 0; j < tbox.options.length; j++) {
                if (tbox.options[j].value == no.value) flag = 1;
            }
            if (flag == 0) tbox.options[tbox.options.length] = no; //目的選單不存在相同文字才做
            fbox.options[i].value = "";
            fbox.options[i].text = "";
        }
    }
    BumpUp(fbox);
    list2_count();
}

function move_v(n) {
    var index = document.form1.list2.selectedIndex;
    var list2 = document.form1.list2;
    var total = list2.options.length - 1;
    if (n == "-") to = -1;
    else to = 1;
    if (index == -1) return;
    if (to == 1 && index == total) return;
    if (to == -1 && index == 0) return;
    var items = new Array;
    var values = new Array;
    for (i = total; i >= 0; i--) {
        items[i] = list2.options[i].text;
        values[i] = list2.options[i].value;
    }
    for (i = total; i >= 0; i--) {
        if (index == i) {
            list2.options[i + to] = new Option(items[i], values[i], 0, 1);
            list2.options[i] = new Option(items[i + to], values[i + to]);
            i--;
        } else list2.options[i] = new Option(items[i], values[i]);
    }
    list2.focus();
}


function BumpUp(box) {
    for (var i = 0; i < box.options.length; i++) {
        if (box.options[i].value == "") {
            for (var j = i; j < box.options.length - 1; j++) {
                box.options[j].value = box.options[j + 1].value;
                box.options[j].text = box.options[j + 1].text;
            }
            var ln = i;
            break;
        }
    }
    if (ln < box.options.length) box.options.length -= 1;
    if (c > 1) {
        c--;
        BumpUp(box);
    }
}

function add_org(fbox, tbox) {
    var flag = 0; //判斷目的選單是否已存在相同文字
    var no = new Option();
    no.value = fbox.value;
    no.text = fbox.value;

    for (var j = 0; j < tbox.options.length; j++) {
        if (tbox.options[j].value == no.value) flag = 1;
    }

    if (flag == 0) tbox.options[tbox.options.length] = no; //目的選單不存在相同文字才做
    fbox.value = "";
}

//將欲排列人數合並成一字串
function combine_str(tbox) {
    var str = "";
    var c = 0;
    for (var i = 0; i < tbox.options.length; i++) {
        if (tbox.options[i].value != "") {
            c++;
            if (c == 1) str = tbox.options[i].value;
            else str = str + '、' + tbox.options[i].value;
        }
    }
    return str;
    //document.signup_1.union_str.value=str;
}
