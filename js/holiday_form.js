$( // 表示網頁完成後才會載入

    function() {
        var date = new Date();
        var year = date.getFullYear() - 1911;
        var month = date.getMonth();
        var day = date.getDate();

        $("body").tooltip({
            selector: "[title]"
        });

        $('#message').hide();
        $('#trip').hide();
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

            if ($('#qry_vtype').val() == "06") {
                $('#trip').show();
            }
            else {
                $('#trip').hide();
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
