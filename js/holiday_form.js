$( // 表示網頁完成後才會載入
    function() {
        // 請假開始日期、結束日期
        $('#leave-start').datetimepicker({
            format: 'YYYY/MM/DD HH'
        });
        $('#leave-end').datetimepicker({
            format: 'YYYY/MM/DD HH'
            // useCurrent: false //Important! See issue #1075
        });
        $("#leave-end").on("dp.change", function (e) {
            $('#leave-start').data("DateTimePicker").maxDate(e.date);
        });

        var date = new Date();
        var year = date.getFullYear() - 1911;
        var month = date.getMonth();
        var day = date.getDate();
        // 部門：MQ5等等的編號
        var depart = $('#hide-depart').text();

        $("body").tooltip({
            selector: "[title]"
        });

        $('#message').hide();
        $('.permit-row').hide();
        $('#place-row').hide();
        $('#trip-row').hide();
        // 加班剩餘
        $('#nouse').hide();
        // 勞基特休 / 教職特休
        $("#qry_vtype").change(function(){
            if ($('#qry_vtype').val() == "23" || $('#qry_vtype').val() == "06") {
                $.ajax({
                    url: 'ajax/holiday_form_ajax.php',
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
            }
            else {
                $('#message').hide();
            }

            // 奉派文號、是否含例假日、是否有課、是否出國
            var vt = ['01', '02', '03', '06', '15', '17', '21', '22'];
            if ( jQuery.inArray( $('#qry_vtype').val(), vt ) != -1 || depart == 'M47' || depart == 'N20' || depart.substr(0, 2) == 'M6') {
                $('.permit-row').show();
            }
            else {
                $('.permit-row').hide();
            }

            // 出差 => 出差公假地點
            var vt = ['01', '02', '03'];
            if ( jQuery.inArray( $('#qry_vtype').val(), vt ) != -1) {
                var place = ['基隆市', '台北市', '新北市',
                '桃園市', '新竹縣', '新竹市',
                '苗栗縣', '台中市', '彰化縣',
                '彰化市', '南投縣', '雲林縣',
                '嘉義縣', '嘉義市', '台南市',
                '高雄市', '屏東縣', '宜蘭縣',
                '花蓮縣', '台東縣', '連江縣',
                '澎湖縣', '金門縣', '自行輸入'];
                var row0 = "<option selected disabled class='text-hide'>請選擇出差地點</option>";
                $('#qry_eplace').append(row0);
                for (var i = 0; i < place.length; i++) {
                    var row = "<option value=" + i + ">" + place[i] + "</option>";
                    $('#qry_eplace').append(row);
                }
                $('#place-row').show();
            }
            else {
                $('#place-row').hide();
            }

            // 若休假 => 是否刷國民旅遊卡
            if ($('#qry_vtype').val() == "06") {
                $('#trip-row').show();
            }
            else {
                $('#trip-row').hide();
            }

        });

        // 職務代理人單位
        // 若選擇其他單位，則須再選該代理人單位為何
        $('#agent_depart').hide();
        $("#qry_agentno").change(function(){
            if ($('#qry_agentno').val() == "0000000") {
                $('#agent_depart').show();
            }
            else {
                $('#agent_depart').hide();
            }
        });

        // 若出差地點選擇自行輸入，則須show input text
        // 若「出差地點」的選擇改變
        $("#qry_eplace").change(function(){
            // 若出差地點選擇「自行輸入」 => enable input text讓使用者輸入
            if ($('#qry_eplace').val() == "23") {
                $('#place-row > td:nth-child(3) > input').prop("disabled", false);
            }
            else {
                $('#place-row > td:nth-child(3) > input').prop("disabled", true);
            }
        });
        // 是否出國的radio checked改變
        $("input[name='abroad']").change(function(){
            // 若選擇是否出國 => enable input text讓使用者輸入
            if ($('input[name="abroad"]:checked').val() == "1") {
                $('#place-row > td:nth-child(3) > input').prop("disabled", false);
                // 並且將出差地點改為「自行輸入」一項
                $('#qry_eplace').val("23");
                // 並且出差地點不可更換
                $('#qry_eplace').prop("disabled", true);
            }
            else {
                // 若沒有要出國就不開放填寫
                $('#place-row > td:nth-child(3) > input').prop("disabled", true);
                // 且出差地點開放選取國內地點
                $('#qry_eplace').prop("disabled", false);
            }
        });

        // select內容，也就是不需根據select所選而有不同sql顯示資料的部分
        // 可以一開始就ajax取內容的
        $.ajax({
            url: 'ajax/holiday_form_ajax.php',
            data: {
              oper: 'qry_item',
              empl_no: $('#empl_no').text(),
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

            },
            error: function(xhr, ajaxOptions, thrownError) {
                // console.log(xhr.responseText);
            }
        });

    }
);

function confirm_reset() {
    return confirm("確定重填？");
}
