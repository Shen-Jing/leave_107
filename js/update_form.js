var date = new Date();
var year = date.getFullYear() - 1911;
var ad_year = date.getFullYear();
var month = date.getMonth() + 1;
var day = date.getDate();
var sn = getQueryVariable('sn');
$( // 表示網頁完成後才會載入
    function() {
        // 部門：MQ5等等的編號
        var depart = $('#hide-depart').text();

        year = year.toString();
        month = month.toString();
        day = day.toString();
        if (year.length < 3)
            year = '0' + year;
        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        // select內容，也就是不需根據select所選而有不同sql顯示資料的部分
        // 可以一開始就ajax取內容的
        $.ajax({
            url: 'ajax/update_form_ajax.php',
            data: {
                oper: 'qry_item',
                empl_no: $('#empl_no').text(),
                depart: depart,
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

                // 職稱欄位，職稱中文(可見)與id(display none)要一起更新
                // EX: 技正
                $('#qry_title').text(JData.qry_title.CODE_CHN_ITEM[0]);
                // EX: F50
                $('#hide-titleid').text(JData.qry_title.CODE_FIELD[0]);

                // 假別 select欄位
                var row0 = "<option selected disabled class='text-hide'>請選擇假別</option>";
                $('#qry_vtype').append(row0);
                for (var i = 1; i < JData.qry_vtype.CODE_FIELD.length; i++) {
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
                // 或許為vacation才對，但此處還是照舊頁面命名
                if (cn > 0)
                    $('#vocation').append('1');
                else
                    $('#vocation').append('0');


                // 查詢舊假單的資料
                $.ajax({
                    async: false,
                    url: "ajax/update_form_ajax.php",
                    type: 'POST',
                    data: { oper: "qry_oldform", sn: sn },
                    success: function(data) {

                        // 原有假單的單位
                        $('#qry_dept').val(data.depart).trigger('change');

                        // 因有寫改變單位時帶出職稱的函數
                        //$('#qry_title').text(data.qry_title);
                        //$('#hide-titleid').text(data.hide-titleid);


                        $('#qry_agentno').val(data.agentno).trigger('change');

                        $('#qry_vtype').val(data.vtype).trigger('change');
                        $('#btime').val(data.btime).trigger('change');
                        $('#etime').val(data.etime).trigger('change');
                        $('#leave-start').data("DateTimePicker").date(data.leave_start);
                        $('#leave-end').data("DateTimePicker").date(data.leave_end);
                        $('#qry_extracase').val(data.extracase).trigger('change');


                        if (data.haveclass == '1') {
                            $('input[name="haveclass"]').first().prop('checked', true).trigger('change');
                        }

                        if (data.abroad == '1') {
                            $('input[name="abroad"]').first().prop('checked', true).trigger('change');
                            $('input[name="eplace_text"]').val(data.eplace).trigger('change');
                            $('#depart-time').data("DateTimePicker").date(data.depart_time);
                            $('#immig-time').data("DateTimePicker").date(data.immig_time);
                        } else {
                            $('#qry_eplace').val(data.eplace).trigger('change');
                            $('#bus-trip-start').data("DateTimePicker").date(data.bus_trip_start);
                            $('#bus-trip-end').data("DateTimePicker").date(data.bus_trip_end);
                        }

                        if (data.saturday == '1') {
                            $('input[name="saturday"]').first().prop('checked', true).trigger('change');
                        }

                        if (data.trip == '1') {
                            $('input[name="trip"]').first().prop('checked', true).trigger('change');
                        }

                        $('input[name="mark"]').val(data.mark).trigger('change');
                        $('input[name="budget"]').val(data.budget).trigger('change');
                        $('input[name="permit"]').val(data.permit).trigger('change');
                        $('input[name="on_dept"]').val(data.on_dept).trigger('change');
                        $('input[name="on_duty"]').val(data.on_duty).trigger('change');
                    },
                    dataType: 'json'
                });
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
            var row = "<option value=" + place[i] + ">" + place[i] + "</option>";
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
            var row = "<option value=" + extracase[i] + ">" + extracase[i] + "</option>";
            $('#qry_extracase').append(row);
        }

        var start_options = {
            defaultDate: new Date(),
            format: 'YYYY/MM/DD',
            ignoreReadonly: true,
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

        // 設定請假開始時間、結束時間的範圍
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

        // 選擇假別變動時
        $("#qry_vtype").change(function() {
            // 清空請假開始、結束時間的所有option
            $('#btime').empty();
            $('#etime').empty();
            // 根據所選假別設定範圍
            leave_time_option();
            // 勞基特休 / 教職休假
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

        // 限制時間選取
        $("#btime").on("change", function() {
            // 若不先轉為int，判斷大小會有問題
            var bt = parseInt($(this).val()),
                et = parseInt($("#etime").val());
            // 若開始時間超過結束時間
            if (bt > et)
                $("#etime").val($(this).val());
            // 讓結束時間與選取的開始時間相同
        });
        $("#etime").on("change", function() {
            var bt = parseInt($("#btime").val()),
                et = parseInt($(this).val());
            // 若結束時間在開始時間之前
            if (et < bt)
                $("#btime").val($(this).val());
            // 讓開始時間與選取的結束時間相同
        });

        // 職務代理人單位
        // 若選擇其他單位，則須再選該代理人單位為何
        $("#agent_depart").hide();
        $("#qry_agentno").change(function() {
            if ($('#qry_agentno').val() == "0000000") {
                $('#agent_depart').show();
            } else {
                $('#agent_depart').hide();
            }
        });

        // 選了新的代理人單位，需重新查詢該單位下有什麼代理人員
        $("#qry_agent_depart").change(function() {
            if ($('#qry_agent_depart').val() != "") {
                $('#qry_agentno').empty();
                $.ajax({
                    url: 'ajax/update_form_ajax.php',
                    data: {
                        oper: 'qry_agent',
                        depart: $('#qry_agent_depart').val(),
                        empl_no: $('#empl_no').text()
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

        // 若改變所屬單位選擇，則需ajax處理叫出該請假者在該單位下的職稱
        $("#qry_dept").change(function() {
            if ($('#qry_dept').val() != "") {
                $.ajax({
                    url: 'ajax/update_form_ajax.php',
                    data: {
                        oper: 'qry_title',
                        empl_no: $('#empl_no').text(),
                        depart: $('#qry_dept').val(),
                    },
                    type: 'POST',
                    dataType: "json",
                    success: function(JData) {
                        // 職稱欄位，職稱中文(可見)與id(display none)要一起更新
                        // EX: 技正
                        $('#qry_title').text(JData.CODE_CHN_ITEM[0]);
                        // EX: F50
                        $('#hide-titleid').text(JData.CODE_FIELD[0]);
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
            if ($('#qry_eplace').val() == "自行輸入") {
                $("input[name='eplace_text']").prop("disabled", false);
                $("input[name='eplace_text']").focus();
            } else {
                $("input[name='eplace_text']").prop("disabled", true);
            }
        });
        // 是否出國的radio checked改變
        $("input[name='abroad']").change(function() {
            // 若選擇是否出國 => enable input text讓使用者輸入
            if ($('input[name="abroad"]:checked').val() == "1") {
                // 出差地點show來填出國地點
                $('#place-row').show();
                $("input[name='eplace_text']").prop("disabled", false);
                $("input[name='eplace_text']").focus();
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
                $("input[name='eplace_text']").prop("disabled", true);
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
                live: 'submitted',
                fields: {
                    vtype: {
                        validators: {
                            notEmpty: {
                                message: '請選擇假別'
                            }
                        }
                    },
                    depart: {
                        validators: {
                            notEmpty: {
                                message: '請選擇單位'
                            }
                        }
                    },
                    agentno: {
                        validators: {
                            notEmpty: {
                                message: '請選擇職務代理人'
                            }
                        }
                    },
                    agent_depart: {
                        validators: {
                            notEmpty: {
                                message: '請選擇職務代理人單位'
                            }
                        }
                    },
                    leave_start: {
                        validators: {
                            notEmpty: {
                                message: '日期不可空白'
                            },
                            date: {
                                format: 'YYYY/MM/DD',
                                message: '不正確的日期格式！'
                            }
                        }
                    },
                    leave_end: {
                        validators: {
                            notEmpty: {
                                message: '日期不可空白'
                            },
                            date: {
                                format: 'YYYY/MM/DD',
                                message: '不正確的日期格式！'
                            }
                        }
                    },
                    depart_time: {
                        validators: {
                            notEmpty: {
                                message: '日期不可空白'
                            },
                            date: {
                                format: 'YYYY/MM/DD',
                                message: '不正確的日期格式！'
                            }
                        }
                    },
                    immig_time: {
                        validators: {
                            notEmpty: {
                                message: '日期不可空白'
                            },
                            date: {
                                format: 'YYYY/MM/DD',
                                message: '不正確的日期格式！'
                            }
                        }
                    },
                    bus_trip_start: {
                        validators: {
                            notEmpty: {
                                message: '日期不可空白'
                            },
                        }
                    },
                    bus_trip_end: {
                        validators: {
                            notEmpty: {
                                message: '日期不可空白'
                            },
                            // date: {
                            //     format: 'YYYY/MM/DD HH時',
                            //     message: '不正確的日期格式！'
                            // }
                        }
                    },
                    meeting_start: {
                        validators: {
                            notEmpty: {
                                message: '日期不可空白'
                            },
                            date: {
                                format: 'YYYY/MM/DD',
                                message: '不正確的日期格式！'
                            }
                        }
                    },
                    meeting_end: {
                        validators: {
                            notEmpty: {
                                message: '日期不可空白'
                            },
                            date: {
                                format: 'YYYY/MM/DD',
                                message: '不正確的日期格式！'
                            }
                        }
                    },
                    eplace: {
                        validators: {
                            notEmpty: {
                                message: '請選擇地點'
                            },
                        }
                    },
                    eplace_text: {
                        validators: {
                            notEmpty: {
                                message: '請填寫地點'
                            },
                        }
                    },
                    mark: {
                        validators: {
                            notEmpty: {
                                message: '請輸入出差或公假事由'
                            },
                        }
                    },
                    remark: {
                        validators: {
                            stringLength: {
                                max: 80,
                                message: '備註欄長度超過上限，請簡要說明！'
                            }
                        }
                    },
                    extracase: {
                        validators: {
                            notEmpty: {
                                message: '請選擇出差原因類型'
                            },
                        }
                    },
                    on_dept: {
                        validators: {
                            stringLength: {
                                max: 30,
                                message: '出差服務單位長度超過上限，請簡要說明！'
                            }
                        }
                    },
                    on_duty: {
                        validators: {
                            stringLength: {
                                max: 30,
                                message: '出差擔任職務長度超過上限，請簡要說明！'
                            }
                        }
                    },
                    budget: {
                        validators: {
                            notEmpty: {
                                message: '請輸入經費來源'
                            },
                        }
                    },
                    permit: {
                        validators: {
                            notEmpty: {
                                message: '請輸入出差奉派文號'
                            },
                        }
                    },
                },
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
        .on('success.form.bv', function(e) {
            var date = new Date();
            var year = date.getFullYear() - 1911;
            var postData = $(this).serialize() +
                "&oper=submit" + "&depart=" + $('#qry_dept').val() + "&empl_no=" + $('#empl_no').text() +
                "&title_id=" + $('#hide-titleid').text() + "&check=" + $('#hide-check').text() + "&voc=" + $('#vocation').text() +
                "&party=" + $('#party').text() + "&year=" + year + "&sn=" + sn;
            var formURL = $('#holidayform').attr('action');
            $.ajax({
                url: 'ajax/' + formURL,
                data: postData,
                type: 'POST',
                dataType: "json",
                success: function(JData) {
                    if (JData.error_code) {
                        toastr["error"](JData.error_message);
                        message(JData.error_message, "danger", 5000);
                    } else {
                        toastr["success"](JData.submit_result);
                        if (JData.submit_remind != "")
                            toastr["success"](JData.submit_remind);
                        // 刷新serailno, 加班補休
                        refresh_form();
                        if ($('input[name=haveclass]:checked').val() == "1") {
                            // 若有課，轉到「有請假補填調補課申請」頁面
                            alert("請假/出差期間有課，將轉到「有請假補填調補課申請」補填資訊。");
                            location.href = "class_add.php";
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    toastr["error"]("呈交表單出了點狀況！");
                    console.log(xhr.responseText);
                }
            });
            e.preventDefault(); //STOP default action
            //e.unbind(); //unbind. to stop multiple form submit.
        }); //send.click

    } // function
);

function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) { return pair[1]; }
    }
    return (false);
}

// 刷新加班補休時數
function refresh_form() {
    $.ajax({
        url: 'ajax/update_form_ajax.php',
        data: {
            oper: 'refresh_form',
            empl_no: $('#empl_no').text(),
            vocdate: "" + year + month + day,
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            // 可補休之加班時數
            $('#qry_nouse').empty();
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
        },
        error: function(xhr, ajaxOptions, thrownError) {
            // console.log(xhr.responseText);
        }
    });
}
// 呼叫此func前，會先清空請假開始、結束時間的所有option後，
function leave_time_option() {
    // 根據是否寒暑假、特殊工作人員設定時間範圍
    var voc = $('#vocation').text();
    var party = $('#party').text();
    var vt = ['01', '02', '03'];
    // 請假開始、結束時間
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
    // 將開始時間設為最早、結束時間設為最晚
    $('#btime').val($('#btime > option:nth-child(2)').val());
    $('#etime').val($('#etime > option:last').val());
}