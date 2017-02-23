$( // 表示網頁完成後才會載入
    function() {
        var date = new Date();
        var year = date.getFullYear() - 1911;
        var ad_year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        // 部門：MQ5等等的編號
        var depart = $('#hide-depart').text();

        // 修改假單的serialno
        var update_serialno = getQueryVariable("sn");

        year = year.toString();
        month = month.toString();
        day = day.toString();
        if (year.length < 3)
            year = '0' + year;
        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;
        $("body").tooltip({
            selector: "[title]"
        });

        // select內容，也就是不需根據select所選而有不同sql顯示資料的部分
        // 可以一開始就ajax取內容的
        $.ajax({
            url: 'ajax/update_form_ajax.php',
            data: {
                oper: 'qry_item',
                empl_no: $('#empl_no').text(),
                vocdate: "" + year + month + day
            },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                // 單位 select欄位
                var row0 = "<option selected disabled class='text-hide'>請選擇單位</option>";
                $('#qry_dept').append(row0);
                for (var i = 0; i < JData.qry_dept.DEPT_NO.length; i++) {
                    var row = "<option value=" + JData.qry_dept.DEPT_NO[i] + ">" + JData.qry_dept.DEPT_FULL_NAME[i] + "</option>";
                    $('#qry_dept').append(row);
                }
                // 預先帶出第一個單位
                $('#qry_dept').val("" + JData.qry_dept.DEPT_NO[0]);

                // 職稱欄位
                $('#qry_title').text(JData.qry_title.CODE_CHN_ITEM[0]);

                // 假別 select欄位
                var row0 = "<option selected disabled class='text-hide'>請選擇假別</option>";
                $('#qry_vtype').append(row0);
                for (var i = 0; i < JData.qry_vtype.CODE_FIELD.length; i++) {
                    var row = "<option value=" + JData.qry_vtype.CODE_FIELD[i] + ">" + JData.qry_vtype.CODE_CHN_ITEM[i] + "</option>";
                    $('#qry_vtype').append(row);
                }

                // 職務代理人
                var row0 = "<option selected disabled class='text-hide'>請選擇職務代理人</option>";
                $('#qry_agentno').append(row0);
                for (var i = 0; i < JData.qry_agentno.EMPL_NO.length; i++) {
                    var row = "<option value=" + JData.qry_agentno.EMPL_NO[i] + ">" + JData.qry_agentno.EMPL_CHN_NAME[i] + "</option>";
                    $('#qry_agentno').append(row);
                }
                $('#qry_agentno').append("<option value='0000000'>其它單位</option>");

                // 職務代理人單位
                var row0 = "<option selected disabled class='text-hide'>請選擇職務代理人單位</option>";
                $('#qry_agent_depart').append(row0);
                for (var idx in JData.qry_agent_depart) {
                    for (var i in JData.qry_agent_depart[idx]['DEPT_NO']) {
                        var depart_no = JData.qry_agent_depart[idx]['DEPT_NO'][i];
                        var depart_name = JData.qry_agent_depart[idx]['DEPT_FULL_NAME'][i];
                        var row = "<option value=" + depart_no + ">" + depart_name + "</option>";
                        $('#qry_agent_depart').append(row);
                    }
                }

                // 可補休之加班時數
                $('#qry_nouse').append(row0);
                var txt_append = "";
                data_nouse = JData.qry_nouse;
                for (var i = 0; i < data_nouse.OVER_DATE2.length; i++) {
                    txt_append = "";
                    if ((i + 1) % 5 == 0)
                        txt_append += data_nouse['OVER_DATE2'][i] + "(" + data_nouse['NOUSE_TIME'][i] + ") \n";
                    else
                        txt_append += data_nouse['OVER_DATE2'][i] + "(" + data_nouse['NOUSE_TIME'][i] + ") _";
                    $('#qry_nouse').append(txt_append);
                }

                // 是否為特殊工作人員
                $('#party').append(JData.qry_party.EMPL_PARTY[0]);
                // 是否為寒暑假期間
                var cn = JData.qry_voc.COUNT[0];
                if (cn > 0)
                    $('#vocation').append('1');
                else
                    $('#vocation').append('0');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                // console.log(xhr.responseText);
            }
        });

        // 若出差地點的option
        var place = ['基隆市', '台北市', '新北市',
            '桃園市', '新竹縣', '新竹市',
            '苗栗縣', '台中市', '彰化縣',
            '彰化市', '南投縣', '雲林縣',
            '嘉義縣', '嘉義市', '台南市',
            '高雄市', '屏東縣', '宜蘭縣',
            '花蓮縣', '台東縣', '連江縣',
            '澎湖縣', '金門縣', '自行輸入'
        ];
        var row0 = "<option selected disabled class='text-hide'>請選擇出差地點</option>";
        $('#qry_eplace').append(row0);
        for (var i = 0; i < place.length; i++) {
            var row = "<option value=" + i + ">" + place[i] + "</option>";
            $('#qry_eplace').append(row);
        }

        // 出差原因類型，供教卓系統抓資料之判別欄位
        var extracase = ['演講', '研討會',
            '學生課外活動指導', '國際合作', '協辦工作',
            '校外服務', '其它'
        ];
        var row0 = "<option selected disabled class='text-hide'>請選擇出差原因</option>";
        $('#qry_extracase').append(row0);
        for (var i = 0; i < extracase.length; i++) {
            var row = "<option value=" + i + ">" + extracase[i] + "</option>";
            $('#qry_extracase').append(row);
        }

        var start_options = {
            defaultDate: new Date(),
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
                nextCentury: "下個世紀",
                nextDecade: "後十年",
                nextMonth: "下個月",
                nextYear: "下一年",
                pickHour: "Pick Hour",
                pickMinute: "Pick Minute",
                pickSecond: "Pick Second",
                prevCentury: "上個世紀",
                prevDecade: "前十年",
                prevMonth: "上個月",
                prevYear: "前一年",
                selectDecade: "選擇哪十年",
                selectMonth: "選擇月份",
                selectTime: "選擇時間",
                selectYear: "選擇年份",
                today: "今日日期",
            },
            locale: 'zh-tw',
        }
        var end_options = start_options;
        end_options.useCurrent = false;
        // clock_options.format = 'YYYY/MM/DD HH時';

        // 請假開始日期、結束日期

        $('#leave-start').datetimepicker(start_options);
        $('#leave-end').datetimepicker(end_options);
        $("#leave-start").on("dp.change", function(e) {
            // end date的最小為start date所選
            $('#leave-end').data("DateTimePicker").minDate(e.date);
            // 將end date的initial date同步為start date所選
            $('#leave-end').data("DateTimePicker").date(e.date);
        });

        // 請假開始時間、結束時間
        leave_time_option();

        // 出國出入境時間
        $('#depart-time').datetimepicker(start_options);
        $('#immig-time').datetimepicker(end_options);
        $("#depart-time").on("dp.change", function(e) {
            $('#immig-time').data("DateTimePicker").minDate(e.date);
            $('#immig-time').data("DateTimePicker").date(e.date);
        });

        // 出國會議(研究)日程
        $('#meeting-start').datetimepicker(start_options);
        $('#meeting-end').datetimepicker(end_options);
        // 開始時間的選取值為結束時間的最小值
        $("#meeting-start").on("dp.change", function(e) {
            $('#meeting-end').data("DateTimePicker").minDate(e.date);
            $('#meeting-end').data("DateTimePicker").date(e.date);
        });

        var clock_start_options = start_options,
            clock_end_options = end_options;
        clock_start_options.format = clock_end_options.format = 'YYYY/MM/DD HH時';
        clock_start_options.showClose = clock_end_options.showClose = true;
        // 起訖時間
        $('#bus-trip-start').datetimepicker(clock_start_options);
        $('#bus-trip-end').datetimepicker(clock_end_options);
        // 開始時間的選取值為結束時間的最小值
        $("#bus-trip-start").on("dp.change", function(e) {
            $('#bus-trip-end').data("DateTimePicker").minDate(e.date);
            $('#bus-trip-end').data("DateTimePicker").date(e.date);
        });


        $('.bus-trip').hide();

        // 勞基特休 / 教職特休
        $("#qry_vtype").change(function() {
            $('#btime').empty();
            $('#etime').empty();
            leave_time_option();
            if ($('#qry_vtype').val() == "23" || $('#qry_vtype').val() == "06") {
                $.ajax({
                    url: 'ajax/update_form_ajax.php',
                    data: {
                        oper: 'qry_apps',
                        empl_no: $('#empl_no').text(),
                        vtype: $('#qry_vtype').val(),
                        year: year,
                        month: month,
                        day: day
                    },
                    type: 'POST',
                    dataType: "text",
                    success: function(JData) {
                        $('#message > div > ul').html("<li>" + JData + "</li>");
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        // console.log(xhr.responseText);
                    }
                });
                $('#message').show();
            } else {
                $('#message').hide();
            }

            // 奉派文號、是否含例假日、是否有課、是否出國
            var vt = ['01', '02', '03', '06', '15', '17', '21', '22'];
            if (jQuery.inArray($('#qry_vtype').val(), vt) != -1 || depart == 'M47' || depart == 'N20' || depart.substr(0, 2) == 'M6') {
                $('#permit-row').show();
            } else {
                $('#permit-row').hide();
            }

            // 出差、公假1、公假2 => 出差公假地點、出差等相關資訊
            var vt = ['01', '02', '03'];
            if (jQuery.inArray($('#qry_vtype').val(), vt) != -1) {
                $('#place-row').show();
                $('.bus-trip').show();
                // hide備註
                $('#remark').hide();

                if ($('input[name="abroad"]:checked').val() == "0") {
                    // 起訖時間
                    $('#bus-trip-time').show();
                    // 出國會議日程
                    $('#meeting-date').hide();
                } else {
                    $('#bus-trip-time').hide();
                    $('#meeting-date').show();
                }
            } else {
                $('#place-row').hide();
                $('.bus-trip').hide();
                // show備註
                $('#remark').show();
                // 起訖時間
                $('#bus-trip-time').hide();
            }

            // 若休假 => 是否刷國民旅遊卡
            if ($('#qry_vtype').val() == "06") {
                $('#trip-row').show();
            } else {
                $('#trip-row').hide();
            }

            // 若加班補休 => 顯示可補休之加班時數
            if ($('#qry_vtype').val() == "11") {
                $('#nouse').show();
            } else {
                $('#nouse').hide();
            }

            // 出差、公假1 => 經費來源
            var vt = ['01', '02'];
            if (jQuery.inArray($('#qry_vtype').val(), vt) != -1) {
                $('#budget').show();
            } else {
                $('#budget').hide();
            }

            // 非(出差、公假1、公假2) => 備註
            var vt = ['01', '02', '03'];
            if (jQuery.inArray($('#qry_vtype').val(), vt) != -1) {
                $('#remark').hide();
            } else {
                $('#remark').show();
            }
        });

        // 職務代理人單位
        // 若選擇其他單位，則須再選該代理人單位為何
        $('#agent_depart').hide();
        $("#qry_agentno").change(function() {
            if ($('#qry_agentno').val() == "0000000") {
                $('#agent_depart').show();
            } else {
                $('#agent_depart').hide();
            }
        });

        $("#qry_agent_depart").change(function() {
            if ($('#qry_agent_depart').val() != "") {
                $('#qry_agentno').empty();
                $.ajax({
                    url: 'ajax/update_form_ajax.php',
                    data: {
                        oper: 'qry_agent',
                        depart: $('#qry_agent_depart').val(),
                    },
                    type: 'POST',
                    dataType: "json",
                    success: function(JData) {
                        var row0 = "<option selected disabled class='text-hide'>請選擇職務代理人</option>";
                        $('#qry_agentno').append(row0);
                        for (var i = 0; i < JData.EMPL_NO.length; i++) {
                            var row = "<option value=" + JData.EMPL_NO[i] + ">" + JData.EMPL_CHN_NAME[i] + "</option>";
                            $('#qry_agentno').append(row);
                        }
                        $('#qry_agentno').append("<option value='0000000'>其它單位</option>");
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        // console.log(xhr.responseText);
                    }
                });
            }
        });

        // 若出差地點選擇自行輸入，則須show input text
        // 若「出差地點」的選擇改變
        $("#qry_eplace").change(function() {
            // 若出差地點選擇「自行輸入」 => enable input text讓使用者輸入
            if ($('#qry_eplace').val() == "23") {
                $('#place-row > td:nth-child(3) > input').prop("disabled", false);
                $('#place-row > td:nth-child(3) > input').focus();
            } else {
                $('#place-row > td:nth-child(3) > input').prop("disabled", true);
            }
        });
        // 是否出國的radio checked改變
        $("input[name='abroad']").change(function() {
            // 若選擇是否出國 => enable input text讓使用者輸入
            if ($('input[name="abroad"]:checked').val() == "1") {
                // 出差地點show來填出國地點
                $('#place-row').show();
                $('#place-row > td:nth-child(3) > input').prop("disabled", false);
                $('#place-row > td:nth-child(3) > input').focus();
                // 並且將出差地點改為「自行輸入」一項
                $('#qry_eplace').val("23");
                // 並且出差地點不可更換
                $('#qry_eplace').prop("disabled", true);

                // 出入境時間
                $('#depart-immig').show();
                // 出國會議日程
                var vt = ['01', '02', '03'];
                if (jQuery.inArray($('#qry_vtype').val(), vt) != -1) {
                    $('#meeting-date').show();
                    // 起訖時間hide
                    $('#bus-trip-time').hide();
                } else {
                    $('#meeting-date').hide();
                }
            } else {
                // 若沒有要出國就不開放填寫
                $('#place-row > td:nth-child(3) > input').prop("disabled", true);
                // 且出差地點開放選取國內地點
                $('#qry_eplace').prop("disabled", false);

                // 出入境
                $('#depart-immig').hide();
                $('#meeting-date').hide();

                // 起訖時間
                var vt = ['01', '02', '03'];
                if (jQuery.inArray($('#qry_vtype').val(), vt) != -1) {
                    // hide起訖時間
                    $('#bus-trip-time').show();
                } else {
                    $('#bus-trip-time').hide();
                    // 沒有要出國 且 沒有要出差 => hide出差地點
                    $('#place-row').hide();
                }

            }
        });

        $('#holidayform').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            // submitButtons: 'button[type="button"]',
            // excluded: [':not(:visible)'],
            fields: {
                vtype: {
                    trigger: "change",
                    validators: {
                        notEmpty: {
                            message: '請選擇假別'
                        }
                    }
                },
                agentno: {
                    trigger: "change",
                    validators: {
                        notEmpty: {
                            message: '請選擇職務代理人'
                        }
                    }
                },
                leave_start: {
                    trigger: "change",
                    validators: {
                        notEmpty: {
                            message: '請假日期不可空白'
                        },
                        date: {
                            format: 'YYYY/MM/DD',
                            message: '不正確的日期格式！'
                        }
                    }
                },
                leave_end: {
                    trigger: "change",
                    validators: {
                        notEmpty: {
                            message: '請假日期不可空白'
                        },
                        date: {
                            format: 'YYYY/MM/DD',
                            message: '不正確的日期格式！'
                        }
                    }
                },
                mark: {
                    trigger: "blur",
                    validators: {
                        notEmpty: {
                            message: '請輸入出差或公假事由'
                        },
                    }
                },
                remark: {
                    trigger: "change",
                    validators: {
                        stringLength: {
                            max: 80,
                            message: '備註欄長度超過上限，請簡要說明！'
                        }
                    }
                },
                on_dept: {
                    trigger: "change",
                    validators: {
                        stringLength: {
                            max: 30,
                            message: '出差服務單位長度超過上限，請簡要說明！'
                        }
                    }
                },
                on_duty: {
                    trigger: "change",
                    validators: {
                        stringLength: {
                            max: 30,
                            message: '出差擔任職務長度超過上限，請簡要說明！'
                        }
                    }
                },
                budget: {
                    trigger: "change",
                    validators: {
                        notEmpty: {
                            message: '請輸入經費來源'
                        },
                        stringLength: {
                            max: 50,
                            message: '備註欄長度超過上限，請簡要說明！'
                        }
                    }
                },
                permit: {
                    trigger: "change",
                    validators: {
                        notEmpty: {
                            message: '請輸入出差奉派文號'
                        },
                        stringLength: {
                            max: 50,
                            message: '備註欄長度超過上限，請簡要說明！'
                        }
                    }
                },
            }
        });

        $('#holidayform').on('submit', function(e) {
            //alert("j");
            e.preventDefault();
            formSubmit();
            // var date = new Date();
            // var year = date.getFullYear() - 1911;
            // $.ajax({
            //     url: 'ajax/update_form_ajax.php',
            //     data: $('form[name="holidayform"]').serialize()
            //     + "&oper=submit" + "&this_serialno=" + $('#hide-serial').text()
            //     + "&check=" + $('#hide-check').text() + "&voc=" + $('#vocation').text()
            //     + "&party=" + $('#party').text() + "&year=" + year,
            //     type: 'POST',
            //     dataType: "json",
            //     success: function(JData) {
            //         if (JData.error_code){
            //             toastr["error"](JData.error_message);
            //             message(JData.error_message, "danger", 5000);
            //         }
            //         else {
            //             toastr["success"](JData.submit_result);
            //             // reload
            //         }
            //     },
            //     error: function(xhr, ajaxOptions, thrownError) {
            //         toastr["error"]("呈交表單出了點狀況！");
            //         // console.log(xhr.responseText);
            //     }
            // });
        });
    } // function
);

function formSubmit() {
    if (!$("#holidayform").data('bootstrapValidator').isValid() ||
        getQueryVariable("sn") != update_serialno)
        return;

    var date = new Date();
    var year = date.getFullYear() - 1911;
    $.ajax({
        url: 'ajax/update_form_ajax.php',
        data: $('form[name="holidayform"]').serialize() +
            "&oper=submit" + "&this_serialno=" + update_serialno +
            "&check=" + $('#hide-check').text() + "&voc=" + $('#vocation').text() +
            "&party=" + $('#party').text() + "&year=" + year,
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code) {
                toastr["error"](JData.error_message);
                message(JData.error_message, "danger", 5000);
            } else {
                toastr["success"](JData.submit_result);
                // reload
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            toastr["error"]("呈交表單出了點狀況！");
            // console.log(xhr.responseText);
        }
    });
}

function leave_time_option() {
    // 請假開始、結束時間
    var voc = $('#vocation').text();
    var party = $('#party').text();
    var vt = ['01', '02', '03'];
    var bt = 8,
        et;
    if (jQuery.inArray($('#qry_vtype').val(), vt) != -1) {
        bt = 8;
        et = 23;
    } else if (voc == '1') {
        bt = 8;
        et = 15;
    } else if (party == '1') {
        bt = 13;
        et = 21;
    } //特殊上班人員
    else {
        bt = 8;
        et = 16;
    }

    // 寒暑假
    if (voc == '1') {
        // 休假、寒暑休
        vt = ['06', '21', '22'];
        if (jQuery.inArray($('#qry_vtype').val(), vt) != -1) {
            // 開始
            var row0 = "<option selected disabled class='text-hide'></option>";
            $('#btime').append(row0);
            for (var i = bt; i <= 12; i += 4) {
                var row = "<option value=" + i + ">" + i + "</option>";
                $('#btime').append(row);
            }
            $('#btime').append("<option value='1230'> 12:30 </option>");

            // 結束
            var row0 = "<option selected disabled class='text-hide'></option>";
            $('#etime').append(row0);
            for (var i = 12; i <= 16; i += 4) {
                var row = "<option value=" + i + ">" + i + "</option>";
                $('#etime').append(row);
            }
            $('#etime').append("<option value='1630'> 16:30 </option>");
        } else {
            var row0 = "<option selected disabled class='text-hide'></option>";
            $('#btime').append(row0);
            for (var i = bt; i <= et; ++i) {
                var row = "<option value=" + i + ">" + i + "</option>";
                $('#btime').append(row);
                if (i >= 12)
                    $('#btime').append("<option value='" + i + "30'>" + i + ":30 </option>");
            }

            var row0 = "<option selected disabled class='text-hide'></option>";
            $('#etime').append(row0);
            for (var i = bt + 1; i <= et + 1; ++i) {
                var row = "<option value=" + i + ">" + i + "</option>";
                $('#etime').append(row);
                if (i >= 12)
                    $('#etime').append("<option value='" + i + "30'>" + i + ":30 </option>");
            }
        }

    } else {
        // 正常時間
        vt = ['06', '21', '22'];
        if (jQuery.inArray($('#qry_vtype').val(), vt) != -1) {
            // 開始
            var row0 = "<option selected disabled class='text-hide'></option>";
            $('#btime').append(row0);
            for (var i = bt; i <= 13; i += 5) {
                var row = "<option value=" + i + ">" + i + "</option>";
                $('#btime').append(row);
            }

            // 結束
            var row0 = "<option selected disabled class='text-hide'></option>";
            $('#etime').append(row0);
            for (var i = 12; i <= 17; i += 5) {
                var row = "<option value=" + i + ">" + i + "</option>";
                $('#etime').append(row);
            }
        } else {

            var row0 = "<option selected disabled class='text-hide'></option>";
            $('#btime').append(row0);
            for (var i = bt; i <= et; ++i) {
                var row = "<option value=" + i + ">" + i + "</option>";
                $('#btime').append(row);
            }

            var row0 = "<option selected disabled class='text-hide'></option>";
            $('#etime').append(row0);
            for (var i = bt + 1; i <= et + 1; ++i) {
                var row = "<option value=" + i + ">" + i + "</option>";
                $('#etime').append(row);
            }
        }
    }
    $('#btime').val($('#btime > option:nth-child(2)').val());
    $('#etime').val($('#etime > option:last').val());
}

function confirm_reset() {
    return confirm("確定重填？");
}

function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) { return pair[1]; }
    }
    return (false);
}